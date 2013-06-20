<div id="mobilewrapper">
<div id="mobilemain">
	<div id="mobileheader">
    	<a href="http://wanderwith.us" title="Wander" id="wanderlogo">Wander</a>
      	<a href="http://wndrw.me/ppE0MC"><img src="/img/viewPhoto/btn_downloadapp.png" class="mobiledownload" /></a>
    </div>

	<div class="mobiletopbox">
		<? if($isRandom == 1): ?>
		<div class="stumbleText">
		Explore the world with a new friend as your local guide. <br><a href="http://wanderwith.us/viewPhoto/random">See where someone else is exploring now &rarr;</a>
		</div>
		<? endif; ?>
		
		<div id="phototitlebox">
		<? if($chatData->caption) : ?>
		<?=$chatData->caption?>
		<? endif; ?> 
		</div>
		
		<div id="mobilesocialbox">
			<a href="http://twitter.com/share" class="twitter-share-button" style="line-height:14px;" data-url="<?=$chatData->url?>" data-text=" " data-counturl="<?='http://'.$_SERVER['SERVER_NAME'].'/viewPhoto/index/'.$imageFileCode?>" data-via="WanderApp" data-count="none">Tweet</a>
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?=rawurlencode('http://'.$_SERVER['SERVER_NAME'].'/viewPhoto/index/'.$imageFileCode)?>&locale=<?=$locale['default']?>&layout=button_count&show_faces=false&width=80&action=like&font=lucida+grande&colorscheme=light" allowtransparency="true" style="border: medium none; overflow: hidden; width: 80px; height: 20px; line-height:14px; margin-left:8px;" frameborder="0" scrolling="no"></iframe>
		</div>
   </div>
   
    <div id="mobilephototop">    	
    </div>
    <div id="mobilephoto">
    	<img src="/viewPhoto/downloadImage/<?=$imageFileCode?>/messageNo/<?=$chatData->messageNo?>" width="580" />
    </div>
    <div id="mobilephotobottom"> 
	</div>

	<div class="mobilebylinebox">
		<div class="pip">
			<img src="/img/viewPhoto/ico_pip.png" />Taken in <?=$chatData->senderCity?>, <?=$chatData->senderCountry?> by <?=$chatData->senderFirstName?>
		</div> 
	</div>

	<div id="mobilemapbox"><img src="http://maps.google.com/maps/api/staticmap?&center=<?=rawurlencode($senderLocationString)?>&amp;zoom=10&amp;size=600x360&amp;sensor=false"/>
		<div id="mobileframebox">
		</div>
		<div id="mobilepipbox">
		</div>
		<div id="mobilefacebox"><img src="/viewPhoto/downloadProfileImage/<?=$profileImageFileCode?>/width/120/height/120" width="120" />
		</div>
        <div id="mobilemaplinkbox">
        	<a href="http://maps.google.com/?q=<?=rawurlencode($senderLocationString)?>" target="_blank"><img src="/img/viewPhoto/maplink.png" /></a>
        </div>
    </div>

	<div id="mobileabouttext">
		<p><?=$chatData->senderFirstName?> shared this photo with <?=$chatData->receiverFirstName?> using <a href="http://wanderwith.us">Wander</a>, a simple way to explore the world with a new friend as your guide.</p>
		<p><a href="http://wndrw.me/ppE0MC">Download</a> the free iPhone app now and Wander with us!
		<? if($isRandom == 1): ?>
		<br>
		Or check out <a href="http://wanderwith.us/viewPhoto/random" >where someone else is Wandering now &rarr;</a>
		<? endif; ?>
		</p>
	</div>

</div><!-- END main -->
</div><!--END wrapper-->