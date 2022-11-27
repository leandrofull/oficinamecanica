<?php
	namespace app\Controller\Arquivados;

	use app\Controller\Controller;
	use app\View\View;
	use app\Model\Database\Veiculo as VeiculoModel;

	class Veiculos extends Controller {
		public function list(int $pageNum): void {
			// Model
			$veiculo = new VeiculoModel();
			$veiculo->archiveMode = true;
			$veiculo->getAllByPage($pageNum);

			// View
			$view = new View();
			$view->setPageTitle("Veículos Arquivados");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->addCSSFile('resultslist.css');
			$view->addJSFile('desarquivar.js');
			require_once PROJECT_DIRECTORY.'/public/View/Veiculos.php';
		}

		public function unarchive(): void {
			$pageNum = 1;

			// Model
			$veiculo = new VeiculoModel();
			$veiculo->archiveMode = true;
			$archiveReturn = $veiculo->archiveByIds();
			$veiculo->getAllByPage($pageNum);
			$_SESSION['error'] = $archiveReturn['error'];
			$_SESSION['errorMsg'] = $archiveReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/veiculos/arquivados/1");
		}
	}
?>