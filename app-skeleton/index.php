<?php
	
	require(dirname(__FILE__) . "/framework/boot.php");
	
	Application::initialize(dirname(__FILE__) . "/app");
	Application::run();
	
?>