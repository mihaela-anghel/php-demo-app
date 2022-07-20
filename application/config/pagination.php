<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(substr_count(current_url(),"admin") > 0)
{
	$config['per_page'] 		= 20;
	$config['num_links'] 		= 7;
	
	$config['num_tag_open'] 	= '<span class = "pagination">';
	$config['num_tag_close'] 	= '</span>';
	
	$config['cur_tag_open'] 	= '<span class = "pagination_selected">';
	$config['cur_tag_close'] 	= '</span>';		
	
	$config['prev_link'] 		= '&laquo;';
	$config['prev_tag_open'] 	= '<span class = "pagination prev_next">';
	$config['prev_tag_close'] 	= '</span>';
	
	$config['next_link'] 		= '&raquo;';
	$config['next_tag_open'] 	= '<span class = "pagination prev_next">';
	$config['next_tag_close'] 	= '</span>';
	
	$config['first_link'] 		= 'First';
	$config['first_tag_open']   = '<span class = "pagination first_last">';
	$config['first_tag_close']  = '</span>';
	
	$config['last_link'] 		= 'Last';
	$config['last_tag_close'] 	= '</span>';		
	$config['last_tag_open'] 	= '<span class = "pagination first_last">';
}
else
{
	$config['per_page'] 		= 20;
	$config['num_links'] 		= 5;
	
	$config['full_tag_open'] 	= '<ul class="pagination clearfix">';
	$config['full_tag_close'] 	= '</ul>';
	
	$config['num_tag_open'] 	= '<li>';
	$config['num_tag_close'] 	= '</li>';
	
	$config['cur_tag_open'] 	= '<li class="active"><a><b>';
	$config['cur_tag_close'] 	= '</b></a></li>';		
	
	$config['prev_link'] 		= '<i class="fa fa-angle-double-left"></i> Prev';
	$config['prev_tag_open'] 	= '<li class = "prev">';
	$config['prev_tag_close'] 	= '</li>';
	
	$config['next_link'] 		= 'Next <i class="fa fa-angle-double-right"></i>';
	$config['next_tag_open'] 	= '<li class = "next">';
	$config['next_tag_close'] 	= '</li>';
	
	$config['first_link'] 		= 'First';
	$config['first_tag_open']   = '<li class = "first">';
	$config['first_tag_close']  = '</li>';
	
	$config['last_link'] 		= 'Last';
	$config['last_tag_close'] 	= '</li>';		
	$config['last_tag_open'] 	= '<li class = "last">'; 
}