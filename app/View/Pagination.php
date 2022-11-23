<?php
	namespace app\View;

	class Pagination {
		public static function show(string $url, int $pageNum, int $resultsCountPage, int $resultsCountAll): void
		{
			if($resultsCountPage > 0) {
				$pagesTotal = $resultsCountAll/TABLE_ROWS;
				if($pagesTotal > intval($pagesTotal)) $pagesTotal = intval($pagesTotal)+1;

				$paginacaoInicio = $pageNum-2;
				if($paginacaoInicio <= 0) $paginacaoInicio = 1;
				$paginacaoFim = $paginacaoInicio+4;
				if($paginacaoFim > $pagesTotal) $paginacaoFim = $pagesTotal;

				$paginacoes = $paginacaoFim - $paginacaoInicio;
				$paginacaoInicio = $paginacoes-4+$paginacaoInicio;
				if($paginacaoInicio <= 0) $paginacaoInicio = 1;

				echo "<nav>";
				echo '<ul class="pagination">';

				if($paginacaoInicio > 1) {
					echo '<li class="page-item"><a class="page-link" href="'.$url.'/1'.'">Primeira</a></li>';
				}

		  		for($i=$paginacaoInicio;$i<=$paginacaoFim;$i++) {
		  			if($i == $pageNum) {
		  				echo '<li class="page-item active"><a class="page-link" href="#">'.$i.'</a></li>';
		  		 	} else {
		  			echo '<li class="page-item"><a class="page-link" href="'.$url.'/'.$i.'">'.$i.'</a></li>';
		  			}
		  		}

				if($paginacaoFim < $pagesTotal) {
					echo '<li class="page-item"><a class="page-link" href="'.$url.'/'.$pagesTotal.'">Última</a></li>';
				}

			  	echo "</ul>";
			  	echo "</nav>";
			} 
		}
	}
?>