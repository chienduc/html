<?php
/***************************************************************
Module Check Domain
Coder: Phantom
****************************************************************/

# Get value
$name = $request->element('name');
$ex = $request->element('ex');
$string='';

$content = file_get_contents("http://whois.matbao.vn/rss/".$name.".".$ex."/0");
$x = new SimpleXmlElement($content);
foreach($x->channel->item as $entry) {  
                $string .= $entry->title .': '. $entry->description.'<br>';  
       }  
$contents = file_get_contents("http://whois.matbao.vn/rss/".$name.".".$ex."/1");
$xx = new SimpleXmlElement($contents);
if($xx->channel->item)
   foreach($xx->channel->item as $entry) {  
                $string .= $entry->title .': '. $entry->description.'<br>';         
}
echo $string;
?>
