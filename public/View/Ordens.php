<?php require_once(PROJECT_DIRECTORY.'/public/View/Header.php'); ?>

<div id="main">
	<?php require_once(PROJECT_DIRECTORY.'/public/View/Navbar.php'); ?>

	<!-- Return -->
	<?php 
		if(isset($_GET['registerError']) && $_GET['registerError'] == true) {
			echo '<div class="container-fluid welcome" style="background-color:#fc5b5b;font-weight:normal;">';	
			echo $_GET['registerErrorMsg'].'  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">sentiment_dissatisfied</span>';
			echo '</div>';
		} else if(isset($_GET['registerError']) && $_GET['registerError'] == false) {
			echo '<div class="container-fluid welcome" style="font-weight:normal;">';
			echo $_GET['registerErrorMsg'].'  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">mood</span>';
			echo '</div>';
		}
	?>
	<!-- Return -->

	<!-- Page Title -->
	<p class="page-title">Ordens de Serviço</p>
	<!-- Page Title -->

	<!-- Search -->
	<form class="d-flex" role="search" method="GET">
		<div class="search">
			<input class="form-control me-2" type="search" name="search"placeholder="Pesquise pelo número de O.S., cliente, veícul..." value="<?php if(isset($_GET['search']) && !empty($_GET['search'])) echo $_GET['search']; ?>">
			<button class="btn btn-outline-success" type="submit">Pesquisar</button>
		</div>
	</form>
	<!-- Search -->

<form action="<?=DOMAIN."/ordens/arquivar"?>" method="POST" id="form">

	<!-- List -->
	<div class="list">
		<!-- Table -->
		<table class="table">
		  <thead>
		    <tr class="list-cols">
		      <th scope="col">#</th>
		      <th scope="col">Número O.S.</th>
		      <th scope="col">Cliente</th>
		      <th scope="col">Veículo</th>
		      <th scope="col">Status</th>
		      <th scope="col">Tipo</th>
		      <th scope="col">
		      	Últ. Modificação
		      	<span class="material-symbols-outlined">
					help
				</span>
			</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php if($ordem->resultsCountPage > 0) { ?>
		  	<?php for($i=0; $i<$ordem->resultsCountPage; $i++) { ?>
		    <tr>
		      <td>
		      	<input class="form-check-input" type="checkbox" name="selectedItems[]" value="<?=$ordem->id[$i]?>">
		      </td>
		      <td><a href="<?=DOMAIN."/ordem/".$ordem->id[$i]?>"><?=$ordem->numeroOS[$i]?></a></td>
		      <td><?=$ordem->clienteNome[$i]?></td>
		      <td><?=$ordem->veiculoPlaca[$i]?></td>
		      <td><?=$ordem->status[$i]?></td>
		      <td><?=$ordem->servicoTipo[$i]?></td>
		      <td><?=$ordem->dataUltimaModificacao[$i]?></td>
		    </tr>
			<?php } } else { ?>
		    <tr>
		      <td>Nenhuma ordem de serviço encontrada!</td>
		      <td></td>
		      <td></td>
		      <td></td>
		      <td></td>
		      <td></td>
		      <td></td>
		    </tr>
			<?php } ?>
		  </tbody>
		</table>
		<!-- Table -->

		<!-- Pagination -->
		<?php
			if($ordem->archiveMode) {
				app\View\Pagination::show(
					DOMAIN."/ordens/arquivados",
					$pageNum,
					$ordem->resultsCountPage,
					$ordem->resultsCountAll
				);
			} else {
				app\View\Pagination::show(
					DOMAIN."/ordens",
					$pageNum,
					$ordem->resultsCountPage,
					$ordem->resultsCountAll
				);	
			}
		?>

		<!-- Actions -->
		<div class="list-actions">
			<p class="list-actions-title">Ações:</p>
			<p class="list-actions-selections" id="selectedCount">0 selecionados</p>
			<a href="<?=DOMAIN."/ordem/new"?>"><button type="button" id="list-actions-new" class="btn btn-success">Novo</button></a>
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