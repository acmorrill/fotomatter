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
 * The response from a get transaction details.
 * see: @link https://www.paypal.com/en_US/ebook/PP_NVPAPI_DeveloperGuide/Appx_fieldreference.html#2052755 Table A.28 GetTransactionDetails Response Fields
 */

include_once('class.ResponseNVP.php') ;

class GetTransactionDetailsResponseNVP extends ResponseNVP
{
    /**
     * @var array $mItems All items associated with the response.  There
     * is a many to one relationship between items and responses.  By breaking
     * the items out separately this relationship can be maintained in a data
     * base.
     */

    var $mItems ;

	/**
	 * @desc Constructor
	 */

    function GetTransactionDetailsResponseNVP()
    {
        $this->ResponseNVP() ;
    }

	/**
	 * @desc get the array of items associated with the response.
	 * @return array Items.
	 */

    function &getItems()
    {
        return $this->mItems ;
    }

	/**
	 * @desc Parse the items out of the get transactions details.
	 * @param array $theResponse by reference the array of parameters returned by Paypal.
	 * @access protected
	 */
	
    function setResponse(
        &$theResponse)
    {
        $this->mItems = NULL ;
        $this->mItems = array() ;

        foreach (array_keys($theResponse) as $aKey)
        {
            if (preg_match('/^(L_.*?)(\d+)$/', $aKey, $matches) == 1)
            {
                $this->mItems[intval($matches[2])][$matches[1]] = $theResponse[$aKey] ;
                unset($theResponse[$aKey]) ;
            }
        }

        parent::setResponse($theResponse) ;
    }
} ;
?>
