<?php
namespace GQL\Resolvers;

trait FaqTypeResolver {
    
    public function FaqType_faqCategory ($root, $args, $ctx) {
        $ctx->load->model ('catalog/faqcategory');
        return $ctx->model_catalog_faqcategory->getFaqCategory ($root['faqcategory_id']);
	 }
}
?>