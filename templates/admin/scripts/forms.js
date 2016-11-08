function MM_sortField(targ,osk,sk,sd){
  var url = document.location.href;
  url = url.replace('&sk='+osk,'');
  url = url.replace('&sk='+sk,'');
  url = url.replace('&sd=ASC','');
  url = url.replace('&sd=DESC','');
  url = url.replace('&ecode=','&code=');
  url = url.replace('&rcode=','&code=');
  eval(targ+".location='"+url+"&sk="+sk+"&sd="+sd+"'");
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+document.location.href+'&ipp='+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function MM_jump(targ,selObj,url) {
	document.getElementById(targ).src=url+selObj.options[selObj.selectedIndex].value;
}

function showFieldValueControl(selObj,id)
{
	if (selObj.options[selObj.selectedIndex].value > 3) {
		document.getElementById(id).className = "";
	} else document.getElementById(id).className = "hidden";
}

function showControl(id)
{
	document.getElementById(id).className = "";
}

// JavaScript Document
function checkSelect( ojSelect, text ) {
	var k ;
	for (k=ojSelect.options.length-1; k>=0; k--) {
		if (ojSelect.options(k).value == text) {
			ojSelect.options(k).selected = true;
			return true;			
		}
	}
	return false;
}


function checkValue( ojSelect, value ) {
	var k;
	for (k=ojSelect.options.length-1; k>=0; k--) {
		if (ojSelect.options(k).value == value) {
			ojSelect.options(k).selected = true;
			return true;			
		}
	}
	return false;
}

function checkRadio( ojSelect, value ) {
	var k;
	for (k=ojSelect.length-1; k>=0; k--) {
		if (ojSelect(k).value == value) {
			ojSelect(k).checked = true;
			return true;			
		}
	}
	return false;
}
function checkCheck( ojSelect, value ) {
	var k;
	if (ojSelect.value == value) {
		ojSelect.checked = true;
		return true;			
	}
	return false;
}
/* cach su dung 
<script language="javascript" >
	var oj = document['tenForm']['tenSelect'];
	checkSelect(oj,'{TinhTrang}');
</script>
*/

function toggleAllChecks(formName, prefix)
{
	n = "all";

    if (prefix)
    {
        n = prefix + n;
    }
    i = 0;
    e = document.getElementById(n);
    s = e.checked;
    f = document.getElementById(formName);

    while (e = f.elements[i])
    {
        if (e.type == "checkbox" && e.id != n)
        {
            if (!prefix || e.id.indexOf(prefix) != -1)
            {
                e.checked = s;
            }
        }

        i++;
    }
}

function toggleAllChecksPrefix(formName, prefix)
{
	n = "all";

    if (prefix)
    {
        n = prefix + '_' + n;
    }
    i = 0;
    e = document.getElementById(n);
    s = e.checked;
    f = document.getElementById(formName);

    while (e = f.elements[i])
    {
        if (e.type == "checkbox" && e.id != n)
        {
            if (e.id.indexOf(prefix) != -1)
            {
                e.checked = s;
            }
        }
        i++;
    }
}

function formSubmit(form, vmod, vdo, vid)
{
	//alert('hi');
	f = document.getElementById(form);
	f.mod.value = vmod;
	f.doo.value = vdo;
	f.id.value = vid;
	f.submit();
}

function activeSubmit(form)
{
	f = document.forms(form);
	f.plus.value="active";
	f.submit();
}

function activePlusSubmit(form,act)
{
	f = document.forms(form);
	f.plusAct.value=act;
	f.submit();
}

//Variables for addInput functions
var counter = 1;
var limit = 5;
function addInput(divName, fieldType,fieldName,fieldValue){
	if (counter == limit)  {
          alert("Bạn chỉ được quyền tải lên tối đa " + counter + " tập tin mỗi lần!");
     }
     else {
          var newdiv = document.createElement('div');
          newdiv.innerHTML = "<p><input type='"+fieldType+"' name='"+fieldName+"' id='"+fieldName+"' value='"+fieldValue+"'></p>";
          document.getElementById(divName).appendChild(newdiv);
          counter++;
     }
}



function formatCurrency(id) {
	var variable = document.getElementById(id);	
	num = variable.value.toString().replace(/\$|\,/g,"");
	if (isNaN(num)) num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num * 100 + 0.50000000001);
	cents = num % 100;
	num = Math.floor(num / 100).toString();
	if (cents < 10) cents = "0" + cents;
	var maxi = Math.floor((num.length -1)/3);
	for(var i=0; i<maxi; i++)
	num = num.substring(0, num.length - 4*i - 3) + ',' + num.substring(num.length - 4*i -3);
	variable.value=(sign?'':'-')+num+'.'+cents;
}

function showProgressBar( elementToHide )
{
   button = document.getElementById( elementToHide );
   button.style.display = "none";     
   bar = document.getElementById("status_bar");
   bar.style.display = "block";
}

/**
 * resets the user picture/avatar in the profile page
 */
function resetAvatarPicture()
{
    window.document.updateConfig.avatarId.value = 0;
    // and reload the image path
    window.document.updateConfig.avatarPicture.src = '/images/nophoto.gif';
}

function avatarPictureSelectWindow()
{
	width  = 500;
	height = 450;
	
	x = parseInt(screen.width / 2.0) - (width / 2.0);
	y = parseInt(screen.height / 2.0) - (height / 2.0);
	
	UserPicture = window.open( '?op=manage&act=compactListResource&objectId=Avatar', 'AvatarPictureSelect','top='+y+',left='+x+',scrollbars=yes,resizable=yes,toolbar=no,height='+height+',width='+width);
}
function returnAvatarResourceInformation(resId, url)
{
	// set the picture id
    parent.opener.document.updateConfig.avatarId.value = resId;
    // and reload the image path
    parent.opener.document.updateConfig.avatarPicture.src = url;
}

/**
 * resets the map picture in the profile page
 */
function resetMapPicture()
{
    window.document.updateMap.mapId.value = 0;
    // and reload the image path
    window.document.updateMap.mapPicture.src = '/images/nophoto.gif';
}

function mapPictureSelectWindow()
{
	width  = 500;
	height = 450;
	
	x = parseInt(screen.width / 2.0) - (width / 2.0);
	y = parseInt(screen.height / 2.0) - (height / 2.0);
	
	UserPicture = window.open( '?op=manage&act=compactListResource&objectId=Map', 'MapPictureSelect','top='+y+',left='+x+',scrollbars=yes,resizable=yes,toolbar=no,height='+height+',width='+width);
}
function returnMapResourceInformation(resId, url)
{
	// set the picture id
    parent.opener.document.updateMap.mapId.value = resId;
    // and reload the image path
    parent.opener.document.updateMap.mapPicture.src = url;
}

function actionSubmit(form,action)
{
f = document.getElementById(form);
f.plus.value=action;
}

function changePosition(form,id)
{
f = document.getElementById(form);
control = document.getElementById('position_'+id);
f.plus.value='changePosition';
f.cId.value=id;
f.position.value=control.value;
f.submit();
}