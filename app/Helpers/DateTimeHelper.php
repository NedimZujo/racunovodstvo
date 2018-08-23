<?php


namespace App\Helpers;

class DateTimeHelper
{
	public static function getLocalMonthName ($mjesec) {
		$mjeseci=[
			1 => "Januar",
			2 => "Februar",
			3 => "Mart",
			4 => "April",
			5 => "Maj",
			6 => "Juni",
			7 => "Juli",
			8 => "Avgust",
			9 => "Septembar",
			10 => "Oktobar",
			11 => "Novembar",
			12 => "Decembar"
		];

		return $mjeseci[$mjesec];
	}
}