<link rel="shortcut icon" href="/img/favicon.ico?ver=1.2" type="image/x-icon" />
<meta property="og:url" content="http://wanderwith.us" />
<meta property="og:title" content="<?=lang('home_main_title')?>"/>
<meta property="og:description" content="<?=lang('home_description')?>"/>
<meta property="og:type" content="product"/>

<?
switch($currentLanguage)
{
	case "ko":
	{
		$this->lang->load('home', 'korean');
		break;
	}
	case "ja":
	{
		$this->lang->load('home', 'japanese');
		break;
	}
	case "zh":
	{
		$this->lang->load('home', 'simplified_chinese');
		break;
	}
	default:
	{
		$this->lang->load('home', 'english');
	}
}
$this->load->helper('language');
?>

<? if($currentLanguage == "ko"): ?>
<meta property="og:image" content="http://wanderwith.us/img/FB_thumb-kr.png"/>
<? elseif($currentLanguage == "zh"): ?>
<meta property="og:image" content="http://wanderwith.us/img/FB_thumb-cn.png"/>
<? elseif($currentLanguage == "ja"): ?>
<meta property="og:image" content="http://wanderwith.us/img/FB_thumb-jp.png"/>
<? else: ?>
<meta property="og:image" content="http://wanderwith.us/img/FB_thumb-en2.png"/>
<? endif; ?>

<? if($currentLanguage == "ko"): ?>
<link type="text/css" rel="stylesheet" href="/css/yp_en.css" />
<? elseif($currentLanguage == "zh"): ?>
<link type="text/css" rel="stylesheet" href="/css/yp_en.css" />
<? elseif($currentLanguage == "ja"): ?>
<link type="text/css" rel="stylesheet" href="/css/yp_en.css" />
<? else: ?>
<link type="text/css" rel="stylesheet" href="/css/yp_en.css" />
<? endif; ?>

<!--[if !IE 7]>
	<style type="text/css">
		#wrap {display:table;height:100%}
	</style>
<![endif]-->
</head>
<body>