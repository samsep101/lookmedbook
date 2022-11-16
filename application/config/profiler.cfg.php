<?php

    define('PROFILE_ENABLE', false);
    define('PROFILE_MYSQL', true);

    // db or file
    define('PROFILE_LOG_TYPE', 'file');

	$profiler = ProfilerFactory::getProfiler();
	$profiler->setIp(empty($_SERVER['REMOTE_ADDR'])?'127.0.0.1':$_SERVER['REMOTE_ADDR']);

	if(isset($_SERVER['HTTP_REFERER']))
		$profiler->setReferer($_SERVER['HTTP_REFERER']);

	$profiler->setParams(serialize($_REQUEST));


