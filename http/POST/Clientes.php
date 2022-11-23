<?php
	namespace http\POST;

	use app\Controller\Clientes as ClientesController;
	use app\Controller\Arquivados\Clientes as ClientesArquivadosController;

	class Clientes {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'arquivar') {
				$controller = new ClientesController();
				$controller->archive();
			} else if(count($params) == 1 && $params[0] == 'desarquivar') {
				$controller = new ClientesArquivadosController();
				$controller->unarchive();
			} else {
				\HttpError::error404();
				exit;
			}
		}
	}
?>