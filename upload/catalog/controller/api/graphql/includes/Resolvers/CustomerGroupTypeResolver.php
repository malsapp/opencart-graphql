<?php
namespace GQL\Resolvers;

trait CustomerGroupTypeResolver {
    
    public function CustomerGroupType_language ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/language');
        return $ctx->model_localisation_language->getLanguage ($root['language_id']);
    }

}
?>