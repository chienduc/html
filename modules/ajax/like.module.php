<?php
/***************************************************************
Module Like
Coder: Phantom
****************************************************************/
include_once(ROOT_PATH.'classes/dao/comments.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$comments = new Comments(1);
$id=$request->element('id');
if(isset($_SESSION['comment_'.$id])){
 echo 0;
}else{
$_SESSION['comment_'.$id]='oke';
$commentInfo = $comments->getObject($id);
if($commentInfo){
$comments->updateData(array('like'=>$commentInfo->getLike() + 1),$id);

}
echo 1;
}

?>