<?php
namespace GQL\Resolvers;

trait InformationTypeResolver {
    
    public function InformationType_language ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/language');
        return $ctx->model_localisation_language->getLanguage ($root['language_id']);
    }

    public function InformationType_store ($root, $args, &$ctx) {
        $ctx->load->model ('setting/store');
        $stores = $ctx->model_setting_store->getStores ();
        foreach ($stores as $store) {
            if ($store['id'] == $args['id']) {
                return $store;
            }
        }
        return [
            'store_id' => $root['store_id']
        ];
    }

}
?>