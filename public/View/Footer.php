	<!-- Footer -->
	<div class="container-fluid footer">
	  <p>&copy; <?=APP_NAME?> - <?=date('Y')?><br />
	  <?=FOOTER_DESCRIPTION?><br />
	  <strong>Github:</strong> <?=FOOTER_GITHUB?></p>
	</div>
	<!-- Footer -->

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

	<!-- Nav Link Active -->
	<script type="text/javascript">
		<?php if(!empty($view->getNavLinkActive())): ?>
			document.getElementById('nav-link-<?=$view->getNavLinkActive()?>').setAttribute('class', 'nav-link active');
		<?php endif ?>
	</script>

	<?=$view->getJSFiles()?>
</body>
</html>