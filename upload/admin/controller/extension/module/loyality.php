<?php
 
class ControllerExtensionModuleLoyality extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/loyality');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('loyality', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/loyality', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/loyality', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['loyality_status'])) {
			$data['loyality_status'] = $this->request->post['loyality_status'];
		} else {
			$data['loyality_status'] = $this->config->get('loyality_status');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/loyality', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/loyality')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function install() {
		$this->db->query("ALTER TABLE " . DB_PREFIX .
		 "customer_group ADD 
		  reward_points int(11)"
		);
		
		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/loyality');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/loyality');
	}
	
	public function uninstall() {
		$this->db->query("ALTER TABLE " . DB_PREFIX . "customer_group DROP reward_points");

		$this->load->model('user/user_group');

		$this->model_user_user_group->removePermission($this->user->getId(), 'access', 'extension/loyality');
		$this->model_user_user_group->removePermission($this->user->getId(), 'modify', 'extension/loyality');
	}
}
 
?>