<?php


$this->post('auth', 'Auth\AuthApiController@authenticate');
$this->post('auth-refresh', 'Auth\AuthApiController@refreshToken');
$this->apiResource('user', 'Api\v1\UserController', ['except' => ['create', 'edit']]);
$this->get('me', 'Auth\AuthApiController@getAuthenticatedUser');

$this->group([
    'prefix' => 'v1',
    'namespace' => 'Api\v1',
    'middleware' => 'auth:api'
], function () {

    $this->get('products/search', 'ProductController@search');
    $this->apiResource('products', 'ProductController', ['except' => ['create', 'edit']]);
    $this->apiResource('cadastro', 'CadastroController', ['except' => ['create', 'edit']]);

});