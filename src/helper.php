<?php


if (!function_exists('config')){
    function config(bool $json = false,array $options = []){
          return \shefoo\Config::load($json,$options);
    }
}
