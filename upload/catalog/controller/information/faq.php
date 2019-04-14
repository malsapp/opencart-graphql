<?php 
class ControllerInformationFaq extends Controller {  
	public function index() { 
		
		$this->language->load('information/faq');
		
		$this->load->model('catalog/faq');
		

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/faq.css');
		}
		
		
		$this->load->model('catalog/faqcategory');		
		
		$data['faqcategories'] = array();

		$faqcategories = $this->model_catalog_faqcategory->getFaqCategories();	
		
			if($faqcategories){
			foreach ($faqcategories as $result) {
				
				$faqs = array();
                $data['faqcategory_id'] = $result['faqcategory_id'];
				$faq_results = $this->model_catalog_faq->getFaqs($data);
							
				if(count($faq_results)) {
                    foreach ($faq_results as $faq_result) {
                        $faqs[] = array(
                            'faq_id' => $faq_result['faq_id'],
                            'title' => $faq_result['title'],
                            'description' => html_entity_decode($faq_result['description'], ENT_QUOTES, 'UTF-8')
                        );
                    }


                    $data['faqcategories'][] = array(
                        'faqcategory_id' => $result['faqcategory_id'],
                        'title' => $result['title'],
                        'faqs' => $faqs
                    );
                }
            }
			$data['text_empty'] = $this->language->get('text_empty');
		
		$data['button_continue'] = $this->language->get('button_continue');
			$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['continue'] = $this->url->link('common/home');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/faq')
			
		);
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/faq_list.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/faq_list.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('information/faq_list.tpl', $data));
			}
			
		}else{

			$this->document->setTitle($this->language->get('heading_title'));

			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'fq.sort_order';
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
		
			$data['heading_title'] = $this->language->get('heading_title');
			$data['description'] = '';

			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_faq'] = $this->language->get('text_faq');		
			 
			$data['button_continue'] = $this->language->get('button_continue');
			
			$data['faqs'] = array();
			
			$data = array(
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $this->config->get('config_catalog_limit'),
				'limit'                  => $this->config->get('config_catalog_limit')
			);
					
			$product_total = $this->model_catalog_faq->getTotalFaqs($data);
								
			$results = $this->model_catalog_faq->getFaqs($data);
					
			foreach ($results as $result) {
				
				$data['faqs'][] = array(
					'faq_id'  => $result['faq_id'],
					'title'       => $result['title'],
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
				);
			}
						
	
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
					
			$url = '';
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('information/faq', $url . '&page={page}');
			
			$data['pagination'] = $pagination->render();
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['continue'] = $this->url->link('common/home');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/faq_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/faq_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('information/faq_info.tpl', $data));
			}
				
			
		
		}
  	}

	public function faq() {
    	
		$this->language->load('information/faq');

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/faq.css');
		}
		
		$this->load->model('catalog/faqcategory');
		
		$this->load->model('catalog/faq');
		
		if (isset($this->request->get['faqcategory_id'])) {
			$faqcategory_id = $this->request->get['faqcategory_id'];
		} else {
			$faqcategory_id = 0;
		} 
										
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'fq.sort_order';
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
			
		
		
		$faq_category_info = $this->model_catalog_faqcategory->getFaqCategory($faqcategory_id);
	
		if ($faq_category_info) {

			$this->document->setTitle($faq_category_info['title']);
			
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
		   			
			
		
			
			
			$data['faqs'] = array();
			
			$data = array(
				'faqcategory_id'		=> $faqcategory_id, 
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $this->config->get('config_catalog_limit'),
				'limit'                  => $this->config->get('config_catalog_limit')
			);
					
			$product_total = $this->model_catalog_faq->getTotalFaqs($data);
								
			$results = $this->model_catalog_faq->getFaqs($data);
					
			foreach ($results as $result) {
				
				$data['faqs'][] = array(
					'faq_id'  => $result['faq_id'],
					'title'       => $result['title'],
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
				);
			}
						
	
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
					
			$url = '';
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('information/faq/faq', 'faqcategory_id=' . $this->request->get['faqcategory_id'] . $url . '&page={page}');
			
			$data['pagination'] = $pagination->render();
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['heading_title'] = $faq_category_info['title'];
			$data['description'] = html_entity_decode($faq_category_info['description'], ENT_QUOTES, 'UTF-8');

			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_faq'] = $this->language->get('text_faq');		
			 
			$data['button_continue'] = $this->language->get('button_continue');
			$data['continue'] = $this->url->link('common/home');
			$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		
   		);
   		
		$data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_faq'),
			'href'      => $this->url->link('information/faq'),
      		
   		);
		$data['breadcrumbs'][] = array(
       			'text'      => $faq_category_info['title'],
				'href'      => $this->url->link('information/faq/faq', 'faqcategory_id=' . $this->request->get['faqcategory_id'] . $url)
      			
   			);
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/faq_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/faq_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('information/faq_info.tpl', $data));
			}
			
			} else {
			$url = '';
			
			if (isset($this->request->get['faqcategory_id'])) {
				$url .= '&faqcategory_id=' . $this->request->get['faqcategory_id'];
			}
									
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('information/faq', $url),
				
			);
				
			$this->document->setTitle($this->language->get('text_error'));

      		$data['heading_title'] = $this->language->get('text_error');

      		$data['text_error'] = $this->language->get('text_error');

      		$data['button_continue'] = $this->language->get('button_continue');

      		$data['continue'] = $this->url->link('common/home');

					
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
			
					
			
		}
  	}
}
?>