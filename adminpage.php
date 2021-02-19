<?php
	session_start();
	include_once 'admin/config.php';

	function returnUsersAdmin($con,$id) {
		$query=$con->prepare("SELECT ID, nombre FROM PPT_Usuario WHERE admin = ?");
		$query->bind_param("i",$id);

		$query->execute();
   		$result=$query->get_result();
    	$query->close();
    	while($row = $result->fetch_assoc()) {
        	$array[] = $row;
    	}
    	return $array;
	}

	function returnCuentas($con,$id) {
		$query=$con->prepare("SELECT PPT_Cuentas.ID AS ID, PPT_Cuentas.nombre AS nombreCuenta, PPT_Usuario.nombre AS nombreUsuario, PPT_Usuario.ID AS idUsuario FROM PPT_Cuentas LEFT JOIN PPT_Usuario ON PPT_Cuentas.usuario=PPT_Usuario.ID WHERE PPT_Cuentas.admin =?");
		$query->bind_param("i",$id);

		$query->execute();
   		$result=$query->get_result();
    	$query->close();
    	while($row = $result->fetch_assoc()) {
        	$array[$row['idUsuario']] = $row;
    	}
    	return $array;
	}

	if(!isset($_SESSION['user']) || !isset($_SESSION['admin'])) {
		session_destroy();
		unset($_SESSION['user']);
		unset($_SESSION['admin']);
    	header("Location: index.php");

	}

	if ($_SESSION['user']==$_SESSION['admin']) {
		session_destroy();
		unset($_SESSION['user']);
		unset($_SESSION['admin']);
    	header("Location: index.php");
	}

	if (isset($_SESSION['user'])) {
		$error=$_SESSION['error'];
		unset($_SESSION['error']);
	}

	$usuario = returnUser($_SESSION['user'],$con);
	$dependientes=returnUsersAdmin($con,$usuario['ID']);
	$cuentas=returnCuentas($con,$usuario['ID']);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Home | LECM </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" >


	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

  	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
</head>

<style type="text/css">
	.header {
    	position: fixed;
    	padding: 0px;
    	margin: 0px;
    	top: 0px;
    	width:100%;
    	height:45px;
    	z-index: 12;
    	background: linear-gradient(110deg, darkslategrey 70%, gainsboro 60%);
    	font-family: 'Architects Daughter', cursive;
    	font-size:1.5em ;
    	text-shadow:none !important;
    	overflow: hidden;
    	color:azure!important;
    	text-align: center;
    	opacity: .9;
    	box-shadow: 10px 10px 5px white;
  	}

  	.header p {
  		padding: 5px;
  		margin:  0;
  		text-transform: uppercase;
  	}

  	.logout-btn {
		color: azure;
		font-size: 35px;
		top:7px;
		left:7px;
		position: absolute;
		text-shadow: 2px 2px 5px darkkhaki;
	}

	.ui-content {
		position: fixed;
		width: 100%;
		height: 100%;
		background-color: lightskyblue;
	}


	.back {
		position: absolute;
		top: 10%;
		left: 15%;
		width: 60%;
		height: 60%;
		background-color: seashell;
		border: 2px solid tan;
  		border-radius: 20px;
  		padding: 0;
  		margin: 0;
	}

	.back_form {
		position: absolute;
		top: 10%;
		left: 15%;
		width: 60%;
		height: 480px;
		background-color: seashell;
  		border-radius: 20px;
  		padding: 0;
  		margin: 0;
	}

	.back2_form {
		position: absolute;
		top: 8px;
		left: 8px;
		bottom: 8px;
		right: 8px;
		background-color: seashell;
		border: 3px solid tan;
  		border-radius: 20px;
  		padding: 0;
  		margin: 0;
  		padding: 10px;
	}

	.half1_form {
		position: absolute;
		width: 45%;
		margin-top: 10px;
	}

	.half2_form {
		position: absolute;
		margin-top: 10px;
		width: 45%;
		left: 50%;
	}

	#cuenta-page button {
		position: absolute;
		bottom: 10px;
		width: 60%;
		left: 19%;
	}

	.logout {
		position: absolute;
		top:-25px;
		left:-25px;
		width:50px;
		height: 50px;
		border-radius: 25px;
		background-color: tan;
	}

	

	.btn a {
		background-color: steelblue;
		padding: 8px;
    	text-decoration: none !important;
    	font-size: 36px;
    	display: block; /* Display block instead of inline */
    	transition: 0.3s; /* Transition effects on hover (color) */
    	font-weight: normal !important;
    	text-shadow: none!important;
    	color:whitesmoke!important;
	}

	.left {
		position: absolute;
		top:60px;
		left: 5%;
		width: 40%;
	}

	.right {
		position: absolute;
		top:60px;
		left: 55%;
		width: 40%;
	}

	.form {
		margin-top: 120px;
		padding: 10px;
	}

	.popup {
		background: #22c1c3; 
		background: -webkit-linear-gradient(to right, #fdbb2d, #22c1c3); 
		background: linear-gradient(to right, #fdbb2d, #22c1c3);
	}

	#nuevoAhorrador {
		.position: relative;
		width:600px;
		height:400px;
	}

	.popup .title {
		position: absolute;
		width: 100%;
		height: 50px;
		padding-left: 20px;
	}

	.popup .title p {
		text-align: left;
		color: darkred;
		font-family: 'Amatic SC', cursive;
		font-size: 2.1em;
	}

	.form_popup {
		position: absolute;
		width:400px;
		right:10px;
		top:50px;
		height: 300px;
	}

	#nuevoAhorrador button, #nuevaCuenta button {
		background: #642B73; 
		background: -webkit-linear-gradient(to right, #C6426E, #642B73); 
		background: linear-gradient(to right, #C6426E, #642B73); 
		
		color:whitesmoke !important;
		text-shadow: none;
		position: absolute;
		bottom: 10px;
	}

	#nuevaCuenta {
		position: relative;
		width:600px;
		height:440px;
	}

	#nuevaCuenta .form_nombre {
		position: absolute;
		top:90px;
		left:25px;
		right:25px;
	}

	#nuevaCuenta label {
		color: darkred;
		font-family: 'Amatic SC', cursive;
		font-size: 1.5em;
	}

	#nuevaCuenta .form1 {
		position: absolute;
		top:160px;
		left:25px;
		width:160px;
	}

	#nuevaCuenta .form1 input,  #nuevaCuenta .form2 input, #nuevaCuenta .form3 input{
		text-align: right;
	}

	#nuevaCuenta .form2 {
		position: absolute;
		top:160px;
		left:220px;
		width:160px;
	}

	#nuevaCuenta .form3 {
		position: absolute;
		top:160px;
		right:25px;
		width:160px;
	}

	#nuevaCuenta button {
		width:60%;
		right:25px;
	}

</style>
<script type="text/javascript">

	$(document).on('pagebeforeshow', '#main-page', function(){ 
    	$(document).on("popupbeforeposition", "#nuevoAhorrador",function( event, ui ) {
    		$("input[name='usuario']","#nuevoAhorrador").val("")
    		$("input[name='nombre']","#nuevoAhorrador").val("")
    		$("input[name='password']","#nuevoAhorrador").val("")
    		$("input[name='password2']","#nuevoAhorrador").val("")
    		$("button","#nuevoAhorrador").prop('disabled',true)
    	});  
	});

	$(document).on('pagebeforeshow', '#main-page', function(){ 
    	$(document).on("popupbeforeposition", "#nuevaCuenta",function( event, ui ) {
    		$("input[name='name']","#nuevaCuenta").val("")
    		$("button","#nuevaCuenta").prop('disabled',true)
    	});  
	});

	function checkPassword() {
		if ($("input[name='password']","#nuevoAhorrador").val()=="") {
			return "Introduzca una contraseña"
		}
		if ($("input[name='password']","#nuevoAhorrador").val().length<4) {
			return "La contraseña debe tener al menos 4 caracteres"
		}


		if ($("input[name='password']","#nuevoAhorrador").val()!=$("input[name='password2']","#nuevoAhorrador").val()) {
			return "Las contraseñas no coinciden"
		}
		return 1
	} 

	function checkUsuario() {
		if ($("input[name='usuario']","#nuevoAhorrador").val()=="") {
			return "Introduzca un usuario"
		}
		if ($("input[name='usuario']","#nuevoAhorrador").val().length<4) {
			return "El usuario debe tener al menos 4 caracteres"
		}
		return true;
	}

	function checkNombre() {
		if ($("input[name='nombre']","#nuevoAhorrador").val()=="") {
			return "Introduzca un nombre"
		}
		if ($("input[name='nombre']","#nuevoAhorrador").val().length<2) {
			return "El nombre debe tener al menos 2 caracteres"
		}
		return true;
	}

	function checkFormUsuario() {
		if (checkUsuario()!=1) {
			$(".check","#nuevoAhorrador").text(checkUsuario())
			$("button","#nuevoAhorrador").prop('disabled',true)
			return
		}
		if (checkNombre()!=1) {
			$(".check","#nuevoAhorrador").text(checkNombre())
			$("button","#nuevoAhorrador").prop('disabled',true)
			return
		}
		if (checkPassword()!=1) {
			$(".check","#nuevoAhorrador").text(checkPassword())
			$("button","#nuevoAhorrador").prop('disabled',true)
			return
		} 		$(".check","#nuevoAhorrador").text("")
		$("button","#nuevoAhorrador").prop('disabled',false)
		return
	}

	jQuery(document).ready(function($) {

        $("#logout-button").click(function() {
            var result = confirm("¿Desea salir?");
            if (result) {
                var myForm = document.getElementById("logout_form");
                myForm.submit();
            }
        });

        $("input[name='usuario']","#nuevoAhorrador").on('keyup', function() {
        	checkFormUsuario()
		});

		$("input[name='password']","#nuevoAhorrador").on('keyup', function() {
        	checkFormUsuario()
		});

		$("input[name='password2']","#nuevoAhorrador").on('keyup', function() {
        	checkFormUsuario()
		});

		$("input[name='nombre']","#nuevoAhorrador").on('keyup', function() {
        	checkFormUsuario()
		});

		$("input[name='name']","#nuevaCuenta").on('keyup', function() {
        	if ($("input[name='name']","#nuevaCuenta").val()=="") {
        		$("button","#nuevaCuenta").prop('disabled',true)
				return
			}
			if ($("input[name='name']","#nuevaCuenta").val().length<4) {
				$("button","#nuevaCuenta").prop('disabled',true)
				return
			}
			$("button","#nuevaCuenta").prop('disabled',false)
		});

		$("a",".form").click(function() {
           $("input[name='usuario']","#nuevaCuenta").val($(this).data('id'))
        });

     })
</script>
<body>

<div data-role="page" id="main-page">

	<div data-role="popup" id="nuevoAhorrador" class="ui-corner-all popup">
		<div class="title">
			<p>Nuevo Ahorrador</p>
		</div>
		<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
		<div class="form_popup">
		<form role="form" action="helperCuentas.php" method="post" data-ajax ="false">
			<fieldset data-role="fieldcontain">
				<input type="text" name="usuario" placeholder="usuario" >
			</fieldset>
			<fieldset data-role="field-contain">
				<input type="text" name="nombre" placeholder="nombre">
			</fieldset>
			<fieldset data-role="field-contain">
				<input type="password" name="password" placeholder="contraseña">
			</fieldset>
			<fieldset data-role="field-contain">
				<input type="password" name="password2" placeholder="repetir contraseña">
			</fieldset>
			<p class="check"></p>
			<button type="submit" name="submit" value="nuevoAhorrador">GUARDAR</button>
		</form>
		</div>
	</div>

	<div data-role="popup" id="nuevaCuenta" class="ui-corner-all popup">
		<div class="title">
			<p>Nueva Cuenta</p>
		</div>
		<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
		<form role="form" action="helperCuentas.php" method="post" data-ajax ="false">
			<div class="form_nombre">
				<input type="text" name="name" placeholder="Nombre de la cuenta">
			</div>
			<div class="form1">
				<fieldset data-role="field-contain">
					<label>Balance Inicial:</label>
					<input type="number" step="0.01" name="balance" value="0">
				</fieldset>
				<fieldset data-role="field-contain">
					<label>Ahorro Inicial:</label>
					<input type="number" step="0.5" name="ahorro" value="0">
				</fieldset>
				<fieldset data-role="field-contain">
					<label>Paga semanal:</label>
					<input type="number" step="0.5" name="paga" value="3">
				</fieldset>
			</div>
			<div class="form2">
				<fieldset data-role="field-contain">
					<label>Porcentaje hucha:</label>
					<input type="number" name="hucha" value="10">
				</fieldset>
				<fieldset data-role="field-contain">
					<label>Comisión Gestión:</label>
					<input type="number" name="comision" value="10">
				</fieldset>		
			</div>
			<div class="form3">
				<fieldset data-role="field-contain">
					<label>Interes +:</label>
					<input type="number" step="0.5" name="interes1" value="5">
				</fieldset>
				<fieldset data-role="field-contain">
					<label>Interes -:</label>
					<input type="number" step="0.5" name="interes2" value="7">
				</fieldset>
			</div>
			<input type="hidden" name="usuario">
					<button type="submit" name="submit" value="cuenta">CREAR</button>
				</form>
	</div>

  	<div role="main" class="ui-content">
  		<div class="error">
  			<p><?php echo $error; ?></p>
  		</div>
  		<div class="back">
  			<div class="logout">
  				<a href="#" id="logout-button" data-ajax="false"><i class="material-icons logout-btn">power_settings_new</i></a>
					<form id="logout_form" role="form" action="index.php" method="post" data-ajax ="false">
        				<input type="hidden" name="logout" value="logout" />
        			</form>
  			</div>
  			<div class="title">
  				<p><?php echo $usuario['nombre']; ?></p>
  			</div>
  			<div class="btn left">
  				<a href="#nuevoAhorrador" data-rel="popup" data-position-to="window" >Nuevo Dependiente</a>
  			</div>
  			<div class="btn right">
  				<a href="#nuevaCuenta" data-rel="popup" data-position-to="window" >Nueva Cuenta</a>
  			</div>
  			<div class="form">
  				<form role="form" action="cuentaspage.php" method="post" data-ajax ="false">
        		<?php foreach ($cuentas as $cuenta) { 
        			echo "<button type='submit' name='submit' value='".$cuenta['ID']."'>".$cuenta['nombreCuenta']."</button>";
        		}
        		?>
        		</form>
        		<?php foreach ($dependientes as $dep) {
        			if (!array_key_exists($dep['ID'], $cuentas)) {
        				echo "<a href='#nuevaCuenta' data-rel='popup' data-id='".$dep['ID']."' data-position-to='window'>".$dep['nombre']."</a>";
        			}
        		} ?>
        	</div>
  		</div>
  	</div>
</div>

</body>
</html>