<?php
 
class ControllerExtensionModulePhotoGallery extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/photo_gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('photo_gallery', $this->request->post);

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
			'href' => $this->url->link('extension/module/photo_gallery', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/photo_gallery', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['photo_gallery_status'])) {
			$data['photo_gallery_status'] = $this->request->post['photo_gallery_status'];
		} else {
			$data['photo_gallery_status'] = $this->config->get('photo_gallery_status');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/photo_gallery', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/photo_gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "photo` (
		  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
		  `image` varchar(255) NOT NULL,
		  `date_added` datetime NOT NULL,
		  `status` tinyint(1) NOT NULL,
		  PRIMARY KEY (`photo_id`)
		)");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "photo_description` (
		  `photo_description_id` int(11) NOT NULL AUTO_INCREMENT,
		  `photo_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `title` varchar(255) COLLATE utf8_bin NOT NULL,
		  `description` text COLLATE utf8_bin NOT NULL,
		  `short_description` text COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`photo_description_id`)
		)");
		
		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/photo_gallery');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/photo_gallery');
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "photo_gallery`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "photo_description`");

		$this->load->model('user/user_group');

		$this->model_user_user_group->removePermission($this->user->getId(), 'access', 'extension/photo_gallery');
		$this->model_user_user_group->removePermission($this->user->getId(), 'modify', 'extension/photo_gallery');
	}
}
 
?>