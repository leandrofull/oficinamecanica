<?php
	namespace http\POST;

	use app\Controller\Veiculos as VeiculosController;
	use app\Controller\Arquivados\Veiculos as VeiculosArquivadosController;

	class Veiculos {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'arquivar') {
				$controller = new VeiculosController();
				$controller->archive();
			} else if(count($params) == 1 && $params[0] == 'desarquivar') {
				$controller = new VeiculosArquivadosController();
				$controller->unarchive();
			} else {
				\HttpError::error404();
				exit;
			}
		}
	}
?>