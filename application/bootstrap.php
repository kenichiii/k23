<?php

namespace {

use k23\MCV\DispatcherService;

    require_once 'Bootstrap/Autoload.php';
    
    \App\Bootstrap\Autoload::init();

    $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
    
    $dispatcher = new DispatcherService($url);
    $response = $dispatcher->dispatch();
    
        //serve output
        foreach($response->getHeaders() as $header) {
            header($header);
        }
        
        echo $response->getBody();    
}