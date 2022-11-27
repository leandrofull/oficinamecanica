<?php
	namespace app\Controller\Arquivados;

	use app\Controller\Controller;
	use app\View\View;
	use app\Model\Database\Cliente as ClienteModel;

	class Clientes extends Controller {
		public function list(int $pageNum): void {
			// Model
			$cliente = new ClienteModel();
			$cliente->archiveMode = true;
			$cliente->getAllByPage($pageNum);

			// View
			$view = new View();
			$view->setPageTitle("Clientes Arquivados");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->addCSSFile('resultslist.css');
			$view->addJSFile('desarquivar.js');
			require_once PROJECT_DIRECTORY.'/public/View/Clientes.php';
		}

		public function unarchive(): void {
			$pageNum = 1;

			// Model
			$cliente = new ClienteModel();
			$cliente->archiveMode = true;
			$archiveReturn = $cliente->archiveByIds();
			$cliente->getAllByPage($pageNum);
			$_SESSION['error'] = $archiveReturn['error'];
			$_SESSION['errorMsg'] = $archiveReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/clientes/arquivados/1");
		}

	}
?>