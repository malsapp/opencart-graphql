<?php
namespace GQL\Resolvers;

trait CustomerTypeResolver {
    
    public function CustomerType_customer_group ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer_group');
        return $ctx->model_account_customer_group->getCustomerGroup ($root['customer_group_id']);
    }

    public function CustomerType_store ($root, $args, &$ctx) {
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

    public function CustomerType_language ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/language');
        return $ctx->model_localisation_language->getLanguage ($root['language_id']);
    }

}
?>