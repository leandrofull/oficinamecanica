<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$view->getPageTitle()?></title>

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

	<style type="text/css">
		body {
			color:white;
			background-color:#212529;
		}

		#telaLogin {
			justify-content:center;
			align-items:center;
			display:flex;
			width:100%;
			height:100vh;
		}
	</style>
</head>
<body>
	<div id="telaLogin">
		<div>
			<img src="<?=DOMAIN."/PNG/login-logo.png"?>" height="110" /><br /><br />
			<label for="inputLogin"><strong>Login:</strong></label>
			<input type="text" id="inputLogin" class="form-control" /><br />
			<label for="inputSenha"><strong>Senha:</strong></label>
			<input type="password" id="inputSenha" class="form-control" /><br />
			<input type="button" id="btnLogin" class="btn btn-success" value="Logar" />
		</div>
	</div>

	<input type="hidden" id="teclaEnter" />

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
	<script type="text/javascript">
		window.onload = function() {
			document.getElementById('inputLogin').focus();
		}

		document.getElementById('inputLogin').onkeyup = function(e) {
			var key = e.which || e.keyCode;
  			if (key == 13) {
    			verify();
  			}
		}

		document.getElementById('inputSenha').onkeyup = function(e) {
			var key = e.which || e.keyCode;
  			if (key == 13) {
    			verify();
  			}
		}

		document.getElementById('btnLogin').addEventListener('click', verify, false);

		function verify() {
			document.getElementById('btnLogin').setAttribute('disabled', 'disabled');

			let login = document.getElementById('inputLogin').value;
			let senha = document.getElementById('inputSenha').value;

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '<?=DOMAIN?>/login/verify',
				data: {
						"login" : login,
						"senha" : senha
				},
				success: function(response) {
					alert(response.msg);
					if(response.logged == "1") location.reload();
					else document.getElementById('btnLogin').removeAttribute('disabled');
				}
			});
		}
	</script>
</body>
</html>