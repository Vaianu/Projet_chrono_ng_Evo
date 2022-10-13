<?php
	class Temps{
		private $_heures;
		private $_minutes;
		private $_secondes;


		public function __construct($h=0,$m=0,$s=0){
			$this->_heures = $h;
			$this->_minutes = $m;
			$this->_secondes = $s;
		}

		public function calculTemps(Temps $arrivee, Temps $depart){
			$a = $arrivee->_heures * 3600;
			$a += $arrivee->_minutes * 60;
			$a += $arrivee->_secondes;
			
			$d = $depart->_heures * 3600;
			$d += $depart->_minutes * 60;
			$d += $depart->_secondes;
			
			$total = $a - $d;
			$this->_secondes = $total;
	
			$this->_minutes += $this->_secondes / 60;
			$this->_secondes %= 60;
			$this->_heures += $this->_minutes / 60;
			$this->_minutes %= 60;
		}

		/*public function getTemps()
		{
			if (intval($this->_heures) == 0)
				return intval($this->_minutes).'min'.intval($this->_secondes).'s';
			else
				return intval($this->_heures).'h'.intval($this->_minutes).'min'.intval($this->_secondes).'s';
		}*/
		
		public function getTempsBdd()
		{
			return intval($this->_heures).':'.intval($this->_minutes).':'.intval($this->_secondes);
		}
	}

?>