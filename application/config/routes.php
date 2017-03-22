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
$route['login'] = 'login/index';

//admin
$route['admin'] = 'admin/index';
$route['admin/deleted-items'] = 'admin/deleted_items';
$route['admin/locations'] = 'admin/locations';
$route['admin/categories'] = 'admin/categories';
$route['admin/statistics'] = 'admin/statistics';

//upload
$route['upload/do_upload/(:num)'] = 'upload/do_upload/$1';

//search routes
$route['search'] = 'search';

//location routes
$route['locations/create'] = 'locations/create';
$route['locations/get'] = 'locations/get';
$route['locations/update/(:num)'] = 'locations/update/$1';
$route['locations/delete/(:num)'] = 'locations/delete/$1';
$route['locations'] = 'locations/index';

//item routes
$route['items/get'] = 'items/get';
$route['items/restore/(:num)'] = 'items/restore/$1';
$route['items/delete/(:num)'] = 'items/delete/$1';
$route['items/create'] = 'items/create';
$route['items/create/(:num)'] = 'items/create/$1';
$route['items/remove/(:num)'] = 'items/remove/$1';
$route['items/view(:num)'] = 'items/view/$1';
$route['items/(:num)/(:num)'] = 'items/index/$1/$2';

//category routes
$route['categories/get'] = 'categories/get';
$route['categories/create'] = 'categories/create';
$route['categories/update/(:num)'] = 'categories/update/$1';
$route['categories/delete/(:num)'] = 'categories/delete/$1';
$route['categories/(:num)'] = 'categories/index/$1';

//usernote routes
$route['usernotes/get'] = 'usernotes/get';
$route['usernotes/set'] = 'usernotes/set';
$route['usernotes/remove/(:num)'] = 'usernotes/remove/$1';

//user routes
$route['users/(:num)'] = 'users/index/$1';
$route['users/get'] = 'users/get';

//role routes
$route['roles/get'] = 'roles/get';

//loan routes
$route['loans/get'] = 'loans/get';
$route['loans/set'] = 'loans/set';
$route['loans/delete/(:num)'] = 'loans/delete/$1';
$route['loans/close/(:num)'] = 'loans/close/$1';
$route['loans/(:any)/(:num)'] = 'loans/$1/$2';

//default routes
$route['default_controller'] = 'locations';
$route['(:any)'] = 'locations';
