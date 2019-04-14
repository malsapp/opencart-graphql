<?php
namespace GQL;

trait FaqCategoryTypeResolver {
    
    public function FaqCategoryType_faqs ($root, $args, $ctx) { 
	    $ctx->load->model ('catalog/faq');
        $data['faqcategory_id']=$root['faqcategory_id'];
        return $ctx->model_catalog_faq->getFaqs ($data);
	}

}
?>