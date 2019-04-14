<?php
 
class ControllerExtensionModuleDelivery extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/delivery_agent');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('delivery_agent', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/delivery', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/delivery_agent', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/delivery', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['delivery_status'])) {
			$data['delivery_status'] = $this->request->post['delivery_status'];
		} else {
			$data['delivery_status'] = $this->config->get('delivery_status');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/delivery', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/delivery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function install() {
		$this->db->query("ALTER TABLE " . DB_PREFIX .
		 "address ADD 
		  geocode varchar(32)"
		);

		$this->db->query("CREATE TABLE `oc_delivery_agent` (
		  `delivery_agent_id` int(11) NOT NULL AUTO_INCREMENT,
		  `store_id` int(11) NOT NULL DEFAULT '0',
		  `language_id` int(11) NOT NULL,
		  `firstname` varchar(32) NOT NULL,
		  `lastname` varchar(32) NOT NULL,
		  `email` varchar(96) NOT NULL,
		  `telephone` varchar(32) NOT NULL,
		  `password` varchar(40) NOT NULL,
		  `salt` varchar(9) NOT NULL,
		  `address_id` int(11) NOT NULL DEFAULT '0',
		  `status` tinyint(1) NOT NULL,
		  `token` text NOT NULL,
		  `code` varchar(40) NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`delivery_agent_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;"
		);

		$this->db->query("CREATE TABLE `oc_delivery_agent_address` (
		  `address_id` int(11) NOT NULL AUTO_INCREMENT,
		  `delivery_agent_id` int(11) NOT NULL,
		  `firstname` varchar(32) NOT NULL,
		  `lastname` varchar(32) NOT NULL,
		  `company` varchar(40) NOT NULL,
		  `address_1` varchar(128) NOT NULL,
		  `address_2` varchar(128) NOT NULL,
		  `city` varchar(128) NOT NULL,
		  `postcode` varchar(10) NOT NULL,
		  `country_id` int(11) NOT NULL DEFAULT '0',
		  `zone_id` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`address_id`),
		  KEY `delivery_agent_id` (`delivery_agent_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;"
		);

		$this->db->query("CREATE TABLE `oc_delivery_agent_order` (
		  `delivery_agent_id` int(11) NOT NULL,
		  `order_id` int(11) NOT NULL,
		  PRIMARY KEY (delivery_agent_id,order_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;"
		);
		
		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/delivery_agent');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/delivery_agent');
	}
	
	public function uninstall() {
		$this->db->query("ALTER TABLE " . DB_PREFIX . "address DROP geocode");
		$this->db->query("DROP TABLE " . DB_PREFIX . "delivery_agent");
		$this->db->query("DROP TABLE " . DB_PREFIX . "delivery_agent_address");
		$this->db->query("DROP TABLE " . DB_PREFIX . "delivery_agent_order");
		
		$this->load->model('user/user_group');

		$this->model_user_user_group->removePermission($this->user->getId(), 'access', 'extension/delivery_agent');
		$this->model_user_user_group->removePermission($this->user->getId(), 'modify', 'extension/delivery_agent');
	}
}
 
?>