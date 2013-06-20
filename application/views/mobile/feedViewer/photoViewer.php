<div data-role="page">
    <div data-role="header">
        <h1>My Title</h1>
    </div><!-- /header -->
 
    <div data-role="content">
        <!--
    <?=print_r($photoData)?>
    -->
    <? if($photoData->description): ?>
    <h1><?=$photoData->description?></h1>
    <? endif; ?>
    <? if($photoData->caption): ?>
    <h2><?=$photoData->caption?></h2>
    <? endif; ?>
    <div>
        <img src="/viewPhoto/downloadImage/<?=$photoData->fileNo?>/messageNo/<?=$photoData->messageNo?>" style="max-width: 100%">
    </div>
    <div style="display:block">
        <div style="float:left"><img src="/viewPhoto/downloadProfileImage/<?=$profileImageFileCode?>/width/120/height/120" style="max-width:100%; vertical-align:middle" /></div>
        <h4>
            <?=$senderFirstName?>
        </h4>
        <h5>
            from <?=$photoData->cityName?>, <?=$photoData->countryName?>
            <? if($photoData->locationName): ?>
                (<?=$photoData->locationName?>)
            <? endif; ?> 
        </h5>
    </div>
    <div style="padding-top:5px; clear:both">
        <img src="http://maps.google.com/maps/api/staticmap?markers=color:red%7C<?=rawurlencode($senderLocationString)?>&zoom=10&size=580x360&sensor=false" style="max-width:100%">
    </div>
    <input value="Next Photo" type="button" onclick="javascript:window.location.reload()">
    </div><!-- /content -->
 
    <div data-role="footer">
        <h4>My Footer</h4>
    </div><!-- /header -->
 
</div><!-- /page -->