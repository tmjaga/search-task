<?php

class PopulatedPlaces
{
    private static $db;
    private $rowsPerPage = 20;
    private $fieldMappings = [
        'ecattu_id' => 'pp.ecattu_id',
        'populated_places_types' => 'pp.type',
        'name' => 'pp.name',
        'name_en' => 'pp.name_en',
        'district_code' => 'dt.code',
        'district_name' => 'dt.name',
        'municipality_code' => 'mt.code',
        'municipality_name' => 'mt.name',
    ];
    private $likeSearchFields = ['name', 'name_en', 'district_name', 'municipality_name'];

    public function __construct()
	{
        $instance = Database::init();
        self::$db = $instance->getDb();
	}

	public function getAll($params)
	{
        $pageNumber = 1;
        if (isset($params['page']) && preg_match('/^[0-9]+$/', $params['page'])) {
            $pageNumber = $params['page'];
            unset($params['page']);
        }

        // remove fields from $params which is not in $this->fieldMappings array
        $params = array_intersect_key($params, $this->fieldMappings);

        $placeHolders = array_map(function ($key) {
            $separator = in_array($key, $this->likeSearchFields) ? ' LIKE :' : ' = :';
            return $this->fieldMappings[$key] . $separator . $key;
        }, array_keys($params));

        $preparedData = array_map(function ($value, $key) {
           return in_array($key, $this->likeSearchFields) ? "%{$value}%" : $value;
        }, $params, array_keys($params));
        $preparedData = array_combine(array_keys($params), $preparedData);

        // get records count
        $countSql = "SELECT count(*) AS total               
                FROM populated_places pp
                LEFT JOIN populated_places_types pt ON pp.type = pt.id
                LEFT JOIN districts dt ON pp.district_code = dt.code
                LEFT JOIN municipalities mt ON pp.municipality_code = mt.code
                WHERE " . implode(' AND ', $placeHolders);

        $stmt = self::$db->prepare($countSql);
        $stmt->execute($preparedData);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalRecords = $row['total'];
        $totalPages = ceil($totalRecords / $this->rowsPerPage);
        $start = ($pageNumber - 1) * $this->rowsPerPage;

        // main query
        $sql = "SELECT  
                pp.ecattu_id as ecattu, pt.name as type, pp.name as name, pp.name_en as name_en, 
                dt.name as district, dt.code as district_code, 
                mt.name as municipality , mt.code as municipality_code
                FROM populated_places pp
                LEFT JOIN populated_places_types pt ON pp.type = pt.id
                LEFT JOIN districts dt ON pp.district_code = dt.code
                LEFT JOIN municipalities mt ON pp.municipality_code = mt.code
                WHERE " . implode(' AND ', $placeHolders) . " LIMIT {$start},{$this->rowsPerPage}";

        $stmt = self::$db->prepare($sql);
        $stmt->execute($preparedData);
       // $stmt->debugDumpParams();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['data' => $data, 'pages' => $totalPages];
	}

    public function getPlacesTypes()
    {
        $sql = "SELECT id, name FROM populated_places_types";
        $res = self::$db->query($sql, PDO::FETCH_ASSOC);
        $data = $res->fetchAll();

        return $data;
    }

}