<?php
use GraphQL\GraphQL;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/Resolvers/Helpers.php';
require_once __DIR__.'/Types.php';

define ('GQ_INTERNAL_KEY', '__GQ_INTERNAL_KEY__');
define ('GQ_PLUGIN_VERSION', '1.0');

class ControllerApiGraphqlUsage extends Controller {
	public $sess = "";

	function index () {
	    
		error_reporting (0);
		$this->sess = $this->session->getId();
		$rawBody = "";
		if (!isset($this->request->get['token']) && false){
			$result = [
				'error' => [
				    'message' => 'Not Authorized'
				]
			];
		} else{
			$token=$this->request->get['token'];
			// echo $token ;
			
			$this->load->model('account/api');
			$api_info = "";//$this->model_account_api->getApiByKey($token);

// print_r($api_info);

			if(!$api_info && false){
				$result = [
					'error' => [
					    'message' => 'Not Authorized'
					]
				];
			}else{

				try {
					// Set a session and send it back.
					$headers = getallheaders ();
					if (isset($headers['X-Session-Id'])) {
						$this->sess = $headers['X-Session-Id'];
					}

					if (isset ($headers['x-session-id'])){
						$this->sess = $headers['x-session-id'];
					}

					if (!empty ($this->sess)){
						$this->sess = \GQL\getSession ($this, $this->sess);
						header("x-session-id: {$this->sess}");
					}
					
					$rawBody = file_get_contents('php://input');
					
					$data = json_decode($rawBody ?: '', true);
					
					$requestString = isset($data['query']) ? $data['query'] : null;
					$operationName = isset($data['operation']) ? $data['operation'] : null;
					$variableValues = isset($data['variables']) ? $data['variables'] : null;
					if (!(is_object ($variableValues) || is_array($variableValues)))
						$variableValues = json_decode($variableValues, True);
					$types = GQL\Types::Instance ();
					// var_dump($types);
					$rootValue = ['prefix' => 'You said: '];
					// print_r($schema);
					// print_r($requestString);
					// print_r($variableValues);
					
                    //$db = new DB('mysqli', $this->config->get('db_hostname'), $this->config->get('db_username'), $this->config->get('db_password'), $this->config->get('db_database'), $this->config->get('db_port'));
                    //$req = json_encode([$requestString,$rawBody,$_REQUEST,$_GET,$_POST]);
                    //$res =  $this->db->query("INSERT INTO `requests`(`request`) VALUES ('$req')");


					$result = GraphQL::execute($types::$schema, $requestString, null, $this, $variableValues);
					// print_r($result);
					// header('Content-Type: application/json; charset=UTF-8');
					// print_r( json_encode($result));
					
				} catch (\Exception $e) {
					
					$result = [
						'error' => [
						    'message' => $e
						]
					];
				}
			}
		}
		header('Content-Type: application/json; charset=UTF-8');
		// print_r( json_encode($result));
		// $res =json_encode($result)->data;
		// print_r($res);
		echo json_encode($result);
		
	}
}

?>
