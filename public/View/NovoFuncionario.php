<?php require_once(PROJECT_DIRECTORY.'/public/View/Header.php'); ?>

<div id="main">
	<?php require_once(PROJECT_DIRECTORY.'/public/View/Navbar.php'); ?>

	<?php if(empty($funcionario->matricula[0])): ?>
	<form action="<?=DOMAIN."/funcionario/cadastrar"?>" method="POST" id="form">
	<?php else: ?>
	<form action="<?=DOMAIN."/funcionario/editar"?>" method="POST" id="form">
	<?php endif ?>
	<div id="formCadastro">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<div class="mb-3">
						<label for="inputName" class="form-label">Nome Completo: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputName" name="inputName" maxlength="70" value="<?=$funcionario->nome[0]?>">
						<p id="validName" class="validation"></p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-3">
						<label for="inputCargo" class="form-label">Cargo: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputCargo" name="inputCargo" maxlength="50" value="<?=$funcionario->cargo[0]?>">
						<p id="validCargo" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="mb-3">
						<label for="inputMatricula" class="form-label">Matrícula: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputMatricula" name="inputMatricula" maxlength="12" value="<?php if(!empty($funcionario->matricula[0])) echo $funcionario->matricula[0]; else echo "00000000000"; ?>" <?php if(!empty($funcionario->matricula[0])) echo "disabled"; ?>>
						<p id="validMatricula" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="mb-3">
						<label for="inputCPF" class="form-label">CPF: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputCPF" name="inputCPF" maxlength="14" value="<?=$funcionario->cpf[0]?>" <?php if(!empty($funcionario->matricula[0])) echo "disabled"; ?>>
						<p id="validCPF" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="mb-3">
						<label for="inputRemuneracao" class="form-label">Remuneração:</label>
						<input type="text" class="form-control" id="inputRemuneracao" name="inputRemuneracao" maxlength="13" value="<?php if(!empty($funcionario->remuneracao[0])) echo $funcionario->remuneracao[0]; else echo "R\$ 0"; ?>">
						<p id="validRemuneracao" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="mb-3">
						<label for="inputNasc" class="form-label">Data Nasc.:</label>
						<input type="date" class="form-control" id="inputNasc" name="inputNasc" value="<?=$funcionario->dataNascimento[0]?>">
						<p id="validNasc" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="mb-3">
						<label for="inputAdmissao" class="form-label">Admissão:</label>
						<input type="date" class="form-control" id="inputAdmissao" name="inputAdmissao" value="<?=$funcionario->dataAdmissao[0]?>">
						<p id="validAdmissao" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6 dataNasc">
					<div class="mb-3">
						<label for="inputDemissao" class="form-label">Demissão:</label>
						<input type="date" class="form-control" id="inputDemissao" name="inputDemissao" value="<?=$funcionario->dataDemissao[0]?>">
						<p id="validDemissao" class="validation"></p>
					</div>
				</div>
			</div>
			<div id="formButtons">
				<button type="button" id="btnSave" class="btn btn-success">Salvar</button>
			</div>
		</div>
	</div>
	<input type="hidden" name="inputID" value="<?=$funcionario->id[0]?>" />
	</form>

</div>

<?php require_once(PROJECT_DIRECTORY.'/public/View/Footer.php'); ?>