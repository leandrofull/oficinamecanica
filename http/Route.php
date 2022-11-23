<?php
	class Route {
		public static function start(): void {
			self::putEnv();

			$url = explode("/", $_GET['url'], 2);

			$url_main = 'Home';
			$params = '';

			if($url[0] != 'index.php')
				$url_main = ucfirst(strtolower($url[0]));

			if(isset($url[1]))
				$params = $url[1];

			if(!empty($params) && $params[strlen($params)-1] == "/")
				$params = substr($params, 0, strlen($params)-1);

			if(file_exists(PROJECT_DIRECTORY."/http/".$_SERVER['REQUEST_METHOD']."/".$url_main.".php")) {
				require_once PROJECT_DIRECTORY."/http/".$_SERVER['REQUEST_METHOD']."/".$url_main.".php";
				$class = "http\\".$_SERVER['REQUEST_METHOD']."\\".$url_main;
				$obj = new $class;
				$obj->main($params);
				exit;
			}

			HttpError::error404();
		}

		private static function putEnv(): void {
			$file = explode("\n", file_get_contents(PROJECT_DIRECTORY."/.env"));

			for($i=0; $i<count($file); $i++) { 
				putenv(trim($file[$i]));
			}
		}
	}
?>