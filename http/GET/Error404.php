<?php
	namespace http\GET;

	use app\View\View;
	use app\Controller\Contratos;

	class Error404 {
		public function main(string $params): void {
			$controller = new Contratos();
			$view = new View();
			$view->addCSSFile('main.css');
			require_once PROJECT_DIRECTORY."/public/View/Error404.php";
		}
	}
?>