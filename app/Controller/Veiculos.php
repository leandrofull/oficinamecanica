<?php
	namespace app\Controller;

	use app\View\View;
	use app\Model\Database\Veiculo as VeiculoModel;

	class Veiculos extends Controller {
		public function list(int $pageNum): void {
			// Model
			$veiculo = new VeiculoModel();
			$veiculo->getAllByPage($pageNum);

			// View
			$view = new View();
			$view->setPageTitle(APP_NAME." - Veículos");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->addCSSFile('resultslist.css');
			$view->addJSFile('arquivar.js');
			$view->setNavLinkActive('veiculos');
			require_once PROJECT_DIRECTORY.'/public/View/Veiculos.php';
		}

		public function archive(): void {
			$pageNum = 1;

			// Model
			$veiculo = new VeiculoModel();
			$archiveReturn = $veiculo->archiveByIds();
			$veiculo->getAllByPage($pageNum);
			$_SESSION['error'] = $archiveReturn['error'];
			$_SESSION['errorMsg'] = $archiveReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/veiculos");
		}

		public function new(): void {
			// Model
			$veiculo = new VeiculoModel();

			// View
			$view = new View();
			$view->setPageTitle("Novo Veículo");
			$view->addCSSFile('main.css');
			$view->addCSSFile('formCadastro.css');
			$view->addJSFile('novoveiculo.js');
			require_once PROJECT_DIRECTORY.'/public/View/NovoVeiculo.php';
		}

		public function validatePropCPF(): void {
			// Model
			$veiculo = new VeiculoModel();
			$proprietario = $veiculo->validatePropCPF();

			if(!empty($proprietario)) {
				echo json_encode(["proprietario"=>$proprietario]);
			} else {
				\HttpError::error404();
				exit;
			}
		}

		public function edit(string $veiculoID): void {
			// Model
			$veiculo = new VeiculoModel();
			if(!ctype_digit($veiculoID)){
				\HttpError::error404();
				exit;
			} 
			$veiculo->getInfoByID($veiculoID);

			// View
			$view = new View();
			$view->setPageTitle("Visualizar/Editar Veículo");
			$view->addCSSFile('main.css');
			$view->addCSSFile('formCadastro.css');
			$view->addJSFile('novoveiculo.js');
			require_once PROJECT_DIRECTORY.'/public/View/NovoVeiculo.php';
		}

		public function register(): void {
			$pageNum = 1;

			// Model
			$veiculo = new VeiculoModel();
			$registerReturn = $veiculo->register();
			$veiculo->getAllByPage($pageNum);
			$_SESSION['error'] = $registerReturn['error'];
			$_SESSION['errorMsg'] = $registerReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/veiculos");
		}

		public function save(): void {
			$pageNum = 1;

			// Model
			$veiculo = new VeiculoModel();
			$editReturn = $veiculo->edit();
			$veiculo->getAllByPage($pageNum);
			$_SESSION['error'] = $editReturn['error'];
			$_SESSION['errorMsg'] = $editReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/veiculos");
		}
	}
?>