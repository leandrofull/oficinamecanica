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

					if($_FILES['inputVeiculoFotos']['size'][$i] > 4194304) {
						$_FILES['inputVeiculoFotos']['error'][0] = 'ERRO! As fotos devem conter 4 MB cada uma no máximo.';
						return false;
						break;
					}

					$countFiles++;
				}
			}

			if($countFiles > 6) {
				$_FILES['inputVeiculoFotos']['error'][0] = 'ERRO! Só é permitido o envio de 6 fotos por Ordem de Serviço.';
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