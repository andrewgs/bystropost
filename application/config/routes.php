<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "users_interface";
$route['404_override'] = '';

/***************************************************	USERS INTRERFACE	***********************************************/
$route['']								= "users_interface/index";
$route['users/login']					= "users_interface/loginin";
$route['about']							= "users_interface/about";
$route['partners-program']				= "users_interface/partners_program";
$route['webmasters']					= "users_interface/webmasters";
$route['optimizers']					= "users_interface/optimizers";
$route['faq']							= "users_interface/faq";
$route['manner-of-payment']				= "users_interface/manner_payment";
$route['site-monetization']				= "users_interface/site_monetization";
$route['additional-services']			= "users_interface/additional_services";
$route['disclaimer']					= "users_interface/disclaimer";
$route['about-content']					= "users_interface/about_content";
$route['capabilities']					= "users_interface/capabilities";
$route['interface']						= "users_interface/site_interface";

$route['news']							= "users_interface/news";
$route['news/from']						= "users_interface/news";
$route['news/from/:num']				= "users_interface/news";

$route['news/view/:any']				= "users_interface/news_view";

$route['contacts']						= "users_interface/contacts";
$route['prices']						= "users_interface/prices";
$route['forum']							= "users_interface/forum";
$route['site-map']						= "users_interface/site_map";
$route['idea']							= "users_interface/idea";
$route['logoff']						= "users_interface/actions_logoff";

$route['markets-catalog/:any']			= "users_interface/markets_catalog";
$route['users-ratings/advertisers']		= "users_interface/users_ratings";
$route['users-ratings/webmasters']		= "users_interface/users_ratings";

$route['users-ratings/advertisers/reading-rating/:num']= "users_interface/reading_rating";
//$route['users-ratings/webmasters/reading-rating/:num'] = "users_interface/reading_rating";

$route['partner/:num']					= "users_interface/registering";
$route['users/registering/webmaster']	= "users_interface/registering";
$route['users/registering/optimizer']	= "users_interface/registering";

$route['users/restore-password']		= "users_interface/restore_password";

$route['ratings/viewimage/:num']		= "users_interface/viewimage";

/**************************************************	   CLIENTS INTRERFACE	***********************************************/
$route['webmaster-panel/actions/control']							= "clients_interface/control_panel";
$route['webmaster-panel/actions/profile']							= "clients_interface/control_profile";
$route['webmaster-panel/actions/logoff']							= "clients_interface/actions_logoff";

$route['webmaster-panel/actions/partner-program']					= "clients_interface/partner_program";

$route['webmaster-panel/actions/markets']							= "clients_interface/control_markets";

$route['webmaster-panel/actions/markets/disabled/marketid/:num']	= "clients_interface/control_disabled_markets";
$route['webmaster-panel/actions/markets/enabled/marketid/:num']		= "clients_interface/control_enabled_markets";

//$route['webmaster-panel/actions/markets/delete/marketid/:num']		= "clients_interface/control_delete_markets";

$route['webmaster-panel/actions/markets/parsing']					= "clients_interface/control_market_parsing";
$route['webmaster-panel/actions/markets/loading']					= "clients_interface/control_market_loading";

$route['webmaster-panel/actions/platforms']							= "clients_interface/control_platforms";
$route['webmaster-panel/actions/platforms/add-platform']			= "clients_interface/control_add_platform";
$route['webmaster-panel/actions/platforms/edit-platform/:num']		= "clients_interface/control_edit_platform";

$route['webmaster-panel/actions/finished-jobs/platform/platformid/:num']			= "clients_interface/control_finished_jobs";
$route['webmaster-panel/actions/finished-jobs/platform/platformid/:num/from']		= "clients_interface/control_finished_jobs";
$route['webmaster-panel/actions/finished-jobs/platform/platformid/:num/from/:num']	= "clients_interface/control_finished_jobs";

$route['webmaster-panel/actions/finished-jobs/pay-all']				= "clients_interface/control_pay_all";

$route['webmaster-panel/actions/finished-jobs']						= "clients_interface/control_finished_jobs";
$route['webmaster-panel/actions/finished-jobs/from']				= "clients_interface/control_finished_jobs";
$route['webmaster-panel/actions/finished-jobs/from/:num']			= "clients_interface/control_finished_jobs";
$route['webmaster-panel/actions/finished-jobs/set-filter']			= "clients_interface/finished_jobs_filter";
$route['webmaster-panel/actions/finished-jobs/set-count-work']		= "clients_interface/finished_jobs_count_page";
$route['webmaster-panel/actions/finished-jobs/search']				= "clients_interface/finished_jobs_search";

$route['webmaster-panel/actions/platforms/add-platform']			= "clients_interface/control_add_platform";
$route['webmaster-panel/actions/platforms/edit-platform/:num']		= "clients_interface/control_edit_platform";

$route['webmaster-panel/actions/tickets']							= "clients_interface/control_tickets";
$route['webmaster-panel/actions/tickets/from']						= "clients_interface/control_tickets";
$route['webmaster-panel/actions/tickets/from/:num']					= "clients_interface/control_tickets";

$route['webmaster-panel/actions/tickets/hide-closed-tickets']		= "clients_interface/hide_closed_tickets";

$route['webmaster-panel/actions/tickets/view-ticket/:num']			= "clients_interface/control_view_ticket";
$route['webmaster-panel/actions/tickets/view-ticket/:num/from']		= "clients_interface/control_view_ticket";
$route['webmaster-panel/actions/tickets/view-ticket/:num/from/:num']= "clients_interface/control_view_ticket";
$route['webmaster-panel/actions/tickets/delete/ticket-id/:num']		= "clients_interface/control_delete_tickets";

$route['webmaster-panel/actions/mails']								= "clients_interface/control_mails";
$route['webmaster-panel/actions/mails/from']						= "clients_interface/control_mails";
$route['webmaster-panel/actions/mails/from/:num']					= "clients_interface/control_mails";
$route['webmaster-panel/actions/mails/delete/mail-id/:num']			= "clients_interface/control_delete_mail";

$route['webmaster-panel/actions/balance']							= "clients_interface/control_balance";
$route['webmaster-panel/actions/balance/paid']						= "clients_interface/control_balance_paid";
$route['webmaster-panel/actions/balance/successfull']				= "clients_interface/control_balance_successfull";
$route['webmaster-panel/actions/balance/failed']					= "clients_interface/control_balance_failed";
$route['webmaster-panel/actions/balance/payment-history']			= "clients_interface/control_payment_history";
$route['webmaster-panel/actions/balance/payment-history/from']		= "clients_interface/control_payment_history";
$route['webmaster-panel/actions/balance/payment-history/from/:num']	= "clients_interface/control_payment_history";

$route['webmaster-panel/actions/services']							= "clients_interface/control_services";
$route['webmaster-panel/actions/services/serviceid/:num/platforms']	= "clients_interface/control_services_platforms";
$route['webmaster-panel/management/services/delete/serviceid/:num']	= "clients_interface/control_services_delete";

/***************************************************   MANAGERS INTRERFACE	***********************************************/

$route['manager-panel/actions/control']							= "managers_interface/control_panel";
$route['manager-panel/actions/control/from']					= "managers_interface/control_panel";
$route['manager-panel/actions/control/from/:num']				= "managers_interface/control_panel";
$route['manager-panel/actions/control/set-filter']				= "managers_interface/finished_jobs_filter";
$route['manager-panel/actions/control/set-count-work']			= "managers_interface/finished_jobs_count_page";

$route['manager-panel/actions/finished-jobs/search']			= "managers_interface/control_jobs_search";

$route['manager-panel/actions/logoff']							= "managers_interface/actions_logoff";

$route['manager-panel/actions/platforms']						= "managers_interface/control_platforms";
$route['manager-panel/actions/platforms/from']					= "managers_interface/control_platforms";
$route['manager-panel/actions/platforms/from/:num']				= "managers_interface/control_platforms";
$route['manager-panel/actions/platforms/view-platform/:num']	= "managers_interface/control_view_platform";
$route['manager-panel/actions/platforms/edit-platform/:num']	= "managers_interface/control_edit_platform";

$route['manager-panel/actions/platforms/:num/deliver-work']		= "managers_interface/deliver_work";
$route['manager-panel/actions/platforms/remote_deliver_work']	= "managers_interface/remote_deliver_work";

$route['manager-panel/actions/platforms/search']				= "managers_interface/search_platforms";

$route['manager-panel/actions/tickets/inbox']					= "managers_interface/control_tickets_inbox";
$route['manager-panel/actions/tickets/inbox/from']				= "managers_interface/control_tickets_inbox";
$route['manager-panel/actions/tickets/inbox/from/:num']			= "managers_interface/control_tickets_inbox";
$route['manager-panel/actions/tickets/outbox']					= "managers_interface/control_tickets_outbox";
$route['manager-panel/actions/tickets/outbox/from']				= "managers_interface/control_tickets_outbox";
$route['manager-panel/actions/tickets/outbox/from/:num']		= "managers_interface/control_tickets_outbox";

$route['manager-panel/actions/tickets/inbox/view-ticket/:num']				= "managers_interface/control_view_inbox_ticket";
$route['manager-panel/actions/tickets/inbox/view-ticket/:num/from']			= "managers_interface/control_view_inbox_ticket";
$route['manager-panel/actions/tickets/inbox/view-ticket/:num/from/:num']	= "managers_interface/control_view_inbox_ticket";

$route['manager-panel/actions/tickets/outbox/view-ticket/:num']				= "managers_interface/control_view_outbox_ticket";
$route['manager-panel/actions/tickets/outbox/view-ticket/:num/from']		= "managers_interface/control_view_outbox_ticket";
$route['manager-panel/actions/tickets/outbox/view-ticket/:num/from/:num']	= "managers_interface/control_view_outbox_ticket";

$route['manager-panel/actions/tickets/delete/ticket-id/:num']				= "managers_interface/control_delete_tickets";

$route['manager-panel/actions/tickets/inbox/hide-closed-tickets']			= "managers_interface/hide_closed_tickets";
$route['manager-panel/actions/tickets/outbox/hide-closed-tickets']			= "managers_interface/hide_closed_tickets";

$route['manager-panel/actions/mails']								= "managers_interface/control_mails";
$route['manager-panel/actions/mails/from']							= "managers_interface/control_mails";
$route['manager-panel/actions/mails/from/:num']						= "managers_interface/control_mails";

$route['manager-panel/actions/mails/reply/mail-id/:num']			= "managers_interface/control_reply_mail";
$route['manager-panel/actions/mails/delete/mail-id/:num']			= "managers_interface/control_delete_mail";

$route['manager-panel/actions/profile']								= "managers_interface/control_profile";

/*************************************************** OPTIMIZATORS INTRERFACE	*******************************************/

$route['optimizator-panel/actions/control']								= "optimizators_interface/control_panel";
$route['optimizator-panel/actions/control/from']						= "optimizators_interface/control_panel";
$route['optimizator-panel/actions/control/from/:num']					= "optimizators_interface/control_panel";
$route['optimizator-panel/actions/logoff']								= "optimizators_interface/actions_logoff";

/*************************************************** GENERAL INTRERFACE	*******************************************/

$route[':any/viewimage/:num']			= "general_interface/viewimage";
$route['balance/result']				= "general_interface/balance_result";
$route['support']						= "general_interface/support";
$route['views/market-profile']			= "general_interface/load_views";
$route['distribution-of-notifications']	= "general_interface/distribution_of_notifications";

/*************************************************** 	ADMINS INTRERFACE	***********************************************/

$route['admin-panel/actions/control']					= "admin_interface/control_panel";

$route['admin-panel/actions/profile']					= "admin_interface/actions_profile";
$route['admin-panel/actions/forum']						= "admin_interface/actions_forum";
$route['admin-panel/actions/balance']					= "admin_interface/actions_balance";
$route['admin-panel/actions/logoff']					= "admin_interface/actions_logoff";
$route['api']											= "admin_interface/actions_api";

$route['admin-panel/actions/log']						= "admin_interface/actions_log";
$route['admin-panel/actions/log/from']					= "admin_interface/actions_log";
$route['admin-panel/actions/log/from/:num']				= "admin_interface/actions_log";
$route['admin-panel/actions/log/clear']					= "admin_interface/actions_log_clear";

$route['admin-panel/actions/events']					= "admin_interface/actions_events";
$route['admin-panel/actions/events/from']				= "admin_interface/actions_events";
$route['admin-panel/actions/events/from/:num']			= "admin_interface/actions_events";

$route['admin-panel/actions/events/add']				= "admin_interface/actions_events_add";
$route['admin-panel/actions/events/edit/:num']			= "admin_interface/actions_events_edit";
$route['admin-panel/actions/events/delete/eventsid/:num']= "admin_interface/actions_delete_events";

$route['admin-panel/management/users/read-messages/userid/:num']			= "admin_interface/reading_users_messages";
$route['admin-panel/management/users/read-messages/userid/:num/from']		= "admin_interface/reading_users_messages";
$route['admin-panel/management/users/read-messages/userid/:num/from/:num']	= "admin_interface/reading_users_messages";

$route['admin-panel/management/users/usersid/:num/platforms']				= "admin_interface/user_platforms_list";

$route['admin-panel/management/users/remoteid/:num/webmarkets']				= "admin_interface/user_webmarkets_list";
$route['admin-panel/management/users/remoteid/:num/disabled/marketid/:num']	= "admin_interface/user_disabled_markets";
$route['admin-panel/management/users/remoteid/:num/enabled/marketid/:num']	= "admin_interface/user_enabled_markets";
$route['admin-panel/management/users/remoteid/:num/delete/marketid/:num']	= "admin_interface/user_delete_markets";

$route['admin-panel/management/users/userid/:num/finished-jobs']			= "admin_interface/user_finished_jobs";
$route['admin-panel/management/users/userid/:num/finished-jobs/from']		= "admin_interface/user_finished_jobs";
$route['admin-panel/management/users/userid/:num/finished-jobs/from/:num']	= "admin_interface/user_finished_jobs";

$route['admin-panel/actions/finished-jobs/set-filter']						= "admin_interface/finished_jobs_filter";

$route['admin-panel/management/finished-jobs/delete/jobid/:num']			= "admin_interface/delete_finished_jobs";
$route['admin-panel/management/finished-jobs/delete/user/:num']				= "admin_interface/delete_user_jobs";
$route['admin-panel/management/finished-jobs/delete/platform/:num']			= "admin_interface/delete_platform_jobs";

$route['admin-panel/actions/finished-jobs/users-jobs/search']				= "admin_interface/users_search_jobs";
$route['admin-panel/actions/finished-jobs/platform-jobs/search']			= "admin_interface/platform_search_jobs";

$route['admin-panel/management/users/:any/from/:num']	= "admin_interface/management_users";
$route['admin-panel/management/users/userid/:num']		= "admin_interface/management_users_deleting";

$route['admin-panel/management/users/profile/id/:num']	= "admin_interface/management_users_profile";
$route['admin-panel/management/users/:any/search']		= "admin_interface/search_users";
$route['admin-panel/management/users/:any']				= "admin_interface/management_users";

$route['admin-panel/management/platforms']				= "admin_interface/management_platforms";
$route['admin-panel/management/platforms/from']			= "admin_interface/management_platforms";
$route['admin-panel/management/platforms/from/:num']	= "admin_interface/management_platforms";
$route['admin-panel/management/platforms/delete/platformid/:num'] = "admin_interface/management_delete_platform";
$route['admin-panel/management/platforms/view-platform/:num']= "admin_interface/management_view_platform";
$route['admin-panel/management/platforms/edit-platform/:num']	= "admin_interface/control_edit_platform";

$route['admin-panel/management/platforms/platformid/:num/finished-jobs']			= "admin_interface/platform_finished_jobs";
$route['admin-panel/management/platforms/platformid/:num/finished-jobs/from']		= "admin_interface/platform_finished_jobs";
$route['admin-panel/management/platforms/platformid/:num/finished-jobs/from/:num']	= "admin_interface/platform_finished_jobs";

$route['admin-panel/management/platforms/search']		= "admin_interface/search_platforms";

$route['admin-panel/management/platforms/calculate/tic']= "admin_interface/calculate_tic";
$route['admin-panel/management/platforms/calculate/pr']	= "admin_interface/calculate_pr";

$route['admin-panel/management/platforms/calculate']	= "admin_interface/calculate";

$route['admin-panel/management/markets']				= "admin_interface/management_markets";
$route['admin-panel/management/markets/marketid/:num']	= "admin_interface/management_markets_deleting";

$route['admin-panel/management/promocode']				= "admin_interface/management_promocode";
$route['admin-panel/management/promocode/codeid/:num']	= "admin_interface/management_promocode_deleting";

$route['admin-panel/management/services']				= "admin_interface/management_services";
$route['admin-panel/management/services/serviceid/:num']= "admin_interface/management_services_deleting";

$route['admin-panel/management/ratings/advertisers']	= "admin_interface/management_ratings";
$route['admin-panel/management/ratings/webmasters']		= "admin_interface/management_ratings";
$route['admin-panel/management/ratings/ratingid/:num']	= "admin_interface/management_rating_deleting";

$route['admin-panel/management/types-of-work']				= "admin_interface/management_types_work";
$route['admin-panel/management/types-of-work/workid/:num']	= "admin_interface/management_types_work_deleting";

$route['admin-panel/management/mails']					= "admin_interface/management_mails";
$route['admin-panel/management/mails/from']				= "admin_interface/management_mails";
$route['admin-panel/management/mails/from/:num']		= "admin_interface/management_mails";

$route['admin-panel/actions/mails/system-clear']		= "admin_interface/messages_system_clear";

$route['admin-panel/messages/system-message']			= "admin_interface/messages_system";

$route['admin-panel/messages/private-messages/delete-mail/mailid/:num']	= "admin_interface/messages_private_delete";

$route['admin-panel/messages/tickets']									= "admin_interface/messages_tickets";
$route['admin-panel/messages/tickets/from']								= "admin_interface/messages_tickets";
$route['admin-panel/messages/tickets/from/:num']						= "admin_interface/messages_tickets";

$route['admin-panel/messages/tickets/view-ticket/ticket-id/:num']		= "admin_interface/messages_view_ticket";
$route['admin-panel/messages/tickets/view-ticket/ticket-id/:num/from']	= "admin_interface/messages_view_ticket";
$route['admin-panel/messages/tickets/view-ticket/ticket-id/:num/from/:num']	= "admin_interface/messages_view_ticket";
$route['admin-panel/messages/tickets/delete-mail/mail-id/:num']			= "admin_interface/control_delete_msg_ticket";

$route['admin-panel/messages/tickets/hide-closed-tickets']				= "admin_interface/hide_closed_tickets";

$route['admin-panel/actions/statistic']									= "admin_interface/actions_statistic";

$route['admin-panel/actions/control/sendind-registering-info']			= "admin_interface/sendind_registering_info";
$route['admin-panel/actions/partner-program']							= "admin_interface/partner_program";
$route['admin-panel/actions/partner-program/from']						= "admin_interface/partner_program";
$route['admin-panel/actions/partner-program/from/:num']					= "admin_interface/partner_program";

$route['admin-panel/actions/partner-program/partner/:num']				= "admin_interface/partners_list";

/*************************************************** CRON INTRERFACE ***********************************************/

$route['import-deliver-work'] 		= "cron_interface/import_deliver_work";
$route['debitors-auto-blocking'] 	= "cron_interface/debitors_auto_blocking";
$route['users-sending-mail'] 		= "cron_interface/users_sending_mail";
$route['users-checkout'] 			= "cron_interface/users_checkout";
$route['users-checkout-now'] 		= "cron_interface/users_checkout_now";