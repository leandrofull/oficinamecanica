<?php
	namespace app\Controller;

	use app\View\View;
	use app\Model\Database\Contrato as ContratoModel;

	class Contratos extends Controller {
		public function show(string $contratoID): void {
			// Modal
			$teste = preg_match("/[^a-zA-Z0-9]/", $contratoID);
			if($teste) {
				\HttpError::error404();
				exit;
			}

			$contrato = new ContratoModel();
			$contrato->showById($contratoID);

			// View
			$view = new View();
			require_once PROJECT_DIRECTORY."/public/View/Contrato.php";
		}

		public function canvas(string $contratoID): void {
			// Modal
			$teste = preg_match("/[^a-zA-Z0-9]/", $contratoID);
			if($teste) {
				\HttpError::error404();
				exit;
			}

			$contrato = new ContratoModel();
			$contrato->canvas($contratoID);
		}

		public function accept(string $contratoID): void {
			// Modal
			$teste = preg_match("/[^a-zA-Z0-9]/", $contratoID);
			if($teste) {
				\HttpError::error404();
				exit;
			}

			$contrato = new ContratoModel();
			$contrato->accept($contratoID);
		}

		public function notAccept(string $contratoID): void {
			// Modal
			$teste = preg_match("/[^a-zA-Z0-9]/", $contratoID);
			if($teste) {
				\HttpError::error404();
				exit;
			}

			$contrato = new ContratoModel();
			$contrato->notAccept($contratoID);
		}
	}
?>