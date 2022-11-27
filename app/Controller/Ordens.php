<?php
	namespace app\Controller;

	use app\View\View;
	use app\Model\Database\Ordem as OrdemModel;

	class Ordens extends Controller {
		public function list(int $pageNum): void {
			// Model
			$ordem = new OrdemModel();
			$ordem->getAllByPage($pageNum);

			// View
			$view = new View();
			$view->setPageTitle(APP_NAME." - Ordens de Serviço");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->addCSSFile('resultslist.css');
			$view->addJSFile('arquivar.js');
			$view->setNavLinkActive('ordens');
			require_once PROJECT_DIRECTORY.'/public/View/Ordens.php';
		}

		public function archive(): void {
			// Model
			$ordem = new OrdemModel();
			$return = $ordem->archiveByIds();
			$_SESSION['error'] = $return['error'];
			$_SESSION['errorMsg'] = $return['errorMsg'];

			// View
			header("Location: ".DOMAIN."/ordens");
		}

		public function new(): void {
			// Model
			$ordem = new OrdemModel();

			// View
			$view = new View();
			$view->setPageTitle("Nova O.S.");
			$view->addCSSFile('main.css');
			$view->addCSSFile('formCadastro.css');
			$view->addJSFile('novaordem.js');
			require_once PROJECT_DIRECTORY.'/public/View/NovaOrdem.php';
		}

		public function validatePlaca(): void {
			// Model
			$ordem = new OrdemModel();
			$array = $ordem->validatePlaca();

			if(!empty($array['propCPF'])) {
				echo json_encode($array);
			} else {
				\HttpError::error404();
				exit;
			}
		}

		public function validateMatricula(): void {
			// Model
			$ordem = new OrdemModel();
			$responsavel = $ordem->validateMatricula();

			if(!empty($responsavel)) {
				echo json_encode(["responsavel"=>$responsavel]);
			} else {
				\HttpError::error404();
				exit;
			}
		}

		public function edit(string $ordemID): void {
			// Model
			$ordem = new OrdemModel();
			if(!ctype_digit($ordemID)){
				\HttpError::error404();
				exit;
			} 
			$ordem->getInfoByID($ordemID);

			// View
			$view = new View();
			$view->setPageTitle("Visualizar/Editar O.S.");
			$view->addCSSFile('main.css');
			$view->addCSSFile('formCadastro.css');
			$view->addJSFile('novaordem.js');
			$view->addJSFile('excluirVeiculoFotos.js');
			require_once PROJECT_DIRECTORY.'/public/View/NovaOrdem.php';
		}

		public function register(): void {
			// Model
			$ordem = new OrdemModel();
			$return = $ordem->register();
			$_SESSION['error'] = $return['error'];
			$_SESSION['errorMsg'] = $return['errorMsg'];

			// View
			header("Location: ".DOMAIN."/ordens");
		}

		public function deleteVeiculoFotos(): void {
			// Model
			$ordem = new OrdemModel();
			$ordem->deleteVeiculoFotos();
		}

		public function save(): void {
			$pageNum = 1;

			// Model
			$ordem = new OrdemModel();
			$return = $ordem->edit();
			$_SESSION['error'] = $return['error'];
			$_SESSION['errorMsg'] = $return['errorMsg'];

			// View
			header("Location: ".DOMAIN."/ordens");
		}

		public function sendOS(): void {
			// Model
			$ordem = new OrdemModel();
			$ordem->send();
		}

		public function finalize(): void {
			// Model
			$ordem = new OrdemModel();
			$ordem->finalize();
		}
	}
?>