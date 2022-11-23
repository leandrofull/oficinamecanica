<?php require_once(PROJECT_DIRECTORY.'/public/View/Header.php'); ?>

<div id="main">
	<?php require_once(PROJECT_DIRECTORY.'/public/View/Navbar.php'); ?>

	<?php if(empty($veiculo->placa[0])): ?>
	<form action="<?=DOMAIN."/veiculo/cadastrar"?>" method="POST" id="form">
	<?php else: ?>
	<form action="<?=DOMAIN."/veiculo/editar"?>" method="POST" id="form">
	<?php endif ?>
	<div id="formCadastro">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-sm-4 col-md-2">
					<div class="mb-3">
						<label for="inputPlaca" class="form-label">Placa: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputPlaca" name="inputPlaca" maxlength="8" value="<?=$veiculo->placa[0]?>" <?php if(!empty($veiculo->placa[0])) echo "disabled"; ?>>
						<p id="validPlaca" class="validation"></p>
					</div>
				</div>
				<div class="col-12 col-sm-8 col-md-5">
					<div class="mb-3">
						<label for="inputMarca" class="form-label">Marca: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputMarca" name="inputMarca" maxlength="30" value="<?=$veiculo->marca[0]?>">
						<p id="validMarca" class="validation"></p>
					</div>
				</div>
				<div class="col-12 col-sm-8 col-md-5">
					<div class="mb-3">
						<label for="inputModelo" class="form-label">Modelo: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputModelo" name="inputModelo" maxlength="50" value="<?=$veiculo->modelo[0]?>">
						<p id="validModelo" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-sm-5">
					<div class="mb-3">
						<label for="inputCor" class="form-label">Cor: </label>
						<input type="text" class="form-control" id="inputCor" name="inputCor" maxlength="20" value="<?=$veiculo->cor[0]?>">
						<p id="validCor" class="validation"></p>
					</div>
				</div>
				<div class="col-12 col-sm-4 col-md-4">
					<div class="mb-3">
						<label for="inputAnoFab" class="form-label">Ano Fab.: </label>
						<input type="text" class="form-control" id="inputAnoFab" name="inputAnoFab" maxlength="4" value="<?=$veiculo->anoFab[0]?>">
						<p id="validAnoFab" class="validation"></p>
					</div>
				</div>
				<div class="col-12 col-sm-4 col-md-4">
					<div class="mb-3">
						<label for="inputAnoModelo" class="form-label">Ano Modelo: </label>
						<input type="text" class="form-control" id="inputAnoModelo" name="inputAnoModelo" maxlength="4" value="<?=$veiculo->anoModelo[0]?>">
						<p id="validAnoModelo" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-sm-6">
					<div class="mb-3">
						<label for="inputCombustivel" class="form-label">Combustível: </label>
						<select class="form-select" id="inputCombustivel" name="inputCombustivel">
							<option <?php if(empty($veiculo->combustivel[0])) echo "selected"; ?> value="">-- Selecione --</option>
							<option <?php if($veiculo->combustivel[0] == 'Gasolina') echo "selected"; ?> value="Gasolina">Gasolina</option>
							<option <?php if($veiculo->combustivel[0] == 'Etanol') echo "selected"; ?> value="Etanol">Etanol</option>
							<option <?php if($veiculo->combustivel[0] == 'Flex') echo "selected"; ?> value="Flex">Flex</option>
						</select>
						<p id="validCombustivel" class="validation"></p>
					</div>
				</div>
				<div class="col-12">
					<div class="mb-3">
						<label for="inputObs" class="form-label">Observações: </label>
						<textarea class="form-control" id="inputObs" name="inputObs" rows="6" maxlength="1000" style="resize:none"><?=$veiculo->observacoes[0]?></textarea>
						<p id="validObs" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-sm-5">
					<div class="mb-3">
						<label for="inputPropCPF" class="form-label">CPF Proprietário: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputPropCPF" name="inputPropCPF" maxlength="14" value="<?=$veiculo->proprietarioCPF[0]?>">
						<p id="validPropCPF" class="validation"></p>
					</div>
				</div>
				<div class="col-12 col-sm-7">
					<div class="mb-3">
						<label for="inputPropName" class="form-label">Nome Proprietário: </label>
						<input type="text" id="inputPropName" class="form-control" value="<?=$veiculo->proprietario[0]?>" disabled/>
					</div>
				</div>
			</div>
			<br />
			<div id="formButtons">
				<button type="button" id="btnSave" class="btn btn-success">Salvar</button>
				<?php if(!empty($veiculo->placa[0])): ?>
				<a href="<?=DOMAIN."/cliente/".$veiculo->proprietarioID[0]?>"><button type="button" id="btnCliente" class="btn btn-warning">Ficha Proprietário</button></a>
				<?php endif ?>
			</div>
		</div>
	</div>
	<input type="hidden" name="inputID" value="<?=$veiculo->id[0]?>" />
	</form>
</div>

<?php require_once(PROJECT_DIRECTORY.'/public/View/Footer.php'); ?>