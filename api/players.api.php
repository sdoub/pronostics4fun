<?php
class playersapi extends baseapi
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
//         } else !PlayersQuery::create()->findByToken($this->request['token'])
// 			if (array_key_exists('id', $this->args))
// 				$this->User = $this->args["id"];
    }

    protected function players() {
			return $this->Call();
    }
	
		public function GetCall() {
			if (array_key_exists('id', $this->args)) {
				$q = new PlayersQuery();
				$firstPlayer = $q->findPK($this->args["id"]);
				$this->toBeEncoded = false;
				return $firstPlayer->toJson();
			}
			else {
				$this->toBeEncoded = false;
				return PlayersQuery::create()
				->orderByNickname()
				->limit(10)
				->find()
				->toJson();
			}
		}  
	
	
	/**
     * Example of an Endpoint
     */
//      protected function players() {
//         if ($this->method == 'GET') {
//           if (array_key_exists('id', $this->args)) {
// 						$q = new PlayersQuery();
// 						$firstPlayer = $q->findPK($this->args["id"]);
// 						$this->toBeEncoded = false;
// 						return $firstPlayer->toJson();
// 					}
// 					else {
// 						$this->toBeEncoded = false;
// 						return PlayersQuery::create()
// 						->orderByNickname()
// 						->limit(10)
// 						->find()
// 						->toJson();
// 					}
						
//         } elseif ($this->method == 'POST') { 
//             return "method does not support!";
//         } else {
// 					return "method does not support!";					
// 				}
//      }
 }