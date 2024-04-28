<?php

namespace Emmy\Assistant;
use Emmy\Exceptions\OtuException;

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
		$originalUnit = match ($abbreviation) {
			'K' => 1000,
			'M' => 1000000,
			'B' => 1000000000,
			'T' => 1000000000000,
			default => false,
		};
		if(!$originalUnit){
			throw new OtuException("The magnitude '$abbreviation' is not supported.");
		}
		$unit = $length > 0 ? substr($originalUnit, 0, - ($length + $strLength)) : $originalUnit;
		if(empty($unit)){
			throw new OtuException("This figure cannot be properly formatted in '".self::getMagnitude($originalUnit)."' magnitude");
		}
		$figure = $final * $unit;
		self::checkForConsistency($figure, $originalUnit);
		return $figure;
	}

	public static function checkForConsistency($number, $unit)
	{
		$magnitude = self::getMagnitude($unit);
		$length = strlen($unit);
		$lengthOfFigureValue = strlen($number);

		/** 
		* We make room for 'extra'. For instance, we know that 'thousand' has 4 figures in general, so 5000 is within 'thousand' magnitude.
		* However, 65000 is also within 'thousand' magnitude as well, thus should also be marked as correct. Anything not within this range shall be * considered invalid
		**/
		if($lengthOfFigureValue < $length or $lengthOfFigureValue > $length+1){
			throw new OtuException("This is inconsistent for a number of $magnitude"." magnitude.");
		}
	}

	public static function getMagnitude(int $unit): string
	{
		return match ($unit) {
			1000 => "thousand",
			1000000 => "million",
			1000000000 => "billion",
			1000000000000 => "trillion",
			default => 'unknown',
		};
	}

	public static function format(int|float|string $number, int $decimal = 2): string
	{
		return is_numeric($number) ? 
		        number_format($number, $decimal):
					number_format(self::convertToNumber($number), $decimal);
	}
}
