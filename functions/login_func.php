<?php
function convertPersianToEnglishNirwebPanel($string)
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    $output = str_replace($persian, $english, $string);
    return $output;
}

function nirweb_check_username_exists($username)
{
    $username = convertPersianToEnglishNirwebPanel($username);

    if (username_exists($username)) {
        return true;
    }
    if (is_email($username)) {
        if (email_exists($username)) {
            return true;
        }
    } else {
        $users = get_users(array(
            'meta_key' => 'wpyarud_phone',
            'meta_value' => $username,
        ));

        if (!empty($users)) {
            return true;
        }
    }


    return false;

}

function nirweb_send_sms_to_user($phone, $method, $code = '')
{
    global $nirweb_errors;


    $phone = convertPersianToEnglishNirwebPanel($phone);
    $phone = sanitize_text_field($phone);
    $ar_pass = array();
    for ($i = 1; $i <= 4; $i++) {
        $ar_pass[] = rand(1, 9);
    }
    $code = implode("", $ar_pass);
    $end_date = nirweb_create_verify_code($phone, $code);
    if (is_array($end_date)) {
        return $end_date;
    }
    $var = $code;
    $method = $method;
    $To = $phone;

    $response = require_once PATH . 'inc/nirsms.php';
    $data = array('status' => $response,
        'expired' => $end_date
    );
    if (!$response) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wpyarud_verify_code';
        $phone = convertPersianToEnglishNirwebPanel($phone);

        $check = $wpdb->get_row("SELECT * FROM $table_name WHERE user='$phone' ");

        if ($check) {
        }

        $data = array('status' => false,
            'message' => __('در ارسال پیامک مشکلی به وجود آمده است.', 'wpyar_panel')
        );
    }
    return $data;
}

function nirweb_create_verify_code($user_name, $code)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpyarud_verify_code';
    $min = 2;
    $time = strtotime("+$min minute");
    $ip = $_SERVER['REMOTE_ADDR'];


    $check_exist = $wpdb->get_row("SELECT * FROM $table_name WHERE user='$user_name'");

    if ($check_exist) {
        if (strtotime($check_exist->expire_time) > time()) {
            if (is_email($user_name)) {
                return [
                    'status' => 'has',
                    'message' => 'کد تایید قبلی معتبر است',
                    'expired' => $check_exist->expire_time
                ];

            } else {
                return [
                    'status' => 'has',
                    'message' => 'کد تایید قبلی معتبر است',
                    'expired' => $check_exist->expire_time
                ];

            }

        }

        $wpdb->update($table_name, array(
            'code_v' => $code,
            'time_create' => current_time("Y-m-d H:i:s"),
            'expire_time' => date("Y-m-d H:i:s", $time),
            'user_ip' => $ip,
        ), array('user' => $user_name));
        return date("Y-m-d H:i:s", $time);
    } else {

        $frm_ary_elements = array(
            'time_create' => current_time("Y-m-d H:i:s"),
            'expire_time' => date("Y-m-d H:i:s", $time),
            'code_v' => $code,
            'user' => $user_name,
            'user_ip' => $ip,
        );
        $res = $wpdb->insert($table_name, $frm_ary_elements);

        return date("Y-m-d H:i:s", $time);
    }
}

function nirweb_get_username_exists($username)
{
    $username = convertPersianToEnglishNirwebPanel($username);

    if (username_exists($username)) {
        return username_exists($username);
    }
    if (is_email($username)) {
        if (email_exists($username)) {
            return email_exists($username);
        }
    } else {
        $users = get_users(array(
            'meta_key' => 'wpyarud_phone',
            'meta_value' => $username,
        ));

        if (!empty($users)) {
            return $users[0]->id;
        }
    }


    return false;

}

function check_verify_code($phone, $code)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpyarud_verify_code';
    $phone = convertPersianToEnglishNirwebPanel($phone);
    $code = convertPersianToEnglishNirwebPanel($code);
    $check = $wpdb->get_row("SELECT * FROM $table_name WHERE user='$phone' AND code_v ='$code'");

    if ($check) {
        if (strtotime($check->expire_time) > time()) {
            $wpdb->delete($table_name, array('id_v' => $check->id_v));
            return true;

        }

    }
    return false;


}

function nirweb_login_user_width_code($username, $code, $remember = false, $redirect_to = '', $method = 'simple')
{
    global $nirweb_errors;
    $username = convertPersianToEnglishNirwebPanel($username);
    $code = convertPersianToEnglishNirwebPanel($code);
    $username = sanitize_text_field($username);
    $code = sanitize_text_field($code);
    $user_id = nirweb_get_username_exists($username);
    $user = get_userdata($user_id);

    $check = check_verify_code($username, $code);

    if (!$check) {
        $message = 'کد تایید اشتباه است، مجدد بررسی کنید.';

        if ($method == 'ajax') {
            nirweb_return_data_ajax('error', $message);
            exit();
        } else {
            $nirweb_errors[] = $message;

        }
    } else {
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID,true);

        $url = $redirect_to;

        nirweb_return_data_ajax('ok');
        exit();

    }
}

function nirweb_register_user_width_code($username, $code ,$redirect_to = '', $method = 'simple',$display_name)
{

    global $nirweb_errors;
    $username = convertPersianToEnglishNirwebPanel($username);
    $code = convertPersianToEnglishNirwebPanel($code);
    $username = sanitize_text_field($username);
    $code = sanitize_text_field($code);

    $check = check_verify_code($username, $code);

    if (!$check) {
        $message = 'کد تایید اشتباه است، مجدد بررسی کنید.';

        nirweb_return_data_ajax('error', $message);
        exit();

    } else {
        $password = hash('sha256', rand(00000, 999999) . '%password@' . rand(00000, 999999));
        $user_data = array(
            'user_login' => $username,
            'user_pass' => $password,

        );
        $user_id = wp_insert_user($user_data);

        if (!is_wp_error($user_id)) {
            if (!is_email($username) && is_numeric($username)) {
                update_user_meta($user_id, 'wpyarud_phone', $username);
            } elseif(is_email($username)) {
                wp_update_user( array( 'ID' => $user_id, 'user_email' => $username ) );
            }

            wp_update_user( array( 'ID' => $user_id, 'display_name' => $display_name ) );

            wp_clear_auth_cookie();
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id,true);

            nirweb_return_data_ajax('ok');
                exit();

        } else {
            $message = $user_id->get_error_message();
            $message = preg_replace('/<a href="[^"]*">[^<]*<\/a>/', '', $message);
            $message = strip_tags($message);

            nirweb_return_data_ajax('error', $message);
            exit();

        }

    }

}

function nirweb_return_data_ajax($type = 'error', $data = '')
{
    global $nirweb_errors;
    if ($type == 'ok') {

        wp_send_json([
            'status' => 'ok',
            'message' => $data,
        ]);
        exit();

    } else {

        wp_send_json([
            'status' => 'error',
            'message' => $data,
        ]);
        exit();

    }
}


