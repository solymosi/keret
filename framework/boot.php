<?php

	require(dirname(__FILE__) . "/autoload.class.php");
	
	Autoload::initialize();
	Autoload::registerPath(dirname(__FILE__));
	Autoload::registerPath(dirname(__FILE__) . "/vendor");