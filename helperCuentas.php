<?php
	session_start();
	include_once 'admin/config.php';

	function infoCuenta($con,$cuenta) {
		$query=$con->prepare("SELECT PPT_Usuario.nombre, email, pct, PPT_Cuentas.admin FROM PPT_Cuentas LEFT JOIN PPT_Usuario ON PPT_Cuentas.admin=PPT_Usuario.ID WHERE PPT_Cuentas.ID=?");
		$query->bind_param("i",$cuenta);
		if ($query->execute()) {
			$result=$query->get_result();
			$query->close();
			if ($row=$result->fetch_assoc()){
				return $row;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	if (isset($_POST['submit']) && $_POST['submit']=="cuenta") {
		$query=$con->prepare("INSERT INTO PPT_Cuentas ( nombre, usuario, admin, paga, interes1, interes2, pct) VALUES (?,?,?,?,?,?,?)");
		$query->bind_param("siiiiii",$_POST['name'],$_POST['depen'],$_SESSION['user'],$_POST['paga'],$_POST['interes1'],$_POST['interes2'],$_POST['hucha']);
		if ($query->execute()) {
			$cuenta=$query->insert_id;
			$concept="Balance Inicial";
			$date=date("Y-m-d");
			$query->close();
			$query=$con->prepare("INSERT INTO PPT_Cargos ( fecha, amount, ahorro, concept, cuenta, gestion) VALUES (?,?,?,?,?,?)");
			$x=0;
			$query->bind_param("sddsii",$date,$_POST['balance'],$_POST['ahorro'], $concept,$cuenta,$x);
			if ($query->execute()) {
				$query->close();
        		$_SESSION['cuenta']=$cuenta;
        		echo "<script>
				window.location.href='cuentaspage.php';
				</script>";
			} else {
				$query->close();
				echo "ERROR INGRESO";
			}
        	
    	} else {
        	$query->close();
        	echo "ERROR CREAR";
    	}
    	return;
    }

    if (isset($_POST['submit']) && $_POST['submit']=="nuevoCargo") {

    	$info = infoCuenta($con,$_POST['cuenta']);

    	if (!$info) {
    		echo "ERROR";
    		return;
    	}

    	$ahorro=0;
    	$cargo=$_POST['amount'];
    	if ($_POST['tipo']) {
    		$cargo=$cargo*(-1);
    	} else {
    		$ahorro=round($cargo*$info['pct']/100,2);
    	}

    	if ($_POST['id']=="") {
    		$query=$con->prepare("INSERT INTO PPT_Cargos ( fecha, amount, ahorro, concept, cuenta, gestion) VALUES (?,?,?,?,?,?)");
			$query->bind_param("sddsii",$_POST['fecha'],$cargo,$ahorro, $_POST['concept'],$_POST['cuenta'],$_SESSION['user']);
    	} else {
    		$query=$con->prepare("UPDATE PPT_Cargos SET fecha=?, amount=?, ahorro=?, concept=? WHERE ID=?");
			$query->bind_param("sddsi",$_POST['fecha'],$cargo,$ahorro, $_POST['concept'],$_POST['id']);
    	}

    	if ($query->execute()) {
				$query->close();
        		echo "<script>
				window.location.href='cuentaspage.php';
				</script>";
		} else {
				$query->close();
				echo "ERROR INGRESO";
		}
    }

    if (isset($_POST['submit']) && $_POST['submit']=="deleteCargo") {

    	$info = infoCuenta($con,$_POST['cuenta']);

    	if (!$info || $_POST['id']=="") {
    		echo "ERROR";
    		return;
    	}

    	$query=$con->prepare("DELETE FROM PPT_Cargos WHERE ID=?");
		$query->bind_param("i",$_POST['id']);

		if ($query->execute()) {
				$query->close();
        		echo "<script>
				window.location.href='cuentaspage.php';
				</script>";
		} else {
				$query->close();
				echo "ERROR ELIMINAR";
		}

    }

    if (isset($_POST['submit']) && $_POST['submit']=="deleteCuenta") {

    	$query=$con->prepare("DELETE FROM PPT_Cuentas WHERE ID=?");
		$query->bind_param("i",$_POST['id']);

		if ($query->execute()) {
				$query->close();
        		echo "<script>
				window.location.href='adminpage.php';
				</script>";
		} else {
				$query->close();
				echo "ERROR ELIMINAR";
		}

    }

    if (isset($_POST['submit']) && $_POST['submit']=="saveCuenta") {

    		$query=$con->prepare("UPDATE PPT_Cuentas SET nombre=?, paga=?, pct=?, interes1=?, interes2=? WHERE ID=?");
			$query->bind_param("sddddi",$_POST['name'],$_POST['paga'],$_POST['hucha'], $_POST['interes1'],$_POST['interes2'],$_POST['id']);

    	if ($query->execute()) {
				$query->close();
        		echo "<script>
				window.location.href='cuentaspage.php';
				</script>";
		} else {
				$query->close();
				echo "ERROR INGRESO";
		}
    }
?>