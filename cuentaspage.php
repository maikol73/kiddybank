<?php
	
	session_start();
	include_once 'admin/config.php';
	require "cuentaClass.php";

	if(!isset($_SESSION['user']) || !isset($_SESSION['admin'])) {
		echo "no hay nada";
		session_destroy();
    	header("Location: index.php");
    	echo " de nada";
    	return;
	}

	if (isset($_POST['submit'])) {
		$id=$_POST['submit'];
		$_SESSION['cuenta']=$_POST['submit'];
	} else if (isset($_SESSION['cuenta'])) {
		$id=$_SESSION['cuenta'];
	} else {
		header("Location: adminpage.php");
	}
	
	$cuenta=fetchCuenta($con,$id);

	if (!$cuenta) {
		header("Location: adminpage.php");
	}
		
	$cargos=fetchCargos($con,$id);
	$nuevos=checkNuevos($cuenta,$cargos);

	if ($nuevos) {
		if (insertNuevos($con,$nuevos)) {
			echo "<script>
			alert('Nuevos');
			location.reload();
			</script>";
		}
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
	<link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
</head>

<style type="text/css">

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
		right: 30px;
		width: 70%;
		height: 80%;
		background-color: seashell;
  		border-radius: 20px;
  		padding: 0;
  		margin: 0;
	}

	.back2 {
		position: absolute;
		top: 10px;
		left: 10px;
		bottom: 10px;
		right: 10px;
		background-color: seashell;
		border: 3px solid tan;
  		border-radius: 20px;
  		padding: 10px;
  		margin: 0;
  		padding: 20px;
  		overflow-y: scroll;
	}

	.balance {
		top: 20%;
	}

	.ahorro {
		top: 30%;
	}

	.back_balance {
		position: absolute;
		left: 10px;
		width: 20%;
		height: 50px;
		background-color: seashell;
  		border-radius: 20px;
  		padding: 0;
  		margin: 0;
	}

	.back_balance p {
		text-align: center;
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

	table {
    	padding:0;
    	border-collapse: collapse;
    	width:100%;
  	}

  	tbody tr:nth-child(even) {
        background: #e9e9e9;
    }

  	tbody tr:nth-child(odd) {
        background: white;
    }

    tbody td {
    	border:1px solid lightgrey;
    }

    td:nth-child(1), td:nth-child(2){
      text-align: left !important;
      padding: 5px;
    }

    td:nth-child(3), td:nth-child(4), td:nth-child(5), td:nth-child(6){
      text-align: right; !important;
      padding: 5px;
    }

    .green {
    	color:seagreen;
    }

    .red {
    	color: tomato;
    }

    #nuevoCargo {
    	position: relative;
    	width:600px;
    	height:400px;
    	margin: 0;
    	padding: 0;
    	background-color: orange;
    }

    #nuevoCargo .title p{
    	font-family: 'Amatic SC', cursive;
    	text-align: left;
    	font-size: 1.5em;
    	color: whitesmoke;
    	padding-left:20px;
    	padding-top: 10px;
    	margin: 0;
    }

    #nuevoCargo .back_form {
    	background-color: whitesmoke;
    	position: relative;
    	height: 250px;
    	margin-left: 10px;
    	margin-right: 10px;
    }

    #nuevoCargo .radioDiv {
    	position: absolute;
    	left:15px;
    	top:40px;
    	width: 100px;
    	height: 60px;
    }

    #nuevoCargo .fecha {
    	position: absolute;
    	left:140px;
    	top:40px;
    	width: 400px;
    }

    #nuevoCargo .importe {
    	position: absolute;
    	left:140px;
    	top:90px;
    	width: 400px;
    }

    #nuevoCargo .guardar button {
    	position: absolute;
    	bottom:10px;
    	right:10px;
    	width:200px;
    	background-color: seagreen;
    	color: whitesmoke;
    	text-shadow: none;
    }

    #nuevoCargo .eliminar button {
    	position: absolute;
    	bottom:10px;
    	right:230px;
    	width:200px;
    	background-color: tomato;
    	color: whitesmoke;
    	text-shadow: none;
    }

    #nuevoCargo .concepto {
    	position: absolute;
    	left:15px;
    	top:140px;
    	width: 525px;
    }

    .fecha label,  .importe label, .concepto label {
    	position: absolute;
    	left: 5px;
    	top:8px;
    	width:100px;
    	height: 40px;
    	font-size: 1.2em;
    	font-family: 'Amatic SC', cursive;
    }

    .fecha input, .importe input , .concepto input {
    	position: absolute;
    	left:110px;
    	top:5px;
    	width:300px;
    	height: 40px;
    	background-color: lightgreen;
    	text-decoration: none !important;
    }

    .concepto input {
    	width:425px;
    }

    .clickable {
    	cursor: pointer;
    }
</style>

<script type="text/javascript">
	var fechaCargo;
	var importeCargo;
	var importeDeposito;
	var conceptoCargo;
	var idCargo;

	$(document).on('pagebeforeshow', '#main-page', function(){ 
    	$(document).on("popupbeforeposition", "#nuevoCargo",function( event, ui ) {

    		$('#idCargo').val(idCargo)
    		if (!idCargo) {
    			$(".eliminar","#nuevoCargo").hide()
    			return
    		}
    		$(".eliminar","#nuevoCargo").show()
        	if (importeCargo=="") {
				var importe=importeDeposito
				$( "#radio2" ).prop( "checked", true ).checkboxradio( "refresh" );
        		$( "#radio1" ).prop( "checked", false ).checkboxradio( "refresh" );
			} else {
				var importe=importeCargo
				$( "#radio1" ).prop( "checked", true ).checkboxradio( "refresh" );
        		$( "#radio2" ).prop( "checked", false ).checkboxradio( "refresh" );
			}
			$( ".fecha","#nuevoCargo" ).find( "input" ).val(fechaCargo)
			$( ".concepto","#nuevoCargo" ).find( "input" ).val(conceptoCargo)
			$( ".importe","#nuevoCargo" ).find( "input" ).val(importe.slice(0,-2))
			idCargo=undefined
    	});  
	});

	jQuery(document).ready(function($) {

        $("#logout-button").click(function() {
            var result = confirm("¿Desea salir?");
            if (result) {
                var myForm = document.getElementById("logout_form");
                myForm.submit();
            }
        });

        $(".clickable").on("click", function() {
        	var currentRow=$(this).closest("tr");
        	fechaCargo=currentRow.find("td:eq(0)").text();
			conceptoCargo=currentRow.find("td:eq(1)").text();
			importeCargo=currentRow.find("td:eq(2)").text();
			importeDeposito=currentRow.find("td:eq(3)").text();
			idCargo=$(this).data('id')
			$("#nuevoCargo").popup("open"); 
		})
     })
</script>

<body>

<div data-role="page" id="main-page">

	<div data-role="popup" id="nuevoCargo" class="ui-corner-all">
		<div class="title">
			<p>Nuevo Cargo</p>
		</div>
		<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
		<form role="form" action="helperCuentas.php" method="post" data-ajax ="false">
			<div class="back_form">
				<div class="radioDiv">
					<fieldset data-role = "controlgroup" data-mini = "true">
    					<input type="radio" id="radio1" name="tipo" value="1" checked>
    					<label for="radio1">Cargo</label>
    					<input type="radio" id="radio2" name="tipo" value="0">
    					<label for="radio2">Depósito</label>
					</fieldset>
				</div>
				<div class="fecha">
					<label>Fecha:</label>
					<input type="date" name="fecha" value="">
				</div>
				<div class="importe">
					<label>Importe:</label>
					<input type="number" step="0.01" name="amount" value="">
				</div>
				<div class="concepto">
					<label>Concepto:</label>
					<input type="text" name="concept" value="">
				</div>
			</div>
			<div class="guardar">
				<button type="submit" name="submit" value="nuevoCargo">GUARDAR</button>
			</div>
			<div class="eliminar">
				<button type="submit" name="submit" value="deleteCargo">ELIMINAR</button>
			</div>
			
			<input type="hidden" name="cuenta" value="<?php echo $cuenta->getID();?>">
			<input type="hidden" name="gestor" value="<?php echo $_SESSION['user'];?>">
			<input id="idCargo" type="hidden" name="id" value="">
		</form>
	</div>

	<div data-role="popup" id="ajustesForm" class="ui-corner-all">
		<div class="title">
			<p>Ajustes</p>
		</div>
		<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
		<form role="form" action="helperCuentas.php" method="post" data-ajax ="false">
			<fieldset data-role="fieldcontain">
						<label>Nombre:</label>
						<input type="text" name="name" value="<?php echo $cuenta->getNombre()?>">
					</fieldset>
			<fieldset data-role="field-contain">
							<label>Paga semanal:</label>
							<input type="number" name="paga" step="0.5" value="<?php echo $cuenta->getPaga();?>">
						</fieldset>
			<fieldset data-role="field-contain">
							<label>Porcentaje hucha:</label>
							<input type="number" name="hucha" value="<?php echo $cuenta->getPCT();?>">
						</fieldset>
						<fieldset data-role="field-contain">
							<label>Interes +:</label>
							<input type="number" name="interes1" step="0.5" value="<?php echo $cuenta->getInteres1();?>">
						</fieldset>
						<fieldset data-role="field-contain">
							<label>Interes -:</label>
							<input type="number" name="interes2" step="0.5" value="<?php echo $cuenta->getInteres2();?>">
						</fieldset>
				<input type="hidden" name="id" value="<?php echo $cuenta->getID();?>">
				<button type="submit" name="submit" value="saveCuenta">GUARDAR</button>
				<button type="submit" name="submit" value="deleteCuenta">ELIMINAR</button>
		</form>
	</div>

  	<div role="main" class="ui-content">
  		<div class='nombre'>
  			<p><?php echo $cuenta->getNombre(); ?></p>
  			<a href="#nuevoCargo" data-rel="popup" data-position-to="window" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-check ui-btn-icon-left ui-btn-a" data-transition="pop">Nuevo</a>
  			<a href="#ajustesForm" data-rel="popup" data-position-to="window" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-check ui-btn-icon-left ui-btn-a" data-transition="pop">Ajustes</a>
  		</div>
  		<div class="back_balance balance">
  			<p><?php echo $cuenta->getBalance(); ?> €</p>
  		</div>
  		<div class="back_balance ahorro">
  			<p><?php echo $cuenta->getBalance()-$cuenta->getAhorro(); ?> €</p>
  		</div>
  		<div class="back">
  			<div class="back2">
  				<table>
  					<thead>
  						<th>Fecha</th><th>Concepto</th><th>Cargo</th><th>Deposito</th><th>Hucha</th><th>Balance</th>
  					</thead>
  					<tbody>
  				<?php
  					$b=$cuenta->getBalance();
  					$a=$cuenta->getAhorro();
  					foreach ($cargos as $cargo) {
  						echo $cargo->getCargo();
  						echo fetchBalanceRow($a,$b);
  						$b -= $cargo->getAmount();
  						$a -= $cargo->getAhorro();
  					}
  				?>
  			</tbody>
  				</table>
  			</div>
  		</div>
  	</div>
</div>

</body>
</html> 