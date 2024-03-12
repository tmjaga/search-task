<?php

require_once 'classes/Autoloader.php';

Autoloader::register();
new Router();

class Router
{
   public function __construct()
    {
        $serverVar = (empty($_SERVER['PATH_INFO'])) ? $_SERVER['REQUEST_URI'] : $_SERVER['PATH_INFO'];
        $httpVerb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

        $uri = strtolower(trim($serverVar, '/'));
        $parsed = parse_url($uri);
        $uri = $parsed['path'];
        $query = isset($parsed['query']) ? $parsed['query'] : '';
        $uri = str_replace('api/','', $uri);

        $routingRules = [
            ':any' => '[^\/]+'

        ];

        $routes = [
            'get populated_places' => [
                'class' => 'PopulatedPlaces',
                'method' => 'getAll',
            ],
            'get places_types' => [
                'class' => 'PopulatedPlaces',
                'method' => 'getPlacesTypes',
            ],
        ];

        $response = [
            'error' => 'No such route',
        ];

        if ($uri) {
            foreach ($routes as $pattern => $target) {
                $params = [];
                $pattern = str_replace(array_keys($routingRules), array_values($routingRules), $pattern);
                if (preg_match('/^'.$pattern.'$/i', "{$httpVerb} {$uri}", $matches)) {

                    if ($httpVerb == 'get') {
                        parse_str($query, $params);
                    }
                    if (isset($matches[2])) {
                        $params[] = $matches[2];
                    }

                    $response = call_user_func_array([new $target['class'], $target['method']], [$params]);
                    break;
                }
            }
            echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
}
