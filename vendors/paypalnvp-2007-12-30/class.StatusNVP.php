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

class StatusNVP
{
	/**
	 * @var array stack of status values returned by Paypal.
	 */

    var $mStatus ;

	/**
	 * @desc Constructor
	 * @param array $theResponse the parameters returned by Paypal from which status is to be parsed.
	 */

    function StatusNVP(
        &$theResponse)
    {
        $this->mStatus = array() ;

        if (array_key_exists('ACK', $theResponse))
        {
            $this->mStatus['ACK'] = $theResponse['ACK'] ;
            $theResponse['ACK'] = NULL ;
            unset($theResponse['ACK']) ;
        }

        if (!$this->isSuccess())
        {
            $this->getErrorData($theResponse) ;
        }
    }

	/**
	 * @desc Parse error data into the status object.
	 * @param array $theResponse the response data from Paypal.
	 * @access private
	 */

    function getErrorData(&$theResponse)
    {
        foreach($theResponse as $aKey => $aDatum)
        {
            if ($this->isErrorData($aKey))
            {
                preg_match('/(.*?)(\d+)/', $aKey, $matches) ;

                $this->mStatus[intval($matches[2])][$matches[1]] = $aDatum ;
                $theResponse[$aKey] = NULL ;
                unset($theResponse[$aKey]) ;
            }
        }
    }

	/**
	 * @desc is this key part of an error response
	 * @param string $theKey the key to be tested.
	 * @return true if the key is part of an error response.
	 * @access private
	 */

    function isErrorData(
        $theKey)
    {
        $keys =
            array(
                '/L_ERRORCODE\d+/',
                '/L_SHORTMESSAGE\d+/',
                '/L_LONGMESSAGE\d+/',
                '/L_SEVERITYCODE\d+/') ;

        foreach ($keys as $aKey)
        {
            if (preg_match($aKey, $theKey) == 1)
            {
                return true ;
            }
        }

        return false ;
    }

	/**
	 * @desc is this response successfull
	 * @return true if the response from Paypal was successful.
	 * @access public
	 */

    function isSuccess()
    {
        return $this->mStatus['ACK'] == 'Success' ;
    }

	/**
	 * @desc return an array of the long message information.
	 * @param boolean $theHTMLFlag true if the array is to be formatted in HTML.
	 * @return array
	 * @access public
	 */

    function &getLongMessage(
        $theHTMLFlag = FALSE)
    {
        $xxx = array() ;

        for ($i = 0; ; $i++)
        {
            if (array_key_exists($i, $this->mStatus))
            {
                $xxx[] = $this->mStatus[$i]['L_LONGMESSAGE'] ;
            }
            else
            {
                break;
            }
        }

        if (! $theHTMLFlag)
        {
            return $xxx ;
        }

        $xxx = '<div class="longMessage">' . implode('</div><div class="longMessage">', $xxx) . '</div>' ;
        return $xxx ;
    }
} ;
?>
