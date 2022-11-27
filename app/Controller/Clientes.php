<?php
	namespace app\Controller;

	use app\View\View;
	use app\Model\Database\Cliente as ClienteModel;

	class Clientes extends Controller {
		public function list(int $pageNum): void {
			// Model
			$cliente = new ClienteModel();
			$cliente->getAllByPage($pageNum);

			// View
			$view = new View();
			$view->setPageTitle(APP_NAME." - Clientes");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->addCSSFile('resultslist.css');
			$view->addJSFile('arquivar.js');
			$view->setNavLinkActive('clientes');
			require_once PROJECT_DIRECTORY.'/public/View/Clientes.php';
		}

		public function archive(): void {
			$pageNum = 1;

			// Model
			$cliente = new ClienteModel();
			$archiveReturn = $cliente->archiveByIds();
			$cliente->getAllByPage($pageNum);
			$_SESSION['error'] = $archiveReturn['error'];
			$_SESSION['errorMsg'] = $archiveReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/clientes");
		}

		public function new(): void {
			// Model
			$cliente = new ClienteModel();

			// View
			$view = new View();
			$view->setPageTitle("Novo Cliente");
			$view->addCSSFile('main.css');
			$view->addCSSFile('formCadastro.css');
			$view->addJSFile('novocliente.js');
			require_once PROJECT_DIRECTORY.'/public/View/NovoCliente.php';
		}

		public function edit(string $clienteID): void {
			// Model
			$cliente = new ClienteModel();
			if(!ctype_digit($clienteID)){
				\HttpError::error404();
				exit;
			} 
			$cliente->getInfoByID($clienteID);

			// View
			$view = new View();
			$view->setPageTitle("Visualizar/Editar Cliente");
			$view->addCSSFile('main.css');
			$view->addCSSFile('formCadastro.css');
			$view->addJSFile('novocliente.js');
			require_once PROJECT_DIRECTORY.'/public/View/NovoCliente.php';
		}

		public function register(): void {
			$pageNum = 1;

			// Model
			$cliente = new ClienteModel();
			$registerReturn = $cliente->register();
			$cliente->getAllByPage($pageNum);
			$_SESSION['error'] = $registerReturn['error'];
			$_SESSION['errorMsg'] = $registerReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/clientes");
		}

		public function save(): void {
			$pageNum = 1;

			// Model
			$cliente = new ClienteModel();
			$editReturn = $cliente->edit();
			$cliente->getAllByPage($pageNum);
			$_SESSION['error'] = $editReturn['error'];
			$_SESSION['errorMsg'] = $editReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/clientes");
		}
	}
?>