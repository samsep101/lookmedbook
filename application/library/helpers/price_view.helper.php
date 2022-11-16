<?php
/**
 * Price
 *
 */
class PriceViewHelper {

	public static function number($number)
	{
		return number_format($number, 0, ",", " ");
	}
}