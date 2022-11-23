<?php require_once(PROJECT_DIRECTORY.'/public/View/Header.php'); ?>

<div id="main">
	<?php require_once(PROJECT_DIRECTORY.'/public/View/Navbar.php'); ?>

	<!-- Archive Return -->
	<?php 
		if(isset($archiveReturn) && $archiveReturn['error'] == true) {
			echo '<div class="container-fluid welcome" style="background-color:#fc5b5b;font-weight:normal;">';	
			echo $archiveReturn['errorMsg'].'  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">sentiment_dissatisfied</span>';
			echo '</div>';
		} else if(isset($archiveReturn) && $archiveReturn['error'] == false) {
			echo '<div class="container-fluid welcome" style="font-weight:normal;">';
			if($funcionario->archiveMode)
				echo $archiveReturn['affectedRecords'].' funcionários desarquivados com sucesso!  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">mood</span>';
			else
				echo $archiveReturn['affectedRecords'].' funcionários arquivados com sucesso!  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">mood</span>';
			echo '</div>';
		}
	?>
	<!-- Archive Return -->

	<!-- Register Return -->
	<?php 
		if(isset($registerReturn) && $registerReturn['error'] == true) {
			echo '<div class="container-fluid welcome" style="background-color:#fc5b5b;font-weight:normal;">';	
			echo $registerReturn['errorMsg'].'  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">sentiment_dissatisfied</span>';
			echo '</div>';
		} else if(isset($registerReturn) && $registerReturn['error'] == false) {
			echo '<div class="container-fluid welcome" style="font-weight:normal;">';
			echo 'Cadastro realizado com sucesso!  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">mood</span>';
			echo '</div>';
		}
	?>
	<!-- Register Return -->

	<!-- Edit Return -->
	<?php 
		if(isset($editReturn) && $editReturn['error'] == true) {
			echo '<div class="container-fluid welcome" style="background-color:#fc5b5b;font-weight:normal;">';	
			echo $editReturn['errorMsg'].'  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">sentiment_dissatisfied</span>';
			echo '</div>';
		} else if(isset($editReturn) && $editReturn['error'] == false) {
			echo '<div class="container-fluid welcome" style="font-weight:normal;">';
			echo 'Cadastro atualizado com sucesso!  <span class="material-symbols-outlined" style="font-size:2rem;margin-left:5px;">mood</span>';
			echo '</div>';
		}
	?>
	<!-- Edit Return -->

	<!-- Page Title -->
	<p class="page-title">Funcionários Cadastrados</p>
	<!-- Page Title -->

	<!-- Search -->
	<form class="d-flex" role="search" method="GET">
		<div class="search">
			<input class="form-control me-2" type="search" name="search" placeholder="Pesquise pelo nome, cargo, matrícula ou cpf..." value="<?php if(isset($_GET['search']) && !empty($_GET['search'])) echo $_GET['search']; ?>">
			<button class="btn btn-outline-success" type="submit">Pesquisar</button>
		</div>
	</form>
	<!-- Search -->

<form action="<?=DOMAIN."/funcionarios/arquivar"?>" method="POST" id="form">

	<!-- List -->
	<div class="list">
		<!-- Table -->
		<table class="table">
		  <thead>
		    <tr class="list-cols">
		      <th scope="col">#</th>
		      <th scope="col">Nome</th>
		      <th scope="col">Matrícula</th>
		      <th scope="col">Cargo</th>
		      <th scope="col">
		      	Últ. Modificação
		      	<span class="material-symbols-outlined">
					help
				</span>
			</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php if($funcionario->resultsCountPage > 0) { ?>
		  	<?php for($i=0; $i<$funcionario->resultsCountPage; $i++) { ?>
		    <tr>
		      <td>
		      	<input class="form-check-input" type="checkbox" name="selectedItems[]" value="<?=$funcionario->id[$i]?>">
		      </td>
		      <td><a href="<?=DOMAIN."/funcionario/".$funcionario->id[$i]?>"><?=$funcionario->nome[$i]?></a></td>
		      <td><?=$funcionario->matricula[$i]?></td>
		      <td><?=$funcionario->cargo[$i]?></td>
		      <td><?=$funcionario->dataUltimaModificacao[$i]?></td>
		    </tr>
			<?php } } else { ?>
		    <tr>
		      <td>Nenhum funcionário encontrado!</td>
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
			if($funcionario->archiveMode) {
				app\View\Pagination::show(
					DOMAIN."/funcionarios/arquivados",
					$pageNum,
					$funcionario->resultsCountPage,
					$funcionario->resultsCountAll
				);
			} else {
				app\View\Pagination::show(
					DOMAIN."/funcionarios",
					$pageNum,
					$funcionario->resultsCountPage,
					$funcionario->resultsCountAll
				);	
			}
		?>
		<!-- Pagination -->

		<!-- Actions -->
		<div class="list-actions">
			<p class="list-actions-title">Ações:</p>
			<p class="list-actions-selections" id="selectedCount">0 selecionados</p>
			<a href="<?=DOMAIN."/funcionario/new"?>"><button type="button" id="list-actions-new" class="btn btn-success">Novo</button></a>
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