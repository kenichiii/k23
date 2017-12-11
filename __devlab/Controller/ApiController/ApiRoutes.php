<?php

class ApiRoutes
{
    protected $routes = [
      '/user/:id',  [
          'get' => [[],["pkid","title","name","email","token"=>"text"]]
      ]                
    ];
}
