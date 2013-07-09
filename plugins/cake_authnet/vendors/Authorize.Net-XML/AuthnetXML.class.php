<?php

/*************************************************************************************************

This class allows for easy use of any Authorize.Net XML based APIs. More information
about these APIs can be found at http://developer.authorize.net/api/.

PHP version 5

LICENSE: This program is free software: you can redistribute it and/or modify
it under the terms of the MIT License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see http://www.gnu.org/licenses/.

@category   Ecommerce
@package    AuthnetXML
@author     John Conde <authnet@johnconde.net>
@copyright  2011 John Conde
@license    MIT License
@version    1.0.4
@link       http://www.johnconde.net/

**************************************************************************************************/

class AuthnetXMLException extends Exception {}

class AuthnetXML
{
    const USE_PRODUCTION_SERVER  = 0;
    const USE_DEVELOPMENT_SERVER = 1;

    const EXCEPTION_CURL = 10;

    private $ch;
	private $login;
    private $response;
    private $response_xml;
    private $raw_response;
    private $results;
    private $transkey;
    private $url;
    private $xml;

	public function __construct($login, $transkey, $test = self::USE_PRODUCTION_SERVER)
	{
		$login    = trim($login);
        $transkey = trim($transkey);
        if (empty($login) || empty($transkey))
        {
            trigger_error('You have not configured your ' . __CLASS__ . '() login credentials properly.', E_USER_WARNING);
        }

        $this->login    = trim($login);
        $this->transkey = trim($transkey);
        $test           = (bool) $test;

        $subdomain = ($test) ? 'apitest' : 'api';
        $this->url = 'https://' . $subdomain . '.authorize.net/xml/v1/request.api';
	}

    /**
     * remove XML response namespaces
     * without this php will spit out warinings
     * @see http://community.developer.authorize.net/t5/Integration-and-Testing/ARB-with-SimpleXML-PHP-Issue/m-p/7128#M5139
     */
    private function removeResponseXMLNS($input)
    {
        // why remove them one at a time? all three aren't consistantly used in the response
        $input = str_replace(' xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd"','',$input);
        $input = str_replace(' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"','',$input);
        return str_replace(' xmlns:xsd="http://www.w3.org/2001/XMLSchema"','',$input);
    }

	public function __toString()
	{
	    $output  = '';
        $output .= '<table summary="Authorize.Net Results" id="authnet">' . "\n";
        $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Class Parameters</b></th>' . "\n" . '</tr>' . "\n";
        $output .= '<tr>' . "\n\t\t" . '<td><b>API Login ID</b></td><td>' . $this->login . '</td>' . "\n" . '</tr>' . "\n";
        $output .= '<tr>' . "\n\t\t" . '<td><b>Transaction Key</b></td><td>' . $this->transkey . '</td>' . "\n" . '</tr>' . "\n";
        $output .= '<tr>' . "\n\t\t" . '<td><b>Authnet Server URL</b></td><td>' . $this->url . '</td>' . "\n" . '</tr>' . "\n";
        $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Request XML</b></th>' . "\n" . '</tr>' . "\n";
        if (isset($this->xml) && !empty($this->xml))
        {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML(self::removeResponseXMLNS($this->xml));
            $outgoing_xml = $dom->saveXML();

            $output .= '<tr><td>' . "\n";
            $output .= 'XML:</td><td><pre>' . "\n";
            $output .= htmlentities($outgoing_xml) . "\n";
            $output .= '</pre></td></tr>' . "\n";
        }
        if (!empty($this->response))
        {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML(self::removeResponseXMLNS($this->response));
            $response_xml = $dom->saveXML();

            $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Response XML</b></th>' . "\n" . '</tr>' . "\n";
            $output .= '<tr><td>' . "\n";
            $output .= 'XML:</td><td><pre>' . "\n";
            $output .= htmlentities($response_xml) . "\n";
            $output .= '</pre></td></tr>' . "\n";
        }
        $output .= '</table>';

        return $output;
	}

	public function __destruct()
    {
        if (isset($this->ch))
        {
            curl_close($this->ch);
        }
    }

    public function __get($var)
	{
	    return $this->response_xml->$var;
	}

	public function __set($key, $value)
	{
	    trigger_error('You cannot set parameters directly in ' . __CLASS__ . '.', E_USER_WARNING);
	    return false;
	}

	public function __call($api_call, $args)
	{
	    $this->xml = new SimpleXMLElement('<' . $api_call . '></' . $api_call . '>');
        $this->xml->addAttribute('xmlns', 'AnetApi/xml/v1/schema/AnetApiSchema.xsd');
	    $merch_auth = $this->xml->addChild('merchantAuthentication');
        $merch_auth->addChild('name', $this->login);
	    $merch_auth->addChild('transactionKey', $this->transkey);

		$this->setParameters($this->xml, $args[0]);
		$this->process();
	}

	private function setParameters($xml, $array)
	{
	    if (is_array($array))
        {
            $first = true;
            foreach ($array as $key => $value)
            {
                if (is_array($value))
                {
                    if(is_numeric($key))
                    {
                        if($first === true)
                        {
                            $xmlx  = $xml;
                            $first = false;
                        }
                        else
                        {
                            $parent = $xml->xpath('parent::*');
                            $xmlx = $parent[0]->addChild($xml->getName());
                        }
                    }
                    else
                    {
                        $xmlx = $xml->addChild($key);
                    }
                    $this->setParameters($xmlx, $value);
                }
                else
                {
                    $xml->$key = $value;
                }
            }
        }
	}

	private function process()
	{
		$this->xml = $this->xml->asXML();

		$this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
    	curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($this->ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
    	curl_setopt($this->ch, CURLOPT_HEADER, 0);
    	curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->xml);
    	curl_setopt($this->ch, CURLOPT_POST, 1);
    	curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2);
    	curl_setopt($this->ch, CURLOPT_CAINFO, dirname(__FILE__) . '/ssl/cert.pem');

        if(($this->response = curl_exec($this->ch)) !== false)
        {
			$this->raw_response = $this->response;
            $this->response_xml = @new SimpleXMLElement($this->response);

		    curl_close($this->ch);
            unset($this->ch);
            return;
		}
        throw new AuthnetXMLException('Connection error: ' . curl_error($this->ch) . ' (' . curl_errno($this->ch) . ')', self::EXCEPTION_CURL);
	}

	public function isSuccessful()
    {
        $response = (string) $this->response_xml->messages->resultCode;
        return $response == 'Ok';
    }

    public function isError()
    {
        $response = (string) $this->response_xml->messages->resultCode;
        return $response != 'Ok';
    }
    
    public function get_code() {
            return (string)$this->response_xml->messages->message->code;
    }

    public function get_message() {
            return (string)$this->response_xml->messages->message->text;
    }

    public function get_response() {
            return $this->response_xml;
    }
	
    public function get_raw_response() {
            return $this->raw_response;
    }
	
	public function get_parsed_response() {
		$direct_response = (string)$this->get_response()->directResponse;
		$direct_response_values = explode(',', $direct_response);
		
		$result = array();
		$result['full_response_string'] = print_r($direct_response_values, true);
		// response code
//		1 = Approved 
//		2 = Declined 
//		3 = Error 
//		4 = Held for Review
		$result['response_code'] = $direct_response_values[0];
		$result['response_subcode'] = $direct_response_values[1];
		$result['response_reason_code'] = $direct_response_values[2];
		$result['response_reason_text'] = $direct_response_values[3];
		$result['authorization_code'] = $direct_response_values[4];
		// avs_response codes
//		A = Address (Street) matches, ZIP does not
//		B = Address information not provided for AVS check
//		E = AVS error
//		G = Non-U.S. Card Issuing Bank
//		N = No Match on Address (Street) or ZIP
//		P = AVS not applicable for this transaction
//		R = Retry—System unavailable or timed out
//		S = Service not supported by issuer
//		U = Address information is unavailable
//		W = Nine digit ZIP matches, Address (Street) does not
//		X = Address (Street) and nine digit ZIP match
//		Y = Address (Street) and five digit ZIP match
//		Z = Five digit ZIP matches, Address (Street) does not
		$result['avs_response'] = $direct_response_values[5];
		$result['transaction_id'] = $direct_response_values[6];
		$result['amount'] = $direct_response_values[9];
		$result['method'] = $direct_response_values[10];
		$result['transaction_type'] = $direct_response_values[11];
		$result['authnet_profile_id'] = $direct_response_values[12];
		$result['first_name'] = $direct_response_values[13];
		$result['last_name'] = $direct_response_values[14];
		$result['address'] = $direct_response_values[16];
		$result['city'] = $direct_response_values[17];
		$result['state'] = $direct_response_values[18];
		$result['zip'] = $direct_response_values[19];
		$result['country'] = $direct_response_values[20];
		$result['phone'] = $direct_response_values[21];
		$result['email'] = $direct_response_values[25];
		$result['shipping_price'] = $direct_response_values[34];
		$result['md5_hash'] = $direct_response_values[37];
		// ccv response codes
//		0 = CAVV not validated because erroneous data was submitted
//		1 = CAVV failed validation
//		2 = CAVV passed validation
//		3 = CAVV validation could not be performed; issuer attempt incomplete
//		4 = CAVV validation could not be performed; issuer system error
//		5 = Reserved for future use
//		6 = Reserved for future use
//		7 = CAVV attempt – failed validation – issuer available (U.S.-		issued card/non-U.S acquirer)
//		8 = CAVV attempt – passed validation – issuer available (U.S.-issued card/non-U.S. acquirer)
//		9 = CAVV attempt – failed validation – issuer unavailable (U.S.-issued card/non-U.S. acquirer)
//		A = CAVV attempt – passed validation – issuer unavailable (U.S.-issued card/non-U.S. acquirer)
//		B = CAVV passed validation, information only, no liability shift
		$result['credit_card_validation_code_response'] = $direct_response_values[39];
		$result['last_four'] = $direct_response_values[50];
		$result['card_type'] = $direct_response_values[51];
		
		
		return $result;
	}

}

?>
