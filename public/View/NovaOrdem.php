<?php use app\Controller\ClearFilesFolder\PublicTempFolder; ?>
<?php use app\View\VeiculoFotos; ?>
<?php require_once(PROJECT_DIRECTORY.'/public/View/Header.php'); ?>

<div id="main">
	<?php require_once(PROJECT_DIRECTORY.'/public/View/Navbar.php'); ?>

	<?php if(empty($ordem->numeroOS[0])): ?>
	<form action="<?=DOMAIN."/ordem/cadastrar"?>" method="POST" id="form" enctype="multipart/form-data">
	<?php else: ?>
	<form action="<?=DOMAIN."/ordem/editar"?>" method="POST" id="form" enctype="multipart/form-data">
	<?php endif ?>
	<div id="formCadastro">
		<div class="container-fluid">
			<?php if(!empty($ordem->numeroOS[0])): ?>
			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<p style="font-size:1.2rem;margin-bottom:0;padding-bottom:0;">
							<strong>Número O.S.:</strong>
							<?=$ordem->numeroOS[0]?>
						</p>
					</div>
				</div>
			</div>
			<?php endif ?>
			<div class="row">
				<div class="col-12 col-sm-5">
					<div class="mb-3">
						<label for="inputVeiculoPlaca" class="form-label">Placa Veículo: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputVeiculoPlaca" name="inputVeiculoPlaca" maxlength="8" value="<?=$ordem->veiculoPlaca[0]?>" <?php if(!empty($ordem->numeroOS[0])) echo "disabled"; ?>>
						<p id="validVeiculoPlaca" class="validation"></p>
					</div>
				</div>
				<div class="col-12 col-sm-7">
					<div class="mb-3">
						<label for="inputVeiculoMarca" class="form-label">Marca/Modelo Veículo: </label>
						<input type="text" id="inputVeiculoMarca" class="form-control" value="<?=$ordem->veiculoMarca[0]?>" disabled />
						<p class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-sm-5">
					<div class="mb-3">
						<label for="inputClienteCPF" class="form-label">CPF Cliente: </label>
						<input type="text" id="inputClienteCPF" class="form-control" value="<?=$ordem->clienteCPF[0]?>" disabled />
						<p class="validation"></p>
					</div>
				</div>
				<div class="col-12 col-sm-7">
					<div class="mb-3">
						<label for="inputClienteNome" class="form-label">Nome Cliente: </label>
						<input type="text" id="inputClienteNome" class="form-control" value="<?=$ordem->clienteNome[0]?>" disabled />
						<p class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-sm-5">
					<div class="mb-3">
						<label for="inputRespMatricula" class="form-label">Funcionário Resp. Matrícula: <spam>*</spam></label>
						<input type="text" class="form-control" id="inputRespMatricula" name="inputRespMatricula" maxlength="12" value="<?=$ordem->responsavelMatricula[0]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validRespMatricula" class="validation"></p>
					</div>
				</div>
				<div class="col-12 col-sm-7">
					<div class="mb-3">
						<label for="inputRespNome" class="form-label">Funcionário Resp. Nome/Cargo: </label>
						<input type="text" id="inputRespNome" class="form-control" value="<?=$ordem->responsavelNome[0]?>" disabled />
						<p class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<label for="inputVeiculoFotos" class="form-label">Fotos Veículo: <spam>*</spam></label>
						<input class="form-control" type="file" id="inputVeiculoFotos" name="inputVeiculoFotos[]" accept=".jpg, .jpeg, .png" multiple <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validVeiculoFotos" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<?php
					PublicTempFolder::clear();
					VeiculoFotos::show($ordem->veiculoFotos, $ordem->status[0]);
				?>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="mb-3">
						<label for="inputVeiculoHodometro" class="form-label">Hodômetro Veículo: </label>
						<input type="text" id="inputVeiculoHodometro" name="inputVeiculoHodometro" class="form-control" maxlength="7" value="<?=$ordem->veiculoHodometro[0]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?> />
						<p id="validVeiculoHodometro" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="mb-3">
						<label for="inputServicoStatus" class="form-label">Status: </label>
						<select class="form-select" id="inputServicoStatus" disabled>
							<option <?php if($ordem->status[0] == 0) echo "selected"; ?>>Orçamento</option>
							<option <?php if($ordem->status[0] == 1) echo "selected"; ?>>Em andamento</option>
							<option <?php if($ordem->status[0] == 2) echo "selected"; ?>>Validar</option>
							<option <?php if($ordem->status[0] == 3) echo "selected"; ?>>Finalizada</option>
						</select>
						<p class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="mb-3">
						<label for="inputServicoTipo" class="form-label">Tipo de Serviço: <spam>*</spam></label>
						<select class="form-select" id="inputServicoTipo" name="inputServicoTipo" <?php if(!empty($ordem->numeroOS[0])) echo "disabled"; ?>>
							<option value="Conserto" <?php if($ordem->servicoTipo[0] == "Conserto") echo "selected"; ?>>Conserto</option>
							<option value="Revisão" <?php if($ordem->servicoTipo[0] == "Revisão") echo "selected"; ?>>Revisão</option>
						</select>
						<p id="validServicoTipo" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="mb-3 dataNasc">
						<label for="inputServicoPrevisao" class="form-label">Prev. p/ Conclusão:</label>
						<input type="date" class="form-control" id="inputServicoPrevisao" name="inputServicoPrevisao" value="<?=$ordem->servicoPrevisao[0]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validServicoPrevisao" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="mb-3 dataNasc">
						<label for="inputObs" class="form-label">Observações: </label>
						<textarea class="form-control" id="inputObs" name="inputObs" rows="6" maxlength="1000" style="resize:none" <?php if($ordem->status[0] == 3) echo "disabled"; ?>><?=$ordem->observacoes[0]?></textarea>
						<p id="validObs" class="validation"></p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="mb-3 dataNasc">
						<label for="inputHistorico" class="form-label">Histórico: </label>
						<textarea class="form-control" id="inputHistorico" name="inputHistorico" rows="6" maxlength="1000" style="resize:none" disabled><?=$ordem->historico[0]?></textarea>
						<p id="validHistorico" class="validation"></p>
					</div>
				</div>
			</div>
			<br /><hr /><br />
			<div class="row">
				<label class="form-label" style="font-size:1.1rem;">Serviços <spam>*</spam></label>
			</div>
			<div class="row" id="newServico">
				<div class="col-md-6 col-6">
					<div class="mb-3">
						<label for="inputServicoDesc[0]" class="form-label">Descrição: </label>
						<input type="text" class="form-control" id="inputServicoDesc[0]" name="inputServicoDesc[0]" maxlength="70" value="<?=$ordem->ordemServicos[0]["descricao"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validServicoDesc[0]" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6 col-6">
					<div class="mb-3">
						<label for="inputServicoValor[0]" class="form-label">Valor:</label>
						<input type="text" class="form-control" id="inputServicoValor[0]" name="inputServicoValor[0]" maxlength="13" value="<?=$ordem->ordemServicos[0]["valor"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validServicoValor[0]" class="validation"></p>
					</div>	
				</div>
				<?php if($ordem->servicosQtde > 1) { ?>
				<?php for($i=1;$i<$ordem->servicosQtde;$i++) { ?>
					<div class="col-md-6 col-6">
						<div class="mb-3">
							<label for="inputServicoDesc[<?=$i?>]" class="form-label">Descrição: </label>
							<input type="text" class="form-control" id="inputServicoDesc[<?=$i?>]" name="inputServicoDesc[<?=$i?>]" maxlength="70" value="<?=$ordem->ordemServicos[$i]["descricao"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
							<p id="validServicoDesc[<?=$i?>]" class="validation"></p>
						</div>
					</div>
					<div class="col-sm-6 col-6">
						<div class="mb-3">
							<label for="inputServicoValor[<?=$i?>]" class="form-label">Valor:</label>
							<input type="text" class="form-control" id="inputServicoValor[<?=$i?>]" name="inputServicoValor[<?=$i?>]" maxlength="13" value="<?=$ordem->ordemServicos[$i]["valor"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
							<p id="validServicoValor[<?=$i?>]" class="validation"></p>
						</div>	
					</div>
				<?php } } ?>
			</div>
			<?php if($ordem->status[0] == 0): ?>
			<div class="row">
				<div class="col-sm-6 col-4">
					<div class="mb-3">
						<button type="button" id="btnAddServico" class="btn btn-primary">+</button>
					</div>
				</div>
			</div>
			<?php endif ?>
			<br /><hr /><br />
			<div class="row">
				<label class="form-label" style="font-size:1.1rem;">Peças</label>
			</div>
			<div class="row" id="newPeca">
				<div class="col-md-6 col-12">
					<div class="mb-3">
						<label for="inputPecaDesc[0]" class="form-label">Descrição: </label>
						<input type="text" class="form-control" id="inputPecaDesc[0]" name="inputPecaDesc[0]" maxlength="70" value="<?=$ordem->ordemPecas[0]["descricao"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validPecaDesc[0]" class="validation"></p>
					</div>
				</div>
				<div class="col-6 col-sm-6">
					<div class="mb-3">
						<label for="inputPecaQtde[0]" class="form-label">Qtde.: </label>
						<input type="text" class="form-control" id="inputPecaQtde[0]" name="inputPecaQtde[0]" maxlength="2" value="<?=$ordem->ordemPecas[0]["qtde"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validPecaQtde[0]" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6 col-6">
					<div class="mb-3">
						<label for="inputPecaValor[0]" class="form-label">Valor Unid.:</label>
						<input type="text" class="form-control" id="inputPecaValor[0]" name="inputPecaValor[0]" maxlength="13" value="<?=$ordem->ordemPecas[0]["valor"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validPecaValor[0]" class="validation"></p>
					</div>	
				</div>
				<?php if($ordem->pecasQtde > 1) { ?>
				<?php for($i=1;$i<$ordem->pecasQtde;$i++) { ?>
				<div class="col-md-6 col-12">
					<div class="mb-3">
						<label for="inputPecaDesc[<?=$i?>]" class="form-label">Descrição: </label>
						<input type="text" class="form-control" id="inputPecaDesc[<?=$i?>]" name="inputPecaDesc[<?=$i?>]" maxlength="70" value="<?=$ordem->ordemPecas[$i]["descricao"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validPecaDesc[<?=$i?>]" class="validation"></p>
					</div>
				</div>
				<div class="col-6 col-sm-6">
					<div class="mb-3">
						<label for="inputPecaQtde[<?=$i?>]" class="form-label">Qtde.: </label>
						<input type="text" class="form-control" id="inputPecaQtde[<?=$i?>]" name="inputPecaQtde[<?=$i?>]" maxlength="2" value="<?=$ordem->ordemPecas[$i]["qtde"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validPecaQtde[<?=$i?>]" class="validation"></p>
					</div>
				</div>
				<div class="col-sm-6 col-6">
					<div class="mb-3">
						<label for="inputPecaValor[<?=$i?>]" class="form-label">Valor Unid.:</label>
						<input type="text" class="form-control" id="inputPecaValor[<?=$i?>]" name="inputPecaValor[<?=$i?>]" maxlength="13" value="<?=$ordem->ordemPecas[$i]["valor"]?>" <?php if($ordem->status[0] != 0) echo "disabled"; ?>>
						<p id="validPecaValor[<?=$i?>]" class="validation"></p>
					</div>	
				</div>
				<?php } } ?>
			</div>
			<?php if($ordem->status[0] == 0): ?>
			<div class="row">
				<div class="col-sm-6 col-4">
					<div class="mb-3">
						<button type="button" id="btnAddPeca"class="btn btn-primary">+</button>
					</div>
				</div>
			</div>
			<?php endif ?>
			<br /><br />

			<?php if(!empty($ordem->numeroOS[0])): ?>
			<div class="row">
				<div class="col-12">
					<strong>Total em Serviços: </strong>
					<?=$ordem->servicosValorTotal?><br />
					<strong>Total em Peças: </strong>
					<?=$ordem->pecasValorTotal?><br />
					<strong>Valor total da O.S.: </strong>
					<?=$ordem->ordemValorTotal?><br />
				</div>
			</div>
			<br /><br />
			<?php endif ?>

			<div id="formButtons">
				<?php if(empty($ordem->numeroOS[0])): ?>
				<button type="button" id="btnSave" class="btn btn-success">Gerar</button>
				<?php elseif($ordem->status[0] == 0): ?>
				<button type="button" id="btnSave" class="btn btn-success">Alterar</button>
				<button type="button" id="btnSendToCliente" class="btn btn-warning">Enviar p/ Cliente</button>
				<?php elseif($ordem->status[0] == 1): ?>
				<button type="button" id="btnSave" class="btn btn-success">Editar observações</button>
				<button type="button" id="btnFinalize" class="btn btn-danger">Encerrar O.S.</button>
				<?php endif ?>
			</div>
		</div>
	</div>
	<input type="hidden" id="inputID" name="inputID" value="<?=$ordem->id[0]?>" />
	</form>
	<input type="hidden" id="DOMAIN" value="<?=DOMAIN?>" />
	<input type="hidden" id="qtdeServicos" value="<?=$ordem->servicosQtde?>" />
	<input type="hidden" id="qtdePecas" value="<?=$ordem->pecasQtde?>" />
</div>

<div id="modalSendToCliente">
	<div class="modal">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
			    <h5 class="modal-title">Enviar O.S. para o cliente validação</h5>
			    <button type="button" id="modalSendToClienteClose" class="btn-close"></button>
			  </div>
			  <div class="modal-body">
			    <p>Você poderá enviar esta ordem de serviço para que o cliente valide através do WhatsApp.</p>

			    <p>Lembrete: o status da ordem de serviço só poderá ser alterado para "Em andamento" após a confirmação do cliente.</p>
			  </div>
			  <div class="modal-footer">
			  	<label for="inputSendOS"><strong>Número: </strong></label>
			    <input type="text" class="form-control" id="inputSendOS" maxlength="15" />
			    <p class="validation" id="validSendOS" style="color:red;"></p>
			    <button type="button" id="btnSendOS" class="btn btn-primary">Enviar</button>
			  </div>
			</div>
		</div>
	</div>
</div>

<?php require_once(PROJECT_DIRECTORY.'/public/View/Footer.php'); ?>