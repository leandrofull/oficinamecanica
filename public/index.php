<?php
	require_once '../config.php';
	require_once '../autoload.php';
	require_once PROJECT_DIRECTORY."/http/Route.php";
	require_once PROJECT_DIRECTORY."/http/HttpError.php";

	if(is_dir($_GET['url']) && $_GET['url'][strlen($_GET['url'])-1] != '/') {
		header("Location: ".DOMAIN."/".$_GET['url']."/");
		exit;
	}

	session_start();

	Route::start();
?>