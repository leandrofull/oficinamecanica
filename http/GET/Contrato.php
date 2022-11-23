<?php
	namespace http\GET;

	use app\Controller\Contratos as ContratosController;

	class Contrato {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && !empty($params[0])) {
				$controller = new ContratosController();
				$controller->show($params[0]);
			} else if(count($params) == 2 && !empty($params[1]) && $params[0] == 'canvas') {
				$controller = new ContratosController();
				$controller->canvas($params[1]);
			} else if(count($params) == 2 && !empty($params[1]) && $params[1] == 'accept') {
				$controller = new ContratosController();
				$controller->accept($params[0]);
			} else if(count($params) == 2 && !empty($params[1]) && $params[1] == 'notAccept') {
				$controller = new ContratosController();
				$controller->notAccept($params[0]);
			} else {
				\HttpError::error404();
			}
		}
	}
?>