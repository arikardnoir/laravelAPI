<?php

$this->group(['prefix' => 'v1'],function(){
    
    $this->post('auth', 'Auth\AuthApiController@authenticate');
    $this->post('auth-refresh', 'Auth\AuthApiController@refreshToken');
    
    $this->group(['middleware' => 'jwt.auth'],function(){
        
        $this->get('products/search', 'Api\v1\ProductController@search');
        $this->apiResource('products', 'Api\v1\ProductController', ['except' => ['create', 'edit']]);
        
    });
    
});
