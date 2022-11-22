<?php

ApiRoute::group(['namespace' => 'Modules\Testdummy\Http\Controllers', 'middleware' => 'api.auth'], function() {
    // ApiRoute::resource('/testdummy', 'TestdummyController');
});