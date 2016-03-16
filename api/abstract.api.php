<?php
abstract class baseapi
{
    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';
    /**
     * Property: endpoint
     * The Model requested in the URI. eg: /files
     */
    protected $endpoint = '';
    /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
    protected $verb = '';
    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected $args = Array();
    /**
     * Property: file
     * Stores the input of the PUT request
     */
    protected $file = Null;
	   
		protected $toBeEncoded = true;
	
		protected $toBeAuthenticated = true;

    /**
     * Constructor: __construct
     * Allow for CORS, assemble and pre-process the data
     */
    public function __construct($request, $var) {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->args = explode('/', rtrim($request, '/'));
        $this->endpoint = array_shift($this->args);
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }
			
				$this->args = array_merge($this->args, $var);

        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        switch($this->method) {
					case 'DELETE':
					case 'POST':
							$this->request = $this->_cleanInputs($_POST);
							break;
					case 'GET':
							$this->request = $this->_cleanInputs($_GET);
							break;
					case 'PUT':
							$this->request = $this->_cleanInputs($_GET);
							$this->file = file_get_contents("php://input");
							break;
					default:
							$this->_response('Invalid Method', 405);
							break;
        }
    }
	
		protected function checkAPI () {
			if (!array_key_exists('token', $this->request)) {
				return 'NA01'; // Token is required
			} else {
				$playerCount = PlayersQuery::create()->findByToken($this->request['token'])->count();
				if ($playerCount==0) {
					return 'NA02'; // Token is no longer valid
				}
			}
			return null;
		}
	  
	  public function Call() {
			switch($this->method) {
        case 'DELETE':
					return $this->DeleteCall();
					break;
        case 'POST':
					return $this->PostCall();
					break;
        case 'GET':
					return $this->GetCall();
					break;
        case 'PUT':
					return $this->PutCall();
					break;
        default:
					return $this->_response('Invalid Method', 405);
					break;
			}
		}
	
		public function DeleteCall() {
			throw new Exception("Method not implemented");
		}
		public function PostCall() {
			throw new Exception("Method not implemented");
		}
		public function PutCall() {
			throw new Exception("Method not implemented");
		}
		public function GetCall() {
			throw new Exception("Method not implemented");
		}
	
		public function processAPI() {
			$notAuthorisedCode = null;
			if ($this->toBeAuthenticated) {
				$notAuthorisedCode = $this->checkAPI();
			} 
			if ($notAuthorisedCode==null) {
				if (method_exists($this, $this->endpoint)) {
            return $this->_response($this->{$this->endpoint}($this->args));
        }
        return $this->_response(["error" => "No Endpoint", "message"=>"$this->endpoint not found"], 404);
			} else {
				return $this->_response(["error"=>$notAuthorisedCode, "message"=>$this->_errorMessage($notAuthorisedCode)], 401);
			}
    }

    private function _response($data, $status = 200) {
			if (is_array($data) && array_key_exists('status',$data)){
				$status = $data["status"];
			}
			header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
      if ($this->toBeEncoded)
				return json_encode($data);
			else
				return $data;
    }

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

    private function _errorMessage($code) {
        $status = array(  
            'NA01' => 'Token is required',
          	'NA02' => 'Token is no longer valid',
						'UNKNOWN' => 'Unknown error'
        ); 
        return ($status[$code])?$status[$code]:$status["UNKNOWN"]; 
    }

		private function _requestStatus($code) {
        $status = array(  
            200 => 'OK',
          	401 => 'Unauthorized Access',
						403 => 'Access Forbidden',
						404 => 'Not Found',   
            405 => 'Method Not Allowed',
						412 => 'Invalid parameter',
            500 => 'Internal Server Error'
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }
}