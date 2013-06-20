<?
require_once("classes/DbConnect.class.php");
require_once("classes/yongopal.class.php");

$yongopal = YongoPal::getInstance();
@$yongopal->initDatabase($_GET['development']);
$db = @$yongopal->getDatabaseInstance();

if(!$showTablesQuery = @$db->query('show tables'))
{
	header("HTTP/1.1 500 Internal Server Error");
	exit();
}

if($showTablesQuery->num_rows > 0)
{
	echo "pong";
}

?>