<?php
	namespace app\Controller;

	use app\Model\Database\User;

	abstract class Controller {
		public function __construct() {
			if($_GET['url'] == 'login/') $_GET['url'] = 'login';

			$url = explode("/", $_GET['url'], 2);

			if( $_GET['url'] != 'login' &&
			    $_GET['url'] != 'login/verify' && 
			    $url[0] != 'contrato' )
			{
				if(!isset($_SESSION['token']) || !isset($_SESSION['userID'])) {
					header("Location: ".DOMAIN."/login");
				} else {
					$user = new User();
					if(!$user->isLogged()) {
						header("Location: ".DOMAIN."/login");
					}
				}
			}
			else if($_GET['url'] == 'login') {
				if(isset($_SESSION['token']) && isset($_SESSION['userID'])) {
					$user = new User();
					if($user->isLogged()) {
						header("Location: ".DOMAIN."/Home");
					}
				}
			}
		}
	}
?>