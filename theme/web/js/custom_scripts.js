

//jQuery.noConflict();
jQuery(document).ready(function($)
{

var open=false;

var menuvisible=false;
var acesovisible=false;
var buscavisible=false;



jQuery('#tab-container').easytabs();




 /*

jQuery("#form-lgin-3 > div > div > input").unbind('click').click(function(event)
{


alert("funciona");


});

*/




/*  
  jQuery("#cart_button_top , #cart_top_ul").mouseover(function() {

  
  jQuery("#cart_top_ul").css({
   'opacity' : '1',
   '-webkit-transform': 'translate(0,0)',
   '-moz-transform': 'translate(0,0)',
   'transform': 'translate(0,0)'
});
  
  
  
  }).mouseout(function() {


  
	  jQuery("#cart_top_ul").css({
   'opacity' : '0',
   '-webkit-transform': 'translate(856px,0)',
   '-moz-transform': 'translate(856px,0)',
   'transform': 'translate(856px,0)'
});
  


	
  });
*/  
  
   

  

jQuery("#btn_c_acc_movilhe").unbind('click').click(function(){



alert("form");







});







jQuery(".menu-items-wrapper  .menu-items-wrapper-right").unbind('click').click(function(){


jQuery(".user-container i").hide();
jQuery(".user-container i.fa-bars").show();
closeNav();
open=false;

});




		jQuery(".btnmenu").unbind('click').click(function(){
		
		
        jQuery(".user-container i").hide();
		
		if(!open)
		{
		
		//jQuery(".menu-items-wrapper").slideDown();
		
		jQuery(".user-container i.fa-times").show();
		openNav();
		
		 }
		else
		{
		
		//jQuery(".menu-items-wrapper").slideUp();
		jQuery(".user-container i.fa-bars").show();
		closeNav();
		
		
		}
		/*
		v1=jQuery(".menumobile").outerHeight();
		v2=jQuery(".topinfo").outerHeight();
		starttop=v1+v2;
		*/
		//alert(starttop);
		
		
		
		open=!open;
		});






		
		
		//menu aceso
		jQuery("#btn_aceso").unbind('click').click(function(){
		
	
	
		if(!acesovisible)
		{
	
	
			jQuery("#login_mobile_window" ).fadeIn( "normal", function() {
					// Animation complete.
					
					
					
					acesovisible=!acesovisible;
			});
	
	
		}
		else
		{
		
			jQuery("#login_mobile_window" ).fadeOut( "normal", function() {
					// Animation complete.
					
					
					
					acesovisible=!acesovisible;
			});
	
		}
	
		
		
		
		
		
		
		});


		
		
		
		
		
		
		//open busca mobile
		
		
		//menu aceso
		jQuery("#btn_search_mobile").unbind('click').click(function(){
		
	
	
		if(!buscavisible)
		{
	
	
			jQuery("#search_mobile_window" ).fadeIn( "normal", function() {
					// Animation complete.
					
					
					
					buscavisible=!buscavisible;
			});
	
	
		}
		else
		{
		
			jQuery("#search_mobile_window" ).fadeOut( "normal", function() {
					// Animation complete.
					
					
					
					buscavisible=!buscavisible;
			});
	
		}
	
		
		
		
		
		
		
		});


		
		//end busca mobile
		
		
		
		
		//close login_mobile_window
		jQuery("#btn_close_login_mobile").unbind('click').click(function(){
		
	
	
		if(acesovisible)
		{
	
	
			jQuery("#login_mobile_window" ).fadeOut( "normal", function() {
					// Animation complete.
					
					
					
					acesovisible=!acesovisible;
			});
	
	
		}
		
		});

		//close busca_mobile_window
		jQuery("#btn_close_search_mobile").unbind('click').click(function(){
		
	
	
		if(buscavisible)
		{
	
	
			jQuery("#search_mobile_window" ).fadeOut( "normal", function() {
					// Animation complete.
					
					
					
					buscavisible=!buscavisible;
			});
	
	
		}
		
		});
 


		
		jQuery('.bxslider').bxSlider({
  auto: true,
  autoControls: true
});
		   
		   

		   
		   
		   //click parent subitem
		   //close login_mobile_window
		jQuery(".titlemobilewrapper").unbind('click').click(function(event){
		event.preventDefault();
		
		var class_to_show='sub_'+jQuery(this).attr('class').split(' ')[1];
		
		//alert(".subitem_wrapper ."+class_to_show);
		
		//jQuery('.subitem_wrapper').hide();
		
		if(jQuery(this).hasClass("openedsub"))
		{
		
		jQuery(this).removeClass("openedsub");
		jQuery('.subitem_wrapper.'+class_to_show).slideUp();		
		
		}
		else{
		
		
		jQuery(this).addClass("openedsub");
		jQuery('.subitem_wrapper.'+class_to_show).slideDown();
		
		
		}
		
		
		
		});




});





function openNav() {
    document.getElementById("menu_principal_wrapper").style.width = "100%";
	/*
    document.getElementById("slideright1").style.marginLeft = "350px";
    document.getElementById("slideright2").style.marginLeft = "350px";
	*/
}

function closeNav() {
    document.getElementById("menu_principal_wrapper").style.width = "0";
	/*
    document.getElementById("slideright1").style.marginLeft= "0";
    document.getElementById("slideright2").style.marginLeft= "0";
	*/
}

var cat_opened=false;


function showcatt()
{

if(cat_opened)
{


jQuery("#cat_opened").slideUp();


}
else{


jQuery("#cat_opened").slideDown();


}


cat_opened=!cat_opened;

}










/** start tabs **/

function openlist(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

/** end tabs **/