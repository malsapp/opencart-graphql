<?php

namespace GQL\Helpers;

class Address
{
    public static function validateAddress(&$ctx, $args)
    {
        $ctx->load->language('account/address');

        if ((utf8_strlen(trim($args['firstname'])) < 1) || (utf8_strlen(trim($args['firstname'])) > 32)) {
            throw new \Exception($ctx->language->get('error_firstname'));
        }

        if ((utf8_strlen(trim($args['lastname'])) < 1) || (utf8_strlen(trim($args['lastname'])) > 32)) {
            throw new \Exception($ctx->language->get('error_lastname'));
        }

        if ((utf8_strlen(trim($args['address_1'])) < 3) || (utf8_strlen(trim($args['address_1'])) > 128)) {
            throw new \Exception($ctx->language->get('error_address_1'));
        }

        if ((utf8_strlen(trim($args['city'])) < 2) || (utf8_strlen(trim($args['city'])) > 128)) {
            throw new \Exception($ctx->language->get('error_city'));
        }

        $ctx->load->model('localisation/country');

        $country_info = $ctx->model_localisation_country->getCountry($args['country_id']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($args['postcode'])) < 2 || utf8_strlen(trim($args['postcode'])) > 10)) {
            throw new \Exception($ctx->language->get('error_postcode'));
        }

        if ($args['country_id'] == '' || !is_numeric($args['country_id'])) {
            throw new \Exception($ctx->language->get('error_country'));
        }

        if (!isset($args['zone_id']) || $args['zone_id'] == '' || !is_numeric($args['zone_id'])) {
            throw new \Exception($ctx->language->get('error_zone'));
        }

        // Custom field validation
        $ctx->load->model('account/custom_field');

        $custom_fields = $ctx->model_account_custom_field->getCustomFields($ctx->config->get('config_customer_group_id'));

        foreach ($custom_fields as $custom_field) {
            if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($args['custom_field'][$custom_field['custom_field_id']])) {
                throw new \Exception(sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
            } elseif (($custom_field['location'] == 'address') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($args['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                throw new \Exception(sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
            }
        }
    }

    public static function getAddress($ctx, $addressType = 'payment_address')
    {
        if ($ctx->customer->isLogged()) {
            if (isset($ctx->session->data[$addressType]['address_id'])) {
                $ctx->load->model('account/address');
                return $ctx->model_account_address->getAddress($ctx->session->data[$addressType]['address_id']);
            }
        }

        if (isset($ctx->session->data[$addressType])) {
            return $ctx->session->data[$addressType];
        }
    }

    public static function setAddress($ctx, $args, $addressType = 'payment_address')
    {
        if ($ctx->customer->isLogged()) {
            if (isset($args['address_id'])) {
                $ctx->session->data[$addressType] = array('address_id' => $args['address_id']);
                return true;
            }

            validateAddress($ctx, $args['input']);
            $ctx->load->model('account/address');
            $customer_id = $ctx->customer->getId();
            $ctx->model_account_address->addAddress($customer_id, $args['input']);
            if (null !== $ctx->db->getLastId()) {
                $ctx->session->data[$addressType] = array('address_id' => $ctx->db->getLastId());
                return true;
            } else {
                return false;
            }
        }

        if (isset($args['address_id'])) return false;

        $ctx->session->data['guest'][$addressType] = $args['input'];
        $ctx->session->data[$addressType] = $args['input'];
        return true;
    }
}
