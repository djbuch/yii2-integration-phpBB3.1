<?php

namespace nill\forum;

/**
 * Validation class
 * @version 0.1
 */

class phpBBUserValidation extends \phpbb\auth\provider\base {

    /**
     * Database Authentication Constructor
     *
     * @param    phpbb_db_driver    $db
     */
    public function __construct(\phpbb\db\driver\driver_interface $db) {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     */
    public function login($email, $password) {
        // Auth plugins get the password untrimmed.
        // For compatibility we trim() here.
        $password = trim($password);

        // do not allow empty password
        if (!$password) {
            return array(
                'status' => LOGIN_ERROR_PASSWORD,
                'error_msg' => 'NO_PASSWORD_SUPPLIED',
                'user_row' => array('user_id' => ANONYMOUS),
            );
        }

        if (!$email) {
            return array(
                'status' => LOGIN_ERROR_USERNAME,
                'error_msg' => 'LOGIN_ERROR_USERNAME',
                'user_row' => array('user_id' => ANONYMOUS),
            );
        }

       
        $sql = 'SELECT user_id, username, user_password, user_passchg, user_email, user_type, user_login_attempts
            FROM ' . USERS_TABLE . "
            WHERE user_email = '" . $this->db->sql_escape($email) . "'";
        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        // Successful login... set user_login_attempts to zero...
        return array(
            'status' => LOGIN_SUCCESS,
            'error_msg' => false,
            'user_row' => $row,
        );
    }

}
