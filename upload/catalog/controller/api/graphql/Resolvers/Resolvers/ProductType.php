<?php
namespace GQL;

trait ProductTypeResolver {
    
    public function ProductType_manufacturer ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/manufacturer');
        $manufacturer = $ctx->model_catalog_manufacturer->getManufacturer ($root['manufacturer_id']);
        return ($manufacturer) ? $manufacturer : null;
    }

    public function ProductType_in_stock ($root, $args, &$ctx) {
        if (!isset ($root['minimum']) || !isset ($root['quantity'])) return null;
        return $root['quantity'] >= $root['minimum'];
    }

    public function ProductType_categories ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/category');
        $ctx->load->model ('catalog/product');
        $cats = $ctx->model_catalog_product->getCategories ($root['product_id']);
        $res = [];
        foreach ($cats as $cat) {
            $res[] = $ctx->model_catalog_category->getCategory ($cat['category_id']);
        }
        return $res;
    }

    public function ProductType_attributes ($root, $args, &$ctx) { 
        $ctx->load->model ('catalog/product');
        $res = $ctx->model_catalog_product->getProductAttributes ($root['product_id']);
        if (count ($res) == 0) return null;
        return $res;
    }

    public function ProductType_options ($root, $args, &$ctx) { 
        $ctx->load->model ('catalog/product');
        return $ctx->model_catalog_product->getProductOptions ($root['product_id']);
    }

    public function ProductType_discounts ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        return $ctx->model_catalog_product->getProductDiscounts ($root['product_id']);
    }

    public function ProductType_images ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        return $ctx->model_catalog_product->getProductImages ($root['product_id']);
    }

    public function ProductType_wishlist ($root, $args, &$ctx) {
        $ctx->load->model ('account/wishlist');
        if (!isset ($ctx->session->data['wishlist'])) {
            $wishlist = $ctx->model_account_wishlist->getWishlist ();
            if (isset ($wishlist)) {
                foreach ($wishlist as &$item) {
                    $ctx->session->data['wishlist'][] = $item['product_id'];
                }
                $ctx->session->data['wishlist'] = array_unique($ctx->session->data['wishlist']);
            } else {
                $ctx->session->data['wishlist'] = array();
            }
        }
        return in_array ($root['product_id'], $ctx->session->data['wishlist']);
    }

    public function ProductType_formatted ($number, $ctx) {
        return $ctx->currency->format ($number);
    }
}
?>