<?php
	namespace app\Controller;

	use app\View\View;
	use app\Model\Database\User as UserModel;

	class Login extends Controller {
		public function telaLogin(): void {
			// View
			$view = new View();
			$view->setPageTitle(APP_NAME." - Login");
			require_once PROJECT_DIRECTORY.'/public/View/Login.php';
		}

		public function verify(): void {
			// Model
			$user = new UserModel();
			if($user->verify()) echo json_encode( array("logged"=>"1", "msg"=>"Você foi logado com sucesso!" ));
			else echo json_encode( array("logged"=>"0", "msg"=>"ERRO! Usuário e/ou senha inválido(s)." ));
		}

		public function logout(): void {
			// Model
			$user = new UserModel();
			$user->logout();
			header("Location: ".DOMAIN."/login");
		}
	}
?>