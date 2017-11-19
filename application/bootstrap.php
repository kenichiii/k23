<?php

namespace {

use k23\MCV\DispatcherService;

    //BOOT!
    require_once 'Bootstrap/Bootstrap.php';    
    \App\Bootstrap::boot();

    //GET REQUEST
    $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
    
    //GET RESPONSE
    $dispatcher = new DispatcherService($url);
    $response = $dispatcher->dispatch();
    
    //SERVE RESPONSE
    foreach($response->getHeaders() as $declaration) {
        header($declaration);
    }
        
    echo $response->getBody();    
}