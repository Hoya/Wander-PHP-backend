<div data-role="page">
    <div data-role="header">
        <h1>My Title</h1>
    </div><!-- /header -->
 
    <div data-role="content">
    <ul data-role="listview" data-inset="true" data-filter="true">
        <li data-role="list-divider">Missions</li>
        <li><a href="/mobile/feedViewer/photoViewer?countryCode=<?=$countryCode?>&missionNo=" data-ajax="false">View All Photos</a></li>
        <? foreach($missionList as $missionData): ?>
        <li><a href="/mobile/feedViewer/photoViewer?countryCode=<?=$countryCode?>&missionNo=<?=$missionData->missionNo?>" data-ajax="false"><?=str_replace('Share a picture of ', '', $missionData->description)?></a></li>
        <? endforeach;?>
    </ul>
    </div><!-- /content -->
 
    <div data-role="footer">
        <h4>My Footer</h4>
    </div><!-- /header -->
 
</div><!-- /page -->