<?php

/**
 * 创建model实例
 *
 * @param  string   $model  model名称
 * @return object
 */
function model($model)
{
    $model = $model . 'Model';
    $CI    = &get_instance();
    $CI->load->model($model);

    return $CI->$model;
}

/**
 * 创建logic实例
 *
 * @param  string   $logic  logic名称
 * @return object
 */
function logic($logic)
{
    $logic = $logic . 'Logic';
    $CI    = &get_instance();
    $CI->load->logic($logic);

    return $CI->$logic;
}

/**
 * 创建service实例
 *
 * @param  string   $service  service名称
 * @return object
 */
function service($service)
{
    $service = $service . 'Service';
    $CI      = &get_instance();
    $CI->load->service($service);

    return $CI->$service;
}

/**
 * 创建library实例
 *
 * @param  string   $model      model名称
 * @param  array    $params     参数
 * @return object
 */
function library($library, $params = [])
{
    $CI = &get_instance();
    $CI->load->library($library, $params);
    $library = strtolower($library);

    return $CI->$library;
}

/**
 * 创建helper实例
 *
 * @param  string   $helper  helper名称
 * @return object
 */
function helper($helper)
{
    $CI = &get_instance();
    $CI->load->helper($helper);

    return $CI->$helper;
}

function asset($file)
{
    $real_file = FCPATH . DS . 'public/' . trim($file, DS);
    if (!file_exists($real_file)) {
        return $file;
    }

    $hash   = substr(md5(filemtime($real_file)), 0, 16);
    $append = strpos($file, '?') === false ? "?_ms={$hash}" : '&_ms={$hash}';

    return DS . trim($file, DS) . $append;
}

/**
 * 获取数组或对象指定键的值，未设置时返回$default
 *
 * @param  array/object $array      数组/对象
 * @param  string       $key        变量名
 * @param  mixed        $default    默认值
 * @return mixed
 */
function array_get($array, $key, $default = null)
{
    if (is_array($array) && isset($array[$key])) {
        return $array[$key];
    } elseif (is_object($array) && isset($array->$key)) {
        return $array->$key;
    }

    return $default;
}

/**
 * 从二维数组中获取目标字段，并可以index重新索引，比array_column强大
 *
 *      $arr = [
 *          ['id' => 1, 'name' => 'x', 'value' => 'a'],
 *          ['id' => 2, 'name' => 'y', 'value' => 'b'],
 *          ['id' => 3, 'name' => 'z', 'value' => 'c'],
 *      ];
 *
 *      array_pick($arr, 'name', 'id');
 *          => [1 => 'x', 2 => 'y', 3 => 'z'];
 *
 *      array_pick($arr, ['name', 'value'], 'id');
 *          => [
 *                  1 => ['name' => 'x', 'value' => 'a'],
 *                  2 => ['name' => 'y', 'value' => 'b'],
 *                  3 => ['name' => 'z', 'value' => 'c'],
 *             ];
 *
 * @param  array/object     $array
 * @param  string/array     $columns    目标字段
 * @param  string           $index      索引字段
 * @return array
 */
function array_pick($array, $columns, $index = '')
{
    if (is_array($array) && is_string($columns)) {
        return array_column($array, $columns);
    }

    $result = [];
    foreach ($array as $item) {
        if (is_array($columns)) {
            $tmp = [];
            foreach ($columns as $key) {
                $tmp[$key] = array_get($item, $key);
            }

            if ($index && isset($item[$index])) {
                $result[$item[$index]] = $tmp;
            } else {
                $result[] = $tmp;
            }
        } else {
            $value = array_get($item, $columns);

            if ($index && isset($item[$index])) {
                $result[$item[$index]] = $value;
            } else {
                $result[] = $value;
            }
        }
    }

    return $result;
}

/**
 * 加载配置文件中指定的配置
 *
 *      load_config('upload');
 *      load_config('upload.client');
 *
 * @param  string   $name       配置字符串
 * @param  mixed    $default    默认值
 * @return mixed
 */
function load_config($name, $default = null)
{
    $CI = &get_instance();

    // 移除多余的分隔符
    $name = trim($name, '.');

    // 获取配置文件和配置项
    $file  = $name;
    $items = [];
    if (strpos($name, '.') !== false) {
        $items = explode('.', $name);
        $file  = array_shift($items);
    }

    // 加载配置
    static $cache = [];
    $data         = [];
    if (isset($cache[$file])) {
        $data = $cache[$file];
    } else {
        $file = APPPATH . 'config/' . $file . '.php';
        if (!file_exists($file)) {
            show_error('The configuration file ' . $file . ' does not exist.');
        }
        $data         = require $file;
        $cache[$file] = $data;
    }

    if (count($items) < 1) {
        return $data;
    }

    // 加载指定项
    foreach ($items as $item) {
        $data = array_get($data, $item, $default);
    }

    return $data;
}

/**
 * 转换timestamp格式到指定时区
 *
 * @param  integer $timestamp
 * @param  string  $format
 * @param  string  $timezone
 * @return string
 */
function timezone_transfer($timestamp = 0, $format = 'Y-m-d H:i:s', $timezone = 'PRC')
{
    $default_timezone = date_default_timezone_get();
    $date             = date('c', $timestamp);

    date_default_timezone_set($timezone);
    $date = date($format, strtotime($date));
    date_default_timezone_set($default_timezone);

    return $date;
}

/**
 * 对变量进行JSON编码，支持中文
 *
 * @param  mixed    $val 数据
 * @param  int      $opt 选项
 * @return string
 */
function json($val, $opt = JSON_UNESCAPED_UNICODE)
{
    return json_encode($val, JSON_UNESCAPED_UNICODE | $opt);
}

/**
 * 对JSON格式的字符串进行解码，默认返回数组
 *
 * @param  string       $json  JSON字符串
 * @param  boolean      $assoc 是否返回数组
 * @return array/object
 */
function unjson($json, $assoc = true)
{
    if (!is_string($json)) {
        return false;
    }

    return json_decode($json, $assoc);
}

/**
 * 将字节数转成对人类友好格式
 *
 * @param  integer  $bytes 字节数
 * @return string
 */
function byte2unit($bytes = 0)
{
    $res = intval($bytes) > 0 ? intval($bytes) : 0;

    $units = ['Byte', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    foreach ($units as $unit) {
        if ($res < 1024) {
            return round($res, 2) . " {$unit}";
        }

        $res /= 1024;
    }

    return '未知大小';
}

/**
 * 将字符串转成字节数，如 1K => 1024
 *
 * @param  string   $str 字符串
 * @return integer
 */
function unit2byte($str = '')
{
    $str   = strval($str);
    $match = [];
    if (!preg_match('/^(\d+) *([a-zA-Z]+)$/', $str, $match)) {
        return intval($str);
    }
    $res  = intval($match[1]);
    $unit = strtoupper($match[2]);
    if ($res <= 0) {
        return $res;
    }

    $arr_units = [
        ['B', 'BYTE', 'BYTES'],
        ['K', 'KB'],
        ['M', 'MB'],
        ['G', 'GB'],
        ['T', 'TB'],
        ['P', 'PB'],
        ['E', 'EB'],
        ['Z', 'ZB'],
        ['Y', 'YB'],
    ];
    $units = array_reduce(array_values($arr_units), function ($arr, $item) {
        $arr = array_merge($arr ? $arr : [], $item);
        return $arr;
    });
    if (!in_array($unit, $units)) {
        return intval($str);
    }

    foreach ($arr_units as $arr_unit) {
        if (in_array($unit, $arr_unit)) {
            return $res;
        }

        $res *= 1024;
    }

    return -1;
}

function http_get($url)
{
    $ch = curl_init();

    // 设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // 执行并获取HTML文档内容
    $output = curl_exec($ch);

    // 释放curl句柄
    curl_close($ch);

    return $output;
}

function http_post($url)
{
    // 发送请求
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == 'https') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function array2tree($arr, $pk = 'id', $pid = 'pid', $root = 0)
{
    if (!is_array($arr) || !$arr) {
        return $arr;
    }

    // 以主键为key的引用数组
    $refer = [];
    foreach ($arr as $key => $val) {
        $refer[$val[$pk]] = &$arr[$key];
    }

    $result = [];
    foreach ($arr as $key => $val) {
        $parent_id = $val[$pid];
        if ($parent_id == $root) {
            $result[$val[$pk]] = &$arr[$key];
        } elseif (isset($refer[$parent_id])) {
            $parent                        = &$refer[$parent_id];
            $parent['children'][$val[$pk]] = &$arr[$key];
        }
    }

    return $result;
}

function tree2array($tree, &$result = [], $level = 1)
{
    foreach ($tree as $key => $value) {
        $temp = $value;
        if (isset($temp['children']) && $temp['children']) {
            $temp['children'] = true;
            $temp['level']    = $level;
            $result[]         = $temp;
        } else {
            $temp['children'] = false;
            $temp['level']    = $level;
            $result[]         = $temp;
        }
        if (isset($value['children']) && $value['children']) {
            tree2array($value['children'], $result, ($level + 1));
        }
    }
}

function node_name($node, $next_pid)
{
    if (!is_array($node)) {
        return $node;
    }
    if (empty($node['pid'])) {
        return '';
    }

    $res = '';
    for ($i = 2; $i < $node['level']; $i++) {
        $res .= '  │ ';
    }
    if ($node['pid'] !== $next_pid && empty($node['children'])) {
        $res .= '  └─ ';
    } else {
        $res .= '  ├─ ';
    }

    return $res;
}

function subtree($nodes)
{
    if (!is_array($nodes)) {
        return '';
    }

    $res = '<ul class="treeview-menu" style="">';
    foreach ($nodes as $node) {
        $name     = $node['name'];
        $url      = $node['url'];
        $icon     = $node['icon'] ? $node['icon'] : 'fa fa-link';
        $children = empty($node['children']) ? false : $node['children'];

        if (!$children) {
            $res .= '<li class="treeview"><a href="' . $url . '"><i class="' . $icon . '"></i> ' . $name . '</a></li>';
        } else {
            $res .= '<li class="treeview">'
            . '<a href="#"><i class="' . $icon . '"></i> ' . $name
            . '<span class="pull-right-container">'
            . '<i class="fa fa-angle-left pull-right"></i>'
            . '</span>'
            . '</a>'
            . subtree($children)
                . '</li>';
        }
    }
    $res .= '</ul>';

    return $res;
}
