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
//login
$route['login'] = 'login';

//admin
$route['admin'] = 'admin';

//upload
$route['upload'] = 'upload';
$route['upload/do_upload'] = 'upload/do_upload';
$route['upload/do_upload/(:any)'] = 'upload/do_upload/$1';

//search routes
$route['search'] = 'search';

//location routes
$route['locations'] = 'locations';
$route['locations/create'] = 'locations/create';
$route['locations/update/(:any)'] = 'locations/update/$1';
$route['locations/delete/(:any)'] = 'locations/delete/$1';

//item routes
$route['items'] = 'items';
$route['items/restore/(:any)'] = 'items/restore/$1';
$route['items/delete/(:any)'] = 'items/delete/$1';
$route['items/location/(:any)'] = 'items/bylocation/$1';
$route['items/create'] = 'items/create';
$route['items/create/(:any)'] = 'items/create/$1';
$route['items/remove/(:any)'] = 'items/remove/$1';
$route['items/detail/(:num)/(:num)'] = 'items/detail/$1/$2';
$route['items/(:any)'] = 'items/view/$1';

//category routes
$route['categories/create'] = 'categories/create';
$route['categories/update/(:any)'] = 'categories/update/$1';
$route['categories/delete/(:any)'] = 'categories/delete/$1';

//usernote routes
$route['usernotes/remove/(:any)'] = 'usernotes/remove/$1';

//default routes
$route['default_controller'] = 'locations';
$route['(:any)'] = 'locations';
