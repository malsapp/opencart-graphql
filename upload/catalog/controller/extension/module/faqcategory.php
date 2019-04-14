<?php
class ControllerExtensionModuleFaqCategory extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/faqcategory');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['faqcategory_id'] = $parts[0];
		} else {
			$data['faqcategory_id'] = 0;
		}



		$this->load->model('catalog/faqcategory');

		$data['faqcategories'] = array();

		$faqcategories = $this->model_catalog_faqcategory->getFaqCategories();

		foreach ($faqcategories as $faqcategory) {
			$data['faqcategories'][] = array(
				'faqcategory_id' => $faqcategory['faqcategory_id'],
				'title'        => $faqcategory['title'],
				'href'        => $this->url->link('information/faq', 'faqcategory_id=' . $faqcategory['faqcategory_id'])
			);
		}
//
//		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/faqcategory.tpl')) {
//			return $this->load->view($this->config->get('config_template') . '/template/extension/module/faqcategory.tpl', $data);
//		} else {
//			return $this->load->view('extension/module/faqcategory.tpl', $data);
//		}
	}
}