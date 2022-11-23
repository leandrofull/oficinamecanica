<?php
	namespace app\Controller\InputCleaning;

	use app\Controller\Controller;

	class Ordens extends Controller {
		public static function clean(): void {
			if(isset($_POST['inputVeiculoPlaca'])) {
				$_POST['inputVeiculoPlaca'] = preg_replace("/[^a-zA-Z0-9]/", '', strtoupper($_POST['inputVeiculoPlaca']));
				if(strlen($_POST['inputVeiculoPlaca']) != 7) 
					$_POST['inputVeiculoPlaca'] = "";
				else {
					$_POST['inputVeiculoPlaca'] =  substr($_POST['inputVeiculoPlaca'], 0, 3)."-".substr($_POST['inputVeiculoPlaca'], 3);
					if(!preg_match("/[A-Z]{3}\-[0-9][0-9A-Z][0-9]{2}/", $_POST['inputVeiculoPlaca']))
						$_POST['inputVeiculoPlaca'] = "";
				}
			}

			$_POST['inputRespMatricula'] = preg_replace("/[^0-9]/", '', $_POST['inputRespMatricula']);
			if(strlen($_POST['inputRespMatricula']) != 11 || $_POST['inputRespMatricula'] < 1) 
				$_POST['inputRespMatricula'] = "";

			$_POST['inputVeiculoHodometro'] = preg_replace("/[^0-9]/", '', $_POST['inputVeiculoHodometro']);
			if($_POST['inputVeiculoHodometro'] == "0")
				$_POST['inputVeiculoHodometro'] = "0";
			else if($_POST['inputVeiculoHodometro'] > 0 && $_POST['inputVeiculoHodometro'] < 1000000)
				$_POST['inputVeiculoHodometro'] = number_format($_POST['inputVeiculoHodometro'], 0, "", ".");
			else
				$_POST['inputVeiculoHodometro'] = "";

			if(isset($_POST['inputServicoTipo'])) {
				if($_POST['inputServicoTipo'] != 'Conserto' && $_POST['inputServicoTipo'] != 'Revisão')
					$_POST['inputServicoTipo'] = "";
			}

			$_POST['inputServicoPrevisao'] = preg_replace("/[^0-9\-]/", "", $_POST['inputServicoPrevisao']);
			$ano = intval(substr($_POST['inputServicoPrevisao'], 0, 4));
			$mes = intval(substr($_POST['inputServicoPrevisao'], 5, 2));
			$dia = intval(substr($_POST['inputServicoPrevisao'], 8, 2));
			$diaTeste = $dia < 1 || $dia > 31;
			$mesTeste = $mes < 1 || $mes > 12;
			$anoTeste = $ano < 1900 || $ano > 2300;
			$tamanhoTeste = strlen($_POST['inputServicoPrevisao']) != 10;
			if($tamanhoTeste || $diaTeste || $mesTeste || $anoTeste) $_POST['inputServicoPrevisao'] = "";

			$_POST['inputObs'] = preg_replace('/[\\\"\']/', '', trim($_POST['inputObs']));
			if(mb_strlen($_POST['inputObs']) > 1000)
				$_POST['inputObs'] = "";
			else $_POST['inputObs'] = htmlspecialchars($_POST['inputObs']);

			for($i=0;$i<count($_POST['inputServicoDesc']);$i++) {
				$_POST['inputServicoDesc'][$i] = preg_replace("/[^a-zA-Zà-úÀ-Ú\ ]/", "", $_POST['inputServicoDesc'][$i]);
				$_POST['inputServicoDesc'][$i] = preg_replace("/\s{2,}/", " ", trim($_POST['inputServicoDesc'][$i]));
				if(mb_strlen($_POST['inputServicoDesc'][$i]) < 5 || mb_strlen($_POST['inputServicoDesc'][$i]) > 70) 
					$_POST['inputServicoDesc'][$i] = "";

				$_POST['inputServicoValor'][$i] = preg_replace("/[^0-9\,]/", "", $_POST['inputServicoValor'][$i]);
				$virgula = explode(",", $_POST['inputServicoValor'][$i]);
				if(count($virgula) > 2 || empty($virgula[0])) $_POST['inputServicoValor'][$i] = "";
				else {
					$_POST['inputServicoValor'][$i] = str_replace(",", ".", $_POST['inputServicoValor'][$i]);
					$_POST['inputServicoValor'][$i] = number_format($_POST['inputServicoValor'][$i], 2, ".", "");
					
					if($_POST['inputServicoValor'][$i] < 5 || $_POST['inputServicoValor'][$i] > 999999)
						$_POST['inputServicoValor'][$i] = "";
					else {
						$_POST['inputServicoValor'][$i] = number_format($_POST['inputServicoValor'][$i], 2, ",", ".");
						$_POST['inputServicoValor'][$i] = "R\$ ".$_POST['inputServicoValor'][$i];
					}
				}

				if(empty($_POST['inputServicoDesc'][$i]) || empty($_POST['inputServicoValor'][$i]))
				{
					unset($_POST['inputServicoDesc'][$i]);
					unset($_POST['inputServicoValor'][$i]);
				}	
			}

			for($i=0;$i<count($_POST['inputPecaDesc']);$i++) {
				$_POST['inputPecaDesc'][$i] = preg_replace("/[^a-zA-Zà-úÀ-Ú\ ]/", "", $_POST['inputPecaDesc'][$i]);
				$_POST['inputPecaDesc'][$i] = preg_replace("/\s{2,}/", " ", trim($_POST['inputPecaDesc'][$i]));
				if(mb_strlen($_POST['inputPecaDesc'][$i]) < 5 || mb_strlen($_POST['inputPecaDesc'][$i]) > 70) 
					$_POST['inputPecaDesc'][$i] = "";

				$_POST['inputPecaValor'][$i] = preg_replace("/[^0-9\,]/", "", $_POST['inputPecaValor'][$i]);
				$virgula = explode(",", $_POST['inputPecaValor'][$i]);
				if(count($virgula) > 2 || empty($virgula[0])) $_POST['inputPecaValor'][$i] = "";
				else {
					$_POST['inputPecaValor'][$i] = str_replace(",", ".", $_POST['inputPecaValor'][$i]);
					$_POST['inputPecaValor'][$i] = number_format($_POST['inputPecaValor'][$i], 2, ".", "");
					
					if($_POST['inputPecaValor'][$i] < 5 || $_POST['inputPecaValor'][$i] > 999999)
						$_POST['inputPecaValor'][$i] = "";
					else {
						$_POST['inputPecaValor'][$i] = number_format($_POST['inputPecaValor'][$i], 2, ",", ".");
						$_POST['inputPecaValor'][$i] = "R\$ ".$_POST['inputPecaValor'][$i];
					}
				}

				$_POST['inputPecaQtde'][$i] = intval($_POST['inputPecaQtde'][$i]);
				if($_POST['inputPecaQtde'][$i] < 1 || $_POST['inputPecaQtde'][$i] > 99)
					$_POST['inputPecaQtde'][$i] = "";

				if(empty($_POST['inputPecaDesc'][$i]) ||
				empty($_POST['inputPecaValor'][$i]) ||
				empty($_POST['inputPecaQtde'][$i]))
				{
					unset($_POST['inputPecaDesc'][$i]);
					unset($_POST['inputPecaValor'][$i]);
					unset($_POST['inputPecaQtde'][$i]);
				}	
			}
		}
	}
?>