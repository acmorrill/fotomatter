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
 *
 * Response for the get transaction detail class.
 * see: @link https://www.paypal.com/en_US/ebook/PP_NVPAPI_DeveloperGuide/Appx_fieldreference.html#2052755 Table A.27 GetTransactionDetails Request Fields
 */

include_once('class.RequestNVP.php') ;

class GetTransactionDetailsRequestNVP extends RequestNVP
{
	/**
	 * @desc Constructor
	 * @param string $theTransactionId [optional] The transaction for which details are to be returned.
	 */

    function GetTransactionDetailsRequestNVP(
        $theTransactionId = NULL)
    {
        $xxx = array('TRANSACTIONID' => $theTransactionId) ;

        $this->RequestNVP(
            'GetTransactionDetails',
            $xxx) ;
    }

	/**
	 * @desc Set the TRANSACTIONID parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setTRANSACTIONID(
        $theTransactionId)
    {
        $this->set('TRANSACTIONID', $theTransactionId) ;
    }
} ;
?>
