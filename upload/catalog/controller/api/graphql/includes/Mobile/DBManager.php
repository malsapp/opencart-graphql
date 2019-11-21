<?php

namespace GQL\Mobile;

class DBManager
{
    private $table_name;
    private $ctx;
    public function __construct()
    {
        $this->ctx = $GLOBALS['reg'];
        $this->table_name = DB_PREFIX . 'otp_tokens';
    }

    /**
     * Saves a new code or returns last valid one
     * @param string $telephone
     * @param string $code
     * @return string
     */
    public function saveCodeOrGetOldOne($telephone, $code)
    {
        $_code = $this->searchCode($telephone);
        if ($_code) {
            return $_code;
        }

        return $this->insertCode($telephone, $code);
    }

    /**
     * Insert Code in DB to validated against later
     * @param string $telephone
     * @param string $code
     * @return string
     */
    private function insertCode($telephone, $code)
    {
        $this->ctx->get('db')->query(
            "INSERT INTO $this->table_name (telephone, `code`, createdAt) values ({$telephone}, {$code}, NOW())"
        );

        return $code;
    }

    /**
     * Query DB for valid codes for a specific phoneNumber
     * @param string $telephone
     * @return string
     */
    private function searchCode($telephone)
    {
        $result = $this->ctx->get('db')->query(
            "SELECT `code` FROM $this->table_name WHERE telephone= {$telephone} AND is_valid = 1 AND UNIX_TIMESTAMP(createdAt)> ( UNIX_TIMESTAMP(NOW()) - 1000 ) LIMIT 1"
        );

        return $result->row ? $result->row['code'] : false;
    }

    /**
     * Marks code as no more valid
     * @param string $telephone
     * @param string $token
     */
    private function setNotValid($telephone, $token)
    {
        $this->ctx->get('db')->query(
            "UPDATE $this->table_name SET is_valid = 0 WHERE telephone= {$telephone} AND `code` = {$token}"
        );
    }

    /**
     * Get message template
     * It gets the one that corresponds to the current language 
     * @param string $message_topic
     * @return string
     */
    public function getMessageTemplate($message_topic)
    {
        $currentLanguageCode = $this->ctx->get('session')->data['language'];
        return $this->getSettingByKey('mobile_config', "mobile_config_{$message_topic}");
    }

    /**
     * Verifies that the code is valid
     * @param string $telephone
     * @param string $token
     * @return boolean
     */
    public function verifyToken($telephone, $token)
    {
        $code = $this->searchCode($telephone);
        if ($code && $code == $token) {
            $this->setNotValid($telephone, $token);
            return true;
        }

        return false;
    }

    /**
     * Gets a settings record by its code and key
     * @param string $code
     * @param string $key
     * @return array
     */
    public function getSettingByKey($code, $key){
        $query = $this->ctx->get('db')->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->ctx->get('db')->escape($code) . "' AND `key` = '".$this->ctx->get('db')->escape($key)."'");
        return $query->row;
    }

    /**
     * Check if a user with a specific phone_number is already registered 
     * @param string $phone_number
     * @return array
     */
    public function getUserByMobile($phone_number)
    {
        $query = $this->ctx->get('db')->query("SELECT * FROM " . DB_PREFIX . "customer WHERE `telephone` = '" . $this->ctx->get('db')->escape($phone_number) . "'");

        if (count($query->rows) == 0) {
            return false;
        }

        return $query->row;
    }
}
