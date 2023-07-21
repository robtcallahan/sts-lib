<?php
/*******************************************************************************
 *
 * $Id: Obfuscation.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Util/Obfuscation.php $
 *
 *******************************************************************************
 */

// ******************************************************************************
// A reversible password encryption routine by:
// Copyright 2003-2009 by A J Marston <http://www.tonymarston.net>
// Distributed under the GNU General Public Licence
// Modification: May 2007, M. Kolar <http://mkolar.org>:
// No need for repeating the first character of scramble strings at the end;
// instead using the exact inverse function transforming $num2 to $num1.
// Modification: Jan 2009, A J Marston <http://www.tonymarston.net>:
// Use mb_substr() if it is available (for multibyte characters).
// ******************************************************************************

namespace STS\Util;

class Obfuscation
{
	private $scramble1; // 1st string of ASCII characters
	private $scramble2; // 2nd string of ASCII characters
	private $errors; // array of error messages
	private $adj; // 1st adjustment value (optional)
	private $mod; // 2nd adjustment value (optional)
	private $yek; // nothing you need to know about


	// ****************************************************************************
	// class constructor
	// ****************************************************************************
	/*
	This function is automatically called whenever an object is created from this
	class. I use this to set up the contents of the two strings which I call
	scramble1 and scramble2. Each of these strings contains exactly the same
	characters, but in a different order. In this case I am using all 95
	printable characters from the ASCII character set EXCEPT single quote, double
	and backslash as these have special meaning in PHP which can sometimes lead
	to problems. I also use the class constructor to define default values for
	adj and mod.
	*/
	public function __construct()
	{
		/*
		Each of these two strings must contain the same characters, but in a different order. 
		Use only printable characters from the ASCII table. 
		Do not use single quote, double quote or backslash as these have special meanings in PHP.
		Each character can only appear once in each string. 
		*/
		$this->scramble1 = '! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
		$this->scramble2 = 'f^jAE]okIOzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!#$~(;Lt-R}Ma,NvW+Ynb*0X';
		$this->yek       = "PscbYShVjSAABqyK";

		if (strlen($this->scramble1) <> strlen($this->scramble2)) {
			trigger_error('** SCRAMBLE1 is not same length as SCRAMBLE2 **', E_USER_ERROR);
		} // if 

		$this->adj = 1.75; // this value is added to the rolling fudgefactors
		$this->mod = 3; // if divisible by this the adjustment is made negative

	} // constructor 

	// ****************************************************************************
	// The Encrypt function 
	// ****************************************************************************
	/*	
	This function will take a plain text string and encrypt it using the supplied
	key. Notice that the string length defaults to zero.
	*/
	public function encrypt($source, $sourcelen = 0)
	{
		/*
		The first step is to convert the key into an array of numbers which I call
		$fudgefactor. This array is used to 'fudge' or 'adjust' the index number
		obtained from the first character string before it is used on the second
		character string. The contents of the _convertkey function can be viewed here.
		*/
		$this->errors = array();

		$fudgefactor = $this->_convertKey($this->yek);
		if ($this->errors) {
			return null;
		}

		// This next piece of code checks that a source string has actually been supplied.

		if (empty($source)) {
			$this->errors[] = 'No value has been supplied for encryption';
			return null;
		} // if 

		// Next we pad the input string with spaces up the specified length.

		while (strlen($source) < $sourcelen) {
			$source .= ' ';
		} // while 

		// ere we are setting up a for loop to process each character from $source.

		$target  = null;
		$factor2 = 0;
		for ($i = 0; $i < strlen($source); $i++) {
			/*
			Here we extract the next character from $source and identify its position in
			$scramble1. Note that we cannot continue processing if the character cannot be
			found.
			*/
			$char1 = substr($source, $i, 1);
			$num1  = strpos($this->scramble1, $char1);
			if ($num1 === false) {
				$this->errors[] = "Source string contains an invalid character ($char1)";
				return null;
			} // if 

			/*
			Next we obtain the adjustment value from the $fudgefactor array and
			accumulate it in $factor1 along with the previous contents of
			$factor2. The contents of the _applyFudgeFactor function can be
			viewed here.
			*/
			$adj     = $this->_applyFudgeFactor($fudgefactor);
			$factor1 = $factor2 + $adj; // accumulate in $factor1

			/*
			Here we add $factor1 to the offset from the $scramble1 string
			($num1) to give us the offset into the $scramble2 string ($num2).
			Note that factor may contain decimal digits, so it has to be rounded
			in order to supply a whole number.
			*/
			$num2 = round($factor1) + $num1; // generate offset for $scramble2

			/*
			The value at this point may be a negative number or even a large
			positive number, so we have to check that it can actually point to a
			character in the $scramble2 string. The contents of the _checkRange
			function can be viewed here.
			*/
			$num2 = $this->_checkRange($num2); // check range

			/*
			As an added complication to confuse potential hackers we are also
			accumulating the value of $num2 and $factor1 in $factor2.
			*/
			$factor2 = $factor1 + $num2; // accumulate in $factor

			/*
			Here we extract a character from $scramble2 using the value in $num2
			and append it to the output string.
			*/
			$char2 = substr($this->scramble2, $num2, 1);
			$target .= $char2;
			// Finally we close the for loop and return the encrypted string.

		} // for 

		return $target;
	} // encrypt 

	// ****************************************************************************
	// The Decrypt function
	// ****************************************************************************
	/*
	This function will take an encrypted string and turn it into plain text
	using the supplied key. Note that this must be exactly the same key that was
	used to encrypt the string in the first place.
	*/
	public function decrypt($source)
	{
		/*
		The first step is to convert the key into an array of numbers which I
		call $fudgefactor. The contents of the _convertkey function can be
		viewed here.
		*/
		$this->errors = array();

		$fudgefactor = $this->_convertKey($this->yek);
		if ($this->errors) {
			return null;
		}

		// This next piece of code checks that a source string has actually been supplied.
		if (empty($source)) {
			$this->errors[] = 'No value has been supplied for decryption';
			return null;
		} // if 

		// Here we are setting up a for loop to process each character from $source.
		$target  = null;
		$factor2 = 0;
		for ($i = 0; $i < strlen($source); $i++) {
			/*
			Here we extract the next character from $source and identify its
			position in $scramble2. Note that we cannot continue processing if
			the character cannot be found.
			*/
			$char2 = substr($source, $i, 1);
			$num2  = strpos($this->scramble2, $char2);
			if ($num2 === false) {
				$this->errors[] = "Source string contains an invalid character ($char2)";
				return null;
			} // if

			/*
			Next we obtain the adjustment value from the $fudgefactor array and
			accumulate it in $factor1 along with the previous contents of
			$factor2. The contents of the _applyFudgeFactor function can be
			viewed here.
			*/
			$adj     = $this->_applyFudgeFactor($fudgefactor);
			$factor1 = $factor2 + $adj;

			/*
			Here we add $factor1 to the offset from the $scramble2 string
			($num2) to give us the offset into the $scramble1 string ($num1).
			Note that factor may contain decimal digits, so it has to be rounded
			in order to supply a whole number.
			*/
			$num1 = $num2 - round($factor1); // generate offset for $scramble1

			/*
			The value at this point may be a negative number or even a large
			positive number, so we have to check that it can actually point to a
			character in the $scramble1 string. The contents of the _checkRange
			function can be viewed here.
			*/
			$num1 = $this->_checkRange($num1); // check range

			/*
			As an added complication to confuse potential hackers we are also
			accumulating the value of $num2 and $factor1 in $factor2.
			*/
			$factor2 = $factor1 + $num2; // accumulate in $factor2

			// Here we extract a character from $scramble1 using the value in $num1 and append it to the output string.

			$char1 = substr($this->scramble1, $num1, 1);
			$target .= $char1;
			// Finally we close the for loop, return the decrypted string, and close the class.

		} // for 

		return rtrim($target);
	} // decrypt 

	// ****************************************************************************
	public function getAdjustment()
		// return the adjustment value
	{
		return $this->adj;
	} // setAdjustment

	// ****************************************************************************
	public function getModulus()
		// return the modulus value
	{
		return $this->mod;
	} // setModulus

	// ****************************************************************************
	public function setAdjustment($adj)
		// set the adjustment value
	{
		$this->adj = (float) $adj;
	} // setAdjustment

	// ****************************************************************************
	public function setModulus($mod)
		// set the modulus value
	{
		$this->mod = (int) abs($mod); // must be a positive whole number

	} // setModulus

	// ****************************************************************************
	// private methods
	// ****************************************************************************

	// ****************************************************************************
	// The ApplyFudgeFactor function
	// ****************************************************************************
	/*
	This function will return an adjustment value using the contents of the
	$fudgefactor array. Note that $fudgefactor is passed by reference so that it
	can be modified	*/
	private function _applyFudgeFactor(&$fudgefactor)
	{
		// Here we extract the first entry in the array and remove it from the array.
		$fudge = array_shift($fudgefactor);

		// Next we add in the optional $adj value and put the result back into the end of the array.
		$fudge         = $fudge + $this->adj;
		$fudgefactor[] = $fudge;

		// If a $modulus value has been supplied we use it and possibly reverse the sign on the output value.

		if (!empty($this->mod)) // if modifier has been supplied 
		{
			if ($fudge % $this->mod == 0) { // if it is divisible by modifier
				$fudge = $fudge * -1; // reverse then sign
			} // if 
		} // if 

		// There is no more 'fudging' left to do, so we can return the value to the calling process.

		return $fudge;
	} // _applyFudgeFactor 

	// ****************************************************************************
	// The CheckRange function	
	// ****************************************************************************
	/*
	This function checks that the value in $num can actually be used as a pointer to an entry in $scramble1.
	*/
	private function _checkRange($num)
	{
		// First we must round up to the nearest whole number.
		$num = round($num);

		// We use the length of the scramble string as the limit.
		$limit = strlen($this->scramble1);

		// If the value is too high we must reduce it.
		while ($num >= $limit) {
			$num = $num - $limit;
		} // while 

		// If the value is too low we must increase it.
		while ($num < 0) {
			$num = $num + $limit;
		} // while 

		// We can now return a valid pointer back to the calling process.
		return $num;
	} // _checkRange 

	// ****************************************************************************
	// The ConvertKey function
	// ****************************************************************************
	/*	
	This function converts the $yek string into an array of numbers. The first
	check is to ensure that a value has actually been supplied.
	*/
	private function _convertKey($yek)
	{
		if (empty($yek)) {
			$this->errors[] = 'No value has been supplied for the encryption key';
			return null;
		} // if 

		//The first entry in the array is the length of the $yek string.

		$array[] = strlen($yek);

		//Next we set up a for loop to examine every character in the $yek string.

		$tot = 0;
		for ($i = 0; $i < strlen($yek); $i++) {

			/* 
			Here we extract the next character from $yek and identify its
			position in $scramble1. Note that we cannot continue processing if
			the character cannot be found.
			*/
			$char = substr($yek, $i, 1);
			$num  = strpos($this->scramble1, $char);
			if ($num === false) {
				$this->errors[] = "Key contains an invalid character ($char)";
				return null;
			} // if 

			// He we append the number to the output array and accumulate the total for later.

			$array[] = $num;
			$tot     = $tot + $num;
			/*
			At the end of the for loop we add the accumulated total to the end
			of the array and return the array to the calling process.
			*/
		} // for 

		$array[] = $tot;

		return $array;
	} // _convertKey 

} // end encryption_class
