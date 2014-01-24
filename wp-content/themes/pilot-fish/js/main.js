///////////////////////////////		
// Mobile Detection
///////////////////////////////

function isMobile(){
    return (
        (navigator.userAgent.match(/Android/i)) ||
		(navigator.userAgent.match(/webOS/i)) ||
		(navigator.userAgent.match(/iPhone/i)) ||
		(navigator.userAgent.match(/iPod/i)) ||
		(navigator.userAgent.match(/iPad/i)) ||
		(navigator.userAgent.match(/BlackBerry/))
    );
}

///////////////////////////////
// Parallax Scrolling
///////////////////////////////

// Calcualte the home banner parallax scrolling
function scrollBanner() {
    //Get the scoll position of the page
    scrollPos = jQuery(this).scrollTop();

    //Scroll and fade out the banner text
    jQuery('.featured-title').css({
      'margin-top' : -(scrollPos/3)+"px",
      'opacity' : 1-(scrollPos/300)
    });
}

///////////////////////////////
// Initialize Parallax 
///////////////////////////////	

jQuery(document).ready(function(){
	if(!isMobile()) {
		jQuery(window).scroll(function() {	      
	       		scrollBanner();	      
		});
	}
	else {
		jQuery('#banner-text').css({
			'position': 'relative',
			'text-align': 'center',
			'margin': 'auto',
			'padding': '0',
			'top':'0'
		});
	}
});

///////////////////////////////
// Hide Comment Form Elements
///////////////////////////////
	
jQuery(document).ready(function(){
	jQuery( '#commentform label' ).hide();
	jQuery( '#commentform .required' ).hide();
});

///////////////////////////////
// Remove Title of Images
///////////////////////////////

/* The first line waits until the page has finished to load and is ready to manipulate */
$(document).ready(function(){
    /* remove the 'title' attribute of all <img /> tags */
    $("img").removeAttr("title");
});

///////////////////////////////
// Dropdown Menus for Mobile
///////////////////////////////

jQuery(document).ready(function(){
		jQuery("<select />").appendTo("nav");
      		jQuery("<option />", {
		 	"selected": "selected",
		 	"value"   : "",
		 	"text"    : "Go to..."
	      	}).appendTo("nav select");
	      	jQuery("nav a").each(function() {
	       	var el = jQuery(this);
	       	jQuery("<option />", {
		   	"value"   : el.attr("href"),
		   	"text"    : el.text()
	       	}).appendTo("nav select");
	      	});
	      	jQuery("nav select").change(function() {
			window.location = jQuery(this).find("option:selected").val();
	      	}); 
});
