/*	Javascript code for all elements
/*----------------------------------------------------*/

jQuery(window).load(function(){	
	var $container = jQuery('.grid-list');
	$container.masonry({
		itemSelector: '.grid-item-list',
		columnWidth: 190,
		isFitWidth: true,
		gutter: 20
	});
  });

	  jQuery(document).ready(function($) {
	    $('#slide-left').flexslider({
	      animation: "slide",
	      manualControls: "",
	      });
	  });


