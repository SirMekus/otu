<?php

namespace Emmy\App\Assistant;
use Emmy\App\Instruction\Instructor;
use Emmy\App\Exceptions\OtuException;

set_exception_handler('otuPeopleException');

class Otu
{
	public static function convertToNumber(string $number): int|float
	{
		if (is_numeric($number)) {
			return $number;
		}
		preg_match_all('#[a-z]|[A-Z]+#', $number, $abbreviation);
		$abbreviation = count($abbreviation[0]) >= 1 ? $abbreviation[0][0] : 'k';
		$number = trim(str_replace($abbreviation, '', $number));
		$abbreviation = strtoupper($abbreviation);
		if (!is_numeric($number)) {
			throw new OtuException("A non-numeric figure was encountered.");
		}
		
		$payload = explode(".", $number);
		$main = $payload[0];
		$afterPath = "";
		$length = 0;
		$strLength = 0;
		//If a decimal part was supplied
		if (count($payload) > 1) {
			//we only care about the second part, and will discard the others (if any)
			$second = $payload[1];
			//The number of decimal places supplied
			$strLength = strlen($second);
			//We expect the max DP to be 3. For instance, 54.543.
			$length = abs(3 - $strLength);
			//If the number of decimal places is less than 3, we pad with zeros
			$padded = str_pad('', $length, '0');
			//Then we join the two parts. For instance, .55 becomes .550
			$afterPath = $second . $padded;
		}
		//Now we join the whole number with the 'decimal' part without the "." of course.
		$final = $main . $afterPath;
		//Now we determine the unit of the supplied figure
		$originalUnit = Instructor::craftFigureFromAbbreviation($abbreviation);
		// echo $originalUnit;exit;
	
		if(!$originalUnit){
			throw new OtuException("The magnitude '$abbreviation' is not supported.");
		}
		$unit = $length > 0 ? substr($originalUnit, 0, - ($length + $strLength)) : $originalUnit;
		if(empty($unit)){
			throw new OtuException("This figure cannot be properly formatted in '".Instructor::getMagnitude($originalUnit)."' magnitude");
		}
		$figure = $final * $unit;
		self::checkForConsistency($figure, $originalUnit);
		return $figure;
	}

	public static function checkForConsistency(int|float $number, int $unit): void
	{
		$magnitude = Instructor::getMagnitude($unit);
		if(empty($magnitude)){
			throw new OtuException("This figure is out of range of our capabilities for now");
		}
		$length = strlen($unit);
		$lengthOfFigureValue = strlen($number);

		/** 
		* We make room for 'extra'. For instance, we know that 'thousand' has 4 figures in general, so 5000 is within 'thousand' magnitude.
		* However, 65000 (and also 650000) is also within 'thousand' magnitude as well, thus should also be marked as correct. Anything not within this range shall be * considered invalid
		**/
		if($lengthOfFigureValue < $length or $lengthOfFigureValue > $length+2){
			throw new OtuException("This is inconsistent for a number of $magnitude"." magnitude.");
		}
	}

	public static function format(int|float|string $number, int $decimal = 2): string
	{
		return is_numeric($number) ? 
		        number_format($number, $decimal):
					number_format(self::convertToNumber($number), $decimal);
	}

	public static function abbreviate(
		int|float $number, 
		int $decimal=1, 
		bool $round=true,
		bool $useUnit=true
		): string
	{
		$decimalPlaces = match($decimal){
			2=>10,
			default=>100
		};

		$lengthOfNumber = strlen((int)$number);
		if($lengthOfNumber <= 3){
			return $number;
		}

		$magnitude = Instructor::detectMagnitudeFromLengthOfNumber($lengthOfNumber);
		if(empty($magnitude)){
			throw new OtuException("This figure is out of range of our capabilities for now");
		}
		$formatedNumber = number_format($number);
		$exploded = explode(",",$formatedNumber);
		$wholeNumberPart = $exploded[0];
		if(count($exploded) > 1 and $exploded[1] > 0){
			$decimalPart = $round ? 
			              round($exploded[1]/$decimalPlaces) : 
						  (int)($exploded[1]/$decimalPlaces);
			$wholeNumberPart = $wholeNumberPart.".".rtrim($decimalPart, "0");
		}
		$unit = Instructor::getMagnitudeAbbreviation($magnitude);

		return $wholeNumberPart.($useUnit ? $unit : " ".$magnitude);
	}
}
