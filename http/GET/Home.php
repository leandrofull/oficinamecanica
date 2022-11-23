<?php
	namespace http\GET;

	use app\Controller\Home as HomeController;

	class Home {
		public function main(string $params): void {
			if(!empty($params)) {
				\HttpError::error404();
				exit;
			}

			$controller = new HomeController();
			$controller->index();
		}
	}
?>