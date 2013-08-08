<?php

/*
 * Copyright @ 2007, Economic Security Planning, Inc., All rights reserved.
 *
 * $Author: dickmunroe $
 * $Date: 2007/12/30 13:37:34 $
 *
 * Edit History:
 *
 *  Dick Munroe (munroe@csworks.com) 22-Dec-2007
 *      Initial Version Created.
 */

/**
 * @author Dick Munroe (munroe@csworks.com)
 * @copyright copyright @ 2004-2007, Dick Munroe, released under the GPL.
 * @license http://www.csworks.com/publications/ModifiedNetBSD.html
 * @version 1.0.0
 * @package PaypalNVP
 */

class ResponseNVP
{
	/**
	 * @var array the responses from Paypal.
	 */

    var $mParameters ;

	/**
	 * @desc Constructor
	 */

    function ResponseNVP()
    {
        $this->mParameters = NULL ;
    }

	/**
	 * @desc return the value of a parameter or NULL if it doesn't exist in the response.
	 * @param string $theParameter the name of the parameter.
	 * @return mixed the value of the requested parameter.
	 */

    function get(
        $theParameter)
    {
        if (array_key_exists($theParameter, $this->mParameters))
        {
            return $this->mParameters[$theParameter] ;
        }
        else
        {
            return NULL ;
        }
    }

	/**
	 * @desc get the array of response parameters.
	 * @return mixed array of response parameters.
	 */

    function &getResponse()
    {
        return $this->mParameters ;
    }

	/**
	 * @desc set the array of response parameters.
	 * @param array $theResponse the hash of response parameters.
	 * @access protected
	 */

    function setResponse(
        $theResponse)
    {
        $this->mParameters = $theResponse ;
    }
}
?>
