<?php
	namespace app\Controller\InputCleaning;

	use app\Controller\Controller;

	class Clientes extends Controller {
		public static function clean(): void {
			$_POST['inputName'] = preg_replace("/[^a-zA-Zà-úÀ-Ú]/", " ", $_POST['inputName']);
			$_POST['inputName'] = preg_replace("/\s{2,}/", " ", trim($_POST['inputName']));
			if(mb_strlen($_POST['inputName']) < 3) $_POST['inputName'] = "";

			$_POST['inputEmail'] = preg_replace("/\s/", "", strtolower(trim($_POST['inputEmail'])));
			$teste = preg_match("/^[\w-]+(\.[\w-]+)*@(([\w-]{2,63}\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/", $_POST['inputEmail']);
			if(!$teste) $_POST['inputEmail'] = "";

			if(isset($_POST['inputCPF'])) {
				$_POST['inputCPF'] = preg_replace("/\D/", "", $_POST['inputCPF']);
				$_POST['inputCPF'] = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $_POST['inputCPF']);
				$teste = preg_match("/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/", $_POST['inputCPF']);
				if(!$teste) $_POST['inputCPF'] = "";
			}

			$_POST['inputTelefone'] = preg_replace("/\D/", "", $_POST['inputTelefone']);
			$_POST['inputCelular'] = preg_replace("/\D/", "", $_POST['inputCelular']);
			$_POST['inputWhatsapp'] = preg_replace("/\D/", "", $_POST['inputWhatsapp']);
			$_POST['inputTelefone'] = "55".$_POST['inputTelefone'];
			$_POST['inputCelular'] = "55".$_POST['inputCelular'];
			$_POST['inputWhatsapp'] = "55".$_POST['inputWhatsapp'];
			if(strlen($_POST['inputTelefone']) != 12) $_POST['inputTelefone'] = "";
			if(strlen($_POST['inputCelular']) != 13) $_POST['inputCelular'] = "";
			if(strlen($_POST['inputWhatsapp']) != 13) $_POST['inputWhatsapp'] = "";

			$_POST['inputEndereco'] = preg_replace("/[^a-zA-Zà-úÀ-Ú0-9\ ]/", " ", $_POST['inputEndereco']);
			$_POST['inputEndereco'] = preg_replace("/\s{2,}/", " ", trim($_POST['inputEndereco']));
			if(strlen($_POST['inputEndereco']) < 5 || ctype_digit($_POST['inputEndereco'])) $_POST['inputEndereco'] = "";

			$_POST['inputEndNumero'] = preg_replace("/[^a-zA-Z0-9]/", "", $_POST['inputEndNumero']);
			if(strlen($_POST['inputEndNumero']) > 6)
				$_POST['inputEndNumero'] = "";

			$_POST['inputEndCompl'] = preg_replace("/[^a-zA-Zà-úÀ-Ú0-9\ ]/", "", $_POST['inputEndCompl']);
			$_POST['inputEndCompl'] = preg_replace("/\s{2,}/", " ", trim($_POST['inputEndCompl']));
			if(strlen($_POST['inputEndCompl']) < 4 || strlen($_POST['inputEndCompl']) > 10)
				$_POST['inputEndCompl'] = "";

			$_POST['inputCep'] = preg_replace("/\D/", "", $_POST['inputCep']);
			$_POST['inputCep'] = preg_replace("/^([\d]{5})\.*([\d]{3})/", "$1-$2", $_POST['inputCep']);
			if(strlen($_POST['inputCep']) != 9) $_POST['inputCep'] = "";

			$_POST['inputBairro'] = preg_replace("/[^a-zA-Zà-úÀ-Ú0-9\ ]/", "", $_POST['inputBairro']);
			$_POST['inputBairro'] = preg_replace("/\s{2,}/", " ", trim($_POST['inputBairro']));
			if(strlen($_POST['inputBairro']) < 4 || ctype_digit($_POST['inputBairro'])) $_POST['inputBairro'] = "";

			$_POST['inputCidade'] = preg_replace("/[^a-zA-Zà-úÀ-Ú0-9\ ]/", "", $_POST['inputCidade']);
			$_POST['inputCidade'] = preg_replace("/\s{2,}/", " ", trim($_POST['inputCidade']));
			if(strlen($_POST['inputCidade']) < 4 || ctype_digit($_POST['inputCidade'])) $_POST['inputCidade'] = "";

			$_POST['inputUf'] = preg_replace("/[^a-zA-Z]/", "", $_POST['inputUf']);
			if(strlen($_POST['inputUf']) != 2) $_POST['inputUf'] = "";

			$_POST['inputNasc'] = preg_replace("/[^0-9\-]/", "", $_POST['inputNasc']);
			$ano = intval(substr($_POST['inputNasc'], 0, 4));
			$mes = intval(substr($_POST['inputNasc'], 5, 2));
			$dia = intval(substr($_POST['inputNasc'], 8, 2));
			$diaTeste = $dia < 1 || $dia > 31;
			$mesTeste = $mes < 1 || $mes > 12;
			$anoTeste = $ano < 1900 || $ano > 2300;
			$tamanhoTeste = strlen($_POST['inputNasc']) != 10;
			if($tamanhoTeste || $diaTeste || $mesTeste || $anoTeste) $_POST['inputNasc'] = "";

			if(!isset($_POST['inputSexo'])) $_POST['inputSexo'] = "";
			$_POST['inputSexo'] = preg_replace("/[^A-Z]/", "", $_POST['inputSexo']);
			if($_POST['inputSexo'] != "M" &&
				$_POST['inputSexo'] != "F" &&
				$_POST['inputSexo'] != "O") $_POST['inputSexo'] = "";
		}
	}
?>