<?php
  session_start();
  // Terminar la sesión:
  session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>GIO | Grupo Integral Óptico</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="row">
		<div  class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">Iniciar sesión</div>
				<div class="panel-body">
					<form role="form" method="POST" id="frmLogin">
						<fieldset>
							<div class="form-group">
								<input class="form-control" placeholder="Usuario" name="usuario" type="text" autofocus="">
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Contraseña" name="contrasenia" type="password" value="">
							</div>
							
							<button type="submit" class="btn btn-primary">Entrar</button>

							</fieldset>
					</form>
					<div class="col-12" id="msjLogin"></div>
				</div>
			</div>
		</div><!-- /.col-->
	</div><!-- /.row -->	
	

<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/sesion.js"></script>
</body>
</html>
