<?php
	namespace http\GET;

	use app\Controller\Ordens as OrdensController;
	use app\Controller\Arquivados\Ordens as OrdensArquivadasController;

	class Ordens {
		public function main(string $params): void {
			$params = explode("/", $params);

			if(empty($params[0]) && $params[0] != "0")
				$params[0] = "1";

			if($params[0] == 'arquivados' && count($params) == 2) {
				if(!ctype_digit($params[1]) || $params[1] < 1) {
					\HttpError::error404();
					exit;
				}
				$controller = new OrdensArquivadasController();
				$controller->list($params[1]);
				exit;
			} else if(count($params) > 1 || !ctype_digit($params[0]) || $params[0] < 1) {
				\HttpError::error404();
				exit;
			}

			$controller = new OrdensController();
			$controller->list($params[0]);
		}
	}
?>