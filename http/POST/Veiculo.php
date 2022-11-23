<?php
	namespace http\POST;

	use app\Controller\Veiculos as VeiculosController;

	class Veiculo {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'cadastrar') {
				$controller = new VeiculosController();
				$controller->register();
			} else if(count($params) == 1 && $params[0] == 'editar') {
				$controller = new VeiculosController();
				$controller->save();
			} else {
				\HttpError::error404();
			}
		}
	}
?>