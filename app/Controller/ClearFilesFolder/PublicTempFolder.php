<?php
	namespace app\Controller\ClearFilesFolder;

	use app\Controller\Controller;

	class PublicTempFolder extends Controller {
		public static function clear(): void {
			$tempDir = PROJECT_DIRECTORY."/public/Temp";

			if(is_dir($tempDir)) {
				$diretorio = dir($tempDir);
				while($arquivo = $diretorio->read())
				{
					if(($arquivo != '.') && ($arquivo != '..')) {
						unlink($tempDir."/".$arquivo);
					}
				}
				$diretorio->close();
			}
		}
	}
?>