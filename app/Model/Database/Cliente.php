<?php
	namespace app\Model\Database;

	use app\Controller\InputCleaning\Clientes as InputCleaning;

	class Cliente extends Database {
		private array $id = array('');
		private array $nome = array('');
		private array $cpf = array('');
		private array $email = array('');
		private array $telefone = array('');
		private array $celular = array('');
		private array $whatsapp = array('');
		private array $endereco = array();
		private array $cep = array('');
		private array $bairro = array('');
		private array $cidade = array('');
		private array $uf = array('');
		private array $sexo = array('');
		private array $dataNascimento = array('');
		private array $arquivado = array('');
		private array $dataCadastro = array('');
		private array $dataUltimaModificacao = array('');
		private int $resultsCountAll = 0;
		private int $resultsCountPage = 0;
		private string $archiveMode = "0";

		public function __construct() {
			$this->endereco[0]["Endereco"] = "";
			$this->endereco[0]["Numero"] = "";
			$this->endereco[0]["Complemento"] = "";
		} 

		public function getAllByPage(int $pageNum): void {
			if($pageNum == 1) $pageNum = 0;
			else $pageNum = ($pageNum-1)*TABLE_ROWS;

			//PESQUISA
			$pesquisa = "";
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$name = preg_replace("/[^a-zA-Zà-úÀ-Ú]/", " ",$_GET['search']);
				$name = preg_replace("/\s{2,}/", " ", trim($name));
				if(strlen($name) < 3) $name = "156115635";

				$pesquisa = " AND (nome LIKE '%$name%'";

				$cpf = preg_replace("/\D/", "", $_GET['search']);
				$cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
				$teste = preg_match("/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/", $cpf);
				if(!$teste) $cpf = "FAILED";

				$pesquisa .= " OR cpf LIKE '%$cpf%'";

				$telefone = preg_replace("/\D/", "", $_GET['search']);
				$telefone = "55".$telefone;
				if(strlen($telefone) < 12 || strlen($telefone) > 13)
					$telefone = "FAILED";

				$pesquisa .= " OR telefone LIKE '%$telefone%'";
				$pesquisa .= " OR celular LIKE '%$telefone%'";
				$pesquisa .= " OR whatsapp LIKE '%$telefone%')";
			}
			//PESQUISA

			$results = $this->select("id, nome, cpf, telefone, celular, whatsapp, arquivado, ultima_modificacao", "clientes", "WHERE arquivado = ".$this->archiveMode.$pesquisa." ORDER BY id desc LIMIT {$pageNum}, ".TABLE_ROWS);
			$resultsCountAll = $this->select("COUNT(*) as resultsCountAll", "clientes", "WHERE arquivado = ".$this->archiveMode.$pesquisa)->fetch();
			$this->resultsCountAll = $resultsCountAll['resultsCountAll'];
			$this->resultsCountPage = $results->rowCount();

			$results = $results->fetchAll();
			for($i=0;$i<count($results);$i++) {
				$this->id[$i] = $results[$i]["id"];

				$this->arquivado[$i] = $results[$i]["arquivado"];

				if(strlen($results[$i]["nome"]) > 30)
					$this->nome[$i] = substr($results[$i]["nome"], 0, 30)."...";
				else
					$this->nome[$i] = $results[$i]["nome"];

				$this->cpf[$i] = $results[$i]["cpf"];

				if(!empty($results[$i]["whatsapp"]))
					$this->whatsapp[$i] = $results[$i]["whatsapp"];
				else if(!empty($results[$i]["celular"]))
					$this->whatsapp[$i] = $results[$i]["celular"];
				else
					$this->whatsapp[$i] = $results[$i]["telefone"];
				$this->whatsapp[$i] = preg_replace("/^(\d{2})(\d)/", '($1) $2', substr($this->whatsapp[$i], 2));
				$this->whatsapp[$i] = preg_replace("/(\d)(\d{4})$/","$1-$2", $this->whatsapp[$i]);

				$this->dataUltimaModificacao[$i] = explode(" ", $results[$i]["ultima_modificacao"]);
				$this->dataUltimaModificacao[$i] = explode("-", $this->dataUltimaModificacao[$i][0]);
				$this->dataUltimaModificacao[$i] = $this->dataUltimaModificacao[$i][2]."/".$this->dataUltimaModificacao[$i][1]."/".$this->dataUltimaModificacao[$i][0];
			}
		}

		public function getInfoByID(int $clienteID): void {
			$result = $this->select("*", "clientes", "WHERE id = $clienteID");
			$count = $result->rowCount();

			if($count < 1) {
				\HttpError::error404();
				exit;
			} else {
				$result = $result->fetch();
				$this->id[0] = $result['id'];

				$this->nome[0] = $result['nome'];

				$this->cpf[0] = $result['cpf'];

				$this->email[0] = $result['email'];

				$this->telefone[0] = preg_replace("/^(\d{2})(\d{4})(\d{4})/", '($1) $2-$3', substr($result['telefone'], 2));

				$this->celular[0] = preg_replace("/^(\d{2})(\d{5})(\d{4})/", '($1) $2-$3', substr($result['celular'], 2));

				$this->whatsapp[0] = preg_replace("/^(\d{2})(\d{5})(\d{4})/", '($1) $2-$3', substr($result['whatsapp'], 2));

				$endereco = explode(", ", $result['endereco'], 2);
				if(isset($endereco[1])) {	
					$enderecoNum = explode(" - ", $endereco[1], 2);
					if(isset($enderecoNum[1])) {
						$enderecoCompl = $enderecoNum[1];
					} else {
						$enderecoCompl = "";
					}
					$endereco = $endereco[0];
					$enderecoNum = $enderecoNum[0];
				} else {
					$endereco = explode(" - ", $endereco[0], 2);
					if(isset($endereco[1])) {
						$enderecoCompl = $endereco[1];
					} else {
						$enderecoCompl = "";
					}
					$endereco = $endereco[0];
					$enderecoNum = "";
				}
				$this->endereco[0]["Endereco"] = $endereco;
				$this->endereco[0]["Numero"] = $enderecoNum;
				$this->endereco[0]["Complemento"] = $enderecoCompl;

				$this->cep[0] = $result['cep'];

				$this->bairro[0] = $result['bairro'];

				$this->cidade[0] = $result['cidade'];

				$this->uf[0] = $result['uf'];

				$this->sexo[0] = $result['sexo'];

				$this->dataNascimento[0] = $result['data_nascimento'];

				$this->arquivado[0] = $result['arquivado'];
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

						$result = $this->select("id", "clientes", $filter);

						$resultCount = $result->rowCount();

						if($resultCount < 1) {
							$error = true;
							if($this->archiveMode) {
								$errorMsg = 'ERRO! Um dos clientes selecionados não existe ou não está arquivado.';
							} else {
								$errorMsg = 'ERRO! Um dos clientes selecionados não existe ou já está arquivado.';
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
				$errorMsg = 'ERRO! Nenhum cliente foi selecionado.';
			}

			if(!$error) {
				foreach($_POST['selectedItems'] as $key => $value) {
					if($this->archiveMode) {
						$this->update('clientes', ['arquivado', 'ultima_modificacao'], ['false', "'".date('Y-m-d H:i:s')."'"], "WHERE id=".$value);
					} else {
						$this->update('clientes', ['arquivado', 'ultima_modificacao'], ['true', "'".date('Y-m-d H:i:s')."'"], "WHERE id=".$value);
					}
				}
			}

			return [
			"error" => $error,
			"errorMsg" => $errorMsg,
			"affectedRecords" => count($_POST['selectedItems'])];
		}

		public function register(): array {
			$error = false;
			$errorMsg = '';

			InputCleaning::clean();
			
			$testeLogico1 = empty($_POST['inputName']) || empty($_POST['inputCPF']);
			$testeLogico2 = empty($_POST['inputTelefone']) && empty($_POST['inputCelular']) && empty($_POST['inputWhatsapp']);

			if($testeLogico1 || ($testeLogico2)) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível realizar o cadastro. Consulte o administrador ou o suporte.";
			} else {
				extract($_POST);

				$result = $this->select("id", "clientes", "WHERE cpf = '$inputCPF'")->rowCount();

				if($result > 0) {
					$error = true;
					$errorMsg = 'ERRO! Já existe um cliente cadastrado com o CPF informado.';
				} else {
					$table = 'clientes';

					$columns = "id, nome, cpf, email, telefone, celular, whatsapp, ";
					$columns .= "endereco, cep, bairro, cidade, uf, sexo, data_nascimento";				

					if(!empty($inputEndNumero))
						$inputEndereco .= ", ".$inputEndNumero;

					if(!empty($inputEndCompl))
						$inputEndereco .= " - ".$inputEndCompl;

					$values = "null, '$inputName', '$inputCPF', '$inputEmail', ";
					$values .= "'$inputTelefone', '$inputCelular', '$inputWhatsapp', ";
					$values .= "'$inputEndereco', ";
					$values .= "'$inputCep', '$inputBairro', '$inputCidade', '$inputUf', ";
					$values .= "'$inputSexo', '$inputNasc'";

					$this->insert($table, $columns, $values);
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
			
			$testeLogico1 = empty($_POST['inputName']);
			$testeLogico2 = empty($_POST['inputTelefone']) && empty($_POST['inputCelular']) && empty($_POST['inputWhatsapp']);

			if($testeLogico1 || ($testeLogico2)) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível editar este cliente. Consulte o administrador ou o suporte.";
			} else {
				extract($_POST);

				if(!ctype_digit($_POST['inputID'])) {
					$error = true;
					$errorMsg = "ERRO! Não foi possível editar este cliente. Consulte o administrador ou o suporte.";
				} else {
					$result = $this->select("id", "clientes", "WHERE id = {$_POST['inputID']}");

					$count = $result->rowCount();
					$result = $result->fetch();

					if($count < 1) {
						$error = true;
						$errorMsg = "ERRO! Não foi possível editar este cliente. Consulte o administrador ou o suporte.";
					} else {
						$table = 'clientes';

						$columns = ['nome', 'email', 'telefone',
									'celular', 'whatsapp', 'endereco', 'cep', 'bairro',
									'cidade', 'uf', 'sexo', 'data_nascimento', 
									'ultima_modificacao'];	

						if(!empty($inputEndNumero))
							$inputEndereco .= ", ".$inputEndNumero;

						if(!empty($inputEndCompl))
							$inputEndereco .= " - ".$inputEndCompl;

						$values = ["'$inputName'", "'$inputEmail'",
							  		 "'$inputTelefone'", "'$inputCelular'",
							   		"'$inputWhatsapp'", "'$inputEndereco'",
							   		"'$inputCep'",
							   		"'$inputBairro'", "'$inputCidade'", "'$inputUf'",
							   		"'$inputSexo'", "'$inputNasc'",
							   		"'".date('Y-m-d H:i:s')."'"];

						$filter = "WHERE id = ".$_POST['inputID'];
						$this->update($table, $columns, $values, $filter);
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
			$filter = "WHERE data_cadastro BETWEEN '{$mesAnterior}'";
			$filter .= " AND '{$dataAtual}'";

			$tmp = $this->select("COUNT(*) as total", "clientes", $filter);
			
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