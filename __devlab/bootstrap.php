<?php

namespace {

use k23\MCV\DispatcherService;
use App\Bootstrap;

    //BOOT!
    require_once 'Bootstrap/Bootstrap.php';    
    Bootstrap::boot();

    //GET REQUEST
    $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
    
    //GET RESPONSE
    $dispatcher = new DispatcherService($url);
    $response = $dispatcher->dispatch();
    
    //SERVE RESPONSE    
    foreach($response->getHeaders() as $header) {
        header($header);
    }
        
    $body = $response->getBody();
    if($body) {
        echo $body;
    }
    
    //we are done with current request...
}