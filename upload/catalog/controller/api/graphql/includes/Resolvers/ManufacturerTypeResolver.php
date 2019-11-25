<?php
namespace GQL\Resolvers;

trait ManufacturerTypeResolver {
    
    public function ManufacturerType_store ($root, $args, &$ctx) {
        $ctx->load->model ('setting/store');
        $stores = $ctx->model_setting_store->getStores ();
        foreach ($stores as $store) {
            if ($store['id'] == $root['store_id']) {
                return $store;
            }
        }
        return [
            'store_id' => $root['store_id']
        ];
    }

    public function ManufacturerType_products ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        $filter = [
            'filter_manufacturer_id' => $root['manufacturer_id']
        ];
        $products = $ctx->model_catalog_product->getProducts ($filter);
        foreach ($products as &$product) {
            $product['wishlist'] = in_array($product['product_id'], $ctx->session->data['wishlist']);
        }
        return $products;
    }

}
?>