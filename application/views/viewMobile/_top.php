<meta property="og:url" content="http://<?=$_SERVER['SERVER_NAME']?>/viewPhoto/index/<?=$imageFileCode?>"/>
<meta property="og:title" content="<?=$chatData->senderFirstName?>'s photo on Wander"/>
<meta property="og:description" content="<?=$chatData->receiverFirstName?> is using Wander to explore <?=$chatData->senderCity?> with <?=$chatData->senderFirstName?> as a local guide!"/>
<meta property="og:type" content="article"/>
<meta property="og:image" content="http://<?=$_SERVER['SERVER_NAME']?>/viewPhoto/downloadImage/<?=$imageFileCode?>/messageNo/<?=$chatData->messageNo?>"/>

<meta property="og:locality" content="<?=$chatData->senderCity?>" />
<? if($chatData->senderProvince): ?><meta property="og:region" content="<?=$chatData->senderProvince?>" /><? endif; ?>
<meta property="og:country-name" content="<?=$chatData->senderCountry?>" />
<meta name="viewport" content="width=device-width, target-densitydpi=high-dpi" />

<link rel="shortcut icon" href="/img/favicon.ico?ver=1.2" type="image/x-icon" />
<link type="text/css" rel="stylesheet" href="/css/wombat.css" />

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

</head>

<body>