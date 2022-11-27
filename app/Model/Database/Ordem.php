<?php
	namespace app\Model\Database;

	use app\Controller\InputCleaning\Ordens as InputCleaning;
	use app\Controller\InputFilesCheck\Ordens as InputFilesCheck;
	use app\Controller\UploadFile;

	class Ordem extends Database {
		private array $id = array('');
		private array $numeroOS = array('');
		private array $clienteCPF = array('');
		private array $clienteNome = array('');
		private array $veiculoPlaca = array('');
		private array $veiculoMarca = array('');
		private array $veiculoHodometro = array('');
		private array $veiculoFotos = array();
		private array $responsavelMatricula = array('00000000000');
		private array $responsavelNome = array('');
		private array $servicoTipo = array('');
		private array $servicoPrevisao = array('');
		private array $status = array('0');
		private array $observacoes = array('');
		private array $historico = array('');
		private array $ordemServicos = array();
		private array $ordemPecas = array();
		private array $dataUltimaModificacao = array('');
		private string $servicosValorTotal = '0';
		private string $pecasValorTotal = '0';
		private string $ordemValorTotal = '0';
		private string $archiveMode = "0";
		private int $servicosQtde = 1;
		private int $pecasQtde = 1;
		private int $resultsCountAll = 0;
		private int $resultsCountPage = 0;

		public function __construct() {
			$this->veiculoFotos[0]["filename"] = "";
			$this->veiculoFotos[0]["extension"] = "";
			$this->veiculoFotos[0]["id"] = "";

			$this->ordemServicos[0]["descricao"] = "";
			$this->ordemServicos[0]["valor"] = "R$ 0";

			$this->ordemPecas[0]["descricao"] = "";
			$this->ordemPecas[0]["qtde"] = "";
			$this->ordemPecas[0]["valor"] = "R$ 0";
		} 

		public function getAllByPage(int $pageNum): void {
			if($pageNum == 1) $pageNum = 0;
			else $pageNum = ($pageNum-1)*TABLE_ROWS;

			//PESQUISA
			$pesquisa = "";
			if( isset($_GET['search']) &&
				(!empty($_GET['search']) || $_GET['search'] == "0") )
			{
				$_GET['search'] = preg_replace('/[^A-Za-zÀ-Úà-ú0-9\#\(\)\-\.\_\ ]/', "", $_GET['search']);
				
				$numeroOS = str_replace("#", "", trim($_GET['search']));
				if(!ctype_digit($numeroOS)) $numeroOS = "-1";

				$pesquisa = " AND (o.id = {$numeroOS}";

				$clienteCPF = str_replace(".", "", trim($_GET['search']));
				$clienteCPF = str_replace("-", "", $clienteCPF);
				if(!ctype_digit($clienteCPF)) $clienteCPF = "FAILED";
				
				$pesquisa .= " OR REPLACE(REPLACE(c.cpf, '-', ''), '.', '') LIKE '%$clienteCPF%'";

				$clienteNome = preg_replace("/\s{2,}/", " ", trim($_GET['search']));
				if(mb_strlen($clienteNome) < 1) $clienteNome = "156115635";	

				$pesquisa .= " OR c.nome LIKE '%$clienteNome%'";

				$veiculoPlaca = str_replace("-", "", trim($_GET['search']));
				if(mb_strlen($veiculoPlaca) < 1) $veiculoPlaca = "FAILED";
				
				$pesquisa .= " OR REPLACE(v.placa, '-', '') LIKE '%$veiculoPlaca%'";

				$servicoTipo = trim($_GET['search']);

				$pesquisa .= " OR o.servico_tipo LIKE '%$servicoTipo%'";

				if(strpos("orçamento", mb_strtolower(trim($_GET['search']))) !== false) $status = 0;
				else if(strpos("orcamento", mb_strtolower(trim($_GET['search']))) !== false) $status = 0;
				else if(strpos("em andamento", mb_strtolower(trim($_GET['search']))) !== false) $status = 1;
				else if(strpos("finalizada", mb_strtolower(trim($_GET['search']))) !== false) $status = 3;
				else if(strpos("cancelada", mb_strtolower(trim($_GET['search']))) !== false) $status = 4;
				else $status = 5;
				if($status == 3) {
					$pesquisa .= " OR (o.status = $status AND o.validada = 1))";
				} else if($status == 4) {
					$status = 3;
					$pesquisa .= " OR (o.status = $status AND o.validada = 0))";
				} else {
					$pesquisa .= " OR o.status = $status)";
				}
			}
			//PESQUISA

			$results = $this->select("o.id, c.nome as cliente, v.placa as veiculo, o.status, o.ultima_modificacao, o.servico_tipo, o.validada", "ordens o", "INNER JOIN veiculos v ON v.id = o.veiculo_id INNER JOIN clientes c ON c.id = v.proprietario_id WHERE o.arquivado = ".$this->archiveMode.$pesquisa." ORDER BY o.id desc LIMIT {$pageNum}, ".TABLE_ROWS);
			$resultsCountAll = $this->select("COUNT(*) as resultsCountAll", "ordens o", "INNER JOIN veiculos v ON v.id = o.veiculo_id INNER JOIN clientes c ON c.id = v.proprietario_id WHERE o.arquivado = ".$this->archiveMode.$pesquisa)->fetch();
			$this->resultsCountAll = $resultsCountAll['resultsCountAll'];
			$this->resultsCountPage = $results->rowCount();

			$results = $results->fetchAll();
			for($i=0;$i<count($results);$i++) {
				$this->id[$i] = $results[$i]["id"];

				$this->numeroOS[$i] = "#".str_pad($results[$i]["id"], 6 , '0' , STR_PAD_LEFT);

				if(strlen($results[$i]["cliente"]) > 30)
					$this->clienteNome[$i] = substr($results[$i]["cliente"], 0, 30)."...";
				else
					$this->clienteNome[$i] = $results[$i]["cliente"];

				$this->veiculoPlaca[$i] = $results[$i]["veiculo"];

				$status = array();
				$status[0] = "Orçamento";
				$status[1] = "Em andamento";
				$status[2] = "Validar";
				$status[3] = "Finalizada";
				if($results[$i]["validada"] == 0) $status[3] = "Cancelada";
				$this->status[$i] = $status[intval($results[$i]["status"])];

				$this->servicoTipo[$i] = $results[$i]["servico_tipo"];

				$this->dataUltimaModificacao[$i] = explode(" ", $results[$i]["ultima_modificacao"]);
				$this->dataUltimaModificacao[$i] = explode("-", $this->dataUltimaModificacao[$i][0]);
				$this->dataUltimaModificacao[$i] = $this->dataUltimaModificacao[$i][2]."/".$this->dataUltimaModificacao[$i][1]."/".$this->dataUltimaModificacao[$i][0];
			}
		}

		public function getInfoByID(int $ordemID): void {
			$result = $this->select("o.*, v.placa, v.marca, v.modelo, c.cpf, c.nome as cliente, f.matricula, f.nome as funcionario, f.cargo", "ordens o", "INNER JOIN veiculos v ON v.id = o.veiculo_id INNER JOIN clientes c ON c.id = v.proprietario_id INNER JOIN funcionarios f ON f.id = o.responsavel_id WHERE o.id = $ordemID");
			$count = $result->rowCount();

			$resultFotos = $this->select("id, filename, extension", "ordens_fotos", "WHERE ordem_id = $ordemID");

			$resultServicos = $this->select("descricao, valor", "ordens_servicos", "WHERE ordem_id = $ordemID");
			$this->servicosQtde = $resultServicos->rowCount();
			if($this->servicosQtde < 1) $this->servicosQtde = 1;

			$resultPecas = $this->select("descricao, qtde, valor", "ordens_pecas", "WHERE ordem_id = $ordemID");
			$this->pecasQtde = $resultPecas->rowCount();
			if($this->pecasQtde < 1) $this->pecasQtde = 1;

			if($count < 1) {
				\HttpError::error404();
				exit;
			} else {
				$result = $result->fetch();
				$this->id[0] = $result['id'];
				$this->numeroOS[0] = "#".str_pad($result["id"], 6 , '0' , STR_PAD_LEFT);
				$this->veiculoPlaca[0] = $result['placa'];
				$this->veiculoMarca[0] = $result['marca']." ".$result['modelo'];
				$this->clienteCPF[0] = $result['cpf'];
				$this->clienteNome[0] = $result['cliente'];
				$this->responsavelMatricula[0] = $result['matricula'];
				$this->responsavelNome[0] = $result['funcionario']." / ".$result['cargo'];
				$this->veiculoHodometro[0] = $result['veiculo_hodometro'];
				$this->status[0] = $result['status'];
				$this->servicoTipo[0] = $result['servico_tipo'];
				$this->servicoPrevisao[0] = $result['previsao'];
				$this->observacoes[0] = $result['observacoes'];
				$this->historico[0] = $result['historico'];

				$resultFotos = $resultFotos->fetchAll();
				for($i=0;$i<count($resultFotos);$i++) {
					$this->veiculoFotos[$i]["filename"] = $resultFotos[$i]['filename'];
					$this->veiculoFotos[$i]["extension"] = $resultFotos[$i]['extension'];
					$this->veiculoFotos[$i]["id"] = $resultFotos[$i]['id'];
				}

				$resultServicos = $resultServicos->fetchAll();
				for($i=0;$i<count($resultServicos);$i++) {
					$this->ordemServicos[$i]["descricao"] = $resultServicos[$i]['descricao'];
					$this->ordemServicos[$i]["valor"] = $resultServicos[$i]['valor'];
					$valor = preg_replace("/[^0-9\,]/", "", $resultServicos[$i]['valor']);
					$valor = str_replace(",", ".", $valor);
					$this->servicosValorTotal += $valor;
				}

				$resultPecas = $resultPecas->fetchAll();
				for($i=0;$i<count($resultPecas);$i++) {
					$this->ordemPecas[$i]["descricao"] = $resultPecas[$i]['descricao'];
					$this->ordemPecas[$i]["qtde"] = $resultPecas[$i]['qtde'];
					$this->ordemPecas[$i]["valor"] = $resultPecas[$i]['valor'];
					$valor = preg_replace("/[^0-9\,]/", "", $resultPecas[$i]['valor']);
					$valor = str_replace(",", ".", $valor);
					$valor = $this->ordemPecas[$i]["qtde"]*$valor;
					$this->pecasValorTotal += $valor;
				}

				$this->ordemValorTotal = 
									$this->servicosValorTotal + 
									$this->pecasValorTotal;

				$this->ordemValorTotal =
									"R\$ ".number_format(  $this->ordemValorTotal,
														   2, ",", "." );

				$this->servicosValorTotal =
									"R\$ ".number_format(  $this->servicosValorTotal,
														   2, ",", "." );
				$this->pecasValorTotal =
					"R\$ ".number_format(  $this->pecasValorTotal,
										   2, ",", "." );
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

						$result = $this->select("id", "ordens", $filter);

						$resultCount = $result->rowCount();

						if($resultCount < 1) {
							$error = true;
							if($this->archiveMode) {
								$errorMsg = 'ERRO! Uma das ordens de serviço selecionadas não existe ou não está arquivada.';
							} else {
								$errorMsg = 'ERRO! Uma das ordens de serviço selecionadas não existe ou já está arquivada.';
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
				$errorMsg = 'ERRO! Nenhuma ordem de serviço foi selecionada.';
			}

			if(!$error) {
				foreach($_POST['selectedItems'] as $key => $value) {
					if($this->archiveMode) {
						$this->update('ordens', ['arquivado', 'ultima_modificacao'], ['false', "'".date('Y-m-d H:i:s')."'"], "WHERE id=".$value);
					} else {
						$this->update('ordens', ['arquivado', 'ultima_modificacao'], ['true', "'".date('Y-m-d H:i:s')."'"], "WHERE id=".$value);
					}
				}

				if($this->archiveMode) {
					$errorMsg = count($_POST['selectedItems']). ' ordens de serviço foram desarquivadas com sucesso!';
				} else {
					$errorMsg = count($_POST['selectedItems']). ' ordens de serviço foram arquivadas com sucesso!';
				}
			}

			return [
			"error" => $error,
			"errorMsg" => $errorMsg];
		}

		public function validatePlaca(): array {
			$marca = '';
			$propCPF = '';
			$propNome = '';

			if(!empty($_GET['placa'])) {
				$teste = preg_match("/[A-Z]{3}\-[0-9][0-9A-Z][0-9]{2}/", $_GET['placa']);
				if(!$teste) $_GET['placa'] = "";
				else {
					$result = $this->select("a.marca, a.modelo, b.cpf, b.nome", "veiculos a", "INNER JOIN clientes b ON a.proprietario_id = b.id WHERE a.placa = '".$_GET['placa']."'");

					if($result->rowCount() > 0) {
						$result = $result->fetch();
						$marca = $result['marca']." ".$result['modelo'];
						$propCPF = $result['cpf'];
						$propNome = $result['nome'];
					}
				}
			} else $_GET['placa'] = "";

			return [
			"marca" => $marca,
			"propCPF" => $propCPF,
			"propNome" => $propNome
			];
		}

		public function validateMatricula(): string {
			$responsavel = '';

			if(!empty($_GET['matricula'])) {
				$matricula = preg_replace("/[^0-9]/", '', $_GET['matricula']);
				if(strlen($matricula) != 11) $_GET['matricula'] = "";
				else {
					$result = $this->select("nome, cargo", "funcionarios", "WHERE matricula = '".$_GET['matricula']."'");

					if($result->rowCount() > 0) {
						$result = $result->fetch();
						$responsavel = $result['nome']." / ".$result['cargo'];
					}
				}
			} else $_GET['matricula'] = "";

			return $responsavel;
		}

		public function register(): array {
			$error = false;
			$errorMsg = '';
			$veiculoFotos = array();
			$veiculoFotos['filename'] = array();
			$veiculoFotos['extension'] = array();

			InputCleaning::clean();
			
			if(!InputFilesCheck::check(0)) {
				$error = true;
				$errorMsg = $_FILES['inputVeiculoFotos']['error'][0];

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			$testeLogico1 = (empty($_POST['inputVeiculoPlaca']) || 
							empty($_POST['inputRespMatricula']) ||
							empty($_POST['inputServicoTipo']));

			$testeLogico2 = (count($_POST['inputServicoDesc']) < 1);

			if($testeLogico1 || $testeLogico2) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível realizar o cadastro. Consulte o administrador ou o suporte.";

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			extract($_POST);

			$result1 = $this->select("id", "veiculos", "WHERE placa = '$inputVeiculoPlaca'");
			$result2 = $this->select("id", "funcionarios", "WHERE matricula = '$inputRespMatricula'");

			$count1 = $result1->rowCount();
			$count2 = $result2->rowCount();

			if($count1 < 1) {
				$error = true;
				$errorMsg = "ERRO! Não existe um veículo com a placa informada.";

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			if($count2 < 1) {
				$error = true;
				$errorMsg = "ERRO! Não existe um funcionário com a matrícula informada.";

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			$veiculoID = $result1->fetch()['id'];
			$responsavelID = $result2->fetch()['id'];

			for($i=0;$i<count($_FILES['inputVeiculoFotos']['tmp_name']);$i++) {
				$filename = md5(rand()).date('Y-m-d H:i:s').$i;
				$filename = str_replace(":", "", $filename);
				$filename = str_replace("-", "", $filename);
				$filename = str_replace(" ", "", $filename);
				$extension = explode(".", $_FILES['inputVeiculoFotos']['name'][$i]);
				$extension = array_pop($extension);
				$filename = $filename.".".$extension;
				if(UploadFile::start($_FILES['inputVeiculoFotos']['tmp_name'][$i], PROJECT_DIRECTORY."/app/Images/Ordens/Veiculos/".$filename))
				{
					array_push($veiculoFotos['filename'], $filename);
					array_push($veiculoFotos['extension'], $extension);
				}
			}

			$table = 'ordens';

			$columns = 'id, veiculo_id, responsavel_id, ';
			$columns .= 'status, veiculo_hodometro, servico_tipo, ';
			$columns .= 'observacoes, historico, previsao';

			$historico = "-> Ordem de Serviço criada em ".date('d/m/Y H:i:s').";";

			$values = "null, $veiculoID, $responsavelID, ";
			$values .= "0, '$inputVeiculoHodometro', '$inputServicoTipo', ";
			$values .= "'$inputObs', '$historico', '$inputServicoPrevisao'";

			$this->insert($table, $columns, $values);

			$ordemID = $this->getLastInsertId();

			$table = 'ordens_fotos';

			$columns = 'id, filename, extension, ordem_id';

			for($i=0; $i < count($veiculoFotos['filename']); $i++) { 
				$values = "null, '{$veiculoFotos['filename'][$i]}', ";
				$values .= "'{$veiculoFotos['extension'][$i]}', $ordemID";
				$this->insert($table, $columns, $values);
			}

			$table = 'ordens_servicos';

			$columns = 'id, descricao, valor, ordem_id';
			
			foreach($inputServicoDesc as $key => $value) {
				$values = "null, '{$value}', '{$inputServicoValor[$key]}', $ordemID";
				$this->insert($table, $columns, $values);		
			}

			$table = 'ordens_pecas';

			$columns = 'id, descricao, qtde, valor, ordem_id';
			
			foreach($inputPecaDesc as $key => $value) {
				$values = "null, '{$value}', ";
				$values .= "'{$inputPecaQtde[$key]}', '{$inputPecaValor[$key]}', $ordemID";
				$this->insert($table, $columns, $values);		
			}

			$errorMsg = 'Ordem de Serviço gerada com sucesso!';

			return [
			"error" => $error,
			"errorMsg" => $errorMsg];
		}

		public function edit(): array {
			$error = false;
			$errorMsg = '';
			$veiculoFotos = array();
			$veiculoFotos['filename'] = array();
			$veiculoFotos['extension'] = array();

			if(!ctype_digit($_POST['inputID'])) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível alterar as informações da ordem de serviço. Por favor, tente mais tarde.";

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			} else {
				$result = $this->select( "status, historico", "ordens",
							   "WHERE id = {$_POST['inputID']}" );

				$count = $result->rowCount();

				if($count < 1) {
					$error = true;
					$errorMsg = "ERRO! Não foi possível alterar as informações da ordem de serviço. Por favor, tente mais tarde.";

					return [
					"error" => $error,
					"errorMsg" => $errorMsg];
				}

				$result = $result->fetch();
				$historico = $result["historico"];
				$status = $result["status"];
			}

			if($status != 0) {
				$_POST['inputObs'] = preg_replace('/[\\\"\']/', '', trim($_POST['inputObs']));
				if(mb_strlen($_POST['inputObs']) > 1000)
					$_POST['inputObs'] = "";
				else $_POST['inputObs'] = htmlspecialchars($_POST['inputObs']);

				$table = 'ordens';

				$columns = ['observacoes', 'historico', 'ultima_modificacao'];

				$historico .= "\n-> Observações alteradas em ".date('d/m/Y H:i:s').";";

				$values = ["'{$_POST['inputObs']}'", "'$historico'", "'".date('Y-m-d H:i:s')."'"];

				$filter = "WHERE id = {$_POST['inputID']}";

				$this->update($table, $columns, $values, $filter);

				$errorMsg = 'Observações alteradas com sucesso!';

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			InputCleaning::clean();

			$count = $this->select( "id", "ordens_fotos",
									 "WHERE ordem_id = {$_POST['inputID']}" )->rowCount();

			if($count < 1) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível alterar as informações da ordem de serviço. Por favor, tente mais tarde.";

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			if(!InputFilesCheck::check($count)) {
				$error = true;
				$errorMsg = $_FILES['inputVeiculoFotos']['error'][0];

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			$testeLogico1 = (empty($_POST['inputRespMatricula']));

			$testeLogico2 = (count($_POST['inputServicoDesc']) < 1);

			if($testeLogico1 || $testeLogico2) {
				$error = true;
				$errorMsg = "ERRO! Não foi possível alterar as informações da ordem de serviço. Por favor, tente mais tarde.";

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			extract($_POST);

			$result = $this->select("id", "funcionarios", "WHERE matricula = '$inputRespMatricula'");
			$count = $result->rowCount();

			if($count < 1) {
				$error = true;
				$errorMsg = "ERRO! Não existe um funcionário com a matrícula informada.";

				return [
				"error" => $error,
				"errorMsg" => $errorMsg];
			}

			$responsavelID = $result->fetch()['id'];

			for($i=0;$i<count($_FILES['inputVeiculoFotos']['tmp_name']);$i++) {
				if(!empty($_FILES['inputVeiculoFotos']['tmp_name'][$i])) {
					$filename = md5(rand()).date('Y-m-d H:i:s').$i;
					$filename = str_replace(":", "", $filename);
					$filename = str_replace("-", "", $filename);
					$filename = str_replace(" ", "", $filename);
					$extension = explode(".", $_FILES['inputVeiculoFotos']['name'][$i]);
					$extension = array_pop($extension);
					$filename = $filename.".".$extension;
					if(UploadFile::start($_FILES['inputVeiculoFotos']['tmp_name'][$i], PROJECT_DIRECTORY."/app/Images/Ordens/Veiculos/".$filename))
					{
						array_push($veiculoFotos['filename'], $filename);
						array_push($veiculoFotos['extension'], $extension);
					}
				}
			}

			$table = 'ordens';

			$columns = ['responsavel_id', 'veiculo_hodometro',
						'observacoes', 'historico', 'previsao', 'ultima_modificacao'];

			$historico .= "\n-> Ordem de Serviço alterada em ".date('d/m/Y H:i:s').";";

			$values = ["$responsavelID", "'$inputVeiculoHodometro'", 
					   "'$inputObs'", "'$historico'", "'$inputServicoPrevisao'", 
					   "'".date('Y-m-d H:i:s')."'"];

			$filter = "WHERE id = {$_POST['inputID']}";

			$this->update($table, $columns, $values, $filter);

			$table = 'ordens_fotos';

			$columns = 'id, filename, extension, ordem_id';

			if(count($veiculoFotos['filename']) > 0) {
				for($i=0; $i < count($veiculoFotos['filename']); $i++) { 
					$values = "null, '{$veiculoFotos['filename'][$i]}', ";
					$values .= "'{$veiculoFotos['extension'][$i]}', {$_POST['inputID']}";
					$this->insert($table, $columns, $values);
				}
			}

			$table = 'ordens_servicos';

			$this->delete($table, "WHERE ordem_id = {$_POST['inputID']}");

			$columns = 'id, descricao, valor, ordem_id';
			
			foreach($inputServicoDesc as $key => $value) {
				$values = "null, '{$value}', '{$inputServicoValor[$key]}', {$_POST['inputID']}";
				$this->insert($table, $columns, $values);		
			}

			$table = 'ordens_pecas';

			$this->delete($table, "WHERE ordem_id = {$_POST['inputID']}");

			$columns = 'id, descricao, qtde, valor, ordem_id';
			
			foreach($inputPecaDesc as $key => $value) {
				$values = "null, '{$value}', ";
				$values .= "'{$inputPecaQtde[$key]}', '{$inputPecaValor[$key]}', {$_POST['inputID']}";
				$this->insert($table, $columns, $values);		
			}

			$errorMsg = 'Ordem de Serviço alterada com sucesso!';

			return [
			"error" => $error,
			"errorMsg" => $errorMsg];
		}

		public function deleteVeiculoFotos(): void {
			$filename = [];
			$imagesDir = PROJECT_DIRECTORY."/app/Images/Ordens/Veiculos/";
			$return = [];
			$return["error"] = "1";
			$return["msg"] = "";

			if(!isset($_POST['ImagesID'])) {
				$return["msg"] = "ERRO! Nenhuma imagem foi selecionada.";
				echo json_encode($return);
				exit;
			}

			if(count($_POST['ImagesID']) < 1) {
				$return["msg"] = "ERRO! Nenhuma imagem foi selecionada.";
				echo json_encode($return);
				exit;
			}

			if(!ctype_digit($_POST['inputID'])) {
				$return["msg"] = "ERRO! Não foi possível remover as imagens selecionadas. Por favor, tente mais tarde.";
				echo json_encode($return);
				exit;
			}

			$count = $this->select(	'id',
									'ordens_fotos',
									"WHERE ordem_id = {$_POST['inputID']}" )->rowCount();

			if($count <= 1) {
				$return["msg"] = "ERRO! É necessário que pelo menos 1 imagem esteja presente na ordem de serviço. Caso deseja substituir alguma imagem existente, adicione a nova imagem e depois remova a antiga.";
				echo json_encode($return);
				exit;
			}

			for($i=0;$i<count($_POST['ImagesID']);$i++) {
				if(!ctype_digit($_POST['ImagesID'][$i])) {
					$return["msg"] = "ERRO! Não foi possível remover as imagens selecionadas. Por favor, tente mais tarde.";
					echo json_encode($return);
					exit;
				} else {
					$result = $this->select( 'filename',
											 'ordens_fotos',
											 "WHERE id = {$_POST['ImagesID'][$i]}" );

					$count = $result->rowCount();

					if($count < 1) {
						$return["msg"] = "ERRO! Uma das imagens selecionadas já foram deletadas.";
						echo json_encode($return);
						exit;
					} else {
						$filename[$i] = $imagesDir.$result->fetch()["filename"];
					}
				}
			}

			for($i=0;$i<count($_POST['ImagesID']);$i++) {
				if(is_file($filename[$i])) unlink($filename[$i]);

				$result = $this->delete( 'ordens_fotos',
										 "WHERE id = {$_POST['ImagesID'][$i]}" );
			}

			$return["error"] = "0";
			$return["msg"] = "Imagens removidas com sucesso!";
			echo json_encode($return);
		}

		public function send(): void {
			$whatsapp = "55".preg_replace("/[^0-9]/", '', $_POST['WhatsApp']);

			if(strlen($whatsapp) != 13 || !ctype_digit($_POST['inputID'])) {
				\HttpError::error404();
				exit;
			}

			$tmp = $this->select("id, validacao_link", "ordens", "WHERE id = {$_POST['inputID']}");

			$count = $tmp->rowCount();

			$result = '';

			if($count < 1) {
				\HttpError::error404();
				exit;
			} else {
				$result = $tmp->fetch()['validacao_link'];
			}

			if(empty($result)) {
				$validacaoLink = md5(rand()).date('Y-m-d H:i:s').$_POST['inputID'];
				$validacaoLink = str_replace(":", "", $validacaoLink);
				$validacaoLink = str_replace("-", "", $validacaoLink);
				$validacaoLink = str_replace(" ", "", $validacaoLink);
				$this->update("ordens", ['validacao_link'], ["'$validacaoLink'"],
					"WHERE id = {$_POST['inputID']}");
			}

			$this->update("ordens", ['validacao_whatsapp'], ["'{$_POST['WhatsApp']}'"],
					"WHERE id = {$_POST['inputID']}");

			$tmp = $this->select("id, validacao_link", "ordens", "WHERE id = {$_POST['inputID']}");

			$validacaoLink = $tmp->fetch()['validacao_link'];
			$validacaoLink = DOMAIN."/contrato/".$validacaoLink;
			$msg = "Olá! Aqui está o link da ordem de serviço do seu veículo: ";
			$msg .= $validacaoLink;
			$msg .= ". Ao abrir o link, por gentileza, analise os detalhes descritos na O.S. e clique nos botões para concordar ou discordar com os termos dela.";
			$link = "https://api.whatsapp.com/send?phone=";
			$link .= $whatsapp;
			$link .= "&text=";
			$link .= urlencode($msg);

			echo json_encode($link);
		}

		public function finalize(): void {
			if(!ctype_digit($_POST['inputID'])) {
				\HttpError::error404();
				exit;
			}
			
			$tmp = $this->select("status, validada, historico", "ordens", "WHERE id = {$_POST['inputID']}");

			$count = $tmp->rowCount();

			if($count < 1) {
				\HttpError::error404();
				exit;
			} else {
				$result = $tmp->fetch();
				if($result['status'] != 1 || $result['validada'] != 1) {
					\HttpError::error404();
					exit;	
				} else {
					$historico = $result['historico'];
					$historico .= "\n-> Ordem de Serviço finalizada em ".date('d/m/Y H:i:s').";";

					$this->update("ordens", ['status', 'finalizada_em', 'historico', 'ultima_modificacao'], [3, "'".date('Y-m-d H:i:s')."'","'".$historico."'", "'".date('Y-m-d H:i:s')."'"], "WHERE id = {$_POST['inputID']}");

					echo json_encode("Ordem de Serviço finalizada com sucesso!");
				}
			}
		}

		public function amountInThisMonth(): int {
			$dataAtual = date("Y-m-d H:i:s");
			$tmp = date("m");
			$tmp--;
			$tmp = str_pad($tmp, 2, '0', STR_PAD_LEFT);
			$mesAnterior = date("Y-".$tmp."-d H:i:s");
			$filter = "WHERE (data_cadastro BETWEEN '{$mesAnterior}'";
			$filter .= " AND '{$dataAtual}') AND (arquivado = 0)";

			$tmp = $this->select("COUNT(*) as total", "ordens", $filter);
			
			return $tmp->fetch()['total'];
		}

		public function amountInThisMonthByStatus(int $status): int {
			if($status == 3) $validacao = " AND (validada = 1)";
			else if($status == 4) $validacao = " AND (validada = 0)";

			if($status == 4) $status = 3;

			$dataAtual = date("Y-m-d H:i:s");
			$tmp = date("m");
			$tmp--;
			$tmp = str_pad($tmp, 2, '0', STR_PAD_LEFT);
			$mesAnterior = date("Y-".$tmp."-d H:i:s");
			$filter = "WHERE (ultima_modificacao BETWEEN '{$mesAnterior}'";
			$filter .= " AND '{$dataAtual}')";
			$filter .= " AND (status = {$status}) AND (arquivado = 0)";
			if($status == 3 || $status == 4) $filter .= $validacao;

			$tmp = $this->select("COUNT(*) as total", "ordens", $filter);
			
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