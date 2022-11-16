<?php
    $controller_decorators = array(
		/*array(
			'instanceof' => 'ApiController',
			'decorator_class_name' => 'MobileApiCacheDecorator'
		),*/
        array(
            'instanceof' => 'ApiController',
            'decorator_class_name' => 'MobileApiCacheDecorator'
        ),
    );


    Register::add('controller_decorators', $controller_decorators);
