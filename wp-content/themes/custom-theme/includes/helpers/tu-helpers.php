<?php

/**
 * Author: ToanNguyen <anhtoan.dev@gmail.com>
 * Last Edited: 22 March 2017
 * Edited By: ToanNguyen
 */

/**
 * Remove space
 * @param $string
 * @return mixed
 */
function tu_trim_space($string) {
    $string = preg_replace('/\s+/', ' ', $string);
    return $string;
}

/**
 * Remove accent with specific separator
 * @param $str
 * @param string $separator
 * @return mixed
 */
function tu_remove_accent($str, $separator = ' ') {
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    $str = str_replace(array("&quot;", ":", ".", "'", ",", ";", ")", "(", "?", "@", "%", "*", "&", "^", "!", "=", "{", "}", "\\", '"', '-', '‘', '’', '•'), " ", $str);
    $str = trim($str);
    $str = stripslashes($str);
    $str = str_replace(array(' ', '--', '|', "/", '_', "[", "]", "+"), $separator, $str);
    $str = strtolower($str);
    return tu_trim_space($str);
}

/**
 * @param $str
 *
 * @return mixed
 */
function tu_escape_json_string($str)
{
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $str);
    return $result;
}
/**
 * @param array $data
 *
 * @return mixed
 */
function tu_json_encode($data = array(), $encode_type = null){
    //JSON_HEX_QUOT ; JSON_HEX_APOS
    return tu_escape_json_string(json_encode($data, $encode_type ?: JSON_HEX_QUOT | JSON_HEX_APOS));
}

/**
 * Human diff time
 * @param int $time
 * @param int $limit_day
 * @param string $limit_format
 * @param string $limit_before
 * @return string
 */
function tu_human_time_format($time = 0, $limit_day = 365, $limit_format = 'd/m/Y', $limit_before = '') {
    $time_past = time() - $time;
    $limit_day = $limit_day*24*60*60;

    $tokens = array(
        31536000 => 'năm',
        2592000 => 'tháng',
        604800 => 'tuần',
        86400 => 'ngày',
        3600 => 'giờ',
        60 => 'phút',
        1 => 'giây'

    );

    foreach ($tokens as $unit => $text) {
        if ($time_past < $unit || $time_past > $limit_day){
            continue;
        }
        $numberOfUnits = floor($time_past / $unit);
        return $numberOfUnits . ' ' . $text . ' trước';
    }
    return $limit_before.date($limit_format, $time);
}

/**
 * Get file extension
 * @param string $filename
 * @param bool $dot
 * @return string
 */
function tu_get_file_extension($filename = '', $dot = false) {
    $a = explode(".", $filename);
    return $dot == true ? '.'.strtolower($a[count($a) - 1]) : strtolower($a[count($a) - 1]);
}

/**
 * Get file name
 * @param string $filename
 * @param bool $ext
 * @return string
 */
function tu_get_file_name($filename = '', $ext = false) {
    if ($ext == false) {
        return basename($filename, '.' . tu_get_file_extension($filename));
    } else {
        return basename($filename);
    }
}

/**
 * Create directories by date
 * @param $root
 * @param string $extra
 * @return string
 */
function tu_create_data_folder($root, $extra = '') {
    $y = date('Y', time());
    $m = date('m', time());
    $d = date('d', time());
    if (!is_dir($root . '/' . $y))
        mkdir($root . '/' . $y, 0777);
    if (!is_dir($root . '/' . $y . '/' . $m))
        mkdir($root . '/' . $y . '/' . $m, 0777);
    if (!is_dir($root . '/' . $y . '/' . $m . '/' . $d))
        mkdir($root . '/' . $y . '/' . $m . '/' . $d, 0777);
    if (!is_dir($root . '/' . $y . '/' . $m . '/' . $d . '/' . $extra) && $extra){
        mkdir($root . '/' . $y . '/' . $m . '/' . $d . '/' . $extra, 0777);
        $extra = $extra.'/';
    }
    unset($root);
    return $y . '/' . $m . '/' . $d . '/'.$extra;
}

/**
 * Convert Hex to RGB
 * @param $hex
 * @return array
 */
function tu_hex_to_rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);

    return $rgb;
}

/**
 * Substring with length and more
 * @param $str
 * @param $len
 * @param bool $more
 * @param string $charset
 * @return string
 */
function tu_sub_string($str, $len, $more = true, $charset = 'UTF-8') {
    $str = html_entity_decode($str, ENT_QUOTES, $charset);
    $str = strip_tags($str);
    if (mb_strlen($str, $charset) > $len && mb_strpos($str, ' ') !== false) {
        $arr = explode(' ', $str);
        $str = mb_substr($str, 0, $len, $charset);
        $arrRes = explode(' ', $str);
        $last = $arr[count($arrRes) - 1];
        unset($arr);
        if (strcasecmp($arrRes[count($arrRes) - 1], $last)) {
            unset($arrRes[count($arrRes) - 1]);
        }
        return $more == true ? strip_tags(implode(' ', $arrRes)) . "..." : strip_tags(implode(' ', $arrRes));
    }
    return $str;
}

/**
 * Number formatting
 * @param int $number
 * @param string $seperator
 * @param string $fseperator
 * @return string
 */
function tu_format_number($number = 0, $seperator = '.', $fseperator = ',') {
    return number_format((int) $number, 0, $fseperator, $seperator);
}

/**
 * Encrypt - Two way encoding
 * @param $input
 * @param $key_seed
 * @return string
 */
function tu_encode($input, $key_seed) {
    $input = trim($input);
    $block = mcrypt_get_block_size('tripledes', 'ecb');
    $len = strlen($input);
    $padding = $block - ($len % $block);
    $input .= str_repeat(chr($padding), $padding);
    $key = substr(md5($key_seed), 0, 24);
    $iv_size = mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_data = mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $input, MCRYPT_MODE_ECB, $iv);
    return base64_encode($encrypted_data);
}

/**
 * Decrypt - Two way encoding
 * @param $input
 * @param $key_seed
 * @return string
 */
function tu_decode($input, $key_seed) {
    $input = base64_decode($input);
    $key = substr(md5($key_seed), 0, 24);
    $text = mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $input, MCRYPT_MODE_ECB, '12345678');
    $block = mcrypt_get_block_size('tripledes', 'ecb');
    $packing = ord($text{strlen($text) - 1});
    if ($packing and ($packing < $block)) {
        for ($P = strlen($text) - 1; $P >= strlen($text) - $packing; $P--) {
            if (ord($text{$P}) != $packing) {
                $packing = 0;
            }
        }
    }
    $text = substr($text, 0, strlen($text) - $packing);
    return $text;
}

/**
 * Create zip file
 * @param array $files
 * @param string $destination
 * @param bool $overwrite
 * @param string $contain_folder
 * @return bool
 */
function tu_create_zip($files = array(), $destination = '', $overwrite = false, $contain_folder = '') {
    $exist = file_exists($destination);
    if ($exist && !$overwrite) {
        return $exist;
    }

    $valid_files = array();
    if (is_array($files)) {
        foreach ($files as $file) {
            if (file_exists($file)) {
                $valid_files[] = $file;
            }
        }
    }
    if (count($valid_files)) {
        $zip = new ZipArchive();
        if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            return false;
        }

        if($contain_folder) $contain_folder = $contain_folder.'/';
        foreach ($valid_files as $file) {
            $zip->addFile($file, $contain_folder.aj_get_file_name($file, true));
        }
        $zip->close();

        return file_exists($destination);
    } else {
        return false;
    }
}

/**
 * Get price off percent
 * @param $price
 * @param $price_off
 * @return string
 */
function tu_get_percent($price, $price_off){
    return floor((($price - $price_off)/$price)*100).'%';
}