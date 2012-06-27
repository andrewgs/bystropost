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

$route['users/registering']				= "users_interface/registering";
$route['users/registering/successfull']	= "users_interface/reg_successfull";
$route['users/logoff']					= "users_interface/logoff";

/**************************************************	   CLIENTS INTRERFACE	***********************************************/


/***************************************************   MANAGERS INTRERFACE	***********************************************/


/*************************************************** OPTIMIZATORS INTRERFACE	*******************************************/


/*************************************************** 	ADMINS INTRERFACE	***********************************************/
