<?php
	
	require(dirname(__FILE__) . "/vendor/keret/framework/boot.php");
	
	Application::initialize(dirname(__FILE__) . "/app");
	Application::run();
	
?>