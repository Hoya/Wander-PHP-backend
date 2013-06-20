<?
require_once("classes/yongopal.class.php");

function encrypt256($text, $key)
{
	$iv_size = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
	$iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
	return rawurlencode(mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv ));
}

if(isset($_GET["p"]))
{
	$padding = str_repeat("=", (4 - strlen($_GET["p"]) % 4));
	$params = base64_decode($_GET["p"].$padding);

	$params = explode("|", $params);
	if(count($params > 0))
	{
		if($params[0] != 0) $messageNo = $params[0];
		$development = $params[1];
		if(isset($params[2])) $key = $params[2];

		// initiate YongoPal API class
		$yongopal = YongoPal::getInstance();
		$yongopal->initDatabase($development);

		// set error handler
		$yongopal->setErrorHanders();

		require_once('classes/chat.class.php');

		$args['task'] = "getMessageData";
		$args['data'] = new stdClass;
		if(isset($messageNo)) $args['data']->messageNo = $messageNo;
		if(isset($key)) $args['data']->key = $key;

		$chat = new Chat();
		$chat->request($args);
		$chatData = $yongopal->getResults();

		$messageNo = $chatData['result']['messageNo'];
		$fileNo = $chatData['result']['imageFileNo'];
		$key = $chatData['result']['key'];

		$imageFileCode = base64_encode($key);
		$imageFileCode = str_replace('=', '', $imageFileCode);

		if($development == 'debug') $subDomain = 'maruta.';
		elseif($development == 'adhoc') $subDomain = 'james.';
		else $subDomain = null;

		$userAgent = explode("/", $_SERVER['HTTP_USER_AGENT']);
		if($userAgent[0] == 'facebookexternalhit' || $userAgent[0] =='facebookplatform')
		{
			$args['task'] = "downloadPhoto";
			$args['data'] = new stdClass;
			$args['data']->messageNo = $messageNo;
			$args['data']->width = 0;
			$args['data']->height = 0;
			$chat->request($args);
		}
		else
		{
			header('Location: http://'.$subDomain.'yongopal.com/viewPhoto/index/'.$imageFileCode);
		}
	}
}

?>
