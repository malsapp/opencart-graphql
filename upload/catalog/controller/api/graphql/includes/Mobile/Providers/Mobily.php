<?php

namespace GQL\Mobile\Providers;

use GQL\Mobile\Contracts\MobileDriverInterface;

class Mobily extends MobileDriver implements MobileDriverInterface
{
    private $APIKEY;
    private $senderName;

    /**
     * Populate the credentials required to consume the api
     */
    public function __construct($data)
    {
        $this->APIKEY = $data['mobily_apikey'];
        $this->senderName = $data['mobily_senderName'];
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
        $sender = $this->senderName;
        $sender = urlencode($sender);
        $timeSend = 0;
        $dateSend = 0;
        $applicationType = "68";
        $domainName = $_SERVER['SERVER_NAME'];
        $MsgID = rand(1, 99999);
        $deleteKey = 0;
        $viewResult = 1;
        $url = "http://www.mobily.ws/api/msgSend.php";
        $stringToPost = "apiKey=" . $this->APIKEY . "&numbers=" . $telephone . "&sender=" . $sender . "&msg=" . $messageContent . "&timeSend=" . $timeSend . "&dateSend=" . $dateSend . "&applicationType=" . $applicationType . "&domainName=" . $domainName . "&msgId=" . $MsgID . "&deleteKey=" . $deleteKey . "&lang=3";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $stringToPost);
        $result = curl_exec($ch);

        if ($result) {
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
