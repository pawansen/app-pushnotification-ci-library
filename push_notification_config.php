<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*
|--------------------------------------------------------------------------
| Push Notification
|--------------------------------------------------------------------------
|
| Push Notification IOS pem file certification path
|
*/
$config['CERTIFICATE_PATH'] = APPPATH;

/*
|--------------------------------------------------------------------------
| Push Notification
|--------------------------------------------------------------------------
|
| Push Notification Android FCM/GCM api access key
|
*/
$config['API_ACCESS_KEY'] = "AIzaShhh55699j8l4SBkcYHGF8755CFGG2VXc1BqQE";

/*
|--------------------------------------------------------------------------
| Push Notification
|--------------------------------------------------------------------------
|
| Push Notification IOS pem file set passphrase
|
*/
$config['IOS_PASSPHRASE'] = "123456";

/*
|--------------------------------------------------------------------------
| Push Notification
|--------------------------------------------------------------------------
|
| Push Notification android gcm url
|
*/
$config['GCM_URL'] = 'https://android.googleapis.com/gcm/send';

/*
|--------------------------------------------------------------------------
| Push Notification
|--------------------------------------------------------------------------
|
| Push Notification android fcm url
|
*/
$config['FCM_URL'] = 'https://fcm.googleapis.com/fcm/send';

/*
|--------------------------------------------------------------------------
| Push Notification
|--------------------------------------------------------------------------
|
| Push Notification device token error message
|
*/
$config['TOKEN_ERROR'] = "Device Token must be required";

/*
|--------------------------------------------------------------------------
| Push Notification
|--------------------------------------------------------------------------
|
| Push Notification array message error message
|
*/
$config['DATA_EMPTY'] = "Notification message must be required";

