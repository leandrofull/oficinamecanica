<?php
	namespace http\POST;

	use app\Controller\Ordens as OrdensController;

	class Ordem {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'cadastrar') {
				$controller = new OrdensController();
				$controller->register();
			} else if(count($params) == 1 && $params[0] == 'excluirVeiculoFotos') {
				$controller = new OrdensController();
				$controller->deleteVeiculoFotos();
			} else if(count($params) == 1 && $params[0] == 'editar') {
				$controller = new OrdensController();
				$controller->save();
			} else if(count($params) == 1 && $params[0] == 'sendOS') {
				$controller = new OrdensController();
				$controller->sendOS();
			} else if(count($params) == 1 && $params[0] == 'finalize') {
				$controller = new OrdensController();
				$controller->finalize();
			} else {
				\HttpError::error404();
			}
		}
	}
?>