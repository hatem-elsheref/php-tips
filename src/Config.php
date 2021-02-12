<?php

namespace shefoo;

class Config extends Configuration {

    public static ?Configuration  $config = null;

    public static function load(bool $useJson = false,array $options=[]){
//        if (!self::$config instanceof Configuration)
//        self::$config = Configuration::load($useJson,$options);
       self::$config = Configuration::load($useJson,$options);
       return  new static;
    }


    public static function get(string $key,$default = null){
        return self::$config->getOption($key,$default);
    }

    public static function set(string $key,$value){
        self::$config->setOption($key,$value);
        return 'ok';
    }

    public static function has(string $key){
        return self::$config->hasOption($key);
    }

    public static function all(){
        return self::$config->getAll();
    }

    public static function root(string $key){
        return self::$config->getRoot($key);
    }
    public static function mode(){
       return self::$config->getMode();
    }
}



