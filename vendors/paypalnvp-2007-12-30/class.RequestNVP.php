<?php

/*
 *	$Author: dickmunroe $
 *	$Date: 2007/12/30 12:12:34 $
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
 * The base class for all requests sent to the PayPal NVP interface.
 */

class RequestNVP
{
	/**
	 * @var array Hash containing the parameters to be passed to paypal.
	 * @access private
	 */

    var $mParameters ;

	/**
	 * @desc Constructor
	 * @param string $theMethod the method being executed by paypal.
	 * @param array $theParameters [optional] Hash containing the parameters to be passed to paypal.
	 * @access public
	 */

    function RequestNVP(
        $theMethod,
        $theParameters = NULL)
    {
        $this->mParameters = array('METHOD' => $theMethod) ;

        if ($theParameters !== NULL)
        {
            $this->mParameters = array_merge($this->mParameters, $theParameters) ;
        }
    }

	/**
	 * @desc returns the array of parameters defined for this request.
	 * @return array the parameters defined for this request.
	 * @access protected
	 */

    function &getParameters()
    {
        return $this->mParameters ;
    }

	/**
	 * @desc returns an array of all required parameters for this request.  This should be
	 * overridden for each request object if validation is to occur.
	 * @return mixed array containing the name of each required parameter.
	 * @access protected
	 */

	function &getRequiredParameters()
	{
		$xxx = array() ;
		return $xxx ;
	}

	/**
	 * @desc Set the value of a parameter.
	 * @param string $theParameter the name of the parameter
	 * @param string $theValue the value of the parameter
	 * @return void
	 * @access public
	 */

    function set(
        $theParameter,
        $theValue)
    {
        $this->mParameters[$theParameter] = $theValue ;
    }

	/**
	 * @desc Check for all required parameters in request.
	 * @param boolean $theErrorFlag True if an error is triggered when a parameter is missing.
	 * @return boolean True if all parameters are present.
	 * @access public
	 */

	function validate(
		$theErrorFlag = FALSE)
	{
		$requiredParameters = &$this->getRequiredParameters() ;
		$parameters = &$this->getParameters() ;

		$return = TRUE ;

		foreach ($requiredParameters as $aParameter)
		{
			$xxx = array_key_exists($aParameter, $parameters) ;

			$return = ($return && $xxx) ;

			if (! $xxx)
			{
				if ($theErrorFlag)
				{
					trigger_error(
						sprintf('Missing parameter: %s', $aParameter),
						E_USER_ERROR) ;
				}
				else
				{
					return $return ;
				}
			}
		}

		return $return ;
	}
} ;

?>
