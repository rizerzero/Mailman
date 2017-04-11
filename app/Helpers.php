<?php namespace App;

class Helpers {
	/**
	 * Generate a hashed string that is URL save
	 * @param  string $string The string to hash
	 * @return string         Hashed string
	 */
	public static function urlSafeHashMake($string)
	{
		return strtr(base64_encode($string), '+/=', '-_,');
	}

	/**
	 * Decode a URL safe hash string
	 * @param  string $string The string to decipher
	 * @return string         The deciphered string
	 */
	public static function urlSafeHashDecode($string)
	{
		return base64_decode(strtr($string, '-_,', '+/='));
	}
}