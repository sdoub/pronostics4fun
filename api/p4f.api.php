<?php
require_once 'abstract.api.php';
class p4fapi extends baseapi
{
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);

        // Abstracted out for example
        //$APIKey = new Models\APIKey();
        //$User = new Models\User();

//         if (!array_key_exists('apiKey', $this->request)) {
//             throw new Exception('No API Key provided');
//         } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
//             throw new Exception('Invalid API Key');
//         } else if (array_key_exists('token', $this->request) &&
//              !$User->get('token', $this->request['token'])) {

//             throw new Exception('Invalid User Token');
//         }

        $this->User = "sdoub";
    }

    /**
     * Example of an Endpoint
     */
     protected function players() {
        if ($this->method == 'GET') {
            return "Your name is " . $this->User;
        } elseif ($this->method == 'POST') { 
            return "method does not support!";
        } else {
					return "method does not support!";					
				}
     }
 }