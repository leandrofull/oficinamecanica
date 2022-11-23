<?php
	namespace http\GET;

	use app\Controller\Veiculos as VeiculosController;

	class Veiculo {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'new') {
				$controller = new VeiculosController();
				$controller->new();
			} else if(count($params) == 1 && $params[0] == 'validatePropCPF') {
				$controller = new VeiculosController();
				$controller->validatePropCPF();
			} else if(count($params) == 1) {
				$controller = new VeiculosController();
				$controller->edit($params[0]);
			} else {
				\HttpError::error404();
			}
		}
	}
?>