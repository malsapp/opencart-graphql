<?php
namespace GQL;

function validateLogin ($args, &$ctx) {
    $errors = array();

    $ctx->load->language('account/login');

    // Check how many login attempts have been made.
    $login_info = $ctx->model_account_customer->getLoginAttempts($args['email']);

    if ($login_info && ($login_info['total'] >= $ctx->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
        throw new \Exception ($ctx->language->get('error_attempts'));
    }

    // Check if customer has been approved.
    $customer_info = $ctx->model_account_customer->getCustomerByEmail($args['email']);

    if ($customer_info && !$customer_info['status']) {
        throw new \Exception ($ctx->language->get('error_approved'));
    }

    if (!$errors) {
        if (!$ctx->customer->login($args['email'], $args['password'])) {
            throw new \Exception ($ctx->language->get('error_login'));

            $ctx->model_account_customer->addLoginAttempt($args['email']);
        } else {
            $ctx->model_account_customer->deleteLoginAttempts($args['email']);
        }
    }
}

function validateSignup ($args, &$ctx) {
    $errors = array();

    $ctx->load->language('account/register');

    if ((utf8_strlen(trim($args['firstname'])) < 1) || (utf8_strlen(trim($args['firstname'])) > 32)) {
        throw new \Exception ($ctx->language->get('error_firstname'));
    }

    if ((utf8_strlen(trim($args['lastname'])) < 1) || (utf8_strlen(trim($args['lastname'])) > 32)) {
        throw new \Exception ($ctx->language->get('error_lastname'));
    }

    if ((utf8_strlen($args['email']) > 96) || !filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
        throw new \Exception ($ctx->language->get('error_email'));
    }

    if ($ctx->model_account_customer->getTotalCustomersByEmail($args['email'])) {
        throw new \Exception ($ctx->language->get('error_exists'));
    }

    if ((utf8_strlen($args['telephone']) < 3) || (utf8_strlen($args['telephone']) > 32)) {
        throw new \Exception ($ctx->language->get('error_telephone'));
    }

    // if ((utf8_strlen(trim($args['address_1'])) < 3) || (utf8_strlen(trim($args['address_1'])) > 128)) {
    //     throw new \Exception ($ctx->language->get('error_address_1'));
    // }

    // if ((utf8_strlen(trim($args['city'])) < 2) || (utf8_strlen(trim($args['city'])) > 128)) {
    //     throw new \Exception ($ctx->language->get('error_city'));
    // }

    // $ctx->load->model('localisation/country');

    // $country_info = $ctx->model_localisation_country->getCountry($args['country_id']);

    // if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($args['postcode'])) < 2 || utf8_strlen(trim($args['postcode'])) > 10)) {
    //     throw new \Exception ($ctx->language->get('error_postcode'));
    // }

    // if ($args['country_id'] == '') {
    //     throw new \Exception ($ctx->language->get('error_country'));
    // }

    // if (!isset($args['zone_id']) || $args['zone_id'] == '' || !is_numeric($args['zone_id'])) {
    //     throw new \Exception ($ctx->language->get('error_zone'));
    // }

    // Customer Group
    if (isset($args['customer_group_id']) && is_array($ctx->config->get('config_customer_group_display')) && in_array($args['customer_group_id'], $ctx->config->get('config_customer_group_display'))) {
        $customer_group_id = $args['customer_group_id'];
    } else {
        $customer_group_id = $ctx->config->get('config_customer_group_id');
    }

    // Custom field validation
    $ctx->load->model('account/custom_field');

    $custom_fields = $ctx->model_account_custom_field->getCustomFields($customer_group_id);

    foreach ($custom_fields as $custom_field) {
        if ($custom_field['required'] && empty($args['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
            throw new \Exception (sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
        } elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($args['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
            throw new \Exception (sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
        }
    }

    if ((utf8_strlen($args['password']) < 4) || (utf8_strlen($args['password']) > 20)) {
        throw new \Exception ($ctx->language->get('error_password'));
    }

    if ($args['confirm'] != $args['password']) {
        throw new \Exception ($ctx->language->get('error_confirm'));
    }

    // Agree to terms
    if ($ctx->config->get('config_account_id')) {
        $ctx->load->model('catalog/information');

        $information_info = $ctx->model_catalog_information->getInformation($ctx->config->get('config_account_id'));

        if ($information_info && !$args['agree']) {
            throw new \Exception (sprintf($ctx->language->get('error_agree'), $information_info['title']));
        }
    }
}

function validatePassword(&$ctx, $args) {
    $errors = array ();

    $ctx->load->language('account/password');

    if ((utf8_strlen($args['password']) < 4) || (utf8_strlen($args['password']) > 20)) {
        throw new \Exception ($ctx->language->get('error_password'));
    }

    if ($args['confirm'] != $args['password']) {
        throw new \Exception ($ctx->language->get('error_confirm'));
    }

    return $errors;
}

function validateCustomerEdit(&$ctx, $args) {
    $errors = array ();

    $ctx->load->language ('account/edit');
    $ctx->load->model ('account/customer');

    if ((utf8_strlen(trim($args['firstname'])) < 1) || (utf8_strlen(trim($args['firstname'])) > 32)) {
        throw new \Exception ($ctx->language->get('error_firstname'));
    }

    if ((utf8_strlen(trim($args['lastname'])) < 1) || (utf8_strlen(trim($args['lastname'])) > 32)) {
        throw new \Exception ($ctx->language->get('error_lastname'));
    }

    if ((utf8_strlen($args['email']) > 96) || !filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
        throw new \Exception ($ctx->language->get('error_email'));
    }

    if (($ctx->customer->getEmail() != $args['email']) && $ctx->model_account_customer->getTotalCustomersByEmail($args['email'])) {
        throw new \Exception ($ctx->language->get('error_exists'));
    }

    if ((utf8_strlen($args['telephone']) < 3) || (utf8_strlen($args['telephone']) > 32)) {
        throw new \Exception ($ctx->language->get('error_telephone'));
    }

    // Custom field validation
    $ctx->load->model('account/custom_field');

    $custom_fields = $ctx->model_account_custom_field->getCustomFields($ctx->config->get('config_customer_group_id'));

    foreach ($custom_fields as $custom_field) {
        if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($args['custom_field'][$custom_field['custom_field_id']])) {
            throw new \Exception (sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
        } elseif (($custom_field['location'] == 'account') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($args['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
            throw new \Exception (sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
        }
    }

    return $errors;
}

function validateAddress (&$ctx, $args) {
    $ctx->load->language ('account/address');

    if ((utf8_strlen(trim($args['firstname'])) < 1) || (utf8_strlen(trim($args['firstname'])) > 32)) {
        throw new \Exception ($ctx->language->get('error_firstname'));
    }

    if ((utf8_strlen(trim($args['lastname'])) < 1) || (utf8_strlen(trim($args['lastname'])) > 32)) {
        throw new \Exception ($ctx->language->get('error_lastname'));
    }

    if ((utf8_strlen(trim($args['address_1'])) < 3) || (utf8_strlen(trim($args['address_1'])) > 128)) {
        throw new \Exception ($ctx->language->get('error_address_1'));
    }

    if ((utf8_strlen(trim($args['city'])) < 2) || (utf8_strlen(trim($args['city'])) > 128)) {
        throw new \Exception ($ctx->language->get('error_city'));
    }

    $ctx->load->model('localisation/country');

    $country_info = $ctx->model_localisation_country->getCountry($args['country_id']);

    if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($args['postcode'])) < 2 || utf8_strlen(trim($args['postcode'])) > 10)) {
        throw new \Exception ($ctx->language->get('error_postcode'));
    }

    if ($args['country_id'] == '' || !is_numeric($args['country_id'])) {
        throw new \Exception ($ctx->language->get('error_country'));
    }

    if (!isset($args['zone_id']) || $args['zone_id'] == '' || !is_numeric($args['zone_id'])) {
        throw new \Exception ($ctx->language->get('error_zone'));
    }

    // Custom field validation
    $ctx->load->model('account/custom_field');

    $custom_fields = $ctx->model_account_custom_field->getCustomFields($ctx->config->get('config_customer_group_id'));

    foreach ($custom_fields as $custom_field) {
        if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($args['custom_field'][$custom_field['custom_field_id']])) {
            throw new \Exception (sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
        } elseif (($custom_field['location'] == 'address') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($args['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
            throw new \Exception (sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
        }
    }
}

function validateCart (&$ctx, $args) {
    $ctx->load->language ('checkout/cart');

    //Get products.
    $products = array ();
    if (isset ($args['input']['products'])) $products = $args['input']['products'];
    if ($ctx->cart->hasProducts()) $products = $ctx->cart->getProducts ();
    if (empty ($products)) throw new \Exception ($ctx->language->get ('text_empty'));

    foreach ($products as $product)
        if (!$product['stock']) throw new \Exception ($ctx->language->get ('error_stock'));

    if (!$ctx->customer->isLogged ()) throw new \Exception ($ctx->language->get ('text_login'));

    foreach ($products as $product) {
        $p_total = $product['quantity'];
        foreach ($products as $product2) {
            if ($product['product_id'] == $product2['product_id'])
                $p_total += $product2['quantity'];
        }

        if ($p_total < $product['minimum']) {
            throw new \Exception (sprintf($ctx->language->get('error_minimum'), $product['name'], $product['minimum']));
        }
    }
}

function getCartType (&$ctx) {
    $total_data = getTotals ($ctx);
    $totals = $total_data['totals'];
    $res = array();
    foreach ($totals as $total) {
        $res[$total['code']] = $total['value'];
    }
    if (!$res['coupon']) $res['coupon'] = 0;
    if (!$res['tax']) $res['tax'] = 0;

    $coupon = isset($ctx->session->data['coupon']) ? $ctx->session->data['coupon'] : '';
    $taxes = calculate_taxes($ctx->cart->getTaxes());

    $productsArray = [];
    $ctx->load->model ('catalog/product');

    foreach($ctx->cart->getProducts () as $product) {

        $oc_product = $ctx->model_catalog_product->getProduct ($product['product_id']);
        $product['stock'] = $oc_product['quantity'];

        $productsArray[] = $product;
    }


    return [
        'items'     => $productsArray,
        'weight'    => $ctx->cart->getWeight (),
        'subtotal'  => $res['sub_total'],
        'tax'       => $taxes,
        'total'     => $total_data['total'],
        'totals'    => $totals,
        'count'     => $ctx->cart->countProducts (),
        'coupon_code'   => $coupon,
        'coupon_discount' => $res['coupon'],
        'has_stock' => $ctx->cart->hasStock (),
        'has_shipping'  => $ctx->cart->hasShipping (),
        'has_download'  => $ctx->cart->hasDownload ()
    ];
}

function calculate_taxes($taxes_array) {
    $total = array_reduce($taxes_array, function($count, $tax) {
        return $total + $tax;
    });

    return isset($total) ? $total : 0;
}

function addItemToCart (&$ctx, &$args) {
    $ctx->load->model ('catalog/product');
    $ctx->load->language('checkout/cart');

    $productId = $args['input']['product_id'];
    $quantity = isset ($args['input']['quantity'])?$args['input']['quantity']:1;
    $options = array ();
    $recurring_id = 0;
    if (isset ($args['input']['recurring_id'])) {
        $recurring_id = $args['input']['recurring_id'];
    }

    foreach ($args['input']['options'] as $option) {
        $options[$option['option_id']] = json_decode ($option['value']);
    }

    $product_options = $ctx->model_catalog_product->getProductOptions($productId);
    foreach ($product_options as $product_option) {
        if ($product_option['required'] && empty($options[$product_option['product_option_id']])) {
            throw new \Exception (sprintf($ctx->language->get('error_required'), $product_option['name']));
        }
    }

    $productInfo = $ctx->model_catalog_product->getProduct ($productId);

    if (!isset ($productInfo)) throw new \Exception ();

    if ($quantity < $productInfo['minimum'])
        $quantity = $productInfo['minimum'];

    $ctx->cart->add ($productId, $quantity, $options, $recurring_id);

    return true;
}

function getAddress ($ctx, $addressType = 'payment_address') {
    if ($ctx->customer->isLogged ()) {
        if (isset ($ctx->session->data[$addressType]['address_id'])) {
            $ctx->load->model ('account/address');
            return $ctx->model_account_address->getAddress ($ctx->session->data[$addressType]['address_id']);
        }
    }

    if (isset ($ctx->session->data[$addressType])) {
        return $ctx->session->data[$addressType];
    }
}

function setAddress ($ctx, $args, $addressType = 'payment_address') {
    if ($ctx->customer->isLogged ()) {
        if (isset ($args['address_id'])) {
            $ctx->session->data[$addressType] = array('address_id' => $args['address_id']);
            return true;
        }

        validateAddress ($ctx, $args['input']);
        $ctx->load->model ('account/address');
        $ctx->model_account_address->addAddress ($args['input']);
        if (null !== $ctx->db->getLastId ()) {
            $ctx->session->data[$addressType] = array('address_id' => $ctx->db->getLastId ());
            return true;
        } else {
            return false;
        }
    }

    if (isset ($args['address_id'])) return false;

    $ctx->session->data['guest'][$addressType] = $args['input'];
    $ctx->session->data[$addressType] = $args['input'];
    return true;
}

function getSession (&$ctx, $session_id) {
    if (isset ($session_id) && !empty ($session_id)) {
        session_write_close ();
        session_id ($session_id);
        session_start ();
    } else {
        session_regenerate_id (true);
        $session_id = session_id();
    }

    ini_set ('session.gc_maxlifetime', 999999999);

    global $reg;
    $reg = getRegistry ($ctx);

    if (!class_exists ('GQL\Sess')) {
        class Sess extends \Session {
        public function get($var) {
                global $reg;
                return $reg->get($var);
            }
        }
    }

    $session = new Sess('db',$reg);
    $ctx->session = $session;
    // $ctx->session = new \Session('db',$reg);
    $ctx->session->__destroy ('default');
    $ctx->session->start ($session_id);
    // $ctx->session->start ('gql', $session_id);

    if (!isset ($ctx->session->data)) $ctx->session->data = array();

    // Language
    if (!isset ($ctx->session->data['language'])) {
        $ctx->session->data['language'] = $ctx->config->get('config_language');
    }

    $language = new \Language($ctx->session->data['language']);
    $language->load($ctx->session->data['language']);

    $ctx->load->model('localisation/language');
    $langs = $ctx->model_localisation_language->getLanguages ();
    foreach ($langs as $lang) {
        if ($lang['code'] == $ctx->session->data['language']) {
            $ctx->config->set('config_language_id', $lang['language_id']);
        }
    }
    $ctx->language = $language;


    // currency
    if (!isset ($ctx->session->data['currency'])) {
        $ctx->session->data['currency'] = $ctx->config->get('config_currency');
    }

    $ctx->customer = new \Cart\Customer ($reg);
    $ctx->cart = new \Cart\Cart ($reg);
    $reg->set ('currency', new \Cart\Currency($reg));

    if (!$ctx->customer->isLogged () && !isset ($ctx->session->data['guest'])) {
        $ctx->session->data['guest'] = array ();
    }

    return session_id ();
}

function getRegistry ($ctx) {
    if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
        $myClassReflection = new \ReflectionClass(get_class($ctx));
        $secret = $myClassReflection->getProperty('registry');
        $secret->setAccessible(true);
        return $secret->getValue($ctx);
    } else {
        $propname="\0ControllerGraphqlUsage\0registry";
        $a = (array) $ctx;
        return $a[$propname];
    }
}

function getTotals (&$ctx) {
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
        //if ($ctx->config->get($result['code'] . '_status')) {
        if ($ctx->config->get('total_' . $result['code'] . '_status')) {
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

function getShippingMethods (&$ctx) {
    $ctx->load->language('checkout/checkout');

    if (isset($ctx->session->data['shipping_address'])) {
        // Shipping Methods
        $method_data = array();

        // $ctx->load->model('extension/extension');
        $ctx->load->model('setting/extension');

        // $results = $ctx->model_extension_extension->getExtensions('shipping');
        $results = $ctx->model_setting_extension->getExtensions('shipping');

        foreach ($results as $result) {
            //if ($ctx->config->get($result['code'] . '_status')) {
            if ($ctx->config->get('shipping_' .$result['code'] . '_status')) {
                $ctx->load->model('extension/shipping/' . $result['code']);

                $quote = $ctx->{'model_extension_shipping_' . $result['code']}->getQuote($ctx->session->data['shipping_address']);

                if ($quote) {
                    $method_data[$result['code']] = array(
                        'title'      => $quote['title'],
                        'quote'      => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error'      => $quote['error']
                    );
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $ctx->session->data['shipping_methods'] = $method_data;
    }

    return $ctx->session->data['shipping_methods'];
}

function forgottenMail (&$ctx, $args) {
    $ctx->load->model('account/customer');
    $ctx->load->language('mail/forgotten');

    $code = token(40);

    $ctx->model_account_customer->editCode($args['email'], $code);

    $subject = sprintf($ctx->language->get('text_subject'), html_entity_decode($ctx->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

    $message  = sprintf($ctx->language->get('text_greeting'), html_entity_decode($ctx->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
    $message .= $ctx->language->get('text_change') . "\n\n";
    $message .= $ctx->url->link('account/reset', 'code=' . $code, true) . "\n\n";
    $message .= sprintf($ctx->language->get('text_ip'), $ctx->request->server['REMOTE_ADDR']) . "\n\n";

    $mail = new \Mail();
    $mail->protocol = $ctx->config->get('config_mail_protocol');
    $mail->parameter = $ctx->config->get('config_mail_parameter');
    $mail->smtp_hostname = $ctx->config->get('config_mail_smtp_hostname');
    $mail->smtp_username = $ctx->config->get('config_mail_smtp_username');
    $mail->smtp_password = html_entity_decode($ctx->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
    $mail->smtp_port = $ctx->config->get('config_mail_smtp_port');
    $mail->smtp_timeout = $ctx->config->get('config_mail_smtp_timeout');

    $mail->setTo($args['email']);
    $mail->setFrom($ctx->config->get('config_email'));
    $mail->setSender(html_entity_decode($ctx->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
    $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
    $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
    $mail->send();

    if ($ctx->config->get('config_customer_activity')) {
        $customer_info = $ctx->model_account_customer->getCustomerByEmail($args['email']);

        if ($customer_info) {
            $ctx->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $customer_info['customer_id'],
                'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
            );

            $ctx->model_account_activity->addActivity('forgotten', $activity_data);
        }
    }
}

function getOrderStatuses (&$ctx) {
    $order_status_data = $ctx->cache->get('order_status.' . (int)$ctx->config->get('config_language_id'));

    if (!$order_status_data) {
        $query = $ctx->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$ctx->config->get('config_language_id') . "' ORDER BY name");

        $order_status_data = $query->rows;

        $ctx->cache->set('order_status.' . (int)$ctx->config->get('config_language_id'), $order_status_data);
    }

    return $order_status_data;
}

if (!function_exists ('pinfo')) {
    function pinfo() {
        ob_start();
        phpinfo();
        $data = ob_get_contents();
        ob_clean();
        return $data;
    }
}

// if (!function_exists ('variationPrice')) {
//     function variationPrice ($args, &$ctx) {
//         $product_id = $args['product_id'];
//         $options = $args['options'];

//         $price = 0;
//         if (!is_numeric ($product_id)) return null;
//         $ctx->load->model ('catalog/product');
//         $product = $ctx->model_catalog_product->getProduct ($product_id);
//         if ($product) $price = $product['price'];
//         if (is_numeric ($product['special']) && (float) $product['special']) {
//             $price = $product['special'];
//         }

//         if (!is_array ($options)) return array ('price' => $price);

//         $p_options = $ctx->model_catalog_product->getProductOptions($product_id);

//         $options_price = 0;
//         foreach ($options as $option) {
//             $option_id = $option['product_option_id'];

//             foreach ($p_options as $p_options) {
//                 if ($option['product_option_id'] == $p_options['product_option_id']) {
//                     foreach ($p_options['product_option_value'] as $p_val) {
//                         $options_price += get_option_price ($option, $p_val);
//                     }
//                 }
//             }
//         }

//         return array (
//             'price' => ($price + $options_price)
//         );
//     }

//     function get_option_price ($option, $p_val) {
//         $result = 0;
//         $option_val = json_decode($option['value'], true);
//         $option_type = $option['type'];
//         $p_val_id = $p_val['product_option_value_id'];

//         if (strtolower ($option_type) != 'checkbox') {
//             if ($p_val_id == $option_val) {
//                 if ($p_val['price_prefix'] == '+') {
//                     $result += $p_val['price'];
//                 } else {
//                     $result -= $p_val['price'];
//                 }
//             }
//         } else {
//             foreach ($option_val as $checked) {
//                 if ($p_val_id == $checked) {
//                     if ($p_val['price_prefix'] == '+') {
//                         $result += $p_val['price'];
//                     } else {
//                         $result -= $p_val['price'];
//                     }
//                 }
//             }
//         }
//         return $result;
//     }
// }

if (!function_exists ('variationData')) {
    function variationData ($args, &$ctx) {
        $product_id = $args['product_id'];
        $options = $args['options'];

        $price = 0;
        if (!is_numeric ($product_id)) return null;
        $ctx->load->model ('catalog/product');
        $product = $ctx->model_catalog_product->getProduct ($product_id);
        if ($product) $price = $product['price'];
        if (is_numeric ($product['special']) && (float) $product['special']) {
            $price = $product['special'];
        }

        if (!is_array ($options)) return array (
            'variation_id' => '',
            'description' => '',
            'price' => $price,
            'sale_price' => 0,
            'description' => '',
            'image' => '',
            'weight' => 0.0,
            'quantity' => '',
        );;

        $p_options = $ctx->model_catalog_product->getProductOptions($product_id);

        $options_price = 0;
        foreach ($options as $option) {
            $option_id = $option['product_option_id'];

            foreach ($p_options as $p_options) {
                if ($option['product_option_id'] == $p_options['product_option_id']) {
                    foreach ($p_options['product_option_value'] as $p_val) {
                        $options_price += get_option_data ($option, $p_val);
                    }
                }
            }
        }

        return array (
            'variation_id' => '',
            'description' => '',
            'price' => ($price + $options_price),
            'sale_price' => 0,
            'description' => '',
            'image' => '',
            'weight' => 0.0,
            'quantity' => '',
        );
    }

    function get_option_data ($option, $p_val) {
        $result = 0;
        $option_val = json_decode($option['value'], true);
        $option_type = $option['type'];
        $p_val_id = $p_val['product_option_value_id'];

        if (strtolower ($option_type) != 'checkbox') {
            if ($p_val_id == $option_val) {
                if ($p_val['price_prefix'] == '+') {
                    $result += $p_val['price'];
                } else {
                    $result -= $p_val['price'];
                }
            }
        } else {
            foreach ($option_val as $checked) {
                if ($p_val_id == $checked) {
                    if ($p_val['price_prefix'] == '+') {
                        $result += $p_val['price'];
                    } else {
                        $result -= $p_val['price'];
                    }
                }
            }
        }
        return $result;
    }
}

function getFormattedDate($ctx, $args){
    $dateFormat = $ctx->config->get('deliverydatetime_dateformat');
    $date=(string)$args['date'];
    $date = str_replace('/', '-',$date);
    if($dateFormat=="MM-DD-YYYY")
    {
        $datevaluechange = date_create_from_format('m-d-Y', $date);
    }
    if($dateFormat=="DD-MM-YYYY")
    {
        $datevaluechange = date_create_from_format('d-m-Y', $date);
    }
    if($dateFormat=="YYYY-MM-DD")
    {
        $datevaluechange = date_create_from_format('Y-m-d', $date);
    }
    if($dateFormat=="DD/MM/YYYY")
    {
        $datevaluechange = date_create_from_format('Y-m-d', $date);
    }
    if($dateFormat=="MM/DD/YYYY")
    {
        $datevaluechange = date_create_from_format('Y-d-m', $date);
    }
    if($dateFormat=="YYYY/MM/DD")
    {
        $datevaluechange = date_create_from_format('d-m-Y', $date);
    }
    return $datevaluechange;
}

function mobilySendMessage ($number, $msg) {
	$url = "www.mobily.ws/api/msgSend.php";
    $applicationType = "68";
    $userAccount = "gomlh";
    $passAccount = "gomlh474";
    $sender = "gomlh";
    $sender = urlencode($sender);
    $domainName = $_SERVER['SERVER_NAME'];
    $timeSend = 0;
    $dateSend = 0;
    $msgId = rand(0, 100000);
    $deleteKey = $msgId;
    $msg = convertToUnicode($msg);

	$stringToPost = "mobile=".$userAccount."&password=".$passAccount."&numbers=".$number."&sender=".$sender."&msg=".$msg."&timeSend=".$timeSend."&dateSend=".$dateSend."&applicationType=".$applicationType."&domainName=".$domainName."&msgId=".$MsgID."&deleteKey=".$deleteKey;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $stringToPost);
	$result = curl_exec($ch);

	return $result == 1;
}

function mobilySendVerificationCode ($countryCode, $number, &$ctx) {
    $code = rand(100000, 999999);
    $number = ltrim($countryCode . $number, "+0");
    $ctx->cache->set($number, $code);
    $txt = sprintf("Your activation code is: %d", $code);
    return mobilySendMessage($number, $txt);
}

function mobilyVerifyCode ($countryCode, $number, $code, &$ctx) {
    $number = ltrim($countryCode . $number, "+0");
    $cachedCode = $ctx->cache->get($number);
    if (!empty($cachedCode) && !empty($code) && $cachedCode == $code) {
        $ctx->cache->delete($number);
        return true;
    }

    return false;
}

function sendVerificationCode ($countryCode, $number) {
    if (!is_numeric ($countryCode) || !is_numeric($number)) {
        return false;
    }

    $api_key="";
    $locale="en";
    $ch = curl_init ();

    curl_setopt ($ch, CURLOPT_URL, "https://api.authy.com/protected/json/phones/verification/start?api_key={$api_key}");
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS,"via=sms&phone_number={$number}&country_code={$countryCode}&locale={$locale}");
    curl_setopt ($ch, CURLOPT_POST, 1);
    $headers = array ();
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec ($ch);
    if (curl_errno ($ch)) {
        return false;
    }
    curl_close ($ch);

    return true;
}

function verifyCode ($countryCode, $number, $verificationCode) {
    if (!is_numeric ($countryCode) || !is_numeric($number)) {
        return false;
    }

    $api_key="";
    $locale="en";
    $ch = curl_init ();

    curl_setopt ($ch, CURLOPT_URL, "https://api.authy.com/protected/json/phones/verification/check?api_key={$api_key}&phone_number={$number}&country_code={$countryCode}&verification_code={$verificationCode}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = json_decode(curl_exec($ch),true);
    if (curl_errno($ch)) {
            return false;
    }
    curl_close ($ch);
    if (isset ($result['errors'])){
        return false;
    }
    return true;
}

//This function to convert messages to our special UNICODE, use it to convert message before send it through the API
function convertToUnicode($message)
{
    $chrArray[0] = "¡";
    $unicodeArray[0] = "060C";
    $chrArray[1] = "º";
    $unicodeArray[1] = "061B";
    $chrArray[2] = "¿";
    $unicodeArray[2] = "061F";
    $chrArray[3] = "Á";
    $unicodeArray[3] = "0621";
    $chrArray[4] = "Â";
    $unicodeArray[4] = "0622";
    $chrArray[5] = "Ã";
    $unicodeArray[5] = "0623";
    $chrArray[6] = "Ä";
    $unicodeArray[6] = "0624";
    $chrArray[7] = "Å";
    $unicodeArray[7] = "0625";
    $chrArray[8] = "Æ";
    $unicodeArray[8] = "0626";
    $chrArray[9] = "Ç";
    $unicodeArray[9] = "0627";
    $chrArray[10] = "È";
    $unicodeArray[10] = "0628";
    $chrArray[11] = "É";
    $unicodeArray[11] = "0629";
    $chrArray[12] = "Ê";
    $unicodeArray[12] = "062A";
    $chrArray[13] = "Ë";
    $unicodeArray[13] = "062B";
    $chrArray[14] = "Ì";
    $unicodeArray[14] = "062C";
    $chrArray[15] = "Í";
    $unicodeArray[15] = "062D";
    $chrArray[16] = "Î";
    $unicodeArray[16] = "062E";
    $chrArray[17] = "Ï";
    $unicodeArray[17] = "062F";
    $chrArray[18] = "Ð";
    $unicodeArray[18] = "0630";
    $chrArray[19] = "Ñ";
    $unicodeArray[19] = "0631";
    $chrArray[20] = "Ò";
    $unicodeArray[20] = "0632";
    $chrArray[21] = "Ó";
    $unicodeArray[21] = "0633";
    $chrArray[22] = "Ô";
    $unicodeArray[22] = "0634";
    $chrArray[23] = "Õ";
    $unicodeArray[23] = "0635";
    $chrArray[24] = "Ö";
    $unicodeArray[24] = "0636";
    $chrArray[25] = "Ø";
    $unicodeArray[25] = "0637";
    $chrArray[26] = "Ù";
    $unicodeArray[26] = "0638";
    $chrArray[27] = "Ú";
    $unicodeArray[27] = "0639";
    $chrArray[28] = "Û";
    $unicodeArray[28] = "063A";
    $chrArray[29] = "Ý";
    $unicodeArray[29] = "0641";
    $chrArray[30] = "Þ";
    $unicodeArray[30] = "0642";
    $chrArray[31] = "ß";
    $unicodeArray[31] = "0643";
    $chrArray[32] = "á";
    $unicodeArray[32] = "0644";
    $chrArray[33] = "ã";
    $unicodeArray[33] = "0645";
    $chrArray[34] = "ä";
    $unicodeArray[34] = "0646";
    $chrArray[35] = "å";
    $unicodeArray[35] = "0647";
    $chrArray[36] = "æ";
    $unicodeArray[36] = "0648";
    $chrArray[37] = "ì";
    $unicodeArray[37] = "0649";
    $chrArray[38] = "í";
    $unicodeArray[38] = "064A";
    $chrArray[39] = "Ü";
    $unicodeArray[39] = "0640";
    $chrArray[40] = "ð";
    $unicodeArray[40] = "064B";
    $chrArray[41] = "ñ";
    $unicodeArray[41] = "064C";
    $chrArray[42] = "ò";
    $unicodeArray[42] = "064D";
    $chrArray[43] = "ó";
    $unicodeArray[43] = "064E";
    $chrArray[44] = "õ";
    $unicodeArray[44] = "064F";
    $chrArray[45] = "ö";
    $unicodeArray[45] = "0650";
    $chrArray[46] = "ø";
    $unicodeArray[46] = "0651";
    $chrArray[47] = "ú";
    $unicodeArray[47] = "0652";
    $chrArray[48] = "!";
    $unicodeArray[48] = "0021";
    $chrArray[49]='"';
    $unicodeArray[49] = "0022";
    $chrArray[50] = "#";
    $unicodeArray[50] = "0023";
    $chrArray[51] = "$";
    $unicodeArray[51] = "0024";
    $chrArray[52] = "%";
    $unicodeArray[52] = "0025";
    $chrArray[53] = "&";
    $unicodeArray[53] = "0026";
    $chrArray[54] = "'";
    $unicodeArray[54] = "0027";
    $chrArray[55] = "(";
    $unicodeArray[55] = "0028";
    $chrArray[56] = ")";
    $unicodeArray[56] = "0029";
    $chrArray[57] = "*";
    $unicodeArray[57] = "002A";
    $chrArray[58] = "+";
    $unicodeArray[58] = "002B";
    $chrArray[59] = ",";
    $unicodeArray[59] = "002C";
    $chrArray[60] = "-";
    $unicodeArray[60] = "002D";
    $chrArray[61] = ".";
    $unicodeArray[61] = "002E";
    $chrArray[62] = "/";
    $unicodeArray[62] = "002F";
    $chrArray[63] = "0";
    $unicodeArray[63] = "0030";
    $chrArray[64] = "1";
    $unicodeArray[64] = "0031";
    $chrArray[65] = "2";
    $unicodeArray[65] = "0032";
    $chrArray[66] = "3";
    $unicodeArray[66] = "0033";
    $chrArray[67] = "4";
    $unicodeArray[67] = "0034";
    $chrArray[68] = "5";
    $unicodeArray[68] = "0035";
    $chrArray[69] = "6";
    $unicodeArray[69] = "0036";
    $chrArray[70] = "7";
    $unicodeArray[70] = "0037";
    $chrArray[71] = "8";
    $unicodeArray[71] = "0038";
    $chrArray[72] = "9";
    $unicodeArray[72] = "0039";
    $chrArray[73] = ":";
    $unicodeArray[73] = "003A";
    $chrArray[74] = ";";
    $unicodeArray[74] = "003B";
    $chrArray[75] = "<";
    $unicodeArray[75] = "003C";
    $chrArray[76] = "=";
    $unicodeArray[76] = "003D";
    $chrArray[77] = ">";
    $unicodeArray[77] = "003E";
    $chrArray[78] = "?";
    $unicodeArray[78] = "003F";
    $chrArray[79] = "@";
    $unicodeArray[79] = "0040";
    $chrArray[80] = "A";
    $unicodeArray[80] = "0041";
    $chrArray[81] = "B";
    $unicodeArray[81] = "0042";
    $chrArray[82] = "C";
    $unicodeArray[82] = "0043";
    $chrArray[83] = "D";
    $unicodeArray[83] = "0044";
    $chrArray[84] = "E";
    $unicodeArray[84] = "0045";
    $chrArray[85] = "F";
    $unicodeArray[85] = "0046";
    $chrArray[86] = "G";
    $unicodeArray[86] = "0047";
    $chrArray[87] = "H";
    $unicodeArray[87] = "0048";
    $chrArray[88] = "I";
    $unicodeArray[88] = "0049";
    $chrArray[89] = "J";
    $unicodeArray[89] = "004A";
    $chrArray[90] = "K";
    $unicodeArray[90] = "004B";
    $chrArray[91] = "L";
    $unicodeArray[91] = "004C";
    $chrArray[92] = "M";
    $unicodeArray[92] = "004D";
    $chrArray[93] = "N";
    $unicodeArray[93] = "004E";
    $chrArray[94] = "O";
    $unicodeArray[94] = "004F";
    $chrArray[95] = "P";
    $unicodeArray[95] = "0050";
    $chrArray[96] = "Q";
    $unicodeArray[96] = "0051";
    $chrArray[97] = "R";
    $unicodeArray[97] = "0052";
    $chrArray[98] = "S";
    $unicodeArray[98] = "0053";
    $chrArray[99] = "T";
    $unicodeArray[99] = "0054";
    $chrArray[100] = "U";
    $unicodeArray[100] = "0055";
    $chrArray[101] = "V";
    $unicodeArray[101] = "0056";
    $chrArray[102] = "W";
    $unicodeArray[102] = "0057";
    $chrArray[103] = "X";
    $unicodeArray[103] = "0058";
    $chrArray[104] = "Y";
    $unicodeArray[104] = "0059";
    $chrArray[105] = "Z";
    $unicodeArray[105] = "005A";
    $chrArray[106] = "[";
    $unicodeArray[106] = "005B";
    $char="\ ";
    $chrArray[107]=trim($char);
    $unicodeArray[107] = "005C";
    $chrArray[108] = "]";
    $unicodeArray[108] = "005D";
    $chrArray[109] = "^";
    $unicodeArray[109] = "005E";
    $chrArray[110] = "_";
    $unicodeArray[110] = "005F";
    $chrArray[111] = "`";
    $unicodeArray[111] = "0060";
    $chrArray[112] = "a";
    $unicodeArray[112] = "0061";
    $chrArray[113] = "b";
    $unicodeArray[113] = "0062";
    $chrArray[114] = "c";
    $unicodeArray[114] = "0063";
    $chrArray[115] = "d";
    $unicodeArray[115] = "0064";
    $chrArray[116] = "e";
    $unicodeArray[116] = "0065";
    $chrArray[117] = "f";
    $unicodeArray[117] = "0066";
    $chrArray[118] = "g";
    $unicodeArray[118] = "0067";
    $chrArray[119] = "h";
    $unicodeArray[119] = "0068";
    $chrArray[120] = "i";
    $unicodeArray[120] = "0069";
    $chrArray[121] = "j";
    $unicodeArray[121] = "006A";
    $chrArray[122] = "k";
    $unicodeArray[122] = "006B";
    $chrArray[123] = "l";
    $unicodeArray[123] = "006C";
    $chrArray[124] = "m";
    $unicodeArray[124] = "006D";
    $chrArray[125] = "n";
    $unicodeArray[125] = "006E";
    $chrArray[126] = "o";
    $unicodeArray[126] = "006F";
    $chrArray[127] = "p";
    $unicodeArray[127] = "0070";
    $chrArray[128] = "q";
    $unicodeArray[128] = "0071";
    $chrArray[129] = "r";
    $unicodeArray[129] = "0072";
    $chrArray[130] = "s";
    $unicodeArray[130] = "0073";
    $chrArray[131] = "t";
    $unicodeArray[131] = "0074";
    $chrArray[132] = "u";
    $unicodeArray[132] = "0075";
    $chrArray[133] = "v";
    $unicodeArray[133] = "0076";
    $chrArray[134] = "w";
    $unicodeArray[134] = "0077";
    $chrArray[135] = "x";
    $unicodeArray[135] = "0078";
    $chrArray[136] = "y";
    $unicodeArray[136] = "0079";
    $chrArray[137] = "z";
    $unicodeArray[137] = "007A";
    $chrArray[138] = "{";
    $unicodeArray[138] = "007B";
    $chrArray[139] = "|";
    $unicodeArray[139] = "007C";
    $chrArray[140] = "}";
    $unicodeArray[140] = "007D";
    $chrArray[141] = "~";
    $unicodeArray[141] = "007E";
    $chrArray[142] = "©";
    $unicodeArray[142] = "00A9";
    $chrArray[143] = "®";
    $unicodeArray[143] = "00AE";
    $chrArray[144] = "÷";
    $unicodeArray[144] = "00F7";
    $chrArray[145] = "×";
    $unicodeArray[145] = "00F7";
    $chrArray[146] = "§";
    $unicodeArray[146] = "00A7";
    $chrArray[147] = " ";
    $unicodeArray[147] = "0020";
    $chrArray[148] = "\n";
    $unicodeArray[148] = "000D";
    $chrArray[149] = "\r";
    $unicodeArray[149] = "000A";

    $strResult = "";
    for($i=0; $i<strlen($message); $i++)
    {
        if(in_array(substr($message,$i,1), $chrArray))
        $strResult.= $unicodeArray[array_search(substr($message,$i,1), $chrArray)];
    }
    return $strResult;
}
