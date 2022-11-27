<?php
	namespace app\Controller\Arquivados;

	use app\Controller\Controller;
	use app\View\View;
	use app\Model\Database\Funcionario as FuncionarioModel;

	class Funcionarios extends Controller {
		public function list(int $pageNum): void {
			// Model
			$funcionario = new FuncionarioModel();
			$funcionario->archiveMode = true;
			$funcionario->getAllByPage($pageNum);

			// View
			$view = new View();
			$view->setPageTitle("Funcionários Arquivados");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->addCSSFile('resultslist.css');
			$view->addJSFile('desarquivar.js');
			require_once PROJECT_DIRECTORY.'/public/View/Funcionarios.php';
		}

		public function unarchive(): void {
			$pageNum = 1;

			// Model
			$funcionario = new FuncionarioModel();
			$funcionario->archiveMode = true;
			$archiveReturn = $funcionario->archiveByIds();
			$funcionario->getAllByPage($pageNum);
			$funcionario->getAllByPage($pageNum);
			$_SESSION['error'] = $archiveReturn['error'];
			$_SESSION['errorMsg'] = $archiveReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/funcionarios/arquivados/1");
		}
	}
?>