<?php
	namespace app\Controller\Arquivados;

	use app\Controller\Controller;
	use app\View\View;
	use app\Model\Database\Ordem as OrdemModel;

	class Ordens extends Controller {
		public function list(int $pageNum): void {
			// Model
			$ordem = new OrdemModel();
			$ordem->archiveMode = true;
			$ordem->getAllByPage($pageNum);

			// View
			$view = new View();
			$view->setPageTitle("O.S. Arquivadas");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->addCSSFile('resultslist.css');
			$view->addJSFile('desarquivar.js');
			require_once PROJECT_DIRECTORY.'/public/View/Ordens.php';
		}

		public function unarchive(): void {
			// Model
			$ordem = new OrdemModel();
			$ordem->archiveMode = true;
			$return = $ordem->archiveByIds();

			// View
			header( "Location: ".DOMAIN."/ordens/arquivados/1?registerError=".
					$return["error"]."&registerErrorMsg=".$return["errorMsg"] );
		}
	}
?>