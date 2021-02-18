<?php
class cuenta {

	private $id;
	private $nombre;
	private $balance;
	private $ahorro;
	private $paga;
	private $interes1;
	private $interes2;
	private $pct;
	private $admin;

	public function __construct($row) {
		$this->id=$row['cuenta'];
		$this->nombre=$row['nombre'];
		$this->balance=$row['balance'];
		$this->ahorro=$row['ahorro'];
		$this->paga=$row['paga'];
		$this->interes1=$row['interes1'];
		$this->interes2=$row['interes2'];
		$this->pct=$row['pct'];
		$this->admin=$row['admin'];
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function getPaga() {
		return $this->paga;
	}

	public function getID() {
		return $this->id;
	}

	public function getAdmin() {
		return $this->admin;
	}

	public function getBalance() {
		return $this->balance;
	}

	public function getAhorro() {
		return $this->ahorro;
	}

	public function getPCT() {
		return $this->pct;
	}

	public function getInteres1() {
		return $this->interes1;
	}

	public function getInteres2() {
		return $this->interes2;
	}

	public function calcAhorro($x) {
		return round($x*$this->pct/100,2);
	}

	public function calcInteres($balance,$ahorro) {
		if ($balance>=$ahorro) {
			return round($balance*$this->interes1/1200,2);
		} else {
			return round(($balance-$ahorro)*$this->interes2/1200,2);
		}
	}

}

function fetchCuenta($con,$id) {
		$query=$con->prepare("SELECT nombre, ROUND(SUM(amount),2) AS balance, ROUND(SUM(ahorro),2) AS ahorro, paga, interes1, interes2, pct, admin, cuenta FROM PPT_Cargos LEFT JOIN PPT_Cuentas ON cuenta=PPT_Cuentas.ID WHERE cuenta=?");
		$query->bind_param("i",$id);
		if ($query->execute()) {
			$result=$query->get_result();
			$query->close();
			if ($row=$result->fetch_assoc()){
				return new cuenta($row);
			} else {
				return null;
			}
		} else {
			return "error";
		}
	}

class cargo {
	private $id;
	private $fecha;
	private $amount;
	private $concept;
	private $gestor;
	private $ahorro;

	public function __construct($row) {
		$this->id=$row['ID'];
		$this->fecha=$row['fecha'];
		$this->amount=$row['amount'];
		$this->concept=$row['concept'];
		$this->gestor=$row['gestion'];
		$this->ahorro=$row['ahorro'];
	}

	public function getCargo(){
		if ($this->gestor==0) {
			$html = "<tr><td>".$this->fecha."</td><td>".$this->concept."</td>";
		} else {
			$html = "<tr class='clickable' data-id='".$this->id."'><td>".$this->fecha."</td><td>".$this->concept."</td>";
		}
		if ($this->amount>0) {
			$html .="<td></td><td class='green'>".$this->amount." €</td>";
		} else {
			$html .="<td class='red'>".abs($this->amount)." €</td><td></td>";
		}
		return $html;
	}

	public function getFecha() {
		return $this->fecha;
	}

	public function getAmount() {
		return $this->amount;
	}

	public function getAhorro() {
		return $this->ahorro;
	}
}

function fetchCargos($con,$id) {
		$query=$con->prepare("SELECT * FROM PPT_Cargos WHERE cuenta = ? ORDER BY fecha DESC");
		$query->bind_param("i",$id);

		$query->execute();
   		$result=$query->get_result();
    	$query->close();
    	while($row = $result->fetch_assoc()) {
        	$array[] = new cargo($row);
    	}
    	return $array;
	}

function checkNuevos($cuenta,$cargos) {
	$f=strtotime("+1 day",strtotime($cargos[0]->getFecha()));
	$balance=$cuenta->getBalance();
	$ahorro=$cuenta->getAhorro();

	while ($f<strtotime("today")) {
		if(date('N',$f) ==7 ) {
			$p=$cuenta->getPaga();
			$nuevos[]="('".date("Y-m-d",$f)."',".$p.",".$cuenta->calcAhorro($p).",'Paga Semanal',".$cuenta->getID().",0) ,";
			$balance += $p;
			$ahorro += $cuenta->calcAhorro($p);
		} 
		if (date('j',$f)==1) {
			$p=$cuenta->calcInteres($balance,$ahorro);
			$a=0;
			if ($p>0) {
				$a=$cuenta->calcAhorro($p);
			}
			$nuevos[]="('".date("Y-m-d",$f)."',".$p.",".$a.",'Interés',".$cuenta->getID().",0) ,";
		}
		$f=strtotime("+1 day",$f);
	}
	return $nuevos;
}

function insertNuevos($con, $nuevos) {
	$sql = "INSERT INTO PPT_Cargos (fecha,amount,ahorro,concept,cuenta,gestion) VALUES ";
			
	foreach ($nuevos as $nuevo) {
		$sql .= $nuevo;
	}
	$sql = substr($sql,0,-2);
		
	$query = $con->prepare($sql);
	if ($query->execute()) {
		return 1;
	} else {
		return 0;
	}
}

function fetchBalanceRow($a,$b) {
	$html="<td>".$a." €</td>";
	if ($b>=$a) {
		$html .="<td>".$b." €</td>";
	} else {
		$html .="<td class='red'>".$b." €</td>";
	}
	$html .="</tr>";
	return $html;
}
?>