<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('topics/create', ['uses' => 'TopicController@create']);    
    $router->post('topics/update/{id}', ['uses' => 'TopicController@update']);   
    $router->get('topic/list',  ['uses' => 'OnlyShowController@showAllTopics']);
    $router->get('topic/{id}',  ['uses' => 'OnlyShowController@ArticletoTopics']);
    $router->get('article/list',  ['uses' => 'OnlyShowController@showAllArticle']);
    $router->post('article/create', ['uses' => 'ArticleController@create']);
    $router->post('article/update/{id}', ['uses' => 'ArticleController@update']);
    $router->post('register', ['uses' => 'AuthController@register']);
    $router->get('article/{id}',  ['uses' => 'OnlyShowController@ArticletoSlug']);
    $router->post('login', ['uses' => 'AuthController@login']);
    $router->post('logout', ['uses' => 'AuthController@Logout']);
});