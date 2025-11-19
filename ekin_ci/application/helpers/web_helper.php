<?php
# @Author: Awan Tengah
# @Date:   2017-05-04T21:17:33+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-01-10T00:14:11+07:00

function get_indo_month_name($month_in_number)
{
    if ($month_in_number == 1) {
        $month = 'Januari';
    } else if ($month_in_number == 2) {
        $month = 'Februari';
    } else if ($month_in_number == 3) {
        $month = 'Maret';
    } else if ($month_in_number == 4) {
        $month = 'April';
    } else if ($month_in_number == 5) {
        $month = 'Mei';
    } else if ($month_in_number == 6) {
        $month = 'Juni';
    } else if ($month_in_number == 7) {
        $month = 'Juli';
    } else if ($month_in_number == 8) {
        $month = 'Agustus';
    } else if ($month_in_number == 9) {
        $month = 'September';
    } else if ($month_in_number == 10) {
        $month = 'Oktober';
    } else if ($month_in_number == 11) {
        $month = 'November';
    } else if ($month_in_number == 12) {
        $month = 'Desember';
    } else {
        $month = '';
    }
    return $month;
}

function indonesian_date($timestamp = '', $date_format = 'l, j F Y | H:i')
{
    if (trim($timestamp) == '') {
        $timestamp = time();
    } elseif (!ctype_digit($timestamp)) {
        $timestamp = strtotime($timestamp);
    }

    $date_format = preg_replace("/S/", "", $date_format);
    $pattern     = array(
        '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
        '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
        '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
        '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
        '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
        '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
        '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
        '/November/', '/December/',
    );
    $replace = array(
        'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
        'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'September',
        'Oktober', 'November', 'Desember',
    );
    $date = date($date_format, $timestamp);
    $date = preg_replace($pattern, $replace, $date);
    $date = "{$date}";
    return $date;
}

function to_date_format($datetime, $to = 'd F Y', $indonesian_format = true)
{
    if($indonesian_format == true) {
        $format = indonesian_date($datetime, $to);
    } else {
        $format = date_format(date_create($datetime), $to);
    }
    return $format;
}

function thumb_name($filename)
{
    $extension_pos = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
    $thumb         = substr($filename, 0, $extension_pos) . '_thumb' . substr($filename, $extension_pos);
    return $thumb;
}

function generate_slug($field, $model, $id = null)
{
    $ci          = &get_instance();
    $get         = $ci->{$model}->first($id);
    $created_at  = $get->created_at;
    $slug        = url_title(strtolower($ci->input->post($field, true)) . '-' . to_date_format($created_at, 'His'));
    $data_update = array(
        'slug' => $slug,
    );
    $ci->{$model}->edit($get->id, $data_update);
}

function error_upload_message($edit_url = null, $error = null)
{
    if (!is_null($edit_url) && !is_null($error)) {
        $ci = &get_instance();
        $ci->session->set_flashdata('message', array('message' => $error, 'class' => 'alert-danger'));
        redirect($edit_url);
    }
    return false;
}

function unlink_file($location = null)
{
    if (!is_null($location)) {
        if (!is_dir($location)) {
            if (is_file($location)) {
                unlink($location);
                return true;
            }
            return false;
        }
        return false;
    }
    return false;
}

function path_image($config = null)
{
    if (!is_null($config)) {
        $ci = &get_instance();
        return $ci->config->item($config);
    }
    return false;
}

function format_currency($number = 0, $with_rp = true)
{
    $currency = ($with_rp == true) ? ('IDR ' . number_format($number, 0, ",", ".")) : (number_format($number, 0, ",", "."));
    return $currency;
}

function format_currency_decimal($number = 0, $with_rp = true, $decimal = 2)
{
    $currency = ($with_rp == true) ? ('IDR ' . number_format($number, $decimal, ",", ".")) : (number_format($number, $decimal, ",", "."));
    return $currency;
}

function create_folder($path)
{
    if (!is_dir($path)) {
        if (!file_exists($path)) {
            $oldmask = umask(0);
            $create  = mkdir($path, 0777, true);
            umask($oldmask);
        }
    }
    return false;
}

function encode_crypt($param)
{
    $ci = &get_instance();
    return str_replace(array('+', '/', '='), array('-', '_', '~'), $ci->encryption->encrypt($param));
}

function decode_crypt($param)
{
    $ci = &get_instance();
    return $ci->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $param));
}

function number_format_short($n, $precision = 1)
{
    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix   = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix   = 'K';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix   = 'M';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix   = 'B';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix   = 'T';
    }
    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
    // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ($precision > 0) {
        $dotzero  = '.' . str_repeat('0', $precision);
        $n_format = str_replace($dotzero, '', $n_format);
    }
    return $n_format . $suffix;
}

function replace_dot($number)
{
    return str_replace('.', '', $number);
}

function get_session($sess)
{
    $ci = &get_instance();
    return $ci->session->userdata($sess);
}

function set_session($arr)
{
    $ci = &get_instance();
    return $ci->session->set_userdata($arr);
}

function get_config_item($config = null)
{
    if (!is_null($config)) {
        $ci = &get_instance();
        return $ci->config->item($config);
    }
    return false;
}

function select_db($db_name = null)
{
    if (!is_null($db_name)) {
        $ci = &get_instance();
        return $ci->load->database($db_name, true);
    }
}

function get_jumlah_hari_kerja($month, $year)
{
    $ci                = &get_instance();
    $dbpresensi        = get_config_item('dbpresensi');
    $jumlah_hari_kerja = $ci->db->query("select {$dbpresensi}.hari_kerja_new('{$year}-{$month}-01',(SELECT LAST_DAY('{$year}-{$month}-01')), 5) jumlah")->row();
    $jumlah_hari_libur = $ci->db->query("SELECT COUNT(*) jumlah FROM {$dbpresensi}.absen_libur WHERE MONTH(tanggal) = '{$month}' AND YEAR(tanggal) = '{$year}' AND DAYNAME(tanggal) NOT IN('Saturday', 'Sunday')")->row();
    $hari_kerja        = $jumlah_hari_kerja->jumlah - $jumlah_hari_libur->jumlah;
    return $hari_kerja;
}

function _get_awal_ramadhan()
{
    $ci = &get_instance();
    $ci->db->select('*');
    $ci->db->where('nama', 'awal_ramadhan');
    return $ci->db->get('presensi.setting')->row();
}

function _get_akhir_ramadhan()
{
    $ci = &get_instance();
    $ci->db->select('*');
    $ci->db->where('nama', 'akhir_ramadhan');
    return $ci->db->get('presensi.setting')->row();
}

function dd($var)
{
    echo '<pre>';
    var_dump($var);
    die;
}

function raw_input()
{
    $ci = &get_instance();
    $stream_clean = $ci->security->xss_clean($ci->input->raw_input_stream);
    $request      = json_decode($stream_clean);
    return $request;
}

function get_date_from_range($start, $end, $format = 'Y-m-d')
{
    $start  = new DateTime($start);
    $end    = new DateTime($end);
    $invert = $start > $end;

    $dates   = array();
    $dates[] = $start->format($format);
    while ($start != $end) {
        $start->modify(($invert ? '-' : '+') . '1 day');
        $dates[] = $start->format($format);
    }
    return $dates;
}
