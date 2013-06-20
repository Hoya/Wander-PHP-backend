<div id="wrapper">
<? if($isRandom == 1): ?>
forwared from random url
<? endif; ?>
<div id="main">
	<div id="header">
    	<a href="http://wanderwith.us" title="Wander" id="wanderlogo">Wander</a>
      	<a href="http://wndrw.me/ppE0MC"><img src="/img/viewPhoto/btn_download.png" class="webdownload" /></a>
    </div>

<div id="leftcol">
	<div class="topbox">
		<? if($chatData->caption) : ?>
		<div id="phototitlebox"><?=$chatData->caption?></div>
		<? endif; ?> 
		<div class="bylinebox">
			<? if($chatData->description) : ?>
			<div class="pip"><img src="/img/viewPhoto/ico_mission.png" /><?=$chatData->description?></div> 
			<? endif; ?> 
			<? if($chatData->cityName) : ?>
			<div class="pip"><img src="/img/viewPhoto/ico_pip.png" />Taken in <?=$chatData->cityName?>, <?=$chatData->countryName?> by <?=$chatData->senderFirstName?></div>
			<? else: ?>
			<div class="pip"><img src="/img/viewPhoto/ico_pip.png" />Taken in <?=$chatData->senderCity?>, <?=$chatData->senderCountry?> by <?=$chatData->senderFirstName?></div>
			<? endif; ?>
		</div>
		<div id="socialbox">
			<a href="http://twitter.com/share" class="twitter-share-button" style="line-height:14px;" data-url="<?=$chatData->url?>" data-text=" " data-counturl="<?='http://'.$_SERVER['SERVER_NAME'].'/viewPhoto/index/'.$imageFileCode?>" data-via="WanderApp" data-count="none">Tweet</a>
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?=rawurlencode('http://'.$_SERVER['SERVER_NAME'].'/viewPhoto/index/'.$imageFileCode)?>&locale=<?=$locale['default']?>&layout=button_count&show_faces=false&width=80&action=like&font=lucida+grande&colorscheme=light" allowtransparency="true" style="border: medium none; overflow: hidden; width: 80px; height: 20px; line-height:14px; margin-left:8px;" frameborder="0" scrolling="no"></iframe>
		</div>
    </div><!-- End topbox-->
    
    <div id="phototop">
    </div>
    <div id="photo">
    	<img src="/viewPhoto/downloadImage/<?=$imageFileCode?>/messageNo/<?=$chatData->messageNo?>" width="580" />
    </div>
    <div id="photobottom">
    </div>
    
    <div id="FBCommentbox">
    	<iframe scrolling="no" id="fbf51427c49bb2" name="f68bf029bb2ee6" frameborder="0" style="border: medium none; overflow: hidden; height: 200px; width: 610px;" class="fb_ltr" src="http://www.facebook.com/plugins/comments.php?api_key=113869198637480&amp;channel_url=http%3A%2F%2Fstatic.ak.fbcdn.net%2Fconnect%2Fxd_proxy.php%3Fversion%3D3%23cb%3Df1a00344dafa08%26origin%3Dhttp%253A%252F%252Fdevelopers.facebook.com%252Ff34a857941c7492%26relation%3Dparent.parent%26transport%3Dpostmessage&amp;href=<?=rawurlencode('http://'.$_SERVER['SERVER_NAME'].'/viewPhoto/index/'.$imageFileCode)?>&amp;locale=<?=$locale['default']?>&amp;numposts=10&amp;sdk=joey&amp;width=610"></iframe>
    </div>

</div><!-- END leftcol -->

<div id="rightcol">
	<div class="topbox">
	</div>
	<div id="mapbox"><img src="http://maps.google.com/maps/api/staticmap?&center=<?=rawurlencode($senderLocationString)?>&amp;zoom=9&amp;size=320x250&amp;sensor=false"/>
		<div id="framebox"></div>
		<div id="pipbox"></div>
		<div id="facebox"><img src="/viewPhoto/downloadProfileImage/<?=$profileImageFileCode?>"  width="90" /></div>
        <div id="maplinkbox"><a href="http://maps.google.com/?q=<?=rawurlencode($senderLocationString)?>" target="_blank"><img src="/img/viewPhoto/maplink.png" /></a></div>
    </div>

	<div id="abouttext">
	<p><?=$chatData->senderFirstName?> shared this photo with <?=$chatData->receiverFirstName?> using <a href="http://wndrw.me/ppE0MC">Wander</a>, a simple way to explore the world with a new friend as your guide.</p>
    <p><a href="http://wndrw.me/ppE0MC">Download</a> the free iPhone app now or check out what other people are sharing around the world:</p>
	</div>
    
     <div id="thumbbox">
     	<!-- Replace w/ random real photos and locations, or photos from Daron for now -->
     	<? $i = 0; ?>
     	<? foreach(array_rand($tempThumbs, 3) as $key): ?>
     	<? if($i == 0): $class = "first"; else: $class = "second"; endif; ?>
		<div class="thumb <?=$class?>"><img src="/img/viewPhoto/tempThumbs/<?=$tempThumbs[$key]['fileName']?>"/><div class="pip"><img src="/img/viewPhoto/ico_pip.png" /><?=$tempThumbs[$key]['cityName']?></div></div>
		<? $i++; ?>
		<? endforeach; ?>
    </div>

</div>


</div><!-- END main -->
</div><!--END wrapper-->