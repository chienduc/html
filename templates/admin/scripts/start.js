/* Javascript By PhanTom*/
var gProject = {};

/*************************************
 ***** Main functions start here *****
 *************************************/

$(document).ready(function(){	
	gProject.initElements.initDatepicker();
});

gProject.initForm = {};

gProject.initElements = {
	initDatepicker : function(){
		//if(jQuery('.inputCal').length > 0){
			var d = new Date();
			var nextday = (d.getDate()+1) + "/" + (d.getMonth()+1) + "/" + d.getFullYear();
			jQuery('#schedule-ngayden-img').unbind().bind('click', function(e){
				e.preventDefault();
				jQuery( "#txtNgayDen" ).datepicker( 'show');
			});
			$( "#txtNgayDen" ).datepicker({
			  defaultDate: "+1w",
			  changeMonth: true,
			  numberOfMonths: 1,
			  dateFormat: "dd/mm/yy",
			  setDate: new Date(),
			  onClose: function( selectedDate ) {
				$( "#txtNgayDi" ).datepicker( "option", "minDate", selectedDate );
			  }
			}).datepicker();
			
			jQuery('#schedule-ngaydi-img').unbind().bind('click', function(e){
				e.preventDefault();
				jQuery( "#txtNgayDi" ).datepicker( 'show');
			});
			$( "#txtNgayDi" ).datepicker({
			  defaultDate: "+1w",
			  changeMonth: true,
			  numberOfMonths: 1,
			  dateFormat: "dd/mm/yy",
			  onClose: function( selectedDate ) {
				$( "#txtNgayDen" ).datepicker( "option", selectedDate );
			  }
			}).datepicker();;
		//}
	},
	
	
};
function getAreas(){
	       var id = jQuery("#area :selected").val();
            if(id!=''){
                jQuery.ajax({
        			type:"GET",
        			url: "/ajax.php?op=getareas",
					data:'id='+id,
        			async:false,
        			dataType:"html",
        			success: function(html){
        			    $('#areas').html(html);
        			}
		          });	
            }	
    }
function getAreasss(){
	       var id = jQuery("#areass :selected").val();
            if(id!=''){
                jQuery.ajax({
        			type:"GET",
        			url: "/ajax.php?op=getareas",
					data:'id='+id,
        			async:false,
        			dataType:"html",
        			success: function(html){
        			    $('#areasss').html(html);
        			}
		          });	
            }	
    }
