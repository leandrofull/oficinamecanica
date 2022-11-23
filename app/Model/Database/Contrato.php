<?php
	namespace app\Model\Database;

	use app\Controller\FPDF\FPDF;
	use app\Controller\getClientIp;

	class Contrato extends Ordem {
		public function getOrdemStatus(string $contratoID): int {
			$tmp = $this->select("status", "ordens", "WHERE validacao_link = '{$contratoID}'");

			$result = $tmp->fetch()['status'];

			return $result;
		}

		public function accept(string $contratoID): void {
			$tmp = $this->select("id, validada, status, historico", "ordens", "WHERE validacao_link = '{$contratoID}'");

			$count = $tmp->rowCount();

			$result = '';

			if($count < 1) {
				\HttpError::error404();
				exit;
			} else {
				$result = $tmp->fetch();
				if($result['validada'] || $result['status'] != 0) {
					\HttpError::error404();
					exit;
				} else {
					$historico = $result['historico'];
					$historico .= "\n-> Serviço autorizado pelo cliente em ".date('d/m/Y').", às ".date('H:i:s').", através deste link, utilizando o IP ".getClientIp::get().";";

					$this->update("ordens", ['status', 'validada', 'validacao_ip', 'validacao_data', 'historico', 'ultima_modificacao'], [1, 1, "'".getClientIp::get()."'", "'".date('Y-m-d H:i:s')."'", "'".$historico."'", "'".date('Y-m-d H:i:s')."'"], "WHERE id = {$result["id"]}");

					echo "Autorização concedida para realização do serviço.";
				}
			}			
		}

		public function notAccept(string $contratoID): void {
			$tmp = $this->select("id, validada, status, historico", "ordens", "WHERE validacao_link = '{$contratoID}'");

			$count = $tmp->rowCount();

			$result = '';

			if($count < 1) {
				\HttpError::error404();
				exit;
			} else {
				$result = $tmp->fetch();
				if($result['validada'] || $result['status'] != 0) {
					\HttpError::error404();
					exit;
				} else {
					$historico = $result['historico'];
					$historico .= "\n-> Serviço não autorizado pelo cliente. O mesmo desautorizou a realização do serviço em ".date("d/m/Y").", às ".date('H:i:s').", através deste link, utilizando o IP ".getClientIp::get().";";

					$this->update("ordens", ['status', 'validada', 'validacao_ip', 'validacao_data', 'historico', 'ultima_modificacao'], [3, 0, "'".getClientIp::get()."'", "'".date('Y-m-d H:i:s')."'", "'".$historico."'", "'".date('Y-m-d H:i:s')."'"], "WHERE id = {$result["id"]}");

					echo "Autorização negada para realização do serviço.";
				}
			}			
		}

		public function showById(string $contratoID): void {
			$tmp = $this->select("id, validada, status", "ordens", "WHERE validacao_link = '{$contratoID}'");

			$count = $tmp->rowCount();

			if($count < 1) {
				\HttpError::error404();
				exit;
			}
		}

		public function canvas(string $contratoID): void {
			$result = $this->select("o.*, o.id as ordemID, c.*, c.nome as clienteNome, c.cpf as clienteCPF, c.telefone, c.whatsapp, c.celular, v.*, f.*, f.nome as respNome, f.cpf as respCPF, o.observacoes as ordemObs, o.data_cadastro as ordemCadastro", "ordens o", "INNER JOIN veiculos v ON v.id = o.veiculo_id INNER JOIN clientes c ON c.id = v.proprietario_id INNER JOIN funcionarios f ON f.id = o.responsavel_id WHERE o.validacao_link = '{$contratoID}'");

			$count = $result->rowCount();
			$result = $result->fetch();

			if($count < 1) {
				\HttpError::error404();
				exit;
			}

			$servicos = $this->select("*", "ordens_servicos", "WHERE ordem_id = {$result['ordemID']}");
			$servicos = $servicos->fetchAll();

			$pecas = $this->select("*", "ordens_pecas", "WHERE ordem_id = {$result['ordemID']}");
			$pecas = $pecas->fetchAll();

			$servicosTotal = 0;
			$pecasTotal = 0;
			$ordemTotal = 0;

			// FPDF
			$pdf = new FPDF();
			$pdf->AddPage();

			// Topo
			$pdf->Image(PROJECT_DIRECTORY."/app/Images/Contratos/PDF/topo.jpg", 7, 0, 170);
			$pdf->SetFont('Helvetica', '', 23);
			$pdf->Cell(0, 0, "O.S. "."#".str_pad($result["ordemID"], 6 , '0' , STR_PAD_LEFT), 0, 0, 'R');

			// Info Cliente
			$pdf->Image(PROJECT_DIRECTORY."/app/Images/Contratos/PDF/info-cliente.jpg", 8, 70, 195);
			$pdf->Ln(77);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, "Nome: ");
			$pdf->SetFont('Helvetica', '', 11);
			$stringLength = $pdf->GetStringWidth("Nome: ");
			$pdf->SetX(15+$stringLength);
			$pdf->Cell(0, 0, $result["clienteNome"]);
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$stringLength = $pdf->GetStringWidth("CPF: ");
			$pdf->Cell(0, 0, "CPF: ");
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->Cell(0, 0, $result["clienteCPF"]);
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, "Telefones:");
			$pdf->SetFont('Helvetica', '', 11);
			$stringLength = $pdf->GetStringWidth("Telefones: ");
			$pdf->SetX(15.5+$stringLength);
			$telefone = ""; $celular = ""; $whatsapp = "";
			if(!empty($result["telefone"])) {
				$telefone = preg_replace("/^(\d{2})(\d{4})(\d{4})/", '($1) $2-$3', substr($result["telefone"], 2));
			}
			if(!empty($result["celular"])) {
				if(!empty($result["telefone"])) $celular = " / ";
				$celular .= preg_replace("/^(\d{2})(\d{5})(\d{4})/", '($1) $2-$3', substr($result["celular"], 2));
			}
			if(!empty($result["whatsapp"])) {
				if(!empty($result["telefone"]) || !empty($result["celular"])) $whatsapp = " / ";
				$whatsapp .= preg_replace("/^(\d{2})(\d{5})(\d{4})/", '($1) $2-$3', substr($result["whatsapp"], 2));
			}
			$pdf->Cell(0, 0, $telefone.$celular.$whatsapp);
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, "E-mail:");
			$stringLength = $pdf->GetStringWidth("E-mail: ");
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->Cell(0, 0, $result['email']);
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, "Bairro:");
			$stringLength = $pdf->GetStringWidth("Bairro: ");
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->Cell(0, 0, $result["bairro"]);
			$pdf->Ln(5.5);
			$stringLength = $pdf->GetStringWidth("Cidade/UF: ");
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->SetX(14);
			$pdf->Cell(0, 0, "Cidade/UF:");
			$pdf->SetX($stringLength+15);
			$pdf->SetFont('Helvetica', '', 11);
			$uf = "";
			if(!empty($result["cidade"])) $uf = " / ";
			$uf .= $result["uf"];
			$pdf->Cell(0, 0, utf8_decode($result["cidade"].$uf));
			$y = $pdf->GetY();

			// Info Veículo
			$pdf->Image(PROJECT_DIRECTORY."/app/Images/Contratos/PDF/info-veiculo.jpg", 8, $y+15, 195);
			$pdf->SetY($y+15+15.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, "Marca/Modelo: ");
			$stringLength = $pdf->GetStringWidth("Marca/Modelo: ");
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$cor = "";
			if(!empty($result["cor"])) $cor = " (".$result["cor"].")";
			$pdf->Cell(0, 0, $result["marca"]." ".$result["modelo"].$cor);
			$pdf->Ln(5.5);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->SetX(14);
			$pdf->Cell(0, 0, "Placa: ");
			$stringLength = $pdf->GetStringWidth("Placa: ");
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->SetX(14+$stringLength);
			$pdf->Cell(0, 0, $result["placa"]);
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$anoFab = $result["ano_fab"];
			$anoModelo = $result["ano_modelo"];
			if($anoFab != 0 && $anoModelo != 0) $anoFab .= " ";
			if($anoFab == 0) $anoFab = "";
			if($anoModelo == 0) $anoModelo = "";
			$pdf->Cell(0, 0, "Ano Fab./Modelo: ");
			$stringLength = $pdf->GetStringWidth("Ano Fab./Modelo: ");
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->Cell(0, 0, $anoFab.$anoModelo);
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Combustível: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Combustível: "));
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->Cell(0, 0, $result["combustivel"]);
			$y = $pdf->GetY();

			// Info O.S.
			$pdf->Image(PROJECT_DIRECTORY."/app/Images/Contratos/PDF/info-os.jpg", 8, $y+15, 195);
			$pdf->SetY($y+15+15.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Data de Abertura da O.S.: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Data de Abertura da O.S.: "));
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->SetX(14+$stringLength);
			$data = explode(" ", $result["ordemCadastro"]);
			$hora = $data[1];
			$data = explode("-", $data[0]);
			$data = $data[2]."/".$data[1]."/".$data[0];
			$pdf->Cell(0, 0, $data." ".$hora);	
			$pdf->Ln(5.5);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->SetX(14);
			$pdf->Cell(0, 0, utf8_decode("Responsável pelo serviço: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Responsável pelo serviço: "));
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->SetX(14+$stringLength);
			$pdf->Cell(0, 0, utf8_decode($result["respNome"]));	
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Cargo do Responsável: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Cargo do Responsável: "));
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->SetX(14+$stringLength);
			$pdf->Cell(0, 0, utf8_decode($result["cargo"]));	
			$pdf->Ln(5.8);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("CPF do Responsável: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("CPF do Responsável: "));
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->SetX(14+$stringLength);
			$pdf->Cell(0, 0, utf8_decode($result["respCPF"]));		
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Hodômetro do Veículo: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Hodômetro do Veículo: "));
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->SetX(14+$stringLength);
			$pdf->Cell(0, 0, utf8_decode($result["veiculo_hodometro"]));
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Tipo de Serviço: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Tipo de Serviço: "));
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->SetX(14+$stringLength);
			$pdf->Cell(0, 0, utf8_decode("Conserto"));
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Previsão de Conclusão: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Previsão de Conclusão: "));
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->SetX(14+$stringLength);
			$previsao = explode("-", $result['previsao']);
			if(count($previsao) == 3)
				$previsao = $previsao[2]."/".$previsao[1]."/".$previsao[0];
			else
				$previsao = "";
			$pdf->Cell(0, 0, $previsao);	
			$pdf->Ln(11);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Observações: "));
			$pdf->Ln(2);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->MultiCell(182.5, 5, htmlspecialchars_decode(utf8_decode($result["ordemObs"])), 0, 'J', false);
			$pdf->Ln(7);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Histórico: "));
			$pdf->Ln(2);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->MultiCell(182.5, 5, htmlspecialchars_decode(utf8_decode($result["historico"])), 0, 'J', false);
			$pdf->Ln(7);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Serviços a executar: "));
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->Cell(0, 0, utf8_decode("#"));
			$pdf->SetX(34);
			$pdf->Cell(0, 0, utf8_decode("Descrição"));
			$pdf->SetX(150);
			$pdf->Cell(0, 0, utf8_decode("Valor"));
			$pdf->SetFont('Helvetica', '', 11);
			for($i=0; $i<count($servicos); $i++) {
				$pdf->Ln(5.5); 
				$pdf->SetX(14);
				$pdf->Cell(0, 0, $i+1);
				$pdf->SetX(34);
				$pdf->Cell(0, 0, utf8_decode($servicos[$i]["descricao"]));
				$pdf->SetX(150);
				$pdf->Cell(0, 0, utf8_decode($servicos[$i]["valor"]));

				$valor = preg_replace("/[^0-9\,]/", "", $servicos[$i]["valor"]);
				$valor = str_replace(",", ".", $valor);
				$servicosTotal += $valor;
			}
			$ordemTotal += $servicosTotal;
			$servicosTotal = "R\$ ".number_format($servicosTotal, 2, ",", "." );
			$pdf->Ln(11);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Peças: "));
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->Cell(0, 0, utf8_decode("#"));
			$pdf->SetX(34);
			$pdf->Cell(0, 0, utf8_decode("Descrição"));
			$pdf->SetX(130);
			$pdf->Cell(0, 0, utf8_decode("Qtde."));
			$pdf->SetX(150);
			$pdf->Cell(0, 0, utf8_decode("Valor Unid."));
			for($i=0; $i<count($pecas); $i++) {
				$pdf->Ln(5.5);
				$pdf->SetFont('Helvetica', '', 11);
				$pdf->SetX(14);
				$pdf->Cell(0, 0, utf8_decode($i+1));
				$pdf->SetX(34);
				$pdf->Cell(0, 0, utf8_decode($pecas[$i]["descricao"]));
				$pdf->SetX(130);
				$pdf->Cell(0, 0, utf8_decode($pecas[$i]["qtde"]));
				$pdf->SetX(150);
				$pdf->Cell(0, 0, utf8_decode($pecas[$i]["valor"]));
				$valor = preg_replace("/[^0-9\,]/", "", $pecas[$i]["valor"]);
				$valor = str_replace(",", ".", $valor);
				$valor = $pecas[$i]["qtde"]*$valor;
				$pecasTotal += $valor;
			}
			$ordemTotal += $pecasTotal;
			$pecasTotal = "R\$ ".number_format($pecasTotal, 2, ",", "." );
			$ordemTotal = "R\$ ".number_format($ordemTotal, 2, ",", "." );
			$pdf->Ln(20);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Total em Serviços: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Total em Serviços: "));
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->Cell(0, 0, utf8_decode($servicosTotal));
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Total em Peças: "));
			$stringLength = $pdf->GetStringWidth(utf8_decode("Total em Peças: "));
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->Cell(0, 0, utf8_decode($pecasTotal));
			$pdf->Ln(5.5);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("Valor total da O.S.: "));

			$stringLength = $pdf->GetStringWidth(utf8_decode("Valor total da O.S.: "));
			$pdf->SetX(14+$stringLength);
			$pdf->SetFont('Helvetica', '', 11);
			$pdf->Cell(0, 0, utf8_decode($ordemTotal));

			// Imagens Veículo
			$pdf->Ln(20);
			$pdf->SetX(14);
			$pdf->SetFont('Helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_decode("* As imagens do veículo estão em posse do responsável pelo serviço prestado. É seu direito,"));
			$pdf->Ln(5.5);
			$pdf->SetX(14).
			$pdf->Cell(0, 0, utf8_decode("como cliente, solicitar e obter uma cópia das mesmas."));

			// Validação
			if($result["validada"] == 0 && $result['status'] == 0) {
				$pdf->Ln(7);
				$pdf->SetX(14);
				$pdf->SetFont('Helvetica', '', 11);
				$pdf->Cell(0, 0, utf8_decode("Aguardando autorização do cliente para iniciar o serviço."));
			}

			if($result["validada"] == 1 && $result['status'] == 1) {
				$pdf->Ln(7);
				$pdf->SetX(14);
				$pdf->SetFont('Helvetica', '', 11);
				$data = explode(" ", $result["validacao_data"]);
				$hora = $data[1];
				$data = explode("-", $data[0]);
				$data = $data[2]."/".$data[1]."/".$data[0];
				$pdf->MultiCell(182.5, 5, utf8_decode("Serviço autorizado pelo cliente em ".$data.", às ".$hora.", através deste link, utilizando o IP ".$result["validacao_ip"]."."), 0, 'J', false);
				$pdf->Ln(7);
				$pdf->SetX(14);
				$pdf->SetFont('Helvetica', 'B', 11);
				$pdf->Cell(0, 0, utf8_decode("Status Atual: "));
				$stringLength = $pdf->GetStringWidth(utf8_decode("Status Atual: "));
				$pdf->SetX(14+$stringLength);
				$pdf->SetFont('Helvetica', '', 11);
				$pdf->Cell(0, 0, "Em progresso.");
			}

			if($result["validada"] == 1 && $result['status'] == 3) {
				$pdf->Ln(7);
				$pdf->SetX(14);
				$pdf->SetFont('Helvetica', '', 11);
				$data = explode(" ", $result["validacao_data"]);
				$hora = $data[1];
				$data = explode("-", $data[0]);
				$data = $data[2]."/".$data[1]."/".$data[0];
				$pdf->MultiCell(182.5, 5, utf8_decode("Serviço autorizado pelo cliente em ".$data.", às ".$hora.", através deste link, utilizando o IP ".$result["validacao_ip"]."."), 0, 'J', false);
				$pdf->Ln(7);
				$pdf->SetX(14);
				$pdf->SetFont('Helvetica', 'B', 11);
				$pdf->Cell(0, 0, utf8_decode("Serviço finalizado em: "));
				$stringLength = $pdf->GetStringWidth(utf8_decode("Serviço finalizado em: "));
				$pdf->SetX(14+$stringLength);
				$pdf->SetFont('Helvetica', '', 11);
				$data = explode(" ", $result["finalizada_em"]);
				$hora = $data[1];
				$data = explode("-", $data[0]);
				$data = $data[2]."/".$data[1]."/".$data[0];
				$pdf->Cell(0, 0, utf8_decode($data.", às ".$hora).".");
			}

			if($result["validada"] == 0 && $result['status'] == 3) {
				$pdf->Ln(7);
				$pdf->SetX(14);
				$pdf->SetFont('Helvetica', '', 11);
				$data = explode(" ", $result["validacao_data"]);
				$hora = $data[1];
				$data = explode("-", $data[0]);
				$data = $data[2]."/".$data[1]."/".$data[0];
				$pdf->MultiCell(182.5, 5, utf8_decode("Serviço não autorizado pelo cliente. O mesmo desautorizou a realização do serviço em ".$data.", às ".$hora.", através deste link, utilizando o IP ".$result["validacao_ip"]."."), 0, 'J', false);
			}

			$pdf->Output();	
		}

		public function __get($atrib) {
			return $this->$atrib;
		}

		public function __set($atrib, $value) {
			$this->$atrib = $value;
		}
	}
?>