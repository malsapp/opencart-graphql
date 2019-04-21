<?php
namespace GQL;

require_once realpath (__DIR__ . '/../Helpers.php');

trait MutationTypeResolver {

    public function MutationType_addReview ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/review');
        $ctx->model_catalog_review->addReview ($args['product_id'], $args['input']);
        return $ctx->db->getLastId ();
    }

    public function MutationType_addAddress ($root, $args, &$ctx) {
        if (!$ctx->customer->isLogged ()) return false;
        $ctx->load->model ('account/address');
        validateAddress ($ctx, $args['input']);
        return $ctx->model_account_address->addAddress ($args['input']);
    }

    public function MutationType_editAddress ($root, $args, &$ctx) {
        if (!$ctx->customer->isLogged ()) return false;
        $ctx->load->model ('account/address');
        validateAddress ($ctx, $args['input']);
        $ctx->model_account_address->editAddress ($args['address_id'], $args['input']);
        return $ctx->db->countAffected () > 0;
    }

    public function MutationType_deleteAddress ($root, $args, &$ctx) {
        if (!$ctx->customer->isLogged ())
            throw new \Exception ();

        $ctx->load->model ('account/address');
        $ctx->load->language ('account/address');

        if (!$ctx->model_account_address->getTotalAddresses() == 1)
            throw new \Exception ($ctx->language->get('error_delete'));

        if ($ctx->customer->getAddressId() == $args['address_id'])
            throw new \Exception ($ctx->language->get('error_default'));

        $ctx->model_account_address->deleteAddress ($args['address_id']);
        return $ctx->db->countAffected () > 0;
    }

    public function MutationType_addOrder ($root, $args, &$ctx) {
        // validateOrder ($ctx, $args);
        $ctx->load->model ('checkout/order');
        $data = $args['input'];
        $data['customer_id'] = $ctx->customer->getId();
        $data['customer_group_id'] = $ctx->customer->getGroupId();
        $data['invoice_prefix'] = $ctx->config->get('config_invoice_prefix');
        $data['store_id'] = $ctx->config->get('config_store_id') || 0;
        $data['store_name'] = $ctx->config->get('config_name');
        if ($data['store_id']) {
            $data['store_url'] = $ctx->config->get('config_url');
        } else {
            if ($ctx->request->server['HTTPS']) {
                $data['store_url'] = HTTPS_SERVER;
            } else {
                $data['store_url'] = HTTP_SERVER;
            }
        }
        $data['affiliate_id'] = 0;
        $data['commission'] = 0;
        $data['marketing_id'] = '';
        $data['tracking'] = '';
        $data['language_id'] = $ctx->config->get('config_language_id');
        $data['currency_id'] = $ctx->currency->getId($ctx->session->data['currency']);
        $data['currency_code'] = $ctx->session->data['currency'];
        $data['currency_value'] = $ctx->currency->getValue($ctx->session->data['currency']);
        $data['ip'] = $ctx->request->server['REMOTE_ADDR'];
        $data['forwarded_ip'] = '';
        $data['accept_language'] = '';

        //get totals from server.
        $totals = getTotals($ctx);
        $data['totals'] = $totals['totals'];
        $data['total'] = $totals['total'];

        //populate products from cart.
        $data['products'] = array();

        foreach ($ctx->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                $option_data[] = array(
                    'product_option_id'       => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'option_id'               => $option['option_id'],
                    'option_value_id'         => $option['option_value_id'],
                    'name'                    => $option['name'],
                    'value'                   => $option['value'],
                    'type'                    => $option['type']
                );
            }

            $data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'download'   => $product['download'],
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $product['price'],
                'total'      => $product['total'],
                'tax'        => $ctx->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => $product['reward']
            );

            error_log ("[Option]:\n" . print_r ($option_data, true));
        }


        // place order.
        $id = $ctx->model_checkout_order->addOrder ($data);
        if (isset ($id)) $ctx->model_checkout_order->addOrderHistory ($id, 1, 'Added from MalsApp.', true);
        return $id;
    }

    public function MutationType_editOrder ($root, $args, &$ctx) {
        // validateOrder ($ctx, $args);
        $ctx->load->model ('checkout/order');
        $ctx->model_checkout_order->editOrder ($args['order_id'], $args['input']);
        return $ctx->db->countAffected () > 0;
    }

    public function MutationType_confirmOrder ($root, $args, &$ctx) {
        // validateOrder ($ctx, $args);
        $ctx->load->model ('checkout/order');
        $ctx->model_checkout_order->confirmOrder ($args['order_id'], $args['confirmation']);
        return $ctx->db->countAffected () > 0;
    }

    public function MutationType_deleteOrder ($root, $args, &$ctx) {
        $ctx->load->model ('checkout/order');
        $ctx->model_checkout_order->deleteOrder ($args['order_id']);
        return $ctx->db->countAffected () > 0;
    }

    public function MutationType_addNewCustomSearch ($root, $args, &$ctx) {
        $ctx->load->model ('account/search');
        $ctx->model_account_search->addSearch ($args['input']);
        return null !== $ctx->db->getLastId ();
    }

    public function MutationType_addItemToCart ($root, $args, &$ctx) {
        addItemToCart ($ctx, $args);
        return getCartType ($ctx);
    }

    public function MutationType_addItemsToCart ($root, $args, &$ctx) {
        foreach ($args['input'] as $item) addItemToCart ($ctx, $args);
        return getCartType ($ctx);
    }

    public function MutationType_updateCartItem ($root, $args, &$ctx) {
        $ctx->cart->update ($args['cart_id'], $args['quantity']);
        return true;
    }

    public function MutationType_deleteCartItem ($root, $args, &$ctx) {
        $ctx->cart->remove ($args['cart_id']);
        return true;
    }

    public function MutationType_emptyCart ($root, $args, &$ctx) {
        $ctx->cart->clear ();
        unset ($ctx->session->data['coupon']);
        return true;
    }

    public function MutationType_addCoupon ($root, $args, &$ctx) {
        $ctx->load->model ('extension/total/coupon');
        if ($ctx->model_extension_total_coupon->getCoupon($args['code'])) {
            $ctx->session->data['coupon'] = $args['code'];
        }
        return getCartType ($ctx);
    }

    public function MutationType_setPaymentAddress ($root, $args, &$ctx) {
        return setAddress ($ctx, $args, 'payment_address');
    }

    public function MutationType_setPaymentAddressById ($root, $args, &$ctx) {
        return setAddress ($ctx, $args, 'payment_address');
    }

    public function MutationType_setPaymentMethod ($root, $args, &$ctx) { return null; }

    public function MutationType_setShippingAddress ($root, $args, &$ctx) {
        return setAddress ($ctx, $args, 'shipping_address');
    }

    public function MutationType_setShippingAddressById ($root, $args, &$ctx) {
        return setAddress ($ctx, $args, 'shipping_address');
    }

    public function MutationType_setShippingMethod ($root, $args, &$ctx) {
        $ctx->load->language('checkout/checkout');
        $shipping = explode('.', $args['code']);
        if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($ctx->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
            throw new \Exception($ctx->language->get('error_shipping'));
        }
        $ctx->session->data['shipping_method'] = $ctx->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
        return isset($ctx->session->data['shipping_method']);
    }

    public function MutationType_addAccountActivity ($root, $args, &$ctx) {
        $ctx->load->model ('account/activity');
        $ctx->model_account_activity->addActivity ($args['key'], $args['input']);
        return null !== $ctx->db->getLastId ();
    }

    public function MutationType_addAffiliateActivity ($root, $args, &$ctx) {
        $ctx->load->model ('affiliate/activity');
        $ctx->model_affiliate_activity->addActivity ($args['key'], $args['input']);
        return null !== $ctx->db->getLastId ();
    }

    public function MutationType_addWishlist ($root, $args, &$ctx) {
        if (!isset ($ctx->session->data['wishlist']))
            $ctx->session->data['wishlist'] = array ();

        $ctx->session->data['wishlist'][] = $args['product_id'];
        $ctx->session->data['wishlist'] = array_unique ($ctx->session->data['wishlist']);

        $ctx->load->model ('account/wishlist');
        $ctx->model_account_wishlist->addWishlist ($args['product_id']);
        return null !== $ctx->db->getLastId ();
    }

    public function MutationType_deleteWishlist ($root, $args, &$ctx) {
        if (!isset ($ctx->session->data['wishlist'])) return false;
        $wishlist = $ctx->session->data['wishlist'];
        if (($key = array_search ($args['product_id'], $wishlist)) !== false) {
            unset ($wishlist[$key]);
            $ctx->session->data['wishlist'] = array_unique ($wishlist);
        }

        $ctx->load->model ('account/wishlist');
        $ctx->model_account_wishlist->deleteWishlist ($args['product_id']);
        return $ctx->db->countAffected () > 0;
    }

    public function MutationType_addReturn ($root, $args, &$ctx) {
        $ctx->load->model ('account/return');
        return $ctx->model_account_return->addReturn($args);
    }

    public function MutationType_deleteLoginAttempts ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer');
        $ctx->model_account_customer->deleteLoginAttempts ($args['email']);
        return $ctx->db->countAffected () > 0;
    }

    public function MutationType_editCustomer ($root, $args, &$ctx) {
        validateCustomerEdit ($ctx, $args['input']);
        if (!$ctx->customer->isLogged ()) throw new \Exception ();

        $ctx->model_account_customer->editCustomer($args['input']);
        return true;
    }

    public function MutationType_editPassword ($root, $args, &$ctx) {
        validatePassword ($ctx, $args);
        if (!$ctx->customer->isLogged()) return false;

        $ctx->load->model('account/customer');
        $ctx->model_account_customer->editPassword($ctx->customer->getEmail(), $args['password']);

        return true;
    }

    public function MutationType_register ($root, $args, &$ctx) {
        $ctx->load->model('account/customer');
        validateSignup ($args['input'], $ctx);

        $customer_id = $ctx->model_account_customer->addCustomer($args['input']);
        $ctx->model_account_customer->deleteLoginAttempts($args['input']['email']);
        $ctx->customer->login($args['input']['email'], $args['input']['password']);
        return $ctx->sess;
    }

    public function MutationType_login ($root, $args, &$ctx) {
        $ctx->load->model('account/customer');
        validateLogin ($args, $ctx);

        unset($ctx->session->data['guest']);

        //Default Addresses
        $ctx->load->model('account/address');
        if ($ctx->config->get('config_tax_customer') == 'payment') {
            $ctx->session->data['payment_address'] = $ctx->model_account_address->getAddress($ctx->customer->getAddressId());
        }

        if ($ctx->config->get('config_tax_customer') == 'shipping') {
            $ctx->session->data['shipping_address'] = $ctx->model_account_address->getAddress($ctx->customer->getAddressId());
        }

        // Wishlist
        if (isset($ctx->session->data['wishlist']) && is_array($ctx->session->data['wishlist'])) {
            $ctx->load->model('account/wishlist');
            foreach ($ctx->session->data['wishlist'] as $key => $product_id) {
                $ctx->model_account_wishlist->addWishlist($product_id);
                unset($ctx->session->data['wishlist'][$key]);
            }
        }

	// Re-apply coupon
	if (isset ($ctx->session->data['coupon'])) {
            $ctx->load->model ('extension/total/coupon');
            if (!$ctx->model_extension_total_coupon->getCoupon ($ctx->session->data['coupon'])) {
                unset ($ctx->session->data['coupon']);
            }
        }

        return $ctx->sess;
    }

    public function MutationType_logout ($root, $args, &$ctx) {
        if ($ctx->customer->isLogged()) {
			$ctx->customer->logout();

			unset($ctx->session->data['shipping_address']);
			unset($ctx->session->data['shipping_method']);
			unset($ctx->session->data['shipping_methods']);
			unset($ctx->session->data['payment_address']);
			unset($ctx->session->data['payment_method']);
			unset($ctx->session->data['payment_methods']);
			unset($ctx->session->data['comment']);
			unset($ctx->session->data['order_id']);
			unset($ctx->session->data['coupon']);
			unset($ctx->session->data['reward']);
			unset($ctx->session->data['voucher']);
			unset($ctx->session->data['vouchers']);
		}

        session_regenerate_id (true);

        return !$ctx->customer->isLogged();
    }

    public function MutationType_forgotten ($root, $args, &$ctx) {
        $ctx->load->model('account/customer');
        $ctx->load->language('account/forgotten');

        error_log(print_r($args, true));

        if (!$ctx->model_account_customer->getTotalCustomersByEmail($args['email'])) {
			throw new \Exception ($ctx->language->get('error_email'));
		}

		$customer_info = $ctx->model_account_customer->getCustomerByEmail($args['email']);

		if ($customer_info && !$customer_info['approved']) {
			throw new \Exception ($ctx->language->get('error_approved'));
		}

        forgottenMail ($ctx, $args);

		return true;
    }

    public function MutationType_contactus ($root, $args, &$ctx) {
        $mail = new \Mail();
        $mail->protocol = $ctx->config->get('config_mail_protocol');
        $mail->parameter = $ctx->config->get('config_mail_parameter');
        $mail->smtp_hostname = $ctx->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $ctx->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($ctx->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $ctx->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $ctx->config->get('config_mail_smtp_timeout');

        $mail->setTo($ctx->config->get('config_email'));
        $mail->setFrom($args['email']);
        $mail->setSender(html_entity_decode($args['name'], ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode(sprintf($args['name']), ENT_QUOTES, 'UTF-8'));
        $mail->setText($args['enquiry']);
        $mail->send();
        return true;
    }

    public function MutationType_setLanguage ($root, $args, &$ctx) {
        $ctx->session->data['language'] = $args['code'];
        return isset ($ctx->session->data['language']);
    }

    public function MutationType_setCurrency ($root, $args, &$ctx) {
        $ctx->session->data['currency'] = $args['code'];
        return isset ($ctx->session->data['currency']);
    }

    public function MutationType_editCode ($root, $args, &$ctx) { return null; }

    public function MutationType_editNewsletter ($root, $args, &$ctx) { return null; }

    public function MutationType_changeOrderStatus ($root, $args, &$ctx) {
        if (!isset ($ctx->session->data['current_agent'])) return null;

        $ctx->load->model ('checkout/order');
        $ctx->load->model ('extension/delivery_agent');
        $agent_id = $ctx->session->data['current_agent'];
        $ctx->model_checkout_order->addOrderHistory ($args['orderId'], $args['statusCode'], "Delivery Agent ({$agent_id}) Via Shopz app.", true);

        return $ctx->model_extension_delivery_agent->getOrder($agent_id, $args['orderId']);
    }

    public function MutationType_daLogin ($root, $args, &$ctx) {
        $ctx->load->model ('extension/delivery_agent');
        $user = $ctx->model_extension_delivery_agent->getDeliveryAgentByEmail ($args['email']);

        if (!isset ($user) || empty ($user)) return null;

        $hash = sha1 ($user['salt'] . sha1 ($user['salt'] . sha1 ($args['password'])));

        if ($hash != $user['password']) return null;

        $ctx->session->data['current_agent'] = $user['delivery_agent_id'];

        return $ctx->sess;
    }

    public function MutationType_sendVerificationCode ($root, $args, &$ctx) {
        return sendVerificationCode (
            $args['countryCode'],
            $args['mobileNumber']
        );
    }

    public function MutationType_verifyMobileCode ($root, $args, &$ctx) {
        return verifyCode (
            $args['countryCode'],
            $args['mobileNumber'],
            $args['verificationCode']
        );
    }

}
?>
