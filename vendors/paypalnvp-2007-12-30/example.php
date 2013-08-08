<?php

/*
 * Copyright @ 2007, Economic Security Planning, Inc., All rights reserved.
 *
 * $Author: dickmunroe $
 * $Date: 2007/12/30 13:47:30 $
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
 * An example collecting transactions and corresponsing details from Paypal
 * using the Paypal NVP framework.
 */

/**
 * @desc Generate a paypal timestamp.
 * @param integer $theTime Unix timestamp.
 * @return string Paypal Timestamp.  Format is: yyyy-mm-ddThh:mm:ssZ
 */

function paypalDate(
    $theTime)
{
    return date('Y-m-d', $theTime) .'T' . date('H:i:s', $theTime) . 'Z' ;
}

include_once('class.GetTransactionDetailsRequestNVP.php') ;
include_once('class.GetTransactionDetailsResponseNVP.php') ;
include_once('class.TransactionSearchRequestNVP.php') ;
include_once('class.TransactionSearchResponseNVP.php') ;
include_once('class.TransportNVP.php') ;

/**
 * @var $paypalAPIconfiguration Paypal configuration data available from Paypal.
 */

$paypalAPIconfiguration =
    array(
        'Credential' => 'API Signature',
        'Username' => '',
        'Password' => '',
        'Signature' => '',
        'RequestDate' => '') ;

/**
 * @var object $transport PaypalNVP transport object, responsible for issuing requests and receiving responses.
 */

$transport =&
    new TransportNVP(
        $paypalAPIconfiguration['Username'],
        $paypalAPIconfiguration['Password'],
        $paypalAPIconfiguration['Signature']) ;

/**
 * @var integer $startDate Unix timestamp of the beginning of period.
 */

$startDate = mktime(0, 0, 0, 12, 1, 2007) ;

/**
 * @var object $searchRequest Search for Paypal transactions.
 */

$searchRequest =&
    new TransactionSearchRequestNVP(
        paypalDate($startDate),
        paypalDate($startDate)) ;

/**
 * @var object $searchResponse Response to searches for Paypal transactions.
 */

$searchResponse =&
    new TransactionSearchResponseNVP() ;

/**
 * @var integer $interval the number of seconds between the start and end of the search period.
 */

$interval = 86400 ;

/**
 * @var object $detailsRequest object requesting the details of a transaction.
 */

$detailsRequest =&
    new GetTransactionDetailsRequestNVP() ;

/**
 * @var object $detailsResponse object containing the details of a transaction.
 */

$detailsResponse =&
    new GetTransactionDetailsResponseNVP() ;

/**
 * The overall algorithm is to request all transactions for a period of
 * interval seconds.
 *
 * If there was an error, reduce the interval by half (assuming that there
 * are too many transactions) and try again.  Keep going until the interval
 * gets too small.
 *
 * Once transactions have been accumulated, go through the transactions one at
 * a time and request the details.
 */

do
{
    $startDate += $interval ;                           // Advance to the beginning of the next day.

    $searchRequest->setENDDATE(paypalDate($startDate - 1)) ;
                                                        // To avoid duplication of transactions,
                                                        // go to the last second of the day.
	$searchRequest->validate(TRUE) ;					// Validate the search request.

    $transport->doRequest($searchRequest, $searchResponse) ;
                                                        // Execute the search and collect the response.
    if ($transport->getStatus()->isSuccess())
    {
        /*
         * The last request was successful, so the search reponse will have
         * data it in.
         */

        var_dump($searchResponse) ;

        $xxx =& $searchResponse->getResponse() ;

        /*
         * The TransactionSearchResponse object organizes the returned data as
         * an array of hashes.  One array element for each returned transaction.
         */

        foreach (array_keys($xxx)  as $aKey)
        {
            /*
             * Since each returned transaction will have a transaction ID,
             * get the details of the transaction given the transaction ID.
             */

            $detailsRequest->setTRANSACTIONID($xxx[$aKey]['L_TRANSACTIONID']) ;

			$detailsRequest->validate(TRUE) ;			// Validate the details request.

            $transport->doRequest($detailsRequest, $detailsResponse) ;

            if ($transport->getStatus()->isSuccess())
            {
                var_dump($detailsResponse) ;
            }
            else
            {
                echo $transport->getStatus()->getLongMessage(TRUE) ;
            }
        }

        $interval = 86400 ;

        $searchRequest->setSTARTDATE(paypalDate($startDate)) ;
    }
    else
    {
        /*
         * for the purposes of this example, I assume that the only
         * error that can be returned is the results list being truncated.
         * Since the Paypal API doesn't have any way to limit the number
         * of transactions returned other than shortening the interval until
         * we get 100 or fewer transactions.
         */

        echo $transport->getStatus()->getLongMessage(TRUE) ;

        $startDate -= $interval ;

        $interval = intval($interval / 2) ;

        if ($interval < 1)
        {
            die('Site is too busy') ;
        }
    }
} while ($startDate < time()) ;
?>
