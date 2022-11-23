<?php
	namespace app\Model\Database;

	use app\Model\Model;

	abstract class Database extends Model {
		private string $lastInsertId;

		private function getConnection(): \PDO {
			$bdHost = getenv('BD_HOST');
			$bdName = getenv('BD_NAME');
			$bdUser = getenv('BD_USER');
			$bdPass = sha1(getenv('BD_PASSWORD'));

			try {
				$conexao = new \PDO('mysql:host='.$bdHost.';dbname='.$bdName, $bdUser, $bdPass);
			} catch(\PDOException $e) {
				echo "ERRO! Contate o suporte ou o administrador para mais informações.";
				exit;
				die;
			}

			return $conexao;
		}

		protected function getLastInsertId(): string {
			return $this->lastInsertId;
		}

		protected function select(string $targets, string $table, string $filter): \PDOStatement {
			try {
				$res = $this->getConnection()->query("SELECT {$targets} FROM {$table} {$filter}");
			} catch(\PDOException $e) {
				echo "ERRO! Contate o suporte ou o administrador para mais informações.";
				exit;
				die;
			}

			return $res;
		}

		protected function insert(string $table, string $columns, string $values): \PDOStatement {
			try {
				$connection = $this->getConnection();
				$res = $connection->query("INSERT INTO {$table} ({$columns}) VALUES ({$values})");
			} catch(\PDOException $e) {
				echo "ERRO! Contate o suporte ou o administrador para mais informações.";
				exit;
				die;
			}

			$this->lastInsertId = $connection->lastInsertId();
			return $res;
		}

		protected function delete(string $table, string $filter): \PDOStatement {
			try {
				$res = $this->getConnection()->query("DELETE FROM {$table} {$filter}");
			} catch(\PDOException $e) {
				echo "ERRO! Contate o suporte ou o administrador para mais informações.";
				exit;
				die;
			}

			return $res;
		}

		protected function update(string $table, array $columns, array $values, string $filter): \PDOStatement
		{
			try {
				$query = "UPDATE {$table} SET ";
				$firstInstance = true;
				foreach($columns as $key => $value) {
					if($firstInstance) {
						$firstInstance = false;
					} else {
						$query .= ",";
					}

					$query .= $value."=".$values[$key];
				}

				$query .= " ".$filter;
		
				$res = $this->getConnection()->query($query);
			} catch(\PDOException $e) {
				echo "ERRO! Contate o suporte ou o administrador para mais informações.";
				exit;
				die;
			}

			return $res;
		}
	}
?>