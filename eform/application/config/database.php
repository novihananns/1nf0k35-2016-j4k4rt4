<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = "default";
$active_record = TRUE;

$db['default']['hostname'] = "localhost";
$db['default']['username'] = "root";
$db['default']['password'] = "triadpass";
$db['default']['database'] = "epus_prog_3172";
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$cl_phc = array('P3172010101','P3172010202','P3172010203','P3172010204','P3172010205','P3172010206','P3172020101','P3172020202','P3172020203','P3172020204','P3172020205','P3172020206','P3172030201','P3172030202','P3172030203','P3172030204','P3172030205','P3172030206','P3172030207','P3172030208','P3172030209','P3172030210','P3172030211','P3172040201','P3172040202','P3172040203','P3172040204','P3172040205','P3172040206','P3172040207','P3172050101','P3172050202','P3172050203','P3172050204','P3172050205','P3172050206','P3172050207','P3172050208','P3172050209','P3172060201','P3172060202','P3172060203','P3172060204','P3172060205','P3172060206','P3172060207','P3172060208','P3172060209','P3172060210','P3172060211','P3172060212','P3172070101','P3172070202','P3172070203','P3172070204','P3172070205','P3172070206','P3172070207','P3172070208','P3172070209','P3172070210','P3172070211','P3172070212','P3172080101','P3172080202','P3172080203','P3172080204','P3172080205','P3172080206','P3172080207','P3172080208','P3172080209','P3172090201','P3172090202','P3172090203','P3172090204','P3172090205','P3172090206','P3172090207','P3172090208','P3172090209','P3172100201','P3172100202','P3172100203','P3172100204','P3172100205','P3172100206','P3172100207');

foreach ($cl_phc as $key) {
	$db_name = 'epuskesmas_live_jaktim_'.$key;
	$db[$db_name]['hostname'] = $db['default']['hostname'];
	$db[$db_name]['username'] = $db['default']['username'];
	$db[$db_name]['password'] = $db['default']['password'];
	$db[$db_name]['database'] = $db_name;
	$db[$db_name]['dbdriver'] = "mysql";
	$db[$db_name]['dbprefix'] = "";
	$db[$db_name]['pconnect'] = FALSE;
	$db[$db_name]['db_debug'] = TRUE;
	$db[$db_name]['cache_on'] = FALSE;
	$db[$db_name]['cachedir'] = "";
	$db[$db_name]['char_set'] = "utf8";
	$db[$db_name]['dbcollat'] = "utf8_general_ci";
	$db[$db_name]['swap_pre'] = '';
	$db[$db_name]['autoinit'] = TRUE;
	$db[$db_name]['stricton'] = FALSE;
}

