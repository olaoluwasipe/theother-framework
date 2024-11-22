<?php
// use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as DB;

// $router->get('/', 'HomeController@index');
$router->get('/login', 'HomeController@login');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'HomeController@register');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

$router->get('/campaign/{code}', 'CampaignController@index');

$router->group(['middleware' => ['auth']], function($router) {
    $router->get('/', 'HomeController@index');

    $router->post('/get-data', 'HomeController@getData');

    $router->get('/get-data/{code}', 'HomeController@getCampaignData');

    $router->add('GET', 'dashboard', function() {
        $subscriptions = DB::connection('mysql2')->table('game_sub')->get();
        print_r($subscriptions);
    });
    
    $router->add('GET', 'settings', function() {
        echo "Admin Settings";
    });
});
