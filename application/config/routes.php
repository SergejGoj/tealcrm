<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['terms']                	= "pages/index/terms";
$route['privacy']               = "pages/index/privacy";
$route['help']                	= "pages/index/help";
$route['about']                	= "pages/index/about";
$route['resources']             = "pages/index/resources";
$route['contact-us']            = "pages/contact_us";
$route['offline/bypass/(\d+)']  = "pages/offline_bypass/$1";
$route['offline']               = "pages/offline";


$route['settings/users']               = "auth/index";

// [pager]
$route['companies/(\d+)']        = "companies/index/$1";
$route['people/(\d+)']        = "people/index/$1";
$route['deals/(\d+)']           = "deals/index/$1";
$route['notes/(\d+)']           = "notes/index/$1";
$route['tasks/(\d+)']           = "tasks/index/$1";
$route['meetings/(\d+)']        = "meetings/index/$1";

$route['messages/(\d+)']        = "messages/index/$1";

// [known routes]
$route['default_controller']    = "users/dashboard";
$route['404_override']          = "pages/index/404";


/* End of file routes.php */
/* Location: ./application/config/routes.php */