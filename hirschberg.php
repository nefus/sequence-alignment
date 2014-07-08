<?php

function recAlign($x, $y, $weight = 'weight'){
	$lenX = strlen($x);
	$lenY = strlen($y);

	if($lenY == 0){
		$resX = $x;
		$resY = str_repeat('-', $lenX);
	} elseif ($lenX == 1) {
		$minWeight = 1.;
		for($i = 0, $k = 0; $i < $lenY; $i++){
			if ($minWeight > ($mW = $weight($x[1], $y[$i])){
				$minWeight = $mW;
				$k = $i;
			}
		}
		if ($weight($x[1], '-') + $weight('-', $y[$k]) < $weight($x[1], $y[$k])){
			$resX = $x[1] . str_repeat('-', $lenY);
			$resY = '-' . $y;
		} else {
			$resX = str_repeat('-', $k - 1) . $x[1] . str_repeat('-', $lenY - $k);
			$resY = $y;
		}
	} else { // $lenY > 0 && $lenX > 1 
		$i = (int) ($lenX / 2);
		
		$l1 = dist(substr($x, 0, $i), $y);
		$l2 = dist(substr($x, - ($lenX - $i), $y)
	}
}

function weight($a, $b){
	return ($a == $b) ? 0 : 1;
}
