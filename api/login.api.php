<?php
class loginapi extends baseapi
{
    protected $User;

    public function __construct($request, $var, $origin) {
        parent::__construct($request, $var);

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
			$this->toBeAuthenticated = false;
    }
	
    protected function login() {
			return $this->Call();
    }
	
		public function PostCall() {
			return true;
		}

		public function GetCall() {
			$_authorisation = new Authorization;
			if(array_key_exists('id', $this->request) && array_key_exists('pwd', $this->request))
			{
				if ($_authorisation->signin($this->request['id'],$this->request['pwd'],false)) {
					return ["login"=>"success" , "token"=>$_SESSION['exp_user']['Token']];
				} else {
					return ["login"=>"failed" ,"status"=>412, "message" => "Invalid id or password"];
				}
			} else {
				throw new Exception('Invalid parameters');
			}
		}

 }