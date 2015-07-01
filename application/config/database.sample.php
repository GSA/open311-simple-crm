<?php 

// set these values for your own installation

// Important:
// if you've got a conf/general.yml file in place, any settings 
// in that file (with DB_ prefixes) will be OVERRIDING the settings you see here!
// see ../../../documentation/ALTERNATIVE_CONFIG.md for details
//==================================================================


$active_group = 'default'; 
$active_record = TRUE;

$db['default']['hostname'] = "localhost";
$db['default']['username'] = "YOUR_USER_NAME";
$db['default']['password'] = "YOUR_PASSWORD";
$db['default']['database'] = "YOUR_DATABASE";

$db['default']['dbdriver'] = "mysql";

$db['default']['dbprefix'] = "";

$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";
$db['default']['swap_pre'] = "";
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

//$db['default']['port'] = 8889;

// get local values if they exist
$db_array = load_general_config('db');
foreach ($db_array as $key => $value) {
    $db['default'][$key] = $value;
}
?>