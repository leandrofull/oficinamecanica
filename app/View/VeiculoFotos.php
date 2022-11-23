<?php
	namespace app\View;

	class VeiculoFotos {
		public static function show(array $infoFiles, int $ordemStatus): void	{
			if(!empty($infoFiles[0]["filename"])) {
				for($i=0;$i<count($infoFiles);$i++) {
					$source = PROJECT_DIRECTORY."/app/Images/Ordens/Veiculos/".$infoFiles[$i]["filename"];
					$dest = PROJECT_DIRECTORY."/public/Temp/image".$i.".".$infoFiles[$i]["extension"];
					$img = DOMAIN."/Temp/image".$i.".".$infoFiles[$i]["extension"];

					copy($source, $dest);

					echo '<div class="col-12 col-md-4 col-sm-2">';
					echo '<div class="mb-3">';
					if($ordemStatus == 0) echo '<input class="form-check-input" type="checkbox" name="checkboxImage[]" value="'.$infoFiles[$i]["id"].'">';
					echo "<div style='cursor:pointer;' onclick='document.getElementById(".'"'."fullScreenImage".$i.'"'.").style.display = ".'"'."flex".'"'."'; >";
					echo "<img src='".$img."' width='100' height='100' style='object-fit:scale-down;'/>";
					echo "</div>";
					echo "</div>";
					echo '</div>';

					echo "<div id='fullScreenImage".$i."' style='position:fixed;left:0;top:0;width:100%;height:100%;background-color:rgba(128, 128, 128, 0.5);padding:0;margin:0;display:none;justify-content:center;align-items:center;' onclick='".'this.style.display = "none";'."'>";
					echo "<img src='".$img."' width='500' height='500' style='object-fit:scale-down;margin:0;padding:0;background-color:white;'/>";
					echo "</div>";
				}

				if($ordemStatus == 0) echo '<a><button type="button" id="btnDeleteImages" class="btn btn-danger">Excluir Imagens</button></a>';
			}
		}
	}
?>