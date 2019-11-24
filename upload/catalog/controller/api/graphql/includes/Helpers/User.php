<?php

namespace GQL\Helpers;

use GQL\Mobile\DBManager;

class User
{
    public static function validateLogin($args, &$ctx)
    {
        $errors = array();

        $ctx->load->language('account/login');

        // Check how many login attempts have been made.
        $login_info = $ctx->model_account_customer->getLoginAttempts($args['email']);

        if ($login_info && ($login_info['total'] >= $ctx->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            throw new \Exception($ctx->language->get('error_attempts'));
        }

        // Check if customer has been approved.
        $customer_info = $ctx->model_account_customer->getCustomerByEmail($args['email']);

        if ($customer_info && !$customer_info['safe']) {
            throw new \Exception($ctx->language->get('error_approved'));
        }

        if (!$errors) {
            if (!$ctx->customer->login($args['email'], $args['password'])) {
                throw new \Exception($ctx->language->get('error_login'));

                $ctx->model_account_customer->addLoginAttempt($args['email']);
            } else {
                $ctx->model_account_customer->deleteLoginAttempts($args['email']);
            }
        }
    }

    public static function validateSignup($args, &$ctx)
    {
        $errors = array();

        $ctx->load->language('account/register');

        if ((utf8_strlen(trim($args['firstname'])) < 1) || (utf8_strlen(trim($args['firstname'])) > 32)) {
            throw new \Exception($ctx->language->get('error_firstname'));
        }

        if ((utf8_strlen(trim($args['lastname'])) < 1) || (utf8_strlen(trim($args['lastname'])) > 32)) {
            throw new \Exception($ctx->language->get('error_lastname'));
        }

        if ((utf8_strlen($args['email']) > 96) || !filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception($ctx->language->get('error_email'));
        }

        if ($ctx->model_account_customer->getTotalCustomersByEmail($args['email'])) {
            throw new \Exception($ctx->language->get('error_exists'));
        }

        if ((utf8_strlen($args['telephone']) < 3) || (utf8_strlen($args['telephone']) > 32)) {
            throw new \Exception($ctx->language->get('error_telephone'));
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
                throw new \Exception(sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
            } elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($args['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                throw new \Exception(sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
            }
        }

        if ((utf8_strlen($args['password']) < 4) || (utf8_strlen($args['password']) > 20)) {
            throw new \Exception($ctx->language->get('error_password'));
        }

        if ($args['confirm'] != $args['password']) {
            throw new \Exception($ctx->language->get('error_confirm'));
        }

        // Agree to terms
        if ($ctx->config->get('config_account_id')) {
            $ctx->load->model('catalog/information');

            $information_info = $ctx->model_catalog_information->getInformation($ctx->config->get('config_account_id'));

            if ($information_info && !$args['agree']) {
                throw new \Exception(sprintf($ctx->language->get('error_agree'), $information_info['title']));
            }
        }
    }

    public static function validatePassword(&$ctx, $args)
    {
        $errors = array();

        $ctx->load->language('account/password');

        if ((utf8_strlen($args['password']) < 4) || (utf8_strlen($args['password']) > 20)) {
            throw new \Exception($ctx->language->get('error_password'));
        }

        if ($args['confirm'] != $args['password']) {
            throw new \Exception($ctx->language->get('error_confirm'));
        }

        return $errors;
    }

    public static function validateCustomerEdit(&$ctx, $args)
    {
        $errors = array();

        $ctx->load->language('account/edit');
        $ctx->load->model('account/customer');

        if ((utf8_strlen(trim($args['firstname'])) < 1) || (utf8_strlen(trim($args['firstname'])) > 32)) {
            throw new \Exception($ctx->language->get('error_firstname'));
        }

        if ((utf8_strlen(trim($args['lastname'])) < 1) || (utf8_strlen(trim($args['lastname'])) > 32)) {
            throw new \Exception($ctx->language->get('error_lastname'));
        }

        if ((utf8_strlen($args['email']) > 96) || !filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception($ctx->language->get('error_email'));
        }

        if (($ctx->customer->getEmail() != $args['email']) && $ctx->model_account_customer->getTotalCustomersByEmail($args['email'])) {
            throw new \Exception($ctx->language->get('error_exists'));
        }

        if ((utf8_strlen($args['telephone']) < 3) || (utf8_strlen($args['telephone']) > 32)) {
            throw new \Exception($ctx->language->get('error_telephone'));
        }

        // Custom field validation
        $ctx->load->model('account/custom_field');

        $custom_fields = $ctx->model_account_custom_field->getCustomFields($ctx->config->get('config_customer_group_id'));

        foreach ($custom_fields as $custom_field) {
            if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($args['custom_field'][$custom_field['custom_field_id']])) {
                throw new \Exception(sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
            } elseif (($custom_field['location'] == 'account') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($args['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                throw new \Exception(sprintf($ctx->language->get('error_custom_field'), $custom_field['name']));
            }
        }

        return $errors;
    }


    public static function forgottenMail(&$ctx, $args)
    {
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

    public static function loginByMobileNumber(&$ctx, $phone_number, $password)
    {
        $user = (new DBManager($ctx))->getUserByMobile($phone_number);

        if (!$user) throw new \Exception('Warning: PhoneNumber/Password is incorrect!');

        $ctx->load->model('account/customer');
        $ctx->model_account_customer->deleteLoginAttempts($user['email']);
        $isValidCredentials = $ctx->customer->login($user['email'], $password);
        if (!$isValidCredentials) throw new \Exception('Warning: PhoneNumber/Password is incorrect!');
        return $ctx->sess;
    }
}
