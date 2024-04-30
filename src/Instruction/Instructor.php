<?php
namespace Emmy\App\Instruction;
class Instructor
{
    public static function craftFigureFromAbbreviation(string $abbreviation):int|bool
    {
        return match ($abbreviation) {
			'K' => 1000,
			'M' => 1000000,
			'B' => 1000000000,
			'T' => 1000000000000,
			default => false,
		};
    }

    public static function getMagnitude(int $unit):string|bool
    {
        return match ($unit) {
			1000 => "thousand",
			1000000 => "million",
			1000000000 => "billion",
			1000000000000 => "trillion",
			default => false,
		};
    }

    public static function detectMagnitudeFromLengthOfNumber(int $lengthOfNumber):string|bool
    {
        return match ($lengthOfNumber) {
			4 => "thousand",
			5 => "thousand",
			6 => "thousand",
			7 => "million",
			8 => "million",
			9 => "million",
			10 => "billion",
			11 => "billion",
			12 => "billion",
			13 => "trillion",
			14 => "trillion",
			15 => "trillion",
			default => false,
		};
    }

	public static function getMagnitudeAbbreviation(string $magnitude): string|bool
	{
		return match ($magnitude) {
			'thousand' => 'K',
			'million' => 'M',
			'billion' => 'B',
			'trillion' => 'T',
			default => false,
		};
	}
}