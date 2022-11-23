<?php
	namespace http\POST;

	use app\Controller\Clientes as ClientesController;

	class Cliente {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'cadastrar') {
				$controller = new ClientesController();
				$controller->register();
			} else if(count($params) == 1 && $params[0] == 'editar') {
				$controller = new ClientesController();
				$controller->save();
			} else {
				\HttpError::error404();
			}
		}
	}
?>