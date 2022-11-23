<?php
	namespace http\GET;

	use app\Controller\Funcionarios as FuncionariosController;

	class Funcionario {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'new') {
				$controller = new FuncionariosController();
				$controller->new();
			} else if(count($params) == 1) {
				$controller = new FuncionariosController();
				$controller->edit($params[0]);
			} else {
				\HttpError::error404();
			}
		}
	}
?>