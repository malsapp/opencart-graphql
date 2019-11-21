<?php

require_once __DIR__.'/vendor/autoload.php';

use GraphQL\GraphQL;
use GQL\Types;
use GQL\Helpers\Utils;

define ('GQ_INTERNAL_KEY', '__GQ_INTERNAL_KEY__');
define ('GQ_PLUGIN_VERSION', '1.129');

class ControllerApiGraphqlUsage extends Controller {
	public $sess = "";
	public function index () {
		error_reporting(0);
		$rawBody = "";
		if (!isset($this->request->get['token']) && false){
			$result = [
				'error' => [
				    'message' => 'Not Authorized'
				]
			];
		} else{
			// $token=$this->request->get['token'];
			// $this->load->model('account/api');
			// $api_info = $this->model_account_api->getApiByKey($token);
			// if(!$api_info && false){
			// 	$result = [
			// 		'error' => [
			// 		    'message' => 'Not Authorized'
			// 		]
			// 	];
			if(false){
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
						$this->sess = Utils::getSession ($this, $this->sess);
						header("x-session-id: {$this->sess}");
					}

					$rawBody = file_get_contents('php://input');
					$data = json_decode($rawBody ?: '', true);
					$requestString = isset($data['query']) ? $data['query'] : null;
					$operationName = isset($data['operation']) ? $data['operation'] : null;
					$variableValues = isset($data['variables']) ? $data['variables'] : null;
					if (!(is_object ($variableValues) || is_array($variableValues)))
						$variableValues = json_decode($variableValues, True);
					$types = Types::Instance ();
					$rootValue = ['prefix' => 'You said: '];
					$result = GraphQL::execute($types::$schema, $requestString, null, $this, $variableValues);
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
		echo json_encode($result);
	}
}

?>
