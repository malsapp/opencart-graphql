<?php
namespace GQL\Mobile\Contracts;

interface MobileDriverInterface
{
    
    /**
     * Sends message via SMS
     * @param array $mobileNumber
     * @param string $messageContent
     * @return array
     */
    public function sendSMS($mobileNumber, $messageContent);

    /**
     * Send OTP in SMS
     * @param array $to
     * @param array $options
     * @return array
     */
    public function sendOTP($to, $options);

    /**
     * Sends Forgot Message as SMS
     * @param array $to
     */
    public function sendForgetPassword($to, $options);

    /**
     * Verify Mobile Token that was sent to phone
     * @param array $to
     * @param string $token
     * @return boolean
     */
    public function verifyOTP($to, $token);
}