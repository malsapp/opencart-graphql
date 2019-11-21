<?php
namespace GQL\Resolvers;

trait OrderTypeResolver {
    
    public function OrderType_store ($root, $args, &$ctx) {
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

    public function OrderType_paymentZone ($root, $args, &$ctx) {
        return [
            'zone_id'   => $root['payment_zone_id'],
            'name'      => $root['payment_zone'],
            'code'      => $root['payment_zone_code']
        ];
    }

    public function OrderType_paymentCountry ($root, $args, &$ctx) {
        return [
            'country_id' => $root['payment_country_id'],
            'name'       => $root['payment_country'],
            'iso_code_2' => $root['payment_iso_code_2'],
            'iso_code_3' => $root['payment_iso_code_3'],
            'address_format' => $root['payment_address_format']
        ];
    }

    public function OrderType_shippingZone ($root, $args, &$ctx) {
        return [
            'zone_id'   => $root['shipping_zone_id'],
            'name'      => $root['shipping_zone'],
            'code'      => $root['shipping_zone_code']
        ];
    }

    public function OrderType_shippingCountry ($root, $args, &$ctx) {
        return [
            'country_id' => $root['shipping_country_id'],
            'name'       => $root['shipping_country'],
            'iso_code_2' => $root['shipping_iso_code_2'],
            'iso_code_3' => $root['shipping_iso_code_3'],
            'address_format' => $root['shipping_address_format']
        ];
    }

    public function OrderType_language ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/language');
        return $ctx->model_localisation_language->getLanguage ($root['language_id']);
    }

    public function OrderType_currency ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/currency');
        return $ctx->model_localisation_currency->getCurrencyByCode ($root['currency_code']);
    }

    public function OrderType_products ($root, $args, &$ctx) {
        $ctx->load->model ('account/order');
        return $ctx->model_account_order->getOrderProducts ($root['order_id']);
    }

}
?>