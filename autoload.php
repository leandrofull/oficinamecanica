<?php
	spl_autoload_register(function($class) {
		$class = str_replace("app\\", "", $class);
		$class = str_replace("\\", "/", $class);
		if(file_exists(PROJECT_DIRECTORY."/app/".$class.".php")) {
			require_once PROJECT_DIRECTORY."/app/".$class.".php";
		}
	});
?>