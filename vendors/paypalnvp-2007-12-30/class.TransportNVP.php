<?php

/*
 *	$Author: dickmunroe $
 *	$Date: 2007/12/30 12:13:45 $
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
 */

include_once('curl/class.curl.php') ;
include_once('class.StatusNVP.php') ;

class TransportNVP
{
    var $mUser ;
    var $mPwd ;
    var $mVersion = "3.2" ;
    var $mSignature ;
    var $mSubject ;
    var $mURL ;

    var $mCurl ;
    var $mResponseHeader ;
    var $mResponse ;
    var $mStatus ;

    function TransportNVP(
        $theUser,
        $thePwd,
        $theSignature = NULL,
        $theSubject = NULL,
        $theURL = "https://api-3t.paypal.com/nvp")
    {
        $this->mUser = $theUser ;
        $this->mPwd = $thePwd ;
        $this->mSignature = $theSignature ;
        $this->mSubject = $theSubject ;
        $this->mURL = $theURL ;

        $this->mCurl =& new curl() ;
        $this->mResponse = array() ;
        $this->mStatus = NULL ;
    }

    function doRequest(
        $theRequestObject,
        $theResponseObject = NULL)
    {
        unset($this->mResponse) ;

        $this->mCurl->setopt(CURLOPT_URL, $this->mURL) ;
        $this->mCurl->setopt(CURLOPT_FOLLOWLOCATION, true) ;
        $this->mCurl->setopt(CURLOPT_POST, true) ;

        $xxx =
            array(
                'USER' => $this->mUser,
                'PWD' => $this->mPwd,
                'VERSION' => $this->mVersion) ;

        if ($this->mSignature !== NULL)
        {
            $xxx['SIGNATURE'] = $this->mSignature ;
        }

        if ($this->mSubject !== NULL)
        {
            $xxx['SUBJECT'] = $this->mSubject ;
        }

        $post = $this->mCurl->asPostString($xxx) . '&' . $this->mCurl->asPostString($theRequestObject->getParameters()) ;

        $this->mCurl->setopt(CURLOPT_POSTFIELDS, $post) ;

        $this->mResponse = $this->mCurl->fromPostString($this->mCurl->exec()) ;

        unset($this->m_status) ;

        $this->mStatus =& new StatusNVP($this->mResponse) ;

        $this->_getResponseHeader() ;

        if ($theResponseObject !== NULL)
        {
            $theResponseObject->setResponse($this->mResponse) ;
            unset($this->mResponse) ;
        }
    }

    function &getResponse()
    {
        return $this->mResponse ;
    }

    function &getResponseHeader()
    {
        return $this->mResponseHeader ;
    }

    function _getResponseHeader()
    {
        $fields =
            array(
                'TIMESTAMP',
                'CORRELATIONID',
                'VERSION',
                'BUILD') ;

        foreach ($fields as $aField)
        {
            if (array_key_exists($aField, $this->mResponse))
            {
                $this->mResponseHeader[$aField] = $this->mResponse[$aField] ;
                unset($this->mResponse[$aField]) ;
            }
        }
    }

    function &getStatus()
    {
        return $this->mStatus ;
    }
}
?>
