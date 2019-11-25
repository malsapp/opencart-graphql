<?php

namespace GQL\Helpers;

class Cart
{
    public static function validateCart(&$ctx, $args)
    {
        $ctx->load->language('checkout/cart');

        //Get products.
        $products = array();
        if (isset($args['input']['products'])) $products = $args['input']['products'];
        if ($ctx->cart->hasProducts()) $products = $ctx->cart->getProducts();
        if (empty($products)) throw new \Exception($ctx->language->get('text_empty'));

        foreach ($products as $product)
            if (!$product['stock']) throw new \Exception($ctx->language->get('error_stock'));

        if (!$ctx->customer->isLogged()) throw new \Exception($ctx->language->get('text_login'));

        foreach ($products as $product) {
            $p_total = $product['quantity'];
            foreach ($products as $product2) {
                if ($product['product_id'] == $product2['product_id'])
                    $p_total += $product2['quantity'];
            }

            if ($p_total < $product['minimum']) {
                throw new \Exception(sprintf($ctx->language->get('error_minimum'), $product['name'], $product['minimum']));
            }
        }
    }

    public static function getCartType(&$ctx)
    {
        $total_data = self::getTotals($ctx);
        $totals = $total_data['totals'];
        $res = array();
        foreach ($totals as $total) {
            $res[$total['code']] = $total['value'];
        }
        if (!$res['coupon']) $res['coupon'] = 0;
        if (!$res['tax']) $res['tax'] = 0;

        $coupon = isset($ctx->session->data['coupon']) ? $ctx->session->data['coupon'] : '';
        $taxes = self::calculate_taxes($ctx->cart->getTaxes());

        $productsArray = [];
        $ctx->load->model('catalog/product');

        foreach ($ctx->cart->getProducts() as $product) {

            $oc_product = $ctx->model_catalog_product->getProduct($product['product_id']);
            $product['stock'] = $oc_product['quantity'];

            $productsArray[] = $product;
        }


        return [
            'items'     => $productsArray,
            'weight'    => $ctx->cart->getWeight(),
            'subtotal'  => $res['sub_total'],
            'tax'       => $taxes,
            'total'     => $total_data['total'],
            'totals'    => $totals,
            'count'     => $ctx->cart->countProducts(),
            'coupon_code'   => $coupon,
            'coupon_discount' => $res['coupon'],
            'has_stock' => $ctx->cart->hasStock(),
            'has_shipping'  => $ctx->cart->hasShipping(),
            'has_download'  => $ctx->cart->hasDownload()
        ];
    }

    public static function calculate_taxes($taxes_array)
    {
        $sum =0;
        $sum = array_reduce($taxes_array, function ($total, $tax) {
            return $total + $tax;
        });

        return $sum;
    }

    public static function addItemToCart(&$ctx, &$args)
    {
        $ctx->load->model('catalog/product');
        $ctx->load->language('checkout/cart');

        $productId = $args['input']['product_id'];
        $quantity = isset($args['input']['quantity']) ? $args['input']['quantity'] : 1;
        $options = array();
        $recurring_id = 0;
        if (isset($args['input']['recurring_id'])) {
            $recurring_id = $args['input']['recurring_id'];
        }

        foreach ($args['input']['options'] as $option) {
            $options[$option['option_id']] = json_decode($option['value']);
        }

        $product_options = $ctx->model_catalog_product->getProductOptions($productId);
        foreach ($product_options as $product_option) {
            if ($product_option['required'] && empty($options[$product_option['product_option_id']])) {
                throw new \Exception(sprintf($ctx->language->get('error_required'), $product_option['name']));
            }
        }

        $productInfo = $ctx->model_catalog_product->getProduct($productId);

        if (!isset($productInfo)) throw new \Exception();

        if ($quantity < $productInfo['minimum'])
            $quantity = $productInfo['minimum'];

        $ctx->cart->add($productId, $quantity, $options, $recurring_id);

        return true;
    }

    public static function getTotals(&$ctx)
    {
        $ctx->load->model('setting/extension');

        $totals = array();
        $taxes = $ctx->cart->getTaxes();
        $total = 0;

        // Because __call can not keep var references so we put them into an array.
        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $sort_order = array();

        $results = $ctx->model_setting_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $ctx->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($ctx->config->get($result['code'] . '_status')) {
                $ctx->load->model('extension/total/' . $result['code']);
                // We have to put the totals in an array so that they pass by reference.
                $ctx->{'model_extension_total_' . $result['code']}->getTotal($total_data);
            }
        }

        $sort_order = array();

        foreach ($totals as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $totals);

        return $total_data;
    }


    public static function getShippingMethods(&$ctx, $type = 0)
    {
        $ctx->load->language('checkout/checkout');

        if (isset($ctx->session->data['shipping_address'])) {
            $ctx->session->data['shipping_methods'] = self::getMethods($ctx, 'shipping');
        }

        return $ctx->session->data['shipping_methods'];
    }

    public static function getPaymentMethods(&$ctx)
    {
        $ctx->load->language('checkout/checkout');

        if (isset($ctx->session->data['shipping_address'])) {
            $ctx->session->data['shipping_methods'] = self::getMethods($ctx, 'payment');
        }

        return $ctx->session->data['shipping_methods'];
    }

    public static function getMethods(&$ctx, $methodType)
    {
        // Methods
        $method_data = array();
        $ctx->load->model('setting/extension');
        $results = $ctx->model_setting_extension->getExtensions($methodType);
        foreach ($results as $result) {
            if ($ctx->config->get($result['code'] . '_status')) {
                $ctx->load->model("extension/$methodType/" . $result['code']);

                if ($methodType == 'shipping') {
                    $quote = $ctx->{"model_extension_{$methodType}_" . $result['code']}->getQuote($ctx->session->data['shipping_address']);
                } elseif ($methodType == 'payment') {
                    $totals = self::getTotals($ctx);
                    $quote = $ctx->{"model_extension_{$methodType}_" . $result['code']}->getMethod($ctx->session->data['shipping_address'], $totals['total']);
                }

                if ($quote) {
                    if ($methodType == 'shipping') {
                        $method_data[$result['code']] = array(
                            'title'      => $quote['title'],
                            'quote'      => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error'      => isset($quote['error']) ? $quote['error'] : ''
                        );
                    } elseif ($methodType == 'payment') {
                        $ctx->load->model('setting/setting');
                        $settings = $ctx->model_setting_setting->getSetting($quote['code']);
                        $detailes = array();

                        if ($quote['code'] == 'bank_transfer') {
                            $keys = array_keys($settings);
                            $pattern = "payment_bank_transfer_bank";
                            $result = preg_grep("/{$pattern}/", $keys);

                            foreach ($result as $index => $associative) {
                                $bank_detailes = explode("\r\n", $settings[$associative]);
                                $_bank = [
                                    'bank_name' => $bank_detailes[0],
                                    'account_name' => $bank_detailes[1],
                                    'account_number' => $bank_detailes[2],
                                    'iban' => $bank_detailes[3],
                                    'bic' => ''
                                ];
                                $detailes[] = $_bank;
                            }
                        }
                        $method_data[$result['code']] = array(
                            'title'      => $quote['title'],
                            'quote'      => [
                                'code' => $quote['code'],
                                'details' => json_encode($detailes, JSON_UNESCAPED_UNICODE)
                            ],
                            'sort_order' => $quote['sort_order'],
                            'error'      => isset($quote['error']) ? $quote['error'] : ''
                        );
                    }
                }
            }
        }

        $sort_order = array();
        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }
        array_multisort($sort_order, SORT_ASC, $method_data);
        return $method_data;
    }

    public static function getOrderStatuses(&$ctx)
    {
        $order_status_data = $ctx->cache->get('order_status.' . (int) $ctx->config->get('config_language_id'));

        if (!$order_status_data) {
            $query = $ctx->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int) $ctx->config->get('config_language_id') . "' ORDER BY name");

            $order_status_data = $query->rows;

            $ctx->cache->set('order_status.' . (int) $ctx->config->get('config_language_id'), $order_status_data);
        }

        return $order_status_data;
    }
}
