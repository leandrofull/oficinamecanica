<?php require_once(PROJECT_DIRECTORY.'/public/View/Header.php'); ?>

<div id="main">
	<?php require_once(PROJECT_DIRECTORY.'/public/View/Navbar.php'); ?>

	<!-- Return -->
	<?php 
		if(isset($_SESSION['error']) && $_SESSION['error'] == true) {
			echo '<div class="container-fluid welcome" style="background-color:#fc5b5b;font-weight:normal;">';	
			echo $_SESSION['errorMsg'].'  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">sentiment_dissatisfied</span>';
			echo '</div>';
			unset($_SESSION['error']);
			unset($_SESSION['errorMsg']);
		} else if(isset($_SESSION['error']) && $_SESSION['error'] == false) {
			echo '<div class="container-fluid welcome" style="font-weight:normal;">';
			echo $_SESSION['errorMsg'].' <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">mood</span>';
			echo '</div>';
			unset($_SESSION['error']);
			unset($_SESSION['errorMsg']);
		}
	?>
	<!-- Return -->

	<!-- Page Title -->
	<p class="page-title">Veículos Cadastrados</p>
	<!-- Page Title -->

	<!-- Search -->
	<form class="d-flex" role="search" method="GET">
		<div class="search">
			<input class="form-control me-2" type="search" name="search" placeholder="Pesquise pela placa, marca, modelo ou proprie..." value="<?php if(isset($_GET['search']) && !empty($_GET['search'])) echo $_GET['search']; ?>">
			<button class="btn btn-outline-success" type="submit">Pesquisar</button>
		</div>
	</form>
	<!-- Search -->

<form action="<?=DOMAIN."/veiculos/arquivar"?>" method="POST" id="form">

	<!-- List -->
	<div class="list">
		<!-- Table -->
		<div class="divTable">
		<table class="table">
		  <thead>
		    <tr class="list-cols">
		      <th scope="col">#</th>
		      <th scope="col">Placa</th>
		      <th scope="col">Marca/Modelo</th>
		      <th scope="col">Proprietário</th>
		      <th scope="col">
		      	Últ. Modificação
		      	<span class="material-symbols-outlined">
					help
				</span>
			</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php if($veiculo->resultsCountPage > 0) { ?>
		  	<?php for($i=0; $i<$veiculo->resultsCountPage; $i++) { ?>
		    <tr>
		      <td>
		      	<input class="form-check-input" type="checkbox" name="selectedItems[]" value="<?=$veiculo->id[$i]?>">
		      </td>
		      <td><a href="<?=DOMAIN."/veiculo/".$veiculo->id[$i]?>"><?=$veiculo->placa[$i]?></a></td>
		      <td><?=$veiculo->marca[$i]?></td>
		      <td><?=$veiculo->proprietario[$i]?></td>
		      <td><?=$veiculo->dataUltimaModificacao[$i]?></td>
		    </tr>
			<?php } } else { ?>
		    <tr>
		      <td>Nenhum veículo encontrado!</td>
		      <td></td>
		      <td></td>
		      <td></td>
		      <td></td>
		    </tr>
			<?php } ?>
		  </tbody>
		</table>
		</div>
		<!-- Table -->

		<!-- Pagination -->
		<?php
			if($veiculo->archiveMode) {
				app\View\Pagination::show(
					DOMAIN."/veiculos/arquivados",
					$pageNum,
					$veiculo->resultsCountPage,
					$veiculo->resultsCountAll
				);
			} else {
				app\View\Pagination::show(
					DOMAIN."/veiculos",
					$pageNum,
					$veiculo->resultsCountPage,
					$veiculo->resultsCountAll
				);	
			}
		?>
		<!-- Pagination -->

		<!-- Actions -->
		<div class="list-actions">
			<p class="list-actions-title">Ações:</p>
			<p class="list-actions-selections" id="selectedCount">0 selecionados</p>
			<a href="<?=DOMAIN."/veiculo/new"?>"><button type="button" id="list-actions-new" class="btn btn-success">Novo</button></a>
			<a href="<?=DOMAIN."/".$view->getNavLinkActive()?>" id='list-actions-archiveds'>
				<button type="button" class="btn btn-primary">Ver arquivados</button>
			</a>
			<button type="button" id="list-actions-archive" class="btn btn-warning">Arquivar</button>
		</div>
		<!-- Actions -->
	</div>
	<!-- List -->
</form>
</div>

<?php require_once(PROJECT_DIRECTORY.'/public/View/Footer.php'); ?>