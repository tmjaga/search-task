<?php
$getParams = [
    'ecattu_id' => '',
    'populated_places_types' => '',
    'name' => '',
    'name_en' => '',
    'district_code' => '',
    'district_name' => '',
    'municipality_code' => '',
    'municipality_name' => '',
    'page' => 1
];
foreach ($_GET as $key => $value) {
    $getParams[$key] = $value;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
<div class="container">
    <div class="row mb-6">
        <div class="col mb-3">
            <h1 class="text-center">
                <strong>Населени места</strong>
            </h1>
        </div>
    </div>

    <form id="searchForm">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="ecattu_id">ЕКАТТЕ</label>
                <input type="text" class="form-control" id="ecattu_id" name="ecattu_id" value="<?php echo $getParams['ecattu_id']; ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="populated_places_types">Тип на населено място</label>
                <select class="form-control" name="populated_places_types" id="populated_places_types">
                    <option value="">-- Select --</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="name ">Име на населено място</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo $getParams['name']; ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="name ">Транслитерация на име</label>
                <input type="text" class="form-control" name="name_en" id="name_en" value="<?php echo $getParams['name_en']; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="district_code ">Код на областта</label>
                <input type="text" class="form-control" name="district_code" id="district_code" maxlength="3" value="<?php echo $getParams['district_code']; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="district_name ">Име на областта</label>
                <input type="text" class="form-control" name="district_name" id="district_name" maxlength="100" value="<?php echo $getParams['district_name']; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="municipality_code ">Код на общината</label>
                <input type="text" class="form-control" name="municipality_code" id="municipality_code" maxlength="5" value="<?php echo $getParams['municipality_code']; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="municipality_name ">Име на общината</label>
                <input type="text" class="form-control" name="municipality_name" id="municipality_name" maxlength="100" value="<?php echo $getParams['municipality_name']; ?>">
            </div>
        </div>

        <button id="send-form" type="button" class="btn btn-primary">Търсене</button>&nbsp;<button id="reset-form" type="button" class="btn btn-primary">Нулирай търсенето</button>

        <input type="hidden" id="page" name="page" value="<?php echo $getParams['page']; ?>">
    </form>
    <br/>
</div>

<div class="container col-md-9">
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">ЕКАТТЕ</th>
            <th scope="col">Тип</th>
            <th scope="col">Населено място</th>
            <th scope="col">Транслитерация</th>
            <th scope="col">Област</th>
            <th scope="col">Област Код</th>
            <th scope="col">Община</th>
            <th scope="col">Община Код</th>
        </tr>
        </thead>
        <tbody id="result">
        </tbody>
    </table>
    <div id="pages"></div>
    <br/>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var selectedType = '<?php echo $getParams['populated_places_types']?>';

    $(function() {

        let jsonData = [];

        $.ajax({
            url: "/api/places_types",
            type: "GET",
            async : false,
            cache: true,
            timeout: 3000,
            dataType: "json",
            error: function (xhr, status, error) {
                alert ("Error Sending Request");
            },
            success: function (data){
                jsonData = data;
                $.each(jsonData, function(key, value) {
                    let selected = (value.id == selectedType) ? true : false;
                    $('#populated_places_types').append($('<option>', {
                        value: value.id,
                        text: value.name,
                        selected: selected
                    }));
                });
            }
        });

        submitForm();
        $('#send-form').click(function() {
            submitForm(1);
        });

        $('#reset-form').click(function() {
            $('#searchForm input, #searchForm select').each(function() {
                $(this).val('');
            });
            $('#page').val('1');
            $('#pages, #result').html('');
        });
    });

const submitForm = (checkForm = 0) => {
    var formData = {};

    $('#searchForm input, #searchForm select').each(function() {
        if ($.trim($(this).val()) !== '') {
            formData[$(this).attr('name')] = $.trim($(this).val());
        }
    });

    if (Object.keys(formData).length == 1) {
        if (checkForm) {
            alert ('Please fill at least one filter');
            return false;
        }
    }
    if (checkForm) {
        formData.page = 1;
    }

    $.ajax({
        type: 'GET',
        url: '/api/populated_places',
        data: formData,
        dataType: 'json',
        success: function(response) {
            let pages = response.pages;
            $('#result').html('');
            $.each(response.data, function(key, row) {
                let html = '<tr>';
                html += '<td scope="row">'+row.ecattu+'</td>'
                html += '<td scope="row">'+row.type+'</td>'
                html += '<td scope="row">'+row.name+'</td>'
                html += '<td scope="row">'+row.name_en+'</td>'
                html += '<td scope="row">'+row.district+'</td>'
                html += '<td scope="row">'+row.district_code+'</td>'
                html += '<td scope="row">'+row.municipality+'</td>'
                html += '<td scope="row">'+row.municipality_code+'</td>'
                html += '</tr>'
                $('#result').append(html);
            });

            delete formData.page;
            let queryString = $.param(formData);
            queryString = 'index.php?' + queryString + '&page=';

            let html = '';
            for (let i = 1; i <= pages; i++) {
                let link = queryString + i;
                html  += '<a href="' + link +'">'+ i + '</a> &nbsp;&nbsp;';
            }
            $('#pages').html('').append(html);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
</script>
</body>
</html>
