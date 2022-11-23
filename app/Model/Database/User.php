<?php
	namespace app\Model\Database;

	class User extends Database {
		public function getNome(): string {
			$tmp = $this->select("nome", "usuarios", "WHERE id = {$_SESSION['userID']}");
			$nome = $tmp->fetch()['nome'];
			return $nome;
		}

		public function isLogged(): bool {
			if(!ctype_digit($_SESSION['userID'])) {
				return false;
			}
		
			$tmp = $this->select("token", "usuarios", "WHERE id = {$_SESSION['userID']}");
			$count = $tmp->rowCount();

			if($count < 1) {
				return false;
			}

			$token = $tmp->fetch()['token'];

			if($token != sha1($_SESSION['token']) || empty($token) || empty($_SESSION['token'])) {
				return false;
			}

			return true;
		}

		public function verify(): bool {
			$testeLogin = preg_replace('/[\\\"\'\ ]/', '', $_POST['login']);
			$testeSenha = preg_replace('/[\\\"\'\ ]/', '', $_POST['senha']);
			if($testeLogin != $_POST['login']) return false;
			if($testeSenha != $_POST['senha']) return false;
			
			$tmp = $this->select("id, login, senha", "usuarios", "WHERE login = '{$_POST['login']}'");

			$count = $tmp->rowCount();

			if($count < 1) return false;

			$result = $tmp->fetch();
			$senha = $result['senha'];

			if($senha != sha1($_POST['senha'])) return false;

			session_regenerate_id();
			$token = rand();
			$_SESSION['userID'] = $result["id"];
			$_SESSION['token'] = $token;
			$token = sha1($token);
			$this->update("usuarios", ["token"], ["'{$token}'"],"WHERE id = {$_SESSION['userID']}");
			return true;
		}

		public function logout(): void {
			$this->update("usuarios", ["token"], ["''"],"WHERE id = {$_SESSION['userID']}");
			session_destroy();
		}
	}
?>