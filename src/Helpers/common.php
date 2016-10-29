<?php

/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('pr')) {

    function pr($data, $exit = 0) {
        print '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">';
        print_r($data);
        print '</pre>';
        if ($exit != 0) {
            exit();
        }
    }

}

/* Kiểm tra các phần tử trong mảng A có tồn tại trong mảng B hay ko?
 * @param Array
 * Nếu có ít nhất 1 phần tử mà không tồn tại thì trả về FALSE
 * Còn lại thì trả về TRUE
 */
if (!function_exists('array_in_array')) {
        function array_in_array($arrA, $arrB, $check_key = false)
        {
            if(!empty($arrA))
            {
                foreach ($arrA as $k => $v)
                {
                    if ($check_key){
                        if(!in_array($k, $arrB, true)){
                            return false;
                        }
                    } else {
                        if(!in_array($v, $arrB, true)){
                            return false;
                        }
                    }
                }
                return true;
            }
            return false;
        }
}

if (!function_exists('explode_end')) {

    function explode_end ($delimiter, $string) {
        $rs = explode($delimiter, $string);
        return end($rs);
    }

}

if (!function_exists('getLastQuery')) {

    function getLastQuery() {
        $queries = DB::getQueryLog();
        return last($queries);
    }

}

if (!function_exists('array_column')) {

    function array_column(array $input, $columnKey, $indexKey = 'id') {
        $array = array();
        foreach ($input as $value) {
            if (!isset($value[$columnKey])) {
                return false;
            }

            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!isset($value[$indexKey])) {
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }

        return $array;
    }

}

if (!function_exists('array_reindex')) {

    function array_reindex(array $input, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            $array[$value[$indexKey]] = $value;
        }

        return $array;
    }

}


function isMenuActive($routeName = '', $action = '', $activeClass = 'active', $routeParam1 = '', $routeParam2 = '') {
    if ($routeName) {
        if ($routeParam1) {
            $url = URL::to(URL::route($routeName, $routeParam1, $routeParam2));
        } else {
            $url = URL::to(URL::route($routeName));
        }
    } elseif ($action)
        $url = URL::action($action);
    if ($url == URL::current())
        return $activeClass;
    else
        return false;
}

if (!function_exists('numberFormat')) {
    function numberFormat($number, $lang= 'vn', $decimal = 2) {
        if($lang=='vn')
            return number_format($number, $decimal, ',', '.');
        else
            return number_format($number, $decimal, '.', ',');
    }
}

if (!function_exists('curlGet')) {

    function curlGet($url = '', $params = array(), $htpassInfo = '') {
        $options = array();
        $options['CURLOPT_AUTOREFERER'] = 1;
        $options['CURLOPT_CRLF'] = 1;
        $options['CURLOPT_NOPROGRESS'] = 1;
        //login htpaswd
        if ($htpassInfo) {
            $options['CURLOPT_USERPWD'] = $htpassInfo;
            $options['CURLOPT_HTTPAUTH'] = CURLAUTH_ANY;
        }

        $http = new cURL($options);
        $http->setOptions($options);
        if (substr($url, -1) != '?' && !empty($params))
            $url .= "?" . http_build_query($params);
        $src = $http->get($url);
        return $src;
    }

}

if (!function_exists('curlPost')) {

    function curlPost($link = '', $field = array()) {
        $options = array();
        $fields = array();
        $options['CURLOPT_AUTOREFERER'] = 1;
        $options['CURLOPT_CRLF'] = 1;
        $options['CURLOPT_NOPROGRESS'] = 1;
        $options['CURLOPT_RETURNTRANSFER'] = 1;
        //login htpaswd
        if ($htpassInfo) {
            $options['CURLOPT_USERPWD'] = $htpassInfo;
            $options['CURLOPT_HTTPAUTH'] = CURLAUTH_ANY;
        }

        $http = new cURL($options);
        $http->setOptions($options);
        $result = $http->post($link, $field);
        return $result;
    }

}

if (!function_exists('curlFileSize')) {

    function curlFileSize($url) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);

        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        curl_close($ch);
        return $size;
    }

}

if (!function_exists('curlCopy')) {

    function curlCopy($source = '', $file = '') {
        $fh = fopen($file, 'w+');
        if ($fh) {
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FILE, $fh);
            // grab URL and pass it to the browser
            curl_exec($ch);
            // close cURL resource, and free up system resources
            curl_close($ch);
            fclose($fh);
            return true;
        }
    }

}
if (!function_exists('secsToDuration')) {

    function secsToDuration($secs) {
        $units = array(
            // "week"   => 7*24*3600,
            // "day"    =>   24*3600,
            "hour" => 3600,
            "minute" => 60,
            "second" => 1,
        );

        // specifically handle zero
        if ($secs == 0)
            return "";
        $s = "";
        foreach ($units as $name => $divisor) {
            if ($quot = intval($secs / $divisor)) {
                $text[] = str_pad($quot, 2, '00', STR_PAD_LEFT);
                $secs -= $quot * $divisor;
            } else {
                $text[] = '00';
            }
        }
        return implode(':', $text);
    }

}
if (!function_exists('randomString')) {

    function randomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}

function shareFB($url = '') {
    return "https://www.facebook.com/dialog/share?app_id=" . FB_APP_ID . "&display=popup&href=" . urlencode($url) . "&redirect_uri=" . urlencode($url);
}

function dateFromVNDate($vnDate){
    $tmp = explode('-', $vnDate);
    try{
        return "{$tmp[2]}-{$tmp[1]}-{$tmp[0]}";
    }
    catch(Exception $e){
        return false;
    }
}

if(!function_exists('limitWords')){
    function limitWords($str, $wordCount = 0){
        $retval = $str;
        if($wordCount && $str){
            $strArray = explode(' ', $str);
            $wordOfStr = count($strArray);
            for($i = 1; $i <= $wordCount; $i++){
                if($i <= $wordOfStr){
                    $retvalArr[] = $strArray[$wordOfStr - $i];
                }
            }
            $retval = implode(' ', array_reverse($retvalArr));
        }

        return $retval;
    }
}

if(!function_exists('isSelected')){
    function isSelected($value, $compare){
        if($value == $compare)
            return 'selected="selected"';
        return '';
    }
}


if(!function_exists('rdr')){
    function rdr($link){
        return route('redirect', ['to' => urlencode(trim($link))]);
    }
}

function ic($imagePath, $width = 0, $height = 0){
    if(strpos($imagePath, 'http://') === false && strpos($imagePath, 'https://') === false)
        if($width || $height){
            return route('imagecache', [$width . "x" . $height,  rawurlencode(trim($imagePath, '/'))]);
        }
        else{
            return URL::to($imagePath);
        }
    else
        return $imagePath;
}