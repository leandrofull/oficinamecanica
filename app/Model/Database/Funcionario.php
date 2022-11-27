<?php
	namespace app\Model\Database;

	use app\Controller\InputCleaning\Funcionarios as InputCleaning;

	class Funcionario extends Database {
		private array $id = array('');
		private array $nome = array('');
		private array $matricula = array('');
		private array $cpf = array('');
		private array $cargo = array('');
		private array $arquivado = array('');
		private array $remuneracao = array('');
		private array $dataNascimento = array('');
		private array $dataAdmissao = array('');
		private array $dataDemissao = array('');
		private array $dataCadastro = array('');
		private array $dataUltimaModificacao = array('');
		private int $resultsCountAll = 0;
		private int $resultsCountPage = 0;
		private string $archiveMode = "0";

		public function getAllByPage(int $pageNum): void {
			if($pageNum == 1) $pageNum = 0;
			else $pageNum = ($pageNum-1)*TABLE_ROWS;

			//PESQUISA
			$pesquisa = "";
			if( isset($_GET['search']) &&
				(!empty($_GET['search']) || $_GET['search'] == "0") )
			{
				$_GET['search'] = preg_replace('/[^A-Za-zÀ-Úà-ú0-9\#\(\)\-\.\_\ ]/', "", $_GET['search']);
				
				$name = preg_replace("/\s{2,}/", " ", trim($_GET['search']));
				if(mb_strlen($name) < 1) $name = "156115635";

				$pesquisa = " AND (nome LIKE '%$name%'";

				$cargo = preg_replace("/\s{2,}/", " ", trim($_GET['search']));
				if(mb_strlen($cargo) < 1) $cargo = "156115635";

				$pesquisa .= " OR cargo LIKE '%$cargo%'";

				$cpf = str_replace(".", "", trim($_GET['search']));
				$cpf = str_replace("-", "", $cpf);
				if(!ctype_digit($cpf)) $cpf = "FAILED";
				
				$pesquisa .= " OR REPLACE(REPLACE(cpf, '-', ''), '.', '') LIKE '%$cpf%'";

				$matricula = trim($_GET['search']);
				if(!ctype_digit($matricula)) $matricula = "FAILED";

				$pesquisa .= " OR matricula LIKE '%$matricula%')";
			}
			//PESQUISA

			$results = $this->select("id, nome, matricula, cargo, arquivado, ultima_modificacao", "funcionarios", "WHERE arquivado = ".$this->archiveMode.$pesquisa." ORDER BY id desc LIMIT {$pageNum}, ".TABLE_ROWS);
			$resultsCountAll = $this->select("COUNT(*) as resultsCountAll", "funcionarios", "WHERE arquivado = ".$this->archiveMode.$pesquisa)->fetch();
			$this->resultsCountAll = $resultsCountAll['resultsCountAll'];
			$this->resultsCountPage = $results->rowCount();

			$results = $results->fetchAll();
			for($i=0;$i<count($results);$i++) {
				$this->id[$i] = $results[$i]["id"];

				$this->arquivado[$i] = $results[$i]["arquivado"];

				if(mb_strlen($results[$i]["nome"]) > 30)
					$this->nome[$i] = substr($results[$i]["nome"], 0, 30)."...";
				else
					$this->nome[$i] = $results[$i]["nome"];

				$this->matricula[$i] = $results[$i]["matricula"];

				if(mb_strlen($results[$i]["cargo"]) > 20)
					$this->cargo[$i] = substr($results[$i]["cargo"], 0, 20)."...";
				else
					$this->cargo[$i] = $results[$i]["cargo"];

				$this->dataUltimaModificacao[$i] = explode(" ", $results[$i]["ultima_modificacao"]);
				$this->dataUltimaModificacao[$i] = explode("-", $this->dataUltimaModificacao[$i][0]);
				$this->dataUltimaModificacao[$i] = $this->dataUltimaModificacao[$i][2]."/".$this->dataUltimaModificacao[$i][1]."/".$this->dataUltimaModificacao[$i][0];
			}
		}

		public function getInfoByID(int $funcionarioID): void {
			$result = $this->select("*", "funcionarios", "WHERE id = $funcionarioID");
			$count = $result->rowCount();

			if($count < 1) {
				\HttpError::error404();
				exit;
			} else {
				$result = $result->fetch();
				$this->id[0] = $result['id'];
				$this->nome[0] = $result['nome'];
				$this->cargo[0] = $result['cargo'];
				$this->matricula[0] = $result['matricula'];
				$this->cpf[0] = $result['cpf'];
				$this->remuneracao[0] = $result['remuneracao'];
				$this->dataNascimento[0] = $result['data_nascimento'];
				$this->dataAdmissao[0] = $result['data_admissao'];
				$this->dataDemissao[0] = $result['data_demissao'];
			}
		}

		public function archiveByIds(): array {
			$error = false;
			$errorMsg = '';

			if(isset($_POST['selectedItems']) && count($_POST['selectedItems']) > 0) {
				foreach($_POST['selectedItems'] as $key => $value) {
					if(ctype_digit($_POST['selectedItems'][$key])) {
						$_POST['selectedItems'][$key] = intval($value);

						$filter = "WHERE id = ".$_POST['selectedItems'][$key];

						if($this->archiveMode) {
							$filter .= " AND arquivado = true";
						} else {
							$filter .= " AND arquivado = false";

						}

						$result = $this->select("id", "funcionarios", $filter);

						$resultCount = $result->rowCount();

						if($resultCount < 1) {
							$error = true;
							if($this->archiveMode) {
								$errorMsg = 'ERRO! Um dos funcionários selecionados não existe ou não está arquivado.';
							} else {
								$errorMsg = 'ERRO! Um dos funcionários selecionados não existe ou já está arquivado.';
							}
							break;
						}
					} else {
						$error = true;
						$errorMsg = 'ERRO! Parâmetros de entrada incorretos.';
						break;
					}
				}
			} else {
				$error = true;
				$errorMsg = 'ERRO! Nenhum funcionário foi selecionado.';
			}

			if(!$error) {
				foreach($_POST['selectedItems'] as $key => $value) {
					if($this->archiveMode) {
						$this->update('funcionarios', ['arquivado', 'ultima_modificacao'], ['false', "'".date('Y-m-d H:i:s')."'"], "WHERE id=".$value);
					} else {
						$this->update('funcionarios', ['arquivado', 'ultima_modificacao'], ['true', "'".date('Y-m-d H:i:s')."'"], "WHERE id=".$value);
					}
				}
				if($this->archiveMode) $errorMsg = count($_POST['selectedItems'])." funcionários foram desarquivados com sucesso!";
				else $errorMsg = count($_POST['selectedItems'])." funcionários foram arquivados com sucesso!";
			}

			return [
			"error" => $error,
			"errorMsg" => $errorMsg];
		}

		public function register(): array {
			$error = false;
			$errorMsg = '';

			InputCleaning::clean();
		
			$testeLogico1 = empty($_POST['inputName']) || empty($_POST['inputCargo']);
			$testeLogico2 = empty($_POST['inputMatricula']) || empty($_POST['inputCPF']);

			if($testeLogico1 || $testeLogico2) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível realizar o cadastro. Consulte o administrador ou o suporte.";
			} else {
				extract($_POST);

				$result1 = $this->select("id", "funcionarios", "WHERE cpf = '$inputCPF'")->rowCount();
				$result2 = $this->select("id", "funcionarios", "WHERE matricula = '$inputMatricula'")->rowCount();

				if($result1 > 0) {
					$error = true;
					$errorMsg = 'ERRO! Já existe um funcionário cadastrado com o CPF informado.';
				} else if($result2 > 0) {
					$error = true;
					$errorMsg = 'ERRO! Já existe um funcionário cadastrado com a matrícula informada.';
				} else {
					$table = 'funcionarios';

					$columns = "id, nome, cargo, matricula, cpf, remuneracao, data_nascimento, ";
					$columns .= "data_admissao, data_demissao";				

					$values = "null, '$inputName', '$inputCargo', '$inputMatricula', ";
					$values .= "'$inputCPF', '$inputRemuneracao', '$inputNasc', ";
					$values .= "'$inputAdmissao', '$inputDemissao'";

					$this->insert($table, $columns, $values);

					$errorMsg = "Funcionário cadastrado com sucesso!";
				}
			}

			return [
			"error" => $error,
			"errorMsg" => $errorMsg];
		}

		public function edit(): array {
			$error = false;
			$errorMsg = '';

			InputCleaning::clean();
			
			$testeLogico = empty($_POST['inputName']) || empty($_POST['inputCargo']);

			if($testeLogico) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível editar este funcionário. Consulte o administrador ou o suporte.";
			} else {
				extract($_POST);

				if(!ctype_digit($_POST['inputID'])) {
					$error = true;
					$errorMsg = "ERRO! Não foi possível editar este funcionário. Consulte o administrador ou o suporte.";
				} else {
					$result = $this->select("id", "funcionarios", "WHERE id = {$_POST['inputID']}");

					$count = $result->rowCount();
					$result = $result->fetch();

					if($count < 1) {
						$error = true;
						$errorMsg = "ERRO! Não foi possível editar este funcionário. Consulte o administrador ou o suporte.";
					} else {
						$table = 'funcionarios';

						$columns = ['nome', 'cargo', 'remuneracao',	'data_nascimento',
									'data_admissao', 'data_demissao', 'ultima_modificacao'];	

						$values = [ "'$inputName'", "'$inputCargo'",
							  		"'$inputRemuneracao'", "'$inputNasc'",
							   		"'$inputAdmissao'", "'$inputDemissao'",
							   		"'".date('Y-m-d H:i:s')."'"];

						$filter = "WHERE id = ".$_POST['inputID'];
						
						$this->update($table, $columns, $values, $filter);

						$errorMsg = "Informações do funcionário atualizadas com sucesso!";
					}
				}
			}

			return [
			"error" => $error,
			"errorMsg" => $errorMsg];
		}

		public function amountInThisMonth(): int {
			$dataAtual = date("Y-m-d H:i:s");
			$tmp = date("m");
			$tmp--;
			$tmp = str_pad($tmp, 2, '0', STR_PAD_LEFT);
			$mesAnterior = date("Y-".$tmp."-d H:i:s");
			$filter = "WHERE (data_cadastro BETWEEN '{$mesAnterior}'";
			$filter .= " AND '{$dataAtual}') AND (arquivado = 0)";

			$tmp = $this->select("COUNT(*) as total", "funcionarios", $filter);
			
			return $tmp->fetch()['total'];
		}

		public function __get($atrib) {
			return $this->$atrib;
		}

		public function __set($atrib, $value) {
			$this->$atrib = $value;
		}
	}
?>