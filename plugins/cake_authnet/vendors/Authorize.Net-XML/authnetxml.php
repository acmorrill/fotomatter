<?php
require_once(ROOT.'/app/vendors/Authorize.Net-XML-master/AuthnetXML.class.php');


class AuthnetxmlComponent extends Object {
     
    /**
     * This is the api object used to subject actual transactions
     * @var AuthnetXML.class object 
     */
    public $authnetXML = null;
    
    /**
     * Dev and live credentials to use for transactions
     * @var array
     */
    public $credentials = array(
      'dev'=>array(
          'id'=>'4bN5vX4zH',
          'key'=>'9QUf6V8m6T74Q9c6'
      ),
      'live'=>array(
          'id'=>'__INSERT_ID_HERE__',
          'key'=>'__INSERT_KEY_HERE__'
      )
    );
    
    /**
     * flip to false to submit live transactions.
     * @var type 
     */
    public $dev_mode = true;
    
    /**
     * figure out what mode we are in, and initialize the autnet object correctly
     */
    public function __construct() {
        if ($this->dev_mode) {
            $this->authnetXML = new AuthnetXML($this->credentails['dev']['id'], $this->credentials['dev']['key'], AuthnetxmlComponent::USE_DEVELOPMENT_SERVER);
        } else {
            $this->authnetXML = new AuthnetXML($this->credentails['live']['id'], $this->credentials['live']['key'], AuthnetxmlComponent::USE_PRODUCTION_SERVER);
        }
    }
    
    
    /**
     * Call this function to create a new payment profile. (for more info see docs here http://www.authorize.net/support/CIM_XML_guide.pdf)
     * 
     * Needs to contain the following three groups of info
     * I have tried to make this fairly generic to port easily to other projects
     * 
     * merchantCustomerID - 
     *  -Our id for the customer. 
     * 
     * PaymentAddress (both address fields should contain)
     *  -firstname
     *  -lastname
     *  -address
     *  -city
     *  -state
     *  -zip
     *  -country
     *  -phoneNumber
     * 
     * ShippingAddress (can be ommited, if its is then payment info can be used
     * 
     * PaymentInfo
     *  -credit_card_number
     *  -expirationDate
     *  -cardCode
     * 
     * @param type $payment_info
     */
    public function createNewProfile($payment_info) {
        //first validate that we have necessary data
        if (empty($payment_info['merchantCustomerID'])) {
         //   throw new Exception('Tried to call createNewPro')
        }
        
        
    }
    
    
    
    
}