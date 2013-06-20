<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = '';
$db['default']['username'] = '';
$db['default']['password'] = '';
$db['default']['database'] = '';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$db['mobileDev']['hostname'] = '';
$db['mobileDev']['username'] = '';
$db['mobileDev']['password'] = '';
$db['mobileDev']['database'] = '';
$db['mobileDev']['dbdriver'] = 'mysql';
$db['mobileDev']['dbprefix'] = '';
$db['mobileDev']['pconnect'] = TRUE;
$db['mobileDev']['db_debug'] = TRUE;
$db['mobileDev']['cache_on'] = FALSE;
$db['mobileDev']['cachedir'] = '';
$db['mobileDev']['char_set'] = 'utf8';
$db['mobileDev']['dbcollat'] = 'utf8_general_ci';
$db['mobileDev']['swap_pre'] = '';
$db['mobileDev']['autoinit'] = TRUE;
$db['mobileDev']['stricton'] = FALSE;

$db['mobileAdhoc']['hostname'] = '';
$db['mobileAdhoc']['username'] = '';
$db['mobileAdhoc']['password'] = '';
$db['mobileAdhoc']['database'] = '';
$db['mobileAdhoc']['dbdriver'] = 'mysql';
$db['mobileAdhoc']['dbprefix'] = '';
$db['mobileAdhoc']['pconnect'] = TRUE;
$db['mobileAdhoc']['db_debug'] = TRUE;
$db['mobileAdhoc']['cache_on'] = FALSE;
$db['mobileAdhoc']['cachedir'] = '';
$db['mobileAdhoc']['char_set'] = 'utf8';
$db['mobileAdhoc']['dbcollat'] = 'utf8_general_ci';
$db['mobileAdhoc']['swap_pre'] = '';
$db['mobileAdhoc']['autoinit'] = TRUE;
$db['mobileAdhoc']['stricton'] = FALSE;

$db['mobile']['hostname'] = '';
$db['mobile']['username'] = '';
$db['mobile']['password'] = '';
$db['mobile']['database'] = '';
$db['mobile']['dbdriver'] = 'mysql';
$db['mobile']['dbprefix'] = '';
$db['mobile']['pconnect'] = TRUE;
$db['mobile']['db_debug'] = TRUE;
$db['mobile']['cache_on'] = FALSE;
$db['mobile']['cachedir'] = '';
$db['mobile']['char_set'] = 'utf8';
$db['mobile']['dbcollat'] = 'utf8_general_ci';
$db['mobile']['swap_pre'] = '';
$db['mobile']['autoinit'] = TRUE;
$db['mobile']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */