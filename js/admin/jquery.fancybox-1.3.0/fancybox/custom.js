jQuery(document).ready(function() {
		
	jQuery(".fancybox_image").fancybox({		
		'padding'			: 0,				   
		'transitionIn'		: 'fade',
		'transitionOut'		: 'fade',		
		'overlayShow'		: true,
		'opacity'			: true,
		'overlayColor'		: '#000',
		'overlayOpacity'	: 0.5
	});	
	
	jQuery(".fancybox_iframe").each(function() {
		a = jQuery(this).attr('rel');
		arr = a.split(',');
		w = parseInt(arr[0]);
		h = parseInt(arr[1]);								
		
		jQuery(this).fancybox({	
			'showNavArrows'		: false,
			'padding'			: 0,				  
			'width'         	: w,
			'height'        	: h,						
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'type'				: 'iframe',			
			'overlayShow'		: true,
			'opacity'			: true,
			'overlayColor'		: '#000',
			'overlayOpacity'	: 0.5
		});
	});
	
	$("a[rel=fancybox_group]").fancybox({
		'padding'			: 0,				   
		'transitionIn'		: 'fade',
		'transitionOut'		: 'fade',		
		'overlayShow'		: true,
		'opacity'			: true,
		'overlayColor'		: '#000',
		'overlayOpacity'	: 0.5,		
		'titlePosition' 	: 'outside',
		'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
		    return '<span id="fancybox-title-over">Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
		}
	});

});	