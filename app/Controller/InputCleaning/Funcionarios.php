<?php
	namespace app\Controller\InputCleaning;

	use app\Controller\Controller;

	class Funcionarios extends Controller {
		public static function clean(): void {
			$_POST['inputName'] = preg_replace("/[^a-zA-Zà-úÀ-Ú\ ]/", "", $_POST['inputName']);
			$_POST['inputName'] = preg_replace("/\s{2,}/", " ", trim($_POST['inputName']));
			if(mb_strlen($_POST['inputName']) < 3 || mb_strlen($_POST['inputName']) > 70) $_POST['inputName'] = "";

			$_POST['inputCargo'] = preg_replace("/[^a-zA-Zà-úÀ-Ú\ ]/", "", $_POST['inputCargo']);
			$_POST['inputCargo'] = preg_replace("/\s{2,}/", " ", trim($_POST['inputCargo']));
			if(mb_strlen($_POST['inputCargo']) < 5 || mb_strlen($_POST['inputCargo']) > 50) $_POST['inputCargo'] = "";

			if(isset($_POST['inputMatricula'])) {
				$_POST['inputMatricula'] = preg_replace("/[^0-9]/", "", $_POST['inputMatricula']);
				if(strlen($_POST['inputMatricula']) != 11) $_POST['inputMatricula'] = "";
				else if($_POST['inputMatricula'] < 1) $_POST['inputMatricula'] = "";
			}

			if(isset($_POST['inputCPF'])) {
				$_POST['inputCPF'] = preg_replace("/\D/", "", $_POST['inputCPF']);
				$_POST['inputCPF'] = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $_POST['inputCPF']);
				$teste = preg_match("/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/", $_POST['inputCPF']);
				if(!$teste) $_POST['inputCPF'] = "";
			}

			$_POST['inputRemuneracao'] = preg_replace("/[^0-9\,]/", "", $_POST['inputRemuneracao']);
			$virgula = explode(",", $_POST['inputRemuneracao']);
			if(count($virgula) > 2 || empty($virgula[0])) $_POST['inputRemuneracao'] = "";
			else {
				$_POST['inputRemuneracao'] = str_replace(",", ".", $_POST['inputRemuneracao']);
				$_POST['inputRemuneracao'] = number_format($_POST['inputRemuneracao'], 2, ".", "");
				
				if($_POST['inputRemuneracao'] < 800 || $_POST['inputRemuneracao'] > 999999)
					$_POST['inputRemuneracao'] = "";
				else {
					$_POST['inputRemuneracao'] = number_format($_POST['inputRemuneracao'], 2, ",", ".");
					$_POST['inputRemuneracao'] = "R\$ ".$_POST['inputRemuneracao'];
				}
			}

			$_POST['inputNasc'] = preg_replace("/[^0-9\-]/", "", $_POST['inputNasc']);
			$ano = intval(substr($_POST['inputNasc'], 0, 4));
			$mes = intval(substr($_POST['inputNasc'], 5, 2));
			$dia = intval(substr($_POST['inputNasc'], 8, 2));
			$diaTeste = $dia < 1 || $dia > 31;
			$mesTeste = $mes < 1 || $mes > 12;
			$anoTeste = $ano < 1900 || $ano > 2300;
			$tamanhoTeste = strlen($_POST['inputNasc']) != 10;
			if($tamanhoTeste || $diaTeste || $mesTeste || $anoTeste) $_POST['inputNasc'] = "";

			$_POST['inputAdmissao'] = preg_replace("/[^0-9\-]/", "", $_POST['inputAdmissao']);
			$ano = intval(substr($_POST['inputAdmissao'], 0, 4));
			$mes = intval(substr($_POST['inputAdmissao'], 5, 2));
			$dia = intval(substr($_POST['inputAdmissao'], 8, 2));
			$diaTeste = $dia < 1 || $dia > 31;
			$mesTeste = $mes < 1 || $mes > 12;
			$anoTeste = $ano < 1900 || $ano > 2300;
			$tamanhoTeste = strlen($_POST['inputAdmissao']) != 10;
			if($tamanhoTeste || $diaTeste || $mesTeste || $anoTeste) $_POST['inputAdmissao'] = "";

			$_POST['inputDemissao'] = preg_replace("/[^0-9\-]/", "", $_POST['inputDemissao']);
			$ano = intval(substr($_POST['inputDemissao'], 0, 4));
			$mes = intval(substr($_POST['inputDemissao'], 5, 2));
			$dia = intval(substr($_POST['inputDemissao'], 8, 2));
			$diaTeste = $dia < 1 || $dia > 31;
			$mesTeste = $mes < 1 || $mes > 12;
			$anoTeste = $ano < 1900 || $ano > 2300;
			$tamanhoTeste = strlen($_POST['inputDemissao']) != 10;
			if($tamanhoTeste || $diaTeste || $mesTeste || $anoTeste) $_POST['inputDemissao'] = "";
		}
	}
?>