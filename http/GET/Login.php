<?php
	namespace http\GET;

	use app\Controller\Login as LoginController;

	class Login {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(empty($params[0])) {
				$controller = new LoginController();
				$controller->telaLogin();
			} else if($params[0] == 'logout') {
				$controller = new LoginController();
				$controller->logout();
			} else {
				\HttpError::error404();
			}
		}
	}
?>