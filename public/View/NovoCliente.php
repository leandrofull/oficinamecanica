<?php require_once(PROJECT_DIRECTORY.'/public/View/Header.php'); ?>

<div id="main">
	<?php require_once(PROJECT_DIRECTORY.'/public/View/Navbar.php'); ?>

	<?php if(empty($cliente->cpf[0])): ?>
	<form action="<?=DOMAIN."/cliente/cadastrar"?>" method="POST" id="form">
	<?php else: ?>
	<form action="<?=DOMAIN."/cliente/editar"?>" method="POST" id="form">
	<?php endif ?>
	<div id="formCadastro">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<div class="mb-3">
						<label for="inputName" class="form-label">Nome Completo: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputName" name="inputName" maxlength="70" value="<?=$cliente->nome[0]?>">
						<p id="validName" class="validation"></p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-3">
						<label for="inputEmail" class="form-label">E-mail:</label>
						<input type="text" class="form-control" id="inputEmail" name="inputEmail" maxlength="70" value="<?=$cliente->email[0]?>">
						<p id="validEmail" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-sm-6">
					<div class="mb-3">
						<label for="inputCPF" class="form-label">CPF: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputCPF" name="inputCPF" maxlength="14" value="<?=$cliente->cpf[0]?>" <?php if(!empty($cliente->cpf[0])) echo "disabled"; ?>>
						<p id="validCPF" class="validation"></p>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<div class="mb-3">
						<label for="inputTelefone" class="form-label">Telefone:<ion-icon name="help-circle-outline" id="helpTelefone"></ion-icon></label>
						<input type="text" class="form-control" id="inputTelefone" name="inputTelefone" maxlength="14" value="<?=$cliente->telefone[0]?>">
						<p id="validTelefone" class="validation"></p>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<div class="mb-3">
						<label for="inputCelular" class="form-label">Celular:<ion-icon name="help-circle-outline" id="helpCelular"></ion-icon></label>
						<input type="text" class="form-control" id="inputCelular" name="inputCelular" maxlength="15" value="<?=$cliente->celular[0]?>">
						<p id="validCelular" class="validation"></p>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<div class="mb-3">
						<label for="inputCelular" class="form-label">WhatsApp:<span class="material-symbols-outlined" id="helpWhatsapp">help</span></label>
						<input type="text" class="form-control" id="inputWhatsapp" name="inputWhatsapp" maxlength="15" value="<?=$cliente->whatsapp[0]?>">
						<p id="validWhatsapp" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-9">
					<div class="mb-3">
						<label for="inputEndereco" class="form-label">Endereço:</label>
						<input type="text" class="form-control" id="inputEndereco" name="inputEndereco" maxlength="70" value="<?=$cliente->endereco[0]["Endereco"]?>">
						<p id="validEndereco" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="mb-3">
						<label for="inputEndNumero" class="form-label">Nº:</label>
						<input type="text" class="form-control" id="inputEndNumero" name="inputEndNumero" maxlength="6" value="<?=$cliente->endereco[0]["Numero"]?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-8">
					<div class="mb-3">
						<label for="inputEndCompl" class="form-label">Complemento:</label>
						<input type="text" class="form-control" id="inputEndCompl" name="inputEndCompl" maxlength="30" value="<?=$cliente->endereco[0]["Complemento"]?>">
						<p id="validEndCompl" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="mb-3">
						<label for="inputCep" class="form-label">CEP:</label>
						<input type="text" class="form-control" id="inputCep" name="inputCep" maxlength="9" value="<?=$cliente->cep[0]?>">
						<p id="validCep" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-5">
					<div class="mb-3">
						<label for="inputBairro" class="form-label">Bairro:</label>
						<input type="text" class="form-control" id="inputBairro" name="inputBairro" maxlength="40" value="<?=$cliente->bairro[0]?>">
						<p id="validBairro" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="mb-3">
						<label for="inputCidade" class="form-label">Cidade:</label>
						<input type="text" class="form-control" id="inputCidade" name="inputCidade" maxlength="40" value="<?=$cliente->cidade[0]?>">
						<p id="validCidade" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="mb-3">
						<label for="inputUf" class="form-label">UF:</label>
						<input type="text" class="form-control" id="inputUf" name="inputUf" maxlength="2" value="<?=$cliente->uf[0]?>">
						<p id="validUf" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="mb-3 dataNasc">
						<label for="inputNasc" class="form-label">Data Nasc.:</label>
						<input type="date" class="form-control" id="inputNasc" name="inputNasc" value="<?=$cliente->dataNascimento[0]?>">
						<p id="validNasc" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<label for="inputSexo" class="form-label">Sexo:</label>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="inputSexo" value="M" <?php if($cliente->sexo[0] == "M") echo "checked"; ?>>
					  <label class="form-check-label">
					    Masculino
					  </label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="inputSexo" value="F" <?php if($cliente->sexo[0] == "F") echo "checked"; ?>>
					  <label class="form-check-label">
					    Feminino
					  </label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="inputSexo" value="O" <?php if($cliente->sexo[0] == "O") echo "checked"; ?>>
					  <label class="form-check-label">
					    Outro
					  </label>
					</div>
				</div>
			</div>
			<div id="formButtons">
				<button type="button" id="btnSave" class="btn btn-success">Salvar</button>
				<?php if(!empty($cliente->cpf[0])): ?>
				<?php if(!$cliente->arquivado[0]): ?>
				<a href="<?=DOMAIN."/veiculos?search=".$cliente->cpf[0]?>"><button type="button" id="btnCliente" class="btn btn-warning">Veículos deste cliente</button></a>
				<?php else: ?>
				<a href="<?=DOMAIN."/veiculos/arquivados/1?search=".$cliente->cpf[0]?>"><button type="button" id="btnCliente" class="btn btn-warning">Veículos deste cliente</button></a>
				<?php endif ?>
				<?php endif ?>
			</div>
		</div>
	</div>
	<input type="hidden" name="inputID" value="<?=$cliente->id[0]?>" />
	</form>

</div>

<?php require_once(PROJECT_DIRECTORY.'/public/View/Footer.php'); ?>