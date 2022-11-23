<?php require_once(PROJECT_DIRECTORY.'/public/View/Header.php'); ?>

<div id="main">
	<?php require_once(PROJECT_DIRECTORY.'/public/View/Navbar.php'); ?>

	<!-- Welcome -->
	<div class="container-fluid welcome">
		Olá, <?=$user->getNome()?>! <span class="material-symbols-outlined">mood</span>
	</div>
	<!-- Welcome -->

	<!-- Summary -->
	<div class="container-fluid summary">
		<div class="row">
			<div class="summary-title">Cadastros deste Mês</div>
		</div>
		<div class="row">
			<div class="col-12 col-sm-6 col-md-3">
				<div class="summary-item">
					<div class="summary-item-title">Clientes</div>
					<div class="summary-item-value"><?=$cliente->amountInThisMonth()?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<div class="summary-item">
					<div class="summary-item-title">Veículos</div>
					<div class="summary-item-value"><?=$veiculo->amountInThisMonth()?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<div class="summary-item">
					<div class="summary-item-title">Funcionários</div>
					<div class="summary-item-value"><?=$funcionario->amountInThisMonth()?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<div class="summary-item">
					<div class="summary-item-title">O.S.</div>
					<div class="summary-item-value"><?=$ordem->amountInThisMonth()?></div>
				</div>
			</div>
		</div>
	</div>
	<!-- Summary -->

	<!-- Summary 2 -->
	<div class="container-fluid summary summary2">
		<div class="row">
			<div class="summary-title">Ordens de Serviços (mês)</div>
		</div>
		<div class="row">
			<div class="col-12 col-sm-6 col-md-3">
				<div class="summary-item">
					<div class="summary-item-title">Orçamento</div>
					<div class="summary-item-value"><?=$ordem->amountInThisMonthByStatus(0)?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<div class="summary-item">
					<div class="summary-item-title">Em andamento</div>
					<div class="summary-item-value"><?=$ordem->amountInThisMonthByStatus(1)?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<div class="summary-item">
					<div class="summary-item-title">Finalizadas</div>
					<div class="summary-item-value"><?=$ordem->amountInThisMonthByStatus(3)?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<div class="summary-item">
					<div class="summary-item-title">Canceladas</div>
					<div class="summary-item-value"><?=$ordem->amountInThisMonthByStatus(4)?></div>
				</div>
			</div>
		</div>
	</div>
	<!-- Summary 2 -->
</div>

<?php require_once(PROJECT_DIRECTORY.'/public/View/Footer.php'); ?>