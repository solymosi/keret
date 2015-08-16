<?php
	
	/*
		Load the Keret framework
		This assumes that the framework is installed into 'vendor/keret'.
		If it is not, adjust this path accordingly.
	*/
	require(dirname(__FILE__) . "/vendor/keret/framework/boot.php");
	
	/*
		Initialize and run the application
		We pass the path of the 'app' folder to 'initialize' so that the
		framework knows where the application files are located.
		Once initialized, this path can be retrieved using Application::root().
	*/
	Application::initialize(dirname(__FILE__) . "/app");
	Application::run();
	
?>