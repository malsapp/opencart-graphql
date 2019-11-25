<?php
class ControllerExtensionModuleShopzGraphql extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/shopz_graphql');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        $data = array();

        $data['error'] = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if ($this->validate()) {
                $this->model_setting_setting->editSetting('mobile_config', $this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
            } else {
                if (count($this->error) > 0) {
                    $data['error']['error_warning'] = $this->language->get('error_warning');
                } else {
                    $data['error']['error_warning'] = '';
                }

                if (isset($this->error['mobile_provider'])) {
                    $data['error']['mobile_provider'] = $this->error['mobile_provider'];
                } else {
                    $data['error']['mobile_provider'] = '';
                }

                if (isset($this->error['mobile_username'])) {
                    $data['error']['mobile_username'] = $this->error['mobile_username'];
                } else {
                    $data['error']['mobile_username'] = '';
                }

                if (isset($this->error['mobile_password'])) {
                    $data['error']['mobile_password'] = $this->error['mobile_password'];
                } else {
                    $data['error']['mobile_password'] = '';
                }

                if (isset($this->error['mobile_sendername'])) {
                    $data['error']['mobile_sendername'] = $this->error['mobile_sendername'];
                } else {
                    $data['error']['mobile_sendername'] = '';
                }

                if (isset($this->error['mobile_message_template_otp'])) {
                    $data['error']['mobile_message_template_otp'] = $this->error['mobile_message_template_otp'];
                } else {
                    $data['error']['mobile_message_template_otp'] = '';
                }

                if (isset($this->error['mobile_message_template_forgot'])) {
                    $data['error']['mobile_message_template_forgot'] = $this->error['mobile_message_template_forgot'];
                } else {
                    $data['error']['mobile_message_template_forgot'] = '';
                }
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
        );


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/shopz_graphql', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/shopz_graphql', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->post['mobile_config_mobile_provider'])) {
            $data['mobile_config_mobile_provider'] = $this->request->post['mobile_config_mobile_provider'];
        } else {
            $data['mobile_config_mobile_provider'] = $this->config->get('mobile_config_mobile_provider');
        }

        if (isset($this->request->post['mobile_config_mobile_username'])) {
            $data['mobile_config_mobile_username'] = $this->request->post['mobile_config_mobile_username'];
        } else {
            $data['mobile_config_mobile_username'] = $this->config->get('mobile_config_mobile_username');
        }

        if (isset($this->request->post['mobile_config_mobile_password'])) {
            $data['mobile_config_mobile_password'] = $this->request->post['mobile_config_mobile_password'];
        } else {
            $data['mobile_config_mobile_password'] = $this->config->get('mobile_config_mobile_password');
        }

        if (isset($this->request->post['mobile_config_mobile_sendername'])) {
            $data['mobile_config_mobile_sendername'] = $this->request->post['mobile_config_mobile_sendername'];
        } else {
            $data['mobile_config_mobile_sendername'] = $this->config->get('mobile_config_mobile_sendername');
        }

        if (isset($this->request->post['mobile_config_mobile_message_template_otp'])) {
            $data['mobile_config_mobile_message_template_otp'] = $this->request->post['mobile_config_mobile_message_template_otp'];
        } else {
            $data['mobile_config_mobile_message_template_otp'] = $this->config->get('mobile_config_mobile_message_template_otp');
        }

        if (isset($this->request->post['mobile_config_mobile_message_template_forgot'])) {
            $data['mobile_config_mobile_message_template_forgot'] = $this->request->post['mobile_config_mobile_message_template_forgot'];
        } else {
            $data['mobile_config_mobile_message_template_forgot'] = $this->config->get('mobile_config_mobile_message_template_forgot');
        }
        if (isset($this->request->post['mobile_config_status'])) {
            $data['mobile_config_status'] = $this->request->post['mobile_config_status'];
        } else {
            $data['mobile_config_status'] = $this->config->get('mobile_config_status');
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['header'] = $this->load->controller('common/header');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['entry_mobile_provider'] = $this->language->get('entry_mobile_provider');
        $data['entry_mobile_username'] = $this->language->get('entry_mobile_username');
        $data['entry_mobile_password'] = $this->language->get('entry_mobile_password');
        $data['entry_mobile_sendername'] = $this->language->get('entry_mobile_sendername');
        $data['entry_mobile_message_template_otp'] = $this->language->get('entry_mobile_message_template_otp');
        $data['entry_mobile_message_template_forgot'] = $this->language->get('entry_mobile_message_template_forgot');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/shopz_graphql', $data));
    }

    public function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/shopz_graphql')) {
            $this->error['error_permission'] = $this->language->get('error_permission');
        }
        if (utf8_strlen($this->request->post['mobile_config_mobile_provider']) < 3 ) {
            $this->error['mobile_provider'] = $this->language->get('error_mobile_provider');
        }
        if (utf8_strlen($this->request->post['mobile_config_mobile_username']) < 3 ) {
            $this->error['mobile_username'] = $this->language->get('error_mobile_username');
        }
        if (utf8_strlen($this->request->post['mobile_config_mobile_password']) < 3 ) {
            $this->error['mobile_password'] = $this->language->get('error_mobile_password');
        }
        if (utf8_strlen($this->request->post['mobile_config_mobile_sendername']) < 3 ) {
            $this->error['mobile_sendername'] = $this->language->get('error_mobile_sendername');
        }
        if (utf8_strlen($this->request->post['mobile_config_mobile_message_template_otp']) < 3 ) {
            $this->error['mobile_message_template_otp'] = $this->language->get('error_mobile_message_template_otp');
        }
        if (utf8_strlen($this->request->post['mobile_config_mobile_message_template_forgot']) < 3 ) {
            $this->error['mobile_message_template_forgot'] = $this->language->get('error_mobile_message_template_forgot');
        }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('setting/setting');
        $this->load->model('user/user_group');

        $query_text = "CREATE TABLE " . DB_PREFIX . "otp_tokens (
            id int(10) NOT NULL AUTO_INCREMENT,
            telephone VARCHAR(20) NOT NULL,
            code VARCHAR(10) NOT NULL,
            is_valid Tinyint(1) NOT NULL DEFAULT 1,
            createdAt DATETIME NOT NULL,
            PRIMARY KEY  (id)
        )";

        try {
            $this->db->query($query_text);
        } catch (\Exception $e) { }


        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/' . $this->request->get['extension']);
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/' . $this->request->get['extension']);
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');
        $this->load->model('user/user_group');

        $this->model_setting_setting->deleteSetting('mobile_config');

        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/module/' . $this->request->get['extension']);
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/module/' . $this->request->get['extension']);
    }
}
