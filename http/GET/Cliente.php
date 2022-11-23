<?php
	namespace http\GET;

	use app\Controller\Clientes as ClientesController;

	class Cliente {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'new') {
				$controller = new ClientesController();
				$controller->new();
			} else if(count($params) == 1) {
				$controller = new ClientesController();
				$controller->edit($params[0]);
			} else {
				\HttpError::error404();
			}
		}
	}
?>