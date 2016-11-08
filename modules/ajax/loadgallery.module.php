<?php
/***************************************************************
Module Load gallary
Coder: Phantom
****************************************************************/

# Get value
$cid = $request->element('id');
$lang = $request->element('lang');
$lang='vn';
$string='';

include_once(ROOT_PATH.'classes/dao/activities.class.php');
include_once(ROOT_PATH.'classes/dao/activitiescategories.class.php');
$activitiesCategories = new ActivitiesCategories($sId);
$activities = new Activities($sId);

$categoryInfo = $activitiesCategories->getObject($cid);
if($categoryInfo){
$listItems = $activities->getObjects(1,"`status`=1 AND `wid`=$cid",array("position"=>"asc"),20);
$string .='<h4>'.$categoryInfo->getName($lang).'</h4>
           <div class="slide pikachoose">
            <ul id="pikame" class="jcarousel-skin-pika">';
if($listItems){
	foreach($listItems as $item){
		$string .='<li><img src="'.DOMAIN.'/upload/1/activities/l_'.$item->getProperty('avatar').'" alt="'.$item->getName($lang).'" /></li>';
	}
	
}
$string .='</ul>
           </div>';

}
if($string) echo $string;
else echo 1;
?>