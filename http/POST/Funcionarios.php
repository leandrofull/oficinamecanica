<?php
	namespace http\POST;

	use app\Controller\Funcionarios as FuncionariosController;
	use app\Controller\Arquivados\Funcionarios as FuncionariosArquivadosController;

	class Funcionarios {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'arquivar') {
				$controller = new FuncionariosController();
				$controller->archive();
			} else if(count($params) == 1 && $params[0] == 'desarquivar') {
				$controller = new FuncionariosArquivadosController();
				$controller->unarchive();
			} else {
				\HttpError::error404();
				exit;
			}
		}
	}
?>