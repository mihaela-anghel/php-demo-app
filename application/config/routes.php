<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] 			= 'home';
$route['404_override'] 					= '';
$route['translate_uri_dashes'] 			= FALSE;

//variables
$lang 									= "([a-zA-Z]{2}/)?";
$index_lang								= "([a-zA-Z]{2}/?)?";

//admin
$route["admin"] 						= "admin";
$route["admin/(.*)"] 					= "admin/$1";

//url unilang
//$route["((.*)-p[0-9]+)?/?(.*)"]		= 'page/index/$1/$3';
//$route["cronjob.php"] 	 			= 'cronjob/index';
//$route["notariate/(.*)"] 				= 'notariate/index/$1';
//$route["anunturi/(.*)?/?(.*)"] 		= 'anunturi/index/$1';

//url multilang
$route["{$lang}generator"]		 	 	= 'generator/index';
$route["{$lang}error_404"]		 	 	= 'error_404/index';
$route["{$lang}((.*)-pag[0-9]+)/?(.*)"]	= 'page/index/$2/$4';
$route["{$lang}((.*)-gal[0-9]+)/?(.*)"]	= 'galleries/details/$2';
$route["{$lang}project/(.*)"] 	 		= 'project/index/$2';
$route["cronjob.php"] 	 				 = 'cronjob/index';

$route["{$lang}((.*)-article[0-9]+)/?(.*)"]	= 'articles/details/$2';
$route["{$lang}articles/?(.*)?/?(.*)"] 	    = 'articles/index/$2/$3';
 
//language
$route["{$index_lang}"]				='home';
$route["{$lang}(.*)"]				='$2';
$route["{$lang}(.*)/(.*)"]			='$2/$3';
