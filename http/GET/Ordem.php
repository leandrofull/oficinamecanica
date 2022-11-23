<?php
	namespace http\GET;

	use app\Controller\Ordens as OrdensController;

	class Ordem {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'new') {
				$controller = new OrdensController();
				$controller->new();
			} else if(count($params) == 2 && $params[0] == 'validateData') {
				if($params[1] == 'placa') {
					$controller = new OrdensController();
					$controller->validatePlaca();
				} else if($params[1] == 'matricula') {
					$controller = new OrdensController();
					$controller->validateMatricula();
				} else {
					\HttpError::error404();
				}
			} else if(count($params) == 1) {
				$controller = new OrdensController();
				$controller->edit($params[0]);
			} else {
				\HttpError::error404();
			}
		}
	}
?>