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
 * A convenient collection of all enumerations used by the PaypalNVP
 * request/responses.
 */

class EnumerationsPaypalNVP
{
    var $mCountryCodes =
        array(
            'Afghanistan' => 'AF',
            'ÌÉland Islands' => 'AX',
            'Albania' => 'AL',
            'Algeria' => 'DZ',
            'American Samoa' => 'AS',
            'Andorra' => 'AD',
            'Angola' => 'AO',
            'Anguilla' => 'AI',
            'Antarctica' => 'AQ',
            'Antigua and Barbuda' => 'AG',
            'Argentina' => 'AR',
            'Armenia' => 'AM',
            'Aruba' => 'AW',
            'Australia' => 'AU',
            'Austria' => 'AT',
            'Azerbaijan' => 'AZ',
            'Bahamas' => 'BS',
            'Bahrain' => 'BH',
            'Bangladesh' => 'BD',
            'Barbados' => 'BB',
            'Belarus' => 'BY',
            'Belgium' => 'BE',
            'Belize' => 'BZ',
            'Benin' => 'BJ',
            'Bermuda' => 'BM',
            'Bhutan' => 'BT',
            'Bolivia' => 'BO',
            'Bosnia and Herzegovina' => 'BA',
            'Botswana' => 'BW',
            'Bouvet Island' => 'BV',
            'Brazil' => 'BR',
            'British Indian Ocean Territory' => 'IO',
            'Brunei Darussalam' => 'BN',
            'Bulgaria' => 'BG',
            'Burkina Faso' => 'BF',
            'Burundi' => 'BI',
            'Cambodia' => 'KH',
            'Cameroon' => 'CM',
            'Canada' => 'CA',
            'Cape Verde' => 'CV',
            'Cayman Islands' => 'KY',
            'Central African Republic' => 'CF',
            'Chad' => 'TD',
            'Chile' => 'CL',
            'China' => 'CN',
            'Christmas Island' => 'CX',
            'Cocos (Keeling) Islands' => 'CC',
            'Colombia' => 'CO',
            'Comoros' => 'KM',
            'Congo' => 'CG',
            'Congo, The Democratic Republic of the' => 'CD',
            'Cook Islands' => 'CK',
            'Costa Rica' => 'CR',
            "Cote D'Ivoire" => 'CI',
            'Croatia' => 'HR',
            'Cuba' => 'CU',
            'Cyprus' => 'CY',
            'Czech Republic' => 'CZ',
            'Denmark' => 'DK',
            'Djibouti' => 'DJ',
            'Dominica' => 'DM',
            'Dominican Republic' => 'DO',
            'Ecuador' => 'EC',
            'Egypt' => 'EG',
            'El Salvador' => 'SV',
            'Equatorial Guinea' => 'GQ',
            'Eritrea' => 'ER',
            'Estonia' => 'EE',
            'Ethiopia' => 'ET',
            'Falkland Islands (Malvinas)' => 'FK',
            'Faroe Islands' => 'FO',
            'Fiji' => 'FJ',
            'Finland' => 'FI',
            'France' => 'FR',
            'French Guiana' => 'GF',
            'French Polynesia' => 'PF',
            'French Southern Territories' => 'TF',
            'Gabon' => 'GA',
            'Gambia' => 'GM',
            'Georgia' => 'GE',
            'Germany' => 'DE',
            'Ghana' => 'GH',
            'Gibraltar' => 'GI',
            'Greece' => 'GR',
            'Greenland' => 'GL',
            'Grenada' => 'GD',
            'Guadeloupe' => 'GP',
            'Guam' => 'GU',
            'Guatemala' => 'GT',
            'Guernsey' => 'GG',
            'Guinea' => 'GN',
            'Guinea-Bissau' => 'GW',
            'Guyana' => 'GY',
            'Haiti' => 'HT',
            'Heard Island and McDonald Islands' => 'HM',
            'Holy See (Vatican City State)' => 'VA',
            'Honduras' => 'HN',
            'Hong Kong' => 'HK',
            'Hungary' => 'HU',
            'Iceland' => 'IS',
            'India' => 'IN',
            'Indonesia' => 'ID',
            'Iran, Islamic Republic of' => 'IR',
            'Iraq' => 'IQ',
            'Ireland' => 'IE',
            'Isle of Man' => 'IM',
            'Israel' => 'IL',
            'Italy' => 'IT',
            'Jamaica' => 'JM',
            'Japan' => 'JP',
            'Jersey' => 'JE',
            'Jordan' => 'JO',
            'Kazakhstan' => 'KZ',
            'Kenya' => 'KE',
            'Kiribati' => 'KI',
            "Korea, Democratic People's Republic of" => 'KP',
            'Korea, Republic of' => 'KR',
            'Kuwait' => 'KW',
            'Kyrgyzstan' => 'KG',
            "Laos People's Democratic Republic" => 'LA',
            'Latvia' => 'LV',
            'Lebanon' => 'LB',
            'Lesotho' => 'LS',
            'Liberia' => 'LR',
            'Libyan Arab Jamahiriya' => 'LY',
            'Liechtenstein' => 'LI',
            'Lithuania' => 'LT',
            'Luxembourg' => 'LU',
            'Macao' => 'MO',
            'Macedonia, The former Yugoslav Republic of' => 'MK',
            'Madagascar' => 'MG',
            'Malawi' => 'MW',
            'Malaysia' => 'MY',
            'Maldives' => 'MV',
            'Mali' => 'ML',
            'Malta' => 'MT',
            'Marshall Islands' => 'MH',
            'Martinique' => 'MQ',
            'Mauritania' => 'MR',
            'Mauritius' => 'MU',
            'Mayotte' => 'YT',
            'Mexico' => 'MX',
            'Micronesia, Federated States of' => 'FM',
            'Moldova, Republic of' => 'MD',
            'Monaco' => 'MC',
            'Mongolia' => 'MN',
            'Montserrat' => 'MS',
            'Morocco' => 'MA',
            'Mozambique' => 'MZ',
            'Myanmar' => 'MM',
            'Namibia' => 'NA',
            'Nauru' => 'NR',
            'Nepal' => 'NP',
            'Netherlands' => 'NL',
            'Netherlands Antilles' => 'AN',
            'New Caledonia' => 'NC',
            'New Zealand' => 'NZ',
            'Nicaragua' => 'NI',
            'Niger' => 'NE',
            'Nigeria' => 'NG',
            'Niue' => 'NU',
            'Norfolk Island' => 'NF',
            'Northern Mariana Islands' => 'MP',
            'Norway' => 'NO',
            'Oman' => 'OM',
            'Pakistan' => 'PK',
            'Palau' => 'PW',
            'Palestinian Territory, Occupied' => 'PS',
            'Panama' => 'PA',
            'Papua New Guinea' => 'PG',
            'Paraguay' => 'PY',
            'Peru' => 'PE',
            'Philippines' => 'PH',
            'Pitcairn' => 'PN',
            'Poland' => 'PL',
            'Portugal' => 'PT',
            'Puerto Rico' => 'PR',
            'Qatar' => 'QA',
            'Reunion' => 'RE',
            'Romania' => 'RO',
            'Russian Federation' => 'RU',
            'Rwanda' => 'RW',
            'Saint Helena' => 'SH',
            'Saint Kitts and Nevis' => 'KN',
            'Saint Lucia' => 'LC',
            'Saint Pierre and Miquelon' => 'PM',
            'Saint Vincent and the Grenadines' => 'VC',
            'Samoa' => 'WS',
            'San Marino' => 'SM',
            'Sao Tome and Principe' => 'ST',
            'Saudi Arabia' => 'SA',
            'Senegal' => 'SN',
            'Serbia and Montenegro' => 'CS',
            'Seychelles' => 'SC',
            'Sierra Leone' => 'SL',
            'Singapore' => 'SG',
            'Slovakia' => 'SK',
            'Slovenia' => 'SI',
            'Solomon Islands' => 'SB',
            'Somalia' => 'SO',
            'South Africa' => 'ZA',
            'South Georgia and the South Sandwich Islands' => 'GS',
            'Spain' => 'ES',
            'Sri Lanka' => 'LK',
            'Sudan' => 'SD',
            'Suriname' => 'SR',
            'SValbard and Jan Mayen' => 'SJ',
            'Swaziland' => 'SZ',
            'Sweden' => 'SE',
            'Switzerland' => 'CH',
            'Syrian Arab Republic' => 'SY',
            'Taiwan, Province of China' => 'TW',
            'Tajikistan' => 'TJ',
            'Tanzania, United Republic of' => 'TZ',
            'Thailand' => 'TH',
            'Timor-Leste' => 'TL',
            'Togo' => 'TG',
            'Tokelau' => 'TK',
            'Tonga' => 'TO',
            'Trinidad and Tobago' => 'TT',
            'Tunisia' => 'TN',
            'Turkey' => 'TR',
            'Turkmenistan' => 'TM',
            'Turks and Caicos Islands' => 'TC',
            'Tuvalu' => 'TV',
            'Uganda' => 'UG',
            'Ukraine' => 'UA',
            'United Arab Emirates' => 'AE',
            'United Kingdom' => 'GB',
            'United States' => 'US',
            'United States Minor Outlying Islands' => 'UM',
            'Uruguay' => 'UY',
            'Uzbekistan' => 'UZ',
            'Vanuatu' => 'VU',
            'Venezuela' => 'VE',
            'Viet Nam' => 'VN',
            'Virgin Islands, British' => 'VG',
            'Virgin Islands, U.S.' => 'VI',
            'Wallis and Futuna' => 'WF',
            'Western Sahara' => 'EH',
            'Yemen' => 'YE',
            'Zambia' => 'ZM',
            'Zimbabwe' => 'ZW') ;

    var $mProvincesAndStates =
        array(
            'Alberta' => 'AB',
            'British Columbia' => 'BC',
            'Manitoba' => 'MB',
            'New Brunswick' => 'NB',
            'Newfoundland and Labrador' => 'NF',
            'Northwest Territories' => 'NT',
            'Nova Scotia' => 'NS',
            'Nunavut' => 'NU',
            'Ontario' => 'ON',
            'Prince Edward Island' => 'PE',
            'Quebec' => 'QC',
            'Saskatchewan' => 'SK',
            'Yukon' => 'YK',
            'Alabama' => 'AL',
            'Alaska' => 'AK',
            'American Samoa' => 'AS',
            'Arizona' => 'AZ',
            'Arkansas' => 'AR',
            'California' => 'CA',
            'Colorado' => 'CO',
            'Connecticut' => 'CT',
            'Delaware' => 'DE',
            'District of Columbia' => 'DC',
            'Federated States of Micronesia' => 'FM',
            'Florida' => 'FL',
            'Georgia' => 'GA',
            'Guam' => 'GU',
            'Hawaii' => 'HI',
            'Idaho' => 'ID',
            'Illinois' => 'IL',
            'Indiana' => 'IN',
            'Iowa' => 'IA',
            'Kansas' => 'KS',
            'Kentucky' => 'KY',
            'Louisiana' => 'LA',
            'Maine' => 'ME',
            'Marshall Islands' => 'MH',
            'Maryland' => 'MD',
            'Massachusetts' => 'MA',
            'Michigan' => 'MI',
            'Minnesota' => 'MN',
            'Mississippi' => 'MS',
            'Missouri' => 'MO',
            'Montana' => 'MT',
            'Nebraska' => 'NE',
            'Nevada' => 'NV',
            'New Hampshire' => 'NH',
            'New Jersey' => 'NJ',
            'New Mexico' => 'NM',
            'New York' => 'NY',
            'North Carolina' => 'NC',
            'North Dakota' => 'ND',
            'Northern Mariana Islands' => 'MP',
            'Ohio' => 'OH',
            'Oklahoma' => 'OK',
            'Oregon' => 'OR',
            'Palau' => 'PW',
            'Pennsylvania' => 'PA',
            'Puerto Rico' => 'PR',
            'Rhode Island' => 'RI',
            'South Carolina' => 'SC',
            'South Dakota' => 'SD',
            'Tennessee' => 'TN',
            'Texas' => 'TX',
            'Utah' => 'UT',
            'Vermont' => 'VT',
            'Virgin Islands' => 'VI',
            'Virginia' => 'VA',
            'Washington' => 'WA',
            'West Virginia' => 'WV',
            'Wisconsin' => 'WI',
            'Wyoming' => 'WY',
            'Armed Forces Americas' => 'AA',
            'Armed Forces' => 'AE',
            'Armed Forces Pacific' => 'AP') ;

    var $mAVSResponseCodes =
        array(
            'A' => array('MATCHED' => 'Address', 'STATUS' => 'Address only (no ZIP)'),
            'B' => array('MATCHED' => 'International', 'STATUS' => 'Address only (no ZIP)'),
            'C' => array('MATCHED' => 'International', 'STATUS' => 'Declined'),
            'D' => array('MATCHED' => 'International', 'STATUS' => 'Address and Postal Code'),
            'E' => array('MATCHED' => 'Not allowed for MOTO (Internet/Phone) transactions', 'STATUS' => 'Declined'),
            'F' => array('MATCHED' => 'UK-specific', 'STATUS' => 'Address and Postal Code'),
            'G' => array('MATCHED' => 'Global Unavailable', 'STATUS' => 'Not applicable'),
            'I' => array('MATCHED' => 'International Unavailable', 'STATUS' => 'Not applicable'),
            'N' => array('MATCHED' => 'No', 'STATUS' => 'Declined'),
            'P' => array('MATCHED' => 'Postal (International)', 'STATUS' => 'Postal Code only (no Address)'),
            'R' => array('MATCHED' => 'Retry', 'STATUS' => 'Retry'),
            'S' => array('MATCHED' => 'Service not Supported', 'STATUS' => 'Not applicable'),
            'U' => array('MATCHED' => 'Unavailable', 'STATUS' => 'Not applicable'),
            'W' => array('MATCHED' => 'Whole ZIP', 'STATUS' => 'Nine-digit ZIP code (no Address)'),
            'X' => array('MATCHED' => 'Exact match', 'STATUS' => 'Address and nine-digit ZIP code'),
            'Y' => array('MATCHED' => 'Yes', 'STATUS' => 'Address and five-digit ZIP'),
            'Z' => array('MATCHED' => 'ZIP', 'STATUS' => 'Five-digit ZIP code (no Address)')) ;

    var $mEnumerations ;

	/**
	 * @desc Constructor
	 */

    function EnumerationsPaypalNVP()
    {
        $this->mEnumerations['DoDirectPayment'] = array() ;

        $this->mEnumerations['DoDirectPayment']['PAYMENTACTIONS'] =
            array(
                'Authorization',
                'Sale') ;

        $this->mEnumerations['DoDirectPayment']['CREDITCARDTYPE'] =
            array(
                'Visa',
                'MasterCard',
                'Discover',
                'Amex',
                'Switch',
                'Solo') ;

        $this->mEnumerations['DoDirectPayment']['COUNTRYCODE'] =&
            $this->mCountryCodes ;

        $this->mEnumerations['DoDirectPayment']['CREDITCARDTYPE'] =
            array(
                'AUD',
                'CAD',
                'EUR',
                'GBP',
                'JPY',
                'USD') ;

        $this->mEnumerations['DoDirectPayment']['SHIPTOSTATE'] =&
            $this->mProvincesAndStates ;

        $this->mEnumerations['DoDirectPayment']['SHIPTOCOUNTRYCODE'] =&
            $this->mCountryCodes ;
    }

	/**
	 * @desc Get the enumerations associated with a specific Paypal method.
	 * @param string $theMethod the name of the method.
	 * @return array enumerations used by the request/response.
	 */

    function &get(
        $theMethod)
    {
        if (array_key_exists($theMethod, $this->mEnumerations))
        {
            return $this->mEnumerations[$theMethod] ;
        }
        else
        {
            $xxx = array() ;
            return $xxx ;
        }
    }
}
?>
