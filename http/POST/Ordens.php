<?php
	namespace http\POST;

	use app\Controller\Ordens as OrdensController;
	use app\Controller\Arquivados\Ordens as OrdensArquivadasController;

	class Ordens {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(count($params) == 1 && $params[0] == 'arquivar') {
				$controller = new OrdensController();
				$controller->archive();
			} else if(count($params) == 1 && $params[0] == 'desarquivar') {
				$controller = new OrdensArquivadasController();
				$controller->unarchive();
			} else {
				\HttpError::error404();
				exit;
			}
		}
	}
?>