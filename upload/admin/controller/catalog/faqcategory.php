<?php    
class ControllerCatalogFaqCategory extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('catalog/faqcategory');
		
		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('catalog/faqcategory');
		
    	$this->getList();
  	}
  
  	public function add() {
		$this->load->language('catalog/faqcategory');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/faqcategory');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_catalog_faqcategory->addCategory($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			
			
		}
    
    	$this->getForm();
  	} 
   
  public function edit() {
		$this->load->language('catalog/faqcategory');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/faqcategory');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_catalog_faqcategory->editCategory($this->request->get['faqcategory_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			
		}
    
    	$this->getForm();
  	}   

  	public function delete() {
		$this->load->language('catalog/faqcategory');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/faqcategory');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $faqcategory_id) {
				$this->model_catalog_faqcategory->deleteCategory($faqcategory_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			
    	}
	
    	$this->getList();
  	}  
    
  	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'fcd.title';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$data['catalog_faqcategorys'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$category_total = $this->model_catalog_faqcategory->getTotalCategorys();
	
		$results = $this->model_catalog_faqcategory->getCategorys($data);
 
    	foreach ($results as $result) {
			$data['catalog_faqcategorys'][] = array(
				'faqcategory_id' => $result['faqcategory_id'],
				'title'          => $result['title'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['faqcategory_id'], $this->request->post['selected']),
				'edit'        => $this->url->link('catalog/faqcategory/edit', 'token=' . $this->session->data['token'] . '&faqcategory_id=' . $result['faqcategory_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('catalog/faqcategory/delete', 'token=' . $this->session->data['token'] . '&faqcategory_id=' . $result['faqcategory_id'] . $url, 'SSL')
			);
		}	
	
		$data['heading_title'] =$this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
	$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');		
		
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_rebuild'] = $this->language->get('button_rebuild');
 
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_title'] = $this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . '&sort=fcd.title' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . '&sort=fc.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$data['insert'] = $this->url->link('catalog/faqcategory/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['delete'] = $this->url->link('catalog/faqcategory/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('catalog/faqcategory_list.tpl', $data));
	}
  
  protected function getForm() {
	  $data['text_form'] = !isset($this->request->get['faqcategory_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
	  $data['text_none'] = $this->language->get('text_none');
    	$data['heading_title'] = $this->language->get('heading_title');

    	$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');
		
				
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_layout'] = $this->language->get('entry_layout');
		  
    	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['tab_general'] = $this->language->get('tab_general');
    	$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');
			  
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}
		
		    
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['faqcategory_id'])) {
			$data['action'] = $this->url->link('catalog/faqcategory/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/faqcategory/edit', 'token=' . $this->session->data['token'] . '&faqcategory_id=' . $this->request->get['faqcategory_id'] . $url, 'SSL');
		}
		
		$data['cancel'] = $this->url->link('catalog/faqcategory', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['token'] = $this->session->data['token'];
		
    	if (isset($this->request->get['faqcategory_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$category_info = $this->model_catalog_faqcategory->getCategory($this->request->get['faqcategory_id']);
    	}

    	$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['faqcategory_description'])) {
			$data['faqcategory_description'] = $this->request->post['faqcategory_description'];
		} elseif (isset($this->request->get['faqcategory_id'])) {
			$data['faqcategory_description'] = $this->model_catalog_faqcategory->getFaqCategoryDescriptions($this->request->get['faqcategory_id']);
		} else {
			$data['faqcategory_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($faqcategory_info)) {
			$data['status'] = $faqcategory_info['status'];
		} else {
			$data['status'] = 1;
		}
		
		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['faqcategory_store'])) {
			$data['faqcategory_store'] = $this->request->post['faqcategory_store'];
		} elseif (isset($category_info)) {
			$data['faqcategory_store'] = $this->model_catalog_faqcategory->getCategoryStores($this->request->get['faqcategory_id']);
		} else {
			$data['faqcategory_store'] = array(0);
		}	
		
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($category_info)) {
			$data['keyword'] = $category_info['keyword'];
		} else {
			$data['keyword'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($category_info)) {
			$data['sort_order'] = $category_info['sort_order'];
		} else {
      		$data['sort_order'] =0;
    	}
		if (isset($this->request->post['faqcategory_layout'])) {
			$data['faqcategory_layout'] = $this->request->post['faqcategory_layout'];
		} elseif (isset($this->request->get['faqcategory_id'])) {
			$data['faqcategory_layout'] = $this->model_catalog_faqcategory->getFaqCategoryLayouts($this->request->get['faqcategory_id']);
		} else {
			$data['faqcategory_layout'] = array();
		}


		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('catalog/faqcategory_form.tpl', $data));
	}  
	 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/faqcategory')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	foreach ($this->request->post['faqcategory_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}    

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/faqcategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}	
		
		$this->load->model('catalog/faq');

		foreach ($this->request->post['selected'] as $faqcategory_id) {
  			$faq_total = $this->model_catalog_faq->geTotaltFaqsByFaqCategoryId($faqcategory_id);
    
			if ($faq_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_faq'), $faq_total);	
			}	
	  	} 
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	}
}
?>