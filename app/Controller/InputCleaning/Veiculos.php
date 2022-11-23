<?php
	namespace app\Controller\InputCleaning;

	use app\Controller\Controller;

	class Veiculos extends Controller {
		public static function clean(): void {
			if(isset($_POST['inputPlaca'])) {
				$_POST['inputPlaca'] = preg_replace('/[^a-zA-Z0-9]/', '', strtoupper($_POST['inputPlaca']));
				$_POST['inputPlaca'] = 
				substr($_POST['inputPlaca'], 0, 3)."-".
				substr($_POST['inputPlaca'], 3);
				$teste = preg_match("/[A-Z]{3}\-[0-9][0-9A-Z][0-9]{2}/", $_POST['inputPlaca']);
				if(!$teste) $_POST['inputPlaca'] = "";
			}

			$_POST['inputMarca'] = preg_replace('/[^a-zA-Zà-úÀ-Ú\ ]/', '', mb_strtoupper($_POST['inputMarca']));
			$_POST['inputMarca'] = preg_replace('/\s{2,}/', ' ', trim($_POST['inputMarca']));
			if(mb_strlen($_POST['inputMarca']) < 3 || mb_strlen($_POST['inputMarca']) > 30)
				$_POST['inputMarca'] = "";

			$_POST['inputModelo'] = preg_replace('/[^a-zA-Zà-úÀ-Ú0-9\.\-\ ]/', '', mb_strtoupper($_POST['inputModelo']));
			$_POST['inputModelo'] = preg_replace('/\s{2,}/', ' ', trim($_POST['inputModelo']));
			if(mb_strlen($_POST['inputModelo']) < 5 || mb_strlen($_POST['inputModelo']) > 50)
				$_POST['inputModelo'] = "";

			$_POST['inputCor'] = preg_replace('/[^a-zA-Zà-úÀ-Ú\ ]/', '', mb_strtoupper($_POST['inputCor']));
			$_POST['inputCor'] = preg_replace('/\s{2,}/', ' ', trim($_POST['inputCor']));
			if(mb_strlen($_POST['inputCor']) < 4 || mb_strlen($_POST['inputCor']) > 20)
				$_POST['inputCor'] = "";

			$_POST['inputAnoFab'] = preg_replace('/[^0-9]/', '', $_POST['inputAnoFab']);
			$_POST['inputAnoModelo'] = preg_replace('/[^0-9]/', '', $_POST['inputAnoModelo']);
			if($_POST['inputAnoFab'] < 1900 || $_POST['inputAnoFab'] > 2300)
				$_POST['inputAnoFab']  = "";
			if($_POST['inputAnoModelo'] < 1900 || $_POST['inputAnoModelo'] > 2300)
				$_POST['inputAnoModelo']  = "";

			$_POST['inputCombustivel'] = preg_replace('/[^a-zA-Z]/', '', $_POST['inputCombustivel']);
			if(!in_array($_POST['inputCombustivel'],
			["Gasolina", "Etanol", "Flex"])) $_POST['inputCombustivel'] = "";

			$_POST['inputObs'] = preg_replace('/[\\\"\']/', '', trim($_POST['inputObs']));
			if(mb_strlen($_POST['inputObs']) > 1000)
				$_POST['inputObs'] = "";
			else $_POST['inputObs'] = htmlspecialchars($_POST['inputObs']);

			$_POST['inputPropCPF'] = preg_replace('/[^0-9]/', '', $_POST['inputPropCPF']);
			$_POST['inputPropCPF'] = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', "$1.$2.$3-$4", $_POST['inputPropCPF']);
			$teste = preg_match('/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', $_POST['inputPropCPF']);
			if(!$teste) $_POST['inputPropCPF'] = "";
		}
	}
?>