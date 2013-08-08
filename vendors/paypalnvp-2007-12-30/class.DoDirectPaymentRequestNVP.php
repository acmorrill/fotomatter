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
 * Issue a direct payment request to paypal.
 */

include_once('class.RequestNVP.php') ;

class DoDirectPaymentRequestNVP extends RequestNVP
{
	/**
	 * @desc Constructor
	 * @param array $theParameters [optional] The parameters to be passed to Paypal.
	 */

    function DoDirectPaymentRequestNVP(
        $theParameters = NULL)
    {
        $this->RequestNVP(
            'DoDirectPayment',
            $theParameters) ;
    }

	/**
	 * @desc Required parameters for each Do Direct Payment request.
	 * See: @link https://www.paypal.com/en_US/ebook/PP_NVPAPI_DeveloperGuide/directpayment.html#1412302 Charging a Credit Card Using DoDirectPayment
	 * @return array Required parameters.
	 */

	function &getRequiredParameters()
	{
		$xxx =
			array(
				'PAYMENTACTION',
				'CREDITCARDTYPE',
				'ACCT',
				'EXPDATE',
				'CVV2',
				'IPADDRESS',
				'FIRSTNAME',
				'LASTNAME') ;

		return $xxx ;
	}

	/**
	 * @desc Set the ACCT parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setACCT(
        $theValue)
    {
        $this->set('ACCT', $theValue) ;
    }

	/**
	 * @desc Set the AMT parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setAMT(
        $theValue)
    {
        $this->set('AMT', $theValue) ;
    }

	/**
	 * @desc Set the BUTTONSOURCE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setBUTTONSOURCE(
        $theValue)
    {
        $this->set('BUTTONSOURCE', $theValue) ;
    }

	/**
	 * @desc Set the CCV2 parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setCCV2(
        $theValue)
    {
        $this->set('CCV2', $theValue) ;
    }

	/**
	 * @desc Set the CITY parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setCITY(
        $theValue)
    {
        $this->set('CITY', $theValue) ;
    }

	/**
	 * @desc Set the COUNTRYCODE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setCOUNTRYCODE(
        $theValue)
    {
        $this->set('COUNTRYCODE', $theValue) ;
    }

	/**
	 * @desc Set the CREDITCARDTYPE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setCREDITCARDTYPE(
        $theValue)
    {
        $this->set('CREDITCARDTYPE', $theValue) ;
    }

	/**
	 * @desc Set the CURRENCYCODE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setCURRENCYCODE(
        $theValue)
    {
        $this->set('CURRENCYCODE', $theValue) ;
    }

	/**
	 * @desc Set the CUSTOM parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setCUSTOM(
        $theValue)
    {
        $this->set('CUSTOM', $theValue) ;
    }

	/**
	 * @desc Set the DESC parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setDESC(
        $theValue)
    {
        $this->set('DESC', $theValue) ;
    }

	/**
	 * @desc Set the EMAIL parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setEMAIL(
        $theValue)
    {
        $this->set('EMAIL', $theValue) ;
    }

	/**
	 * @desc Set the EXPDATE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setEXPDATE(
        $theValue)
    {
        $this->set('EXPDATE', $theValue) ;
    }

	/**
	 * @desc Set the FIRSTNAME parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setFIRSTNAME(
        $theValue)
    {
        $this->set('FIRSTNAME', $theValue) ;
    }

	/**
	 * @desc Set the HANDLINGAMT parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setHANDLINGAMT(
        $theValue)
    {
        $this->set('HANDLINGAMT', $theValue) ;
    }

	/**
	 * @desc Set the INVNUM parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setINVNUM(
        $theValue)
    {
        $this->set('INVNUM', $theValue) ;
    }

	/**
	 * @desc Set the IPDADDRESS parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setIPADDRESS(
        $theValue)
    {
        $this->set('IPADDRESS', $theValue) ;
    }

	/**
	 * @desc Set the ISSUENUMBER parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setISSUENUMBER(
        $theValue)
    {
        $this->set('ISSUENUMBER', $theValue) ;
    }

	/**
	 * @desc Set the ITEMAMT parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setITEMAMT(
        $theValue)
    {
        $this->set('ITEMAMT', $theValue) ;
    }

    /**
     * @desc Set all the items in the transaction.
     * @param array $theItems by reference.
     * The item array is indexed starting at 0 and is a has of hashes.  The
     * hashes indices are L_NAME, L_NUMBER, L_QTY, L_TAXAMT.  They get turned
     * into L_NAME0, etc. inside the request.
     * @return void
     */

    function setItems(
        &$theItems)
    {
        $xxx = array_keys($theItems) ;
        sort($xxx) ;

        $count = 0 ;

        foreach ($xxx as $aKey)
        {
            foreach ($theItems[$aKey] as $field => $value)
            {
                $this->set(sprintf('%s%d', $field, $count), $value) ;
            }

            $count++ ;
        }
    }

	/**
	 * @desc Set the LASTNAME parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setLASTNAME(
        $theValue)
    {
        $this->set('LASTNAME', $theValue) ;
    }

	/**
	 * @desc Set the NOTIFYURL parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setNOTIFYURL(
        $theValue)
    {
        $this->set('NOTIFYURL', $theValue) ;
    }

	/**
	 * @desc Set the PAYMENTACTION parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setPAYMENTACTION(
        $theValue)
    {
        $this->set('PAYMENTACTION', $theValue) ;
    }

	/**
	 * @desc Set the SHIPPINGAMT parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPPINGAMT(
        $theValue)
    {
        $this->set('SHIPPINGAMT', $theValue) ;
    }

	/**
	 * @desc Set the SHIPTOCITY parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPTOCITY(
        $theValue)
    {
        $this->set('SHIPTOCITY', $theValue) ;
    }

	/**
	 * @desc Set the SHIPTOCOUNTRYCODE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPTOCOUNTRYCODE(
        $theValue)
    {
        $this->set('SHIPTOCOUNTRYCODE', $theValue) ;
    }

	/**
	 * @desc Set the SHIPTONAME parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPTONAME(
        $theValue)
    {
        $this->set('SHIPTONAME', $theValue) ;
    }

	/**
	 * @desc Set the SHIPTOPHONENUM parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPTOPHONENUM(
        $theValue)
    {
        $this->set('SHIPTOPHONENUM', $theValue) ;
    }

	/**
	 * @desc Set the SHIPTOSTATE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPTOSTATE(
        $theValue)
    {
        $this->set('SHIPTOSTATE', $theValue) ;
    }

	/**
	 * @desc Set the SHIPTOSTREET parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPTOSTREET(
        $theValue)
    {
        $this->set('SHIPTOSTREET', $theValue) ;
    }

	/**
	 * @desc Set the SHIPTOSTREET2 parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPTOSTREET2(
        $theValue)
    {
        $this->set('SHIPTOSTREET2', $theValue) ;
    }

	/**
	 * @desc Set the SHIPTOZIP parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSHIPTOZIP(
        $theValue)
    {
        $this->set('SHIPTOZIP', $theValue) ;
    }

	/**
	 * @desc Set the STARTDATE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSTARTDATE(
        $theValue)
    {
        $this->set('STARTDATE', $theValue) ;
    }

	/**
	 * @desc Set the STATE parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSTATE(
        $theValue)
    {
        $this->set('STATE', $theValue) ;
    }

	/**
	 * @desc Set the STREET parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSTREET(
        $theValue)
    {
        $this->set('STREET', $theValue) ;
    }

	/**
	 * @desc Set the STREET2 parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setSTREET2(
        $theValue)
    {
        $this->set('STREET2', $theValue) ;
    }

	/**
	 * @desc Set the TAXAMT parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setTAXAMT(
        $theValue)
    {
        $this->set('TAXAMT', $theValue) ;
    }

	/**
	 * @desc Set the ZIP parameter
	 * @param string $theValue the value of the parameter.
	 */

    function setZIP(
        $theValue)
    {
        $this->set('ZIP', $theValue) ;
    }
} ;
?>
