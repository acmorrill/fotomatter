<?php

/*
 *	$Author: dickmunroe $
 *	$Date: 2007/12/30 13:37:34 $
 *
 * Edit History:
 *
 *  Dick Munroe (munroe@csworks.com) 01-Jan-2008
 *      Initial Version Created
 */

/**
 * @author Dick Munroe <munroe@csworks.com>
 * @copyright copyright @ Dick Munroe, 2008 All rights reserved.
 * @license http://www.csworks.com/publications/ModifiedNetBSD.html
 * @version 1.0.0
 * @package paypalNVP
 *
 * Utility interfaces for generating HTML of miscellaneous kinds.  Primarily
 * static functions.
 */

class ToHTML
{
	function ToHTML()
	{}

	function &option(
		&$theKey,
		&$theValue,
		$theFlag)
	{
		$xxx =
			sprintf(
				'<option value="%s"%s>%s</option>',
				$theValue,
				($theFlag ? ' selected="selected"' : ''),
				$theKey) ;
		return $xxx ;
	}

	function &optionsKeyValue(
		&$theOptions,
		$theDefaultOption = NULL)
	{
		$options = '' ;

		foreach ($theOptions as $aKey => $aValue)
		{
			$options .=
				ToHTML::option(
					$aKey,
					$aValue,
					(($theDefaultOption !== NULL) && ($aKey == $theDefaultOption))) ;
		}

		return $options ;
	}

	function &optionsValueKey(
		&$theOptions,
		$theDefaultOption = NULL)
	{
		$options = '' ;

		foreach ($theOptions as $aKey => $aValue)
		{
			$options .=
				ToHTML::option(
					$aValue,
					$aKey,
					(($theDefaultOption !== NULL) && ($aValue == $theDefaultOption))) ;
		}

		return $options ;
	}

	function &optionsValue(
		&$theOptions,
		$theDefaultOption = NULL)
	{
		$options = '' ;

		foreach ($theOptions as $aValue)
		{
			$options .=
				ToHTML::option(
					$aValue,
					$aValue,
					(($theDefaultOption !== NULL) && ($aValue == $theDefaultOption))) ;
		}

		return $options ;
	}
}
?>
