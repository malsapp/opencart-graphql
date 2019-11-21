<?php

namespace GQL\Mobile\Providers;

use GQL\Mobile\Contracts\MobileDriverInterface;
use GQL\Mobile\DBManager;

class Jawaly extends MobileDriver implements MobileDriverInterface
{
    private $USER;
    private $PASSWORD;
    private $SENDERNAME;

    /**
     * Populate the credentials required to consume the api
     */
    public function __construct()
    {
        $this->USER = (new DBManager)->getSettingByKey('mobile_config','mobile_config_mobile_username')['value'];
        $this->PASSWORD = (new DBManager)->getSettingByKey('mobile_config','mobile_config_mobile_password')['value'];
        $this->SENDERNAME = (new DBManager)->getSettingByKey('mobile_config','mobile_config_mobile_sendername')['value'];
    }

    /**
     * Sends message via SMS
     * @param array $mobileNumber
     * @param string $messageContent
     * @return array
     */
    public function sendSMS($mobileNumber, $messageContent)
    {
        $response = [
            'data' => [],
            'errors' => []
        ];
        $telephone = $mobileNumber['country_code'] . $mobileNumber['phone_number'];
        $username = $this->USER;
        $password = $this->PASSWORD;
        $senderName = $this->SENDERNAME;
        $url = "http://www.4jawaly.net/api/sendsms.php?username=$username&password=$password&numbers=$telephone&message=$messageContent&sender=$senderName&unicode=E&return=json";
        $response = json_decode(file_get_contents($url), true);
        if ($response['Code'] == 100) {
            $response['data'][] = [
                'code' => 'DONE',
                'title' => 'Message Sent',
                'content' => 'Message has been sent successfully',
            ];
        } else {
            $response['errors'][] = [
                'code' => 'FAILED',
                'title' => 'Couldn\'t Send',
                'content' => 'An error Occured during Message Send',
            ];
        }
        return $response;
    }
}
