<?php
	namespace app\Controller\InputFilesCheck;

	use app\Controller\Controller;

	class Ordens extends Controller {
		public static function check(int $countFiles): bool {
			for($i=0;$i<count($_FILES['inputVeiculoFotos']['tmp_name']);$i++) {
				if(!empty($_FILES['inputVeiculoFotos']['tmp_name'][$i])) {
					if(mime_content_type($_FILES['inputVeiculoFotos']['tmp_name'][$i])
						!= 'image/jpeg' &&  
					   mime_content_type($_FILES['inputVeiculoFotos']['tmp_name'][$i])
					   	!= 'image/png')
					{
						$_FILES['inputVeiculoFotos']['error'][0] = 'ERRO! Um dos tipos de arquivos enviados não é suportado.';
						return false;
						break;
					}

					if($_FILES['inputVeiculoFotos']['size'][$i] > 10485760) {
						$_FILES['inputVeiculoFotos']['error'][0] = 'ERRO! As fotos devem conter 10 MB cada uma no máximo.';
						return false;
						break;
					}

					$countFiles++;
				}
			}

			if($countFiles > 10) {
				$_FILES['inputVeiculoFotos']['error'][0] = 'ERRO! Só é permitido o envio de 10 fotos por vez.';
				return false;
			}

			if($countFiles < 1) {
				$_FILES['inputVeiculoFotos']['error'][0] = 'ERRO! Você não enviou nenhuma foto do veículo.';
				return false;
			}

			return true;
		}
	}
?>