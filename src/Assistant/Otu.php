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
		
		if (count($payload) > 1) {
			$second = $payload[1];
			
			$strLength = strlen($second);
			
			$length = abs(3 - $strLength);
			
			$padded = str_pad('', $length, '0');
			
			$afterPath = $second . $padded;
		}
		$final = $main . $afterPath;
		
		$originalUnit = Instructor::craftFigureFromAbbreviation($abbreviation);
	
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
