<?php

	require(dirname(__FILE__) . "/autoload.class.php");
	
	Autoload::initialize();
	Autoload::registerPath(dirname(__FILE__));
	Autoload::registerPath(dirname(__FILE__) . "/form");
	Autoload::registerPath(dirname(__FILE__) . "/i18n");
	Autoload::registerPath(dirname(__FILE__) . "/vendor");