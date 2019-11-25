<?php
namespace GQL\Resolvers;

trait CategoryTypeResolver {
    public function CategoryType_description($root, $args, &$ctx){
        return strip_tags(html_entity_decode($root['description']));
    }
    
    public function CategoryType_language ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/language');
        return $ctx->model_localisation_language->getLanguage ($root['language_id']);
    }

    public function CategoryType_store ($root, $args, &$ctx) {
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

    public function CategoryType_parent ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/category');
        $ctx->load->model ('catalog/product');

        $cat = $ctx->model_catalog_category->getCategory ($root['parent_id']);
        $cat['products_count'] = $ctx->model_catalog_product->getTotalProducts ([
            'filter_category_id' => $cat['category_id']
        ]);
        return $cat;
    }

    public function CategoryType_products ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        $ctx->load->model ('catalog/product');

        $data['start'] = isset ($args['start']) ? $args['start'] : 0;
        $data['limit'] = isset ($args['limit']) ? $args['limit'] : 20;
        // to avoid warnings
        $data['sort'] = isset ($args['sort']) ? $args['sort'] : null;
        $data['order'] = isset ($args['order']) ? $args['order'] : null;
        $data['filter_category_id'] = $root['category_id'];

        return $ctx->model_catalog_product->getProducts ($data);
    }

    public function CategoryType_categories ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/category');
        $cats = $ctx->model_catalog_category->getCategories ($root['category_id']);
        foreach ($cats as &$cat) {
            $cat['products_count'] = $ctx->model_catalog_product->getTotalProducts ([
                'filter_category_id' => $cat['category_id'],
                'filter_sub_category' => true
            ]);
        }
        return $cats;
    }

    public function CategoryType_filters ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/category');
        return $ctx->model_catalog_category->getCategoryFilters ($root['category_id']);
    }

}
?>