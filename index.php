<?php

require_once 'src/Configuration.php';
require_once 'src/Config.php';
require_once 'src/helper.php';

use shefoo\Config;


// using
#1
// run the configuration
//Config::load(false);

#2
$config = config(true);


$options=[
    'some1'=>'val1',
    'some2'=>'val2',
    'some3'=>'val3',
];
$config = config(false,$options);


/*
 * $config::mode();
 * $config::all();
 * $config::has('x');
 * $config::set('x','y');
 * $config::get('x','default');
 */


// show the config read from array or json files
var_dump(Config::mode());
// get all configurations
var_dump(Config::all());
// get selected key or null if not found
var_dump(Config::get('app.name'));
// get selected key or default if not found
var_dump(Config::get('app.name','default'));
// set existing key or update if founded
var_dump(Config::set('app','sd'));
// check if configurations has a key
var_dump(Config::has('configurations.has.key'));
// get all configurations of selected file
var_dump(Config::root('auth'));




