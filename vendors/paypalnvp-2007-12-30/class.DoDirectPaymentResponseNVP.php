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

include_once('class.ResponseNVP.php') ;

class DoDirectPaymentResponseNVP extends ResponseNVP
{
	/**
	 * @desc Constructor
	 */
	
    function DoDirectPaymentResponseNVP()
    {
        $this->ResponseNVP() ;
    }
} ;
?>