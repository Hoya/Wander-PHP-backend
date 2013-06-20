<?
class Crypto
{
	public function encrypt256($text, $key)
	{
		$iv_size = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
		return rawurlencode(mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv ));
	}
        
	public function decrypt256($text, $key)
	{
		$iv_size = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
		$result = trim ( mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, $key, rawurldecode ( $text ), MCRYPT_MODE_ECB, $iv ) );
		return $result;
	}
}
?>