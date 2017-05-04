<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Push_Notification class
 * Send Push Notification ios and android(FCM & GCM)
 *
 * @developer    Pawan Sen, Software Developer
 * @license   Free
 */
class Push_Notification {

    private $_CI;
    private $_CERTIFICATE_PATH;
    private $_API_ACCESS_KEY;
    private $_GCM_URL;
    private $_FCM_URL;
    public $_TOKEN_ERROR = "";
    public $_DATA_EMPTY = "";
    private $_IOS_PASSPHRASE;

    public function __construct() {

        $this->_CI = &get_instance();
        $this->_CI->load->config('push_notification_config');
        $this->_CERTIFICATE_PATH = $this->_CI->config->item('CERTIFICATE_PATH');
        $this->_API_ACCESS_KEY   = $this->_CI->config->item('API_ACCESS_KEY');
        $this->_IOS_PASSPHRASE   = $this->_CI->config->item('IOS_PASSPHRASE');
        $this->_GCM_URL          = $this->_CI->config->item('GCM_URL');
        $this->_FCM_URL          = $this->_CI->config->item('FCM_URL');
        $this->_TOKEN_ERROR      = $this->_CI->config->item('TOKEN_ERROR');
        $this->_DATA_EMPTY       = $this->_CI->config->item('DATA_EMPTY');
    }
    
    /**
     * @method android_gcm
     * 
     * call method android GCM library
     * 
     * @param data_array,target_token
     * @access public
     * @return boolean TRUE or FALSE 
     * 
     */

    public function android_gcm($data_array = array(), $target_token) {
        
        if (empty($data_array) && !is_array($data_array)){
            return $this->_DATA_EMPTY;
        }

        if (empty($target_token)){
            return $this->_TOKEN_ERROR;
        }

        $fields = array
            (
            'data' => $data_array
        );

        /** set device token to send push notification * */
        if (is_array($target_token)) {
            $fields['registration_ids'] = $target_token;
        } else {
            $fields['to'] = $target_token;
        }

        /** set header with key * */
        $headers = array
            (
            'Authorization: key=' . $this->_API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_GCM_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

   /**
     * @method android_fcm
     * 
     * android FCM library
     * 
     * @param data_array,target_token
     * @access public
     * @return boolean TRUE or FALSE 
     * 
     */
    public function android_fcm($data_array = array(), $target_token) {

        if (empty($data_array) && !is_array($data_array)){
            return $this->_DATA_EMPTY;
        }

        if (empty($target_token)){
            return $this->_TOKEN_ERROR;
        }

        $fields = array(
            'registration_ids' => $target_token,
            'data' => $data_array,
        );

        /** set header with key * */
        $headers = array(
            'Authorization:key=' . $this->_API_ACCESS_KEY,
            'Content-Type:application/json'
        );
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->_FCM_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    /**
     * @method ios
     * 
     * IOS method library
     * 
     * @param body,target_token
     * @access public
     * @return boolean TRUE or FALSE 
     * 
     */
    public function ios($body = array(),$target_token) {
        
        // Check the payload body
        if (empty($body) && !is_array($body))
            return $this->_DATA_EMPTY;
        
        if (empty($target_token))
            return $this->_TOKEN_ERROR;

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->_CERTIFICATE_PATH);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->_IOS_PASSPHRASE);

        // Open a connection to the APNS server
        $fp = stream_socket_client(APNS_GATEWAY_URL, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp) {
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        } else {
            exit('Connected to APNS' . PHP_EOL);
        }

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $target_token) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        // Close the connection to the server
        fclose($fp);
        return $result;
    }

}
