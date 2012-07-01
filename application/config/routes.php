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

$route[':any/viewimage/:num']			= "users_interface/viewimage";

/**************************************************	   CLIENTS INTRERFACE	***********************************************/
$route['webmaster-panel/actions/control']						= "clients_interface/control_panel";

$route['webmaster-panel/actions/platforms']						= "clients_interface/control_platforms";
$route['webmaster-panel/actions/platforms/add-platform']		= "clients_interface/control_add_platform";
$route['webmaster-panel/actions/platforms/edit-platform/:num']	= "clients_interface/control_edit_platform";

$route['webmaster-panel/actions/mails']							= "clients_interface/control_mails";
$route['webmaster-panel/actions/platforms/reply/mail-id/:num']= "clients_interface/control_reply_mail";
$route['webmaster-panel/actions/platforms/delete/mail-id/:num']	= "clients_interface/control_delete_mail";

$route['views/market-profile'] 							= "clients_interface/views";
$route['webmaster-panel/actions/cabinet']				= "clients_interface/actions_cabinet";

/***************************************************   MANAGERS INTRERFACE	***********************************************/


/*************************************************** OPTIMIZATORS INTRERFACE	*******************************************/


/*************************************************** 	ADMINS INTRERFACE	***********************************************/
$route['admin-panel/actions/control']			= "admin_interface/control_panel";
$route['admin-panel/actions/cabinet']			= "admin_interface/actions_cabinet";

$route['admin-panel/management/users/:any/from/:num']	= "admin_interface/management_users";
$route['admin-panel/management/users/userid/:num']		= "admin_interface/management_users_deleting";
$route['admin-panel/management/users/:any']				= "admin_interface/management_users";

$route['admin-panel/management/platforms']				= "admin_interface/management_platforms";
$route['admin-panel/management/markets']				= "admin_interface/management_markets";
$route['admin-panel/management/markets/marketid/:num']	= "admin_interface/management_markets_deleting";


$route['admin-panel/messages/support']									= "admin_interface/messages_support";
$route['admin-panel/messages/private-messages']							= "admin_interface/messages_private";
$route['admin-panel/messages/private-messages/from']					= "admin_interface/messages_private";
$route['admin-panel/messages/private-messages/from/:num']				= "admin_interface/messages_private";

$route['admin-panel/messages/system-message']				= "admin_interface/messages_system";

$route['admin-panel/messages/private-messages/delete-mail/mailid/:num']	= "admin_interface/messages_private_delete";
$route['admin-panel/messages/tickets']									= "admin_interface/messages_tickets";