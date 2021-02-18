<?php
	session_start();
	include_once 'admin/config.php';
	$usuario = returnUser($_SESSION['user'],$con);
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
		font-size: 25px;
		position: absolute;
		left:10px;
		top:9px;
		text-shadow: 2px 2px 5px darkkhaki;
	}

</style>
<script type="text/javascript">
	jQuery(document).ready(function($) {



        $("#logout-button").click(function() {
            var result = confirm("¿Desea salir?");
            if (result) {
                var myForm = document.getElementById("logout_form");
                myForm.submit();
            }
        });
     })
</script>
<body>

	<div data-role="page" id="main-page">
	<div class="header">
		<a href="#" id="logout-button" data-ajax="false"><i class="material-icons logout-btn">power_settings_new</i></a>
		<form id="logout_form" role="form" action="index.php" method="post" data-ajax ="false">
        	<input type="hidden" name="logout" value="logout" />
        </form>
  		<p><?php echo $usuario['nombre']; ?></p>
  	</div>

  	<div role="main" class="ui-content">
  	</div>
</body>
</html>