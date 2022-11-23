<?php
	namespace http\POST;

	use app\Controller\Login as LoginController;

	class Login {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'verify') {
				$controller = new LoginController();
				$controller->verify();
			} else {
				\HttpError::error404();
			}
		}
	}
?>