<?php
	namespace app\Controller;

	class UploadFile extends Controller {
		public static function start(string $input, string $output): bool {
			// Inicia
			$curl = curl_init();

			// Configura
			curl_setopt($curl, CURLOPT_URL, 'https://api.tinify.com/shrink');
			curl_setopt($curl, CURLOPT_USERPWD, 'api:XjjWpP6THVXsLmHMpSBKd1BVjcRlSmdw');
			curl_setopt($curl, CURLOPT_POSTFIELDS, file_get_contents($input));
			curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);

			// Envio e armazenamento da resposta
			$response = curl_exec($curl);
			$outputLink = json_decode(substr($response, curl_getinfo($curl, CURLINFO_HEADER_SIZE)))->output->url;

			// Fecha e limpa recursos
			curl_close($curl);

			$return = file_put_contents($output, file_get_contents($outputLink));

			if($return === false) return false;
			else return true;
		}
	}
?>