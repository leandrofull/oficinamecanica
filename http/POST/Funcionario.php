<?php
	namespace http\POST;

	use app\Controller\Funcionarios as FuncionariosController;

	class Funcionario {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'cadastrar') {
				$controller = new FuncionariosController();
				$controller->register();
			} else if(count($params) == 1 && $params[0] == 'editar') {
				$controller = new FuncionariosController();
				$controller->save();
			} else {
				\HttpError::error404();
			}
		}
	}
?>