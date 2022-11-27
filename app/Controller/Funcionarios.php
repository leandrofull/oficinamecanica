<?php
	namespace app\Controller;

	use app\View\View;
	use app\Model\Database\Funcionario as FuncionarioModel;

	class Funcionarios extends Controller {
		public function list(int $pageNum): void {
			// Model
			$funcionario = new FuncionarioModel();
			$funcionario->getAllByPage($pageNum);

			// View
			$view = new View();
			$view->setPageTitle(APP_NAME." - Funcionários");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->addCSSFile('resultslist.css');
			$view->addJSFile('arquivar.js');
			$view->setNavLinkActive('funcionarios');
			require_once PROJECT_DIRECTORY.'/public/View/Funcionarios.php';
		}

		public function archive(): void {
			$pageNum = 1;

			// Model
			$funcionario = new FuncionarioModel();
			$archiveReturn = $funcionario->archiveByIds();
			$funcionario->getAllByPage($pageNum);
			$_SESSION['error'] = $archiveReturn['error'];
			$_SESSION['errorMsg'] = $archiveReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/funcionarios");
		}

		public function new(): void {
			// Model
			$funcionario = new FuncionarioModel();

			// View
			$view = new View();
			$view->setPageTitle("Novo Funcionário");
			$view->addCSSFile('main.css');
			$view->addCSSFile('formCadastro.css');
			$view->addJSFile('novofuncionario.js');
			require_once PROJECT_DIRECTORY.'/public/View/NovoFuncionario.php';
		}

		public function edit(string $funcionarioID): void {
			// Model
			$funcionario = new FuncionarioModel();
			if(!ctype_digit($funcionarioID)){
				\HttpError::error404();
				exit;
			} 
			$funcionario->getInfoByID($funcionarioID);

			// View
			$view = new View();
			$view->setPageTitle("Visualizar/Editar Funcionario");
			$view->addCSSFile('main.css');
			$view->addCSSFile('formCadastro.css');
			$view->addJSFile('novofuncionario.js');
			require_once PROJECT_DIRECTORY.'/public/View/NovoFuncionario.php';
		}

		public function register(): void {
			$pageNum = 1;

			// Model
			$funcionario = new FuncionarioModel();
			$registerReturn = $funcionario->register();
			$funcionario->getAllByPage($pageNum);
			$_SESSION['error'] = $registerReturn['error'];
			$_SESSION['errorMsg'] = $registerReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/funcionarios");
		}

		public function save(): void {
			$pageNum = 1;

			// Model
			$funcionario = new FuncionarioModel();
			$editReturn = $funcionario->edit();
			$funcionario->getAllByPage($pageNum);
			$_SESSION['error'] = $editReturn['error'];
			$_SESSION['errorMsg'] = $editReturn['errorMsg'];

			// View
			header("Location: ".DOMAIN."/funcionarios");
		}
	}
?>