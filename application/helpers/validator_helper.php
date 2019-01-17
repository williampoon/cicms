<?php

/**
 * 是否邮件地址
 *
 * @param  string   $string
 * @return boolean
 */
function is_email($string)
{
    return preg_match("/^[0-9a-zA-Z]+(?:[\_\.\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $string);
}
