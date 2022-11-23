<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=APP_NAME?> - Contrato</title>
</head>
<body>
	<?php if($contrato->getOrdemStatus($contratoID) == 0): ?>
	<button type='button' id="btnAccept">Concordo com os Termos</button>
	<button type='button' id="btnNotAccept">Discordo</button>
	<?php else: ?>
	<p>
		<?php 
			$status = array();
			$status[1] = 'Em progresso.';
			$status[3] = 'Concluído.';
			echo "<strong>Status Atual: </strong>".$status[$contrato->getOrdemStatus($contratoID)];
		?>
	</p>
	<?php endif ?>
	<br /><br />
	<iframe src="<?=DOMAIN?>/contrato/canvas/<?=$contratoID?>" style='width:100%;height:1000px;display:flex;justify-content:center;align-items:center;'></iframe>
	<?php if($contrato->getOrdemStatus($contratoID) == 0): ?>
	<script type="text/javascript">
		document.getElementById('btnAccept').addEventListener('click', accept, false);
		document.getElementById('btnNotAccept').addEventListener('click', notAccept, false);

		function accept() {
			window.location.href = '<?=DOMAIN?>/contrato/<?=$contratoID?>/accept';
		}

		function notAccept() {
			window.location.href = '<?=DOMAIN?>/contrato/<?=$contratoID?>/notAccept';
		}
	</script>
	<?php endif ?>
</body>
</html>