<?php
/**
	These methods are used for converting a decimal such as .333 to a fraction (1/3)
*/
class Fraction {
	var $whole = 0;
	var $fraction = 0;
	/* *
		Function that drives the conversion of a decimal to a fraction
		@param $value decimal to convert
		@param $period the character that represents a comma (could be ',' in some countries)
	*/
	function Fraction($value) {
		global $LangUI;
		if (strstr($value, "."))
		{
			// Now convert the decimal part to a fraction
			list($integer_part,$decimal_part) = explode(".", $value, 2);
			$decimal_part = "." . $decimal_part;
			$loop=0;
			$result=array();
			$this->decimalToFraction($decimal_part,$loop,$result);
			if ($integer_part == '0') $integer_part="";
			// Simplify the fraction
			$this->simplifyFraction($result);
			$this->whole = $integer_part;
		} else {
			// It is a whole number.
			$this->whole = $value;
		}
	}
	
	/**
		Gives a string representation of the fraction, a New fraction needs to be created first)
	*/
	function toString() {
		if ($this->fraction != "") return ($this->whole . ' ' . $this->fraction);
		else return ($this->whole);
	}
	
	/**
		Converts a decimal to a fraction
		@param $decimal Decimal to convert
		@param $loop counter to keep track of state
		@param $result pointer to the resulting fraction
	*/
	function decimalToFraction($decimal,$loop,&$result) {
		$a = (1.0/(float)$decimal);
		$b = ( $a - floor($a) );
		$loop++;
		if ($b > .01 && $loop <= 5) $this->decimalToFraction($b,$loop,$result);
		$result[$loop] = floor($a);
	}

	/**
		Simplifies a fraction
		@param $fraction fraction to convert
		@param $loop keeps track of state
		@param $top the top of the fraction
		@param $bottom the bottom of the fraction
	*/
	function simplifyFraction($fraction) {
		$loop = count($fraction);
		$top = 1;
		$bottom = $fraction[$loop];
		while ($loop > 0) {
			$next = 0;
			if (isset($fraction[$loop - 1]))
				$next = $fraction[$loop-1];
			$a = ($bottom * $next) + $top;
			$top = $bottom;
			$bottom = $a;
			$loop--;
		}
		$this->fraction = "$bottom/$top";
	}
	
	/**
		Converts a string that represents a fraction to a double, this function can be
		called statically.
		@param $str The string to convert
		@return A floating point number
	*/
	function strToFloat($str) {
		list($whole, $frac) = split(' ', $str);
		if (preg_match("/\//", $whole, $matches)) {
			$frac = $whole;
			$ret = 0;
		} else $ret = $whole;
		
		// Now deal with the fraction part
		if ($frac) {
			list($top,$bot) = split('\/', $frac);
			if ($top > 0 && $bot > 0) 
				$ret += ($top / $bot);
		}
		return $ret;
	}
}
?>