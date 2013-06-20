<div data-role="page">
    <div data-role="header">
        <h1>My Title</h1>
    </div><!-- /header -->
 
    <div data-role="content">
    <ul data-role="listview" data-inset="true" data-filter="true">
        <li data-role="list-divider">Countrys</li>
        <li><a href="/mobile/feedViewer/selectMissions?countryCode=" data-ajax="false">All Countries</a></li>
        <? foreach($countryList as $countryData): ?>
        <li><a href="/mobile/feedViewer/selectMissions?countryCode=<?=$countryData->countryCode?>" data-ajax="false"><?=$countryData->countryName?></a></li>
        <? endforeach;?>
    </ul>
    </div><!-- /content -->
 
    <div data-role="footer">
        <h4>My Footer</h4>
    </div><!-- /header -->
 
</div><!-- /page -->