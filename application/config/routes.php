<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "users_interface";
$route['404_override'] = '';

/***************************************************	USERS INTRERFACE	***********************************************/
$route['']								= "users_interface/index";
$route['about']							= "users_interface/about";
$route['webmasters']					= "users_interface/webmasters";
$route['optimizers']					= "users_interface/optimizers";
$route['regulations']					= "users_interface/regulations";
$route['support']						= "users_interface/support";
$route['faq']							= "users_interface/faq";

$route['users/cabinet']					= "users_interface/access_cabinet";

$route['users/registering']				= "users_interface/registering";
$route['users/registering/successfull']	= "users_interface/reg_successfull";
$route['users/logoff']					= "users_interface/logoff";

/**************************************************	   CLIENTS INTRERFACE	***********************************************/


/***************************************************   MANAGERS INTRERFACE	***********************************************/


/*************************************************** OPTIMIZATORS INTRERFACE	*******************************************/


/*************************************************** 	ADMINS INTRERFACE	***********************************************/
$route['admin-panel/actions/control']			= "admin_interface/control_panel";
$route['admin-panel/actions/cabinet']			= "admin_interface/actions_cabinet";

$route['admin-panel/management/users/:any/from/:num']	= "admin_interface/management_users";
$route['admin-panel/management/users/:any']				= "admin_interface/management_users";

$route['admin-panel/management/platforms']		= "admin_interface/management_platforms";

$route['admin-panel/messages/support']			= "admin_interface/messages_support";
$route['admin-panel/messages/tickets']			= "admin_interface/messages_tickets";