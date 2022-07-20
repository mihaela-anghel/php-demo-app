//display on vif from more
//========================================================
function show_div(vector_divs,id_active_div)
{	
	var array = new Array(id_active_div);	    
	for(var i=0; i< vector_divs.length; i++ )
    {    	
    	if(vector_divs[i] != id_active_div)
    		document.getElementById(vector_divs[i]).style.display = 'none';
    	else if(vector_divs[i] == id_active_div)
    		document.getElementById(array[0]).style.display = 'block';
    }
}

//disable option in a select on IE
//========================================================
jQuery(document).ready(function() {
	  jQuery('option[disabled]').css({'color': '#999999'});
	  jQuery('select').change(function() {
	    if(this.options[this.selectedIndex].disabled) {
	      if(this.options.length == 0) {
	        this.selectedIndex =-1;
	      } else {
	        this.selectedIndex--;
	      }
	      jQuery(this).trigger('change');
	    }
	  });
	  jQuery('select').each(function(it) {
	    if(this.options[this.selectedIndex].disabled)
	      this.onchange();
	  });
	});


//select all checkbox
//========================================================
function checkAll(field) 
{	
	if (!(field.length)) 		
		field.checked = true;			
	else		
		for (var i = 0; i < field.length; i++) 		
			field[i].checked = true;				
}

//deselect all checkbox
//========================================================
function uncheckAll(field) 
{
	if (!(field.length)) 
		field.checked = false;
	else 
		for (var i = 0; i < field.length; i++)
			field[i].checked = false;
} 


//set autoheight for an element (iframe, div, etc)
//========================================================
var last_h;	
function auto_height(parent_element_id, current_element, height_for_completion)
{	
	last_h=jQuery(current_element).height();
	//setInterval("set_height('"+parent_element_id+"','"+current_element+"','"+height_for_completion+"')", 200);			
	set_height(parent_element_id,current_element, height_for_completion)
}
function set_height(parent_element_id,current_element, height_for_completion)
{
	if(last_h!=0 && last_h==jQuery(current_element).height())
	{
		height_for_completion = parseInt(height_for_completion);
		h=jQuery(current_element).height()+height_for_completion;						
		if(parent.document.getElementById(parent_element_id).style.height != null && parent.document.getElementById(parent_element_id).style.height!=h+'px')
		{
			parent.document.getElementById(parent_element_id).style.height = h+'px';
		}						
	}
	else
	{
		last_h=jQuery(current_element).height();
	}	
}

//change field value by ajax in admin
//========================================================
function change_ajax(element)
{	
	var jsontext 	= $(element).parent().find('span.hide').html();
	var array 		= jQuery.parseJSON(jsontext);
		
	new_value 	= array["values"][0];
	new_class 	= array["classes"][0];
	new_label 	= array["labels"][0];
	if(element.attr('class') == array["classes"][0])
	{
		new_value 	= array["values"][1];
		new_class 	= array["classes"][1];
		new_label 	= array["labels"][1];
	}		
	url =  array["url"]+'/'+array["field"]+'/'+new_value;			
	
	$(element).after('<div id="change_ajax_loading" style="position:absolute; margin-left:-20px;"><img src = "'+base_url+'images/loading.gif" width = "20" height="20" alt="" /></div>');
	jQuery.get(url, {},
	function(data)
	{																																																							
		element.attr('class',new_class);		
		element.html(new_label);		
		$('#change_ajax_loading').remove();
	});				
}