<?php
/***************************************************************
Module Check Domain
Coder: Phantom
****************************************************************/

# Get value
$name = $request->element('name');
$ex = $request->element('ex');
$string='';
# Valid data input
$array=explode("_",$ex);
foreach($array as $ar){
	 $content = file_get_contents("http://whois.matbao.vn/rss/".$name.".".$ar."/0"); 
	 $x = new SimpleXmlElement($content); 
	 if($x->channel->item) $string.='_'.$ar.'|1';
	   else $string.='_'.$ar.'|0';
}
echo $string;
?>