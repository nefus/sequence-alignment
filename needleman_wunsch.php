<?php


$x = isset($argv[1]) ? $argv[1] : 'MAEHTE';
$y = isset($argv[2]) ? $argv[2] : 'AEHREN';

class NeedlemanWunsch {

	const UP        = 'u';
	const LEFT      = 'l';
	const DIAGONAL  = 'd';
	const INSERT    = '_';

	var $gapPenalty =  -1;
	var $match      =  3;
	var $mismatch   =  0;
	var $m = array(array(0));

	function __construct($x, $y){
		$this->x = $this->makeArray('$'.$x);
		$this->y = $this->makeArray('$'.$y);

	}

	function equal($a, $b){
		return ($a == $b ? $this->match : $this->mismatch);
	}

	function constructMatrix(){
		$count_x = count($this->x);
		$count_y = count($this->y);

		$this->m = array(array(0));
		$this->d = array();
		$m = &$this->m;
		$d = &$this->d;
		$x = &$this->x;
		$y = &$this->y;

		$d[0][0] = 'd';
		for($i = 1; $i < $count_x; $i++){
			$d[$i][0] = 'u';
			$m[$i][0] = $m[$i -1][0] + $this->gapPenalty;

		}
		for($j = 1; $j < $count_y; $j++){
			$d[0][$j] = 'l';
			$m[0][$j] = $m[0][$j -1] + $this->gapPenalty;
		}

		for($i = 1; $i < $count_x; $i++){
			for($j = 1; $j < $count_y; $j++){
				$qDiag = $m[$i -1][$j -1] + $this->equal($x[$i], $y[$j]);
				$qLeft = $m[$i][$j -1]    + $this->gapPenalty;
				$qUp   = $m[$i -1][$j]    + $this->gapPenalty;

				if($qDiag >= $qUp){
					if($qDiag >= $qLeft){
						$m[$i][$j] = $qDiag;
						$d[$i][$j] = self::DIAGONAL;	
					} else {
						$m[$i][$j] = $qLeft;
						$d[$i][$j] = self::LEFT;
					}
				} elseif($qUp >= $qLeft) {
					$m[$i][$j] = $qUp;
					$d[$i][$j] = self::UP;
				
				} else {
					$m[$i][$j] = $qLeft;
					$d[$i][$j] = self::LEFT;
				}

			}
		}

		return $this->m;
	}

	function makeAlignment(){
		$x = array();
		$y = array();

		$i = count($this->x) -1;
		$j = count($this->y) -1;

		$m =& $this->m;

		while($i >= 0 && $j >= 0){
			$dir = $this->d[$i][$j];
				
			switch($dir){
				case self::UP:
					// insert into y
					array_unshift($y, self::INSERT);
					 $i--;
					break;

				case self::LEFT:
					// insert into x
					array_unshift($x, self::INSERT);
					$j--;
					break;

				case self::DIAGONAL:
					// check if equal
					array_unshift($x, $this->x[$i]);
					array_unshift($y, $this->y[$j]);
					$i--;
					$j--;
					break;
			}
		}
		return array('x' => $x, 'y' => $y);
	}
	
	function __toString(){
		$res = $d = '';
		for($i = 0; $i < count($this->m); $i++){
			$row = $this->m[$i];
			for($j = 0; $j < count($row); $j++){
				$res .= sprintf('%5d', $row[$j]);
				$d .= $this->d[$i][$j];
			}
			$d .= PHP_EOL;
			$res .= PHP_EOL;
		}
		return $res .PHP_EOL. $d;
	}

	function makeArray($s){
		// "abc" => array("a", "b", "c");
		if(is_string($s)){
			return array_slice(preg_split('//', $s), 1, -1);
		}
	}

}

$m = new NeedlemanWunsch($x, $y);
$m->constructMatrix();
print_r($m->makeAlignment());

echo $m;


//print_r($m->d);
