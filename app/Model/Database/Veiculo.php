<?php
	namespace app\Model\Database;

	use app\Controller\InputCleaning\Veiculos as InputCleaning;

	class Veiculo extends Database {
		private array $id = array('');
		private array $placa = array('');
		private array $marca = array('');
		private array $modelo = array('');
		private array $cor = array('');
		private array $anoFab = array('');
		private array $anoModelo = array('');
		private array $combustivel = array('');
		private array $observacoes = array('');
		private array $proprietarioID = array('');
		private array $proprietario = array('');
		private array $proprietarioCPF = array('');
		private array $arquivado = array('');
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
				
				$placa = str_replace("-", "", trim($_GET['search']));
				if(mb_strlen($placa) < 1) $placa = "FAILED";
				
				$pesquisa .= " AND (REPLACE(a.placa, '-', '') LIKE '%$placa%'";

				$marca = preg_replace("/\s{2,}/", " ", trim($_GET['search']));
				if(mb_strlen($marca) < 1) $marca = "@@@@@";

				$pesquisa .= " OR concat(a.marca, ' ', a.modelo) LIKE '%$marca%'";

				$modelo = preg_replace("/\s{2,}/", " ", trim($_GET['search']));
				if(mb_strlen($modelo) < 1) $modelo = "@@@@@";

				$pesquisa .= " OR concat(a.marca, ' ', a.modelo) LIKE '%$modelo%'";

				$propCPF = str_replace(".", "", trim($_GET['search']));
				$propCPF = str_replace("-", "", $propCPF);
				if(!ctype_digit($propCPF)) $propCPF = "FAILED";
				
				$pesquisa .= " OR REPLACE(REPLACE(b.cpf, '-', ''), '.', '') LIKE '%$propCPF%'";

				$propName = preg_replace("/\s{2,}/", " ", trim($_GET['search']));
				if(mb_strlen($propName) < 1) $propName = "156115635";

				$pesquisa .= " OR b.nome LIKE '%$propName%')";
			}
			//PESQUISA

			$results = $this->select("a.id, a.placa, a.marca, a.modelo, b.nome as proprietario, a.ultima_modificacao", "veiculos a", "INNER JOIN clientes b ON a.proprietario_id = b.id WHERE a.arquivado = ".$this->archiveMode.$pesquisa." ORDER BY a.id desc LIMIT {$pageNum}, ".TABLE_ROWS);
			$resultsCountAll = $this->select("COUNT(*) as resultsCountAll", "veiculos a", "INNER JOIN clientes b ON a.proprietario_id = b.id WHERE a.arquivado = ".$this->archiveMode.$pesquisa)->fetch();
			$this->resultsCountAll = $resultsCountAll['resultsCountAll'];
			$this->resultsCountPage = $results->rowCount();

			$results = $results->fetchAll();
			for($i=0;$i<count($results);$i++) {
				$this->id[$i] = $results[$i]["id"];

				$this->placa[$i] = $results[$i]["placa"];

				$this->marca[$i] = $results[$i]["marca"]." ".$results[$i]["modelo"];
				if(strlen($this->marca[$i]) > 25)
					$this->marca[$i] = substr($this->marca[$i], 0, 25)."...";

				$this->proprietario[$i] = $results[$i]["proprietario"];
				if(strlen($this->proprietario[$i]) > 25)
					$this->proprietario[$i] = substr($this->proprietario[$i], 0, 25)."...";

				$this->dataUltimaModificacao[$i] = explode(" ", $results[$i]["ultima_modificacao"]);
				$this->dataUltimaModificacao[$i] = explode("-", $this->dataUltimaModificacao[$i][0]);
				$this->dataUltimaModificacao[$i] = $this->dataUltimaModificacao[$i][2]."/".$this->dataUltimaModificacao[$i][1]."/".$this->dataUltimaModificacao[$i][0];
			}
		}

		public function getInfoByID(int $veiculoID): void {
			$result = $this->select("a.*, b.id as proprietarioID, b.nome as proprietario, b.cpf as proprietarioCPF", "veiculos a", "INNER JOIN clientes b ON a.proprietario_id = b.id WHERE a.id = $veiculoID");

			$count = $result->rowCount();

			if($count < 1) {
				\HttpError::error404();
				exit;
			} else {
				$result = $result->fetch();
				$this->id[0] = $result['id'];

				$this->placa[0] = $result['placa'];

				$this->marca[0] = $result['marca'];

				$this->modelo[0] = $result['modelo'];

				$this->cor[0] = $result['cor'];

				$this->anoFab[0] = $result['ano_fab'];
				if($this->anoFab[0] == 0) $this->anoFab[0] = "";

				$this->anoModelo[0] = $result['ano_modelo'];
				if($this->anoModelo[0] == 0) $this->anoModelo[0] = "";

				$this->combustivel[0] = $result['combustivel'];

				$this->observacoes[0] = $result['observacoes'];

				$this->proprietarioID[0] = $result['proprietarioID'];

				$this->proprietario[0] = $result['proprietario'];

				$this->proprietarioCPF[0] = $result['proprietarioCPF'];
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

						$result = $this->select("id", "veiculos", $filter);

						$resultCount = $result->rowCount();

						if($resultCount < 1) {
							$error = true;
							if($this->archiveMode) {
								$errorMsg = 'ERRO! Um dos veículos selecionados não existe ou não está arquivado.';
							} else {
								$errorMsg = 'ERRO! Um dos veículos selecionados não existe ou já está arquivado.';
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
				$errorMsg = 'ERRO! Nenhum veículo foi selecionado.';
			}

			if(!$error) {
				foreach($_POST['selectedItems'] as $key => $value) {
					if($this->archiveMode) {
						$this->update('veiculos', ['arquivado', 'ultima_modificacao'], ['false', "'".date('Y-m-d H:i:s')."'"], "WHERE id=".$value);
					} else {
						$this->update('veiculos', ['arquivado', 'ultima_modificacao'], ['true', "'".date('Y-m-d H:i:s')."'"], "WHERE id=".$value);
					}
				}
				if($this->archiveMode) $errorMsg = count($_POST['selectedItems'])." veículos foram desarquivados com sucesso!";
				else $errorMsg = count($_POST['selectedItems'])." veículos foram arquivados com sucesso!";
			}

			return [
			"error" => $error,
			"errorMsg" => $errorMsg];
		}

		public function validatePropCPF(): string {
			$proprietario = '';

			if(!empty($_GET['CPF'])) {
				$teste = preg_match("/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/", $_GET['CPF']);
				if(!$teste) $_GET['CPF'] = "";
				else {
					$result = $this->select("nome, cpf", "clientes", "WHERE cpf = '".$_GET['CPF']."'");

					if($result->rowCount() > 0) {
						$result = $result->fetch();
						$proprietario = $result["nome"];
					}
				}
			} else $_GET['CPF'] = "";

			return $proprietario;
		}

		public function register(): array {
			$error = false;
			$errorMsg = '';

			InputCleaning::clean();
	
			$testeLogico = empty($_POST['inputPlaca']) ||	empty($_POST['inputMarca'])  ||	empty($_POST['inputModelo']) || empty($_POST['inputPropCPF']);

			if($testeLogico) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível realizar o cadastro. Consulte o administrador ou o suporte.";
			} else {
				extract($_POST);

				$result = $this->select("id", "veiculos", "WHERE placa = '$inputPlaca'")->rowCount();

				if($result > 0) {
					$error = true;
					$errorMsg = 'ERRO! Já existe um veículo cadastrado com a placa informada.';
				} else {
					$result = $this->select("id", "clientes", "WHERE cpf = '$inputPropCPF'");

					$idProp = $result->fetch();

					if($result->rowCount() < 1) {
						$error = true;
						$errorMsg = 'ERRO! Não existe um cliente cadastrado com o cpf informado.';
					} else {
						$idProp = $idProp['id'];

						$table = 'veiculos';

						$columns = "id, placa, marca, modelo, cor, ano_fab, ano_modelo, combustivel, observacoes, proprietario_id ";		

						$values = "null, '$inputPlaca', '$inputMarca', '$inputModelo', ";
						$values .= "'$inputCor', '$inputAnoFab', '$inputAnoModelo', ";
						$values .= "'$inputCombustivel', ";
						$values .= "'$inputObs', '$idProp'";

						$this->insert($table, $columns, $values);

						$errorMsg = "Veículo cadastrado com sucesso!";
					}
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
			
			$testeLogico = empty($_POST['inputMarca'])  ||	empty($_POST['inputModelo']) || empty($_POST['inputPropCPF']);

			if($testeLogico) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível editar este veículo. Consulte o administrador ou o suporte.";
			} else {
				extract($_POST);

				if(!ctype_digit($_POST['inputID'])) {
					$error = true;
					$errorMsg = "ERRO! Não foi possível editar este veículo. Consulte o administrador ou o suporte.";
				} else {
					$result = $this->select("id", "veiculos", "WHERE id = {$_POST['inputID']}");

					$count = $result->rowCount();

					if($count < 1) {
						$error = true;
						$errorMsg = "ERRO! Não foi possível editar este veículo. Consulte o administrador ou o suporte.";
					} else {
						$result = $this->select("id", "clientes", "WHERE cpf = '{$_POST['inputPropCPF']}'");

						$count = $result->rowCount();

						if($count < 1) {
							$error = true;
							$errorMsg = "ERRO! Não foi possível editar este veículo. Consulte o administrador ou o suporte.";
						} else {
							$result = $result->fetch();
							$idProp = $result['id'];
							$table = 'veiculos';

							$columns = ['marca', 'modelo', 'cor',
										'ano_fab', 'ano_modelo', 'combustivel',
										'observacoes', 'proprietario_id',
										'ultima_modificacao'];

							$values = [ "'$inputMarca'", "'$inputModelo'",
							  			"'$inputCor'", "'$inputAnoFab'",
							   			"'$inputAnoModelo'", "'$inputCombustivel'",
							   			"'$inputObs'", "$idProp",
							   			"'".date('Y-m-d H:i:s')."'" ];

							$filter = "WHERE id = ".$_POST['inputID'];
							$this->update($table, $columns, $values, $filter);

							$errorMsg = "Informações do veículo atualizadas com sucesso!";
						}
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

			$tmp = $this->select("COUNT(*) as total", "veiculos", $filter);
			
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