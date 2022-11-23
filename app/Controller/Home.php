<?php
	namespace app\Controller;

	use app\View\View;
	use app\Model\Database\User;
	use app\Model\Database\Cliente;
	use app\Model\Database\Veiculo;
	use app\Model\Database\Funcionario;
	use app\Model\Database\Ordem;

	class Home extends Controller {
		public function index(): void {
			// Model
			$user = new User();
			$cliente = new Cliente();
			$veiculo = new Veiculo();
			$funcionario = new Funcionario();
			$ordem = new Ordem();

			// View
			$view = new View();
			$view->setPageTitle(APP_NAME." - Home");
			$view->addCSSFile('main.css');
			$view->addCSSFile('home.css');
			$view->setNavLinkActive('home');
			require_once PROJECT_DIRECTORY.'/public/View/Home.php';
		}
	}
?>