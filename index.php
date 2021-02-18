<?php
session_start();
include_once 'admin/config.php';

function returnUsuario($name,$con) {
	$name=strtolower($name);

	$query=$con->prepare("SELECT * FROM PPT_Usuario WHERE usuario = ?");
	$query->bind_param("s",$name);

	if ($query->execute()) {

		$result=$query->get_result();
		$query->close();

		if ($row=$result->fetch_assoc()){
			
			return $row;

		} else {

			return null;
		}

	} else {

		return "error";
	}
}


unset($errormsg);

if (isset($_POST['logout'])) {

	if(isset($_SESSION['user'])) {
    		session_destroy();
    		unset($_SESSION['user']);
	} 
	
} else  if (isset($_POST['login'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $usuario = returnUsuario($name,$con);

    if ($usuario=="error") {
    	$errormsg="Upps, algo ha salido mal";
    } else if ($usuario== null) {
    	$errormsg = "No encuentro el Usuario en la base de datos";
    } else {
    	if ($password==$usuario['password']) {

				if($_POST['setcookie']) {
     				setcookie("ppt[name]", $name, time() + 31536000);
     				setcookie("ppt[password]", $password, time() + 3153600);
				}
				$_SESSION['user'] = $usuario['ID'];
				if ($usuario['admin']==1) {
					$_SESSION['admin']=1;
					header("Location: adminpage.php");
				} else {
					$_SESSION['admin'] = $usuario['admin'];
       				header("Location: main.php");
				}
				return;
		} else {

				$errormsg = "¡¡¡Contraseña incorrecta!!!";
		}
    }
    
} else if(isset($_SESSION['user'])!="") {
		if ($_SESSION['admin']==1) {
			header("Location: adminpage.php");
		}
       	header("Location: main.php");
	
} 

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

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Architects+Daughter&display=swap" rel="stylesheet">
</head>

<body>
<style type="text/css">
	.form {
		position: absolute;
		right: 30px;
		width: 55%;
		top: 100px;
		background-color: whitesmoke;
		padding: 20px;
		box-shadow: 2px 2px 8px 8px darkslategrey, -2px 2px 8px 10px dimgrey;
		border: 1px solid #ddd;
		z-index: 1;
		opacity: .9;
	}

	.back {
		position: fixed;
		top:40px;
		width: 100%;
		height: 100%;
	}

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
  	}

  	.btn-c {
		background: darkslategrey !important;
		color:white !important;
		text-shadow:none !important;
		overflow: hidden;
		border-radius: 0 !important;
		margin-top: 20px;
	}

	button.btn-c:hover {
		background-color: dimgrey !important;
	}

	.error {
		font-size: 1.8em;
		color: tomato !important;
		font-weight: bold;
		text-align: center;
	}

</style>

<div data-role="page" id="login-page">
	<div class="header">
  		<p>CUENTAS JONES</p>
  	</div>
	<div class="back">
		<img src="daniel.jpg" alt="Trulli" width="100%" height="auto">
	</div>
  	<div role="main" class="ui-content">

  		<div class="form">
  				<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" data-ajax="false" name="signupform">
                	<fieldset style="border:none">
					<div class="group">
						<label for="user" class="label">Usuario</label>
						<input id="user" name="name" type="text" class="input">
					</div>
					<div class="group">
						<label for="pass" class="label">Contraseña</label>
						<input id="pass" type="password" name="password" class="input" data-type="password">
					</div>
					<div class="group">
						<input id="check" type="checkbox" name="setcookie" class="check" checked>
						<label for="check"><span class="icon"></span> Keep me Signed in</label>
					</div>
					<div class="group">
						<button class="btn-c" type="submit" name="login" value="ENTRAR">ENTRAR</button>  
					</div>
					</fieldset>
            	</form>
            	<p class="error"><?php if (isset($errormsg)) { print_r($errormsg); } ?></p>
  		</div>
  	</div>

</div>


</body>
</html>
  