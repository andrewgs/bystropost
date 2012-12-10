<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdunion extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function delivers_works_manager($uid,$count,$from,$filter){
		
		$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.manager = $uid AND delivesworks.status IN ($filter) ORDER BY delivesworks.date DESC,delivesworks.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_delivers_works_manager($uid,$filter = '0,1'){
		
		$query = "SELECT COUNT(*) AS cnt FROM delivesworks WHERE delivesworks.manager = $uid AND delivesworks.status IN ($filter)";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0]['cnt'];
		return 0;
	}
	
	function delivers_works_webmaster($uid,$count,$from,$filter){
		
		$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.webmaster = $uid AND delivesworks.status IN ($filter) ORDER BY delivesworks.date DESC,delivesworks.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function delivers_works_webmaster_all($uid,$bdate,$edate,$paid,$notpaid){
		
		$statusin = '';
		if($paid && $notpaid):
			$statusin = '0,1';
		elseif($paid):
			$statusin = '1';
		elseif($notpaid):
			$statusin = '0';
		endif;
		
		$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.webmaster = $uid AND delivesworks.date>= '$bdate' AND delivesworks.date<='$edate' AND delivesworks.status IN ($statusin) ORDER BY delivesworks.date DESC,delivesworks.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_delivers_works_webmaster($uid,$filter){
		
		$query = "SELECT delivesworks.* FROM delivesworks WHERE delivesworks.webmaster = $uid AND delivesworks.status IN ($filter)";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function read_user_webmaster($uid){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 1 AND users.id = $uid";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data[0];
		return NULL;
	}
	
	function read_users_group_webmasters($count,$from){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 1 GROUP BY users.id ORDER BY users.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}

	function count_users_group_webmasters(){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 1 GROUP BY users.id ORDER BY users.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}

	function read_users_group_optimizators($count,$from){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 3 GROUP BY users.id ORDER BY users.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_users_group_optimizators(){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 3 GROUP BY users.id ORDER BY users.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}

	function read_users_group_manegers($count,$from){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 2 GROUP BY users.id ORDER BY users.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_users_group_manegers(){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 2 GROUP BY users.id ORDER BY users.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}

	function read_users_group_admin($count,$from){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 5 GROUP BY users.id ORDER BY users.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_users_group_admin(){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE users.type = 5 GROUP BY users.id ORDER BY users.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}

	function read_users_group_all($count,$from){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE TRUE GROUP BY users.id ORDER BY users.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_users_group_all(){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE TRUE GROUP BY users.id ORDER BY users.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}

	function read_mkplatform_by_webmaster($uid){
		
		$query = "SELECT markets.id,markets.title,markets.url,mkplatform.platform FROM markets INNER JOIN mkplatform ON mkplatform.market=markets.id WHERE mkplatform.webmaster = $uid ORDER BY mkplatform.platform,markets.id";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_mails_by_recipient($recipient,$utype,$date,$count,$from){
		
		$query = "SELECT messages.*, users.id AS uid,users.fio,users.login,users.position FROM messages INNER JOIN users ON messages.sender=users.id WHERE (messages.recipient = $recipient OR (messages.group = $utype AND messages.system = 1 AND messages.recipient = $recipient) OR (messages.group = $utype AND messages.system = 1 AND messages.recipient = 0)) AND messages.date >= '$date' ORDER BY messages.date DESC,messages.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_mails_by_recipient($recipient,$utype,$date){
		
		$query = "SELECT messages.*, users.id AS uid,users.fio,users.login,users.position FROM messages INNER JOIN users ON messages.sender=users.id WHERE (messages.recipient = $recipient OR (messages.group = $utype AND messages.system = 1 AND messages.recipient = $recipient) OR (messages.group = $utype AND messages.system = 1 AND messages.recipient = 0)) AND messages.date >= '$date'";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function read_tickets_by_recipient($recipient,$count,$from,$filter = FALSE){
		
		$status = '0,1';
		if($filter):
			$status = '0';
		endif;
		$query = "SELECT tickets.*,tkmsgs.text,platforms.id AS plid,platforms.url FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket LEFT JOIN platforms ON tickets.platform=platforms.id WHERE tickets.recipient = $recipient AND tickets.status IN ($status) GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_tickets_by_sender($sender,$count,$from,$filter = FALSE){
		
		$status = '0,1';
		if($filter):
			$status = '0';
		endif;
		
		$query = "SELECT tickets.*,	tkmsgs.text,platforms.url FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket LEFT JOIN platforms ON tickets.platform=platforms.id WHERE tickets.sender = $sender AND tickets.status IN ($status) GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function view_ticket_info($id){
		
		$query = "SELECT tickets.*,platforms.url FROM tickets INNER JOIN platforms ON tickets.platform=platforms.id WHERE tickets.id = $id LIMIT 1";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function count_tickets_by_sender($sender,$filter = FALSE){
		
		$status = '0,1';
		if($filter):
			$status = '0';
		endif;
		$query = "SELECT tickets.*,tkmsgs.text FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket WHERE tickets.sender = $sender AND tickets.status IN ($status) GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function count_tickets_by_recipient($recipient,$filter = FALSE){
		
		$status = '0,1';
		if($filter):
			$status = '0';
		endif;
		$query = "SELECT tickets.*,tkmsgs.text FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket WHERE tickets.recipient = $recipient AND tickets.status IN ($status) GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function read_mails_by_recipient_pages($recipient,$count,$from){
		
		$query = "SELECT messages.*, users.id AS uid,users.fio,users.login,users.position FROM messages INNER JOIN users ON messages.sender=users.id WHERE messages.recipient = $recipient ORDER BY messages.date DESC,messages.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_mails_by_recipient_pages($recipient){
		
		$query = "SELECT messages.*, users.id AS uid,users.fio,users.login,users.position FROM messages INNER JOIN users ON messages.sender=users.id WHERE messages.recipient = $recipient";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function read_mails_admin_pages($recipient,$count,$from){
		
		$query = "SELECT messages.*, users.id AS uid,users.fio,users.login,users.position FROM messages INNER JOIN users ON messages.sender=users.id WHERE (messages.recipient = $recipient OR messages.recipient = 0) ORDER BY messages.date DESC,messages.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_mails_admin_pages($recipient){
		
		$query = "SELECT messages.*, users.id AS uid,users.fio,users.login,users.position FROM messages INNER JOIN users ON messages.sender=users.id WHERE (messages.recipient = $recipient OR messages.recipient = 0)";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function read_all_tickets($count,$from,$filter = FALSE){
		
		$status = '0,1';
		if($filter):
			$status = '0';
		endif;
		
		$query = "SELECT tickets.*, users.id AS uid,users.fio,users.login,users.type AS utype,users.position,platforms.id AS plid, platforms.url FROM tickets INNER JOIN users ON tickets.sender=users.id INNER JOIN platforms ON tickets.platform=platforms.id WHERE tickets.status IN ($status) GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_all_tickets($filter = FALSE){
		
		$status = '0,1';
		if($filter):
			$status = '0';
		endif;
		
		$query = "SELECT tickets.* FROM tickets WHERE tickets.status IN ($status)";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}

	function read_messages_by_ticket_pages($ticket,$count,$from){
		
		$query = "SELECT tkmsgs.*, users.id AS uid,users.fio,users.login,users.position FROM tkmsgs INNER JOIN users ON tkmsgs.sender=users.id WHERE tkmsgs.ticket = $ticket ORDER BY tkmsgs.date DESC,tkmsgs.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_messages_by_ticket($ticket){
		
		$query = "SELECT tkmsgs.*, users.id AS uid,users.fio,users.login,users.position FROM tkmsgs INNER JOIN users ON tkmsgs.sender=users.id WHERE tkmsgs.ticket = $ticket";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function read_platforms_by_owners_pages($count,$from){
		
		$query = "SELECT platforms.*, users.id AS uid,users.fio,users.login,users.position,0 AS torders,0 AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id WHERE platforms.id > 0 ORDER BY users.login,platforms.date DESC,platforms.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_platform_by_id($pl){
		
		$query = "SELECT platforms.*, users.id AS uid,users.fio,users.login,users.position,0 AS torders,0 AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id WHERE platforms.id = $pl";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_platform($id = FALSE,$url,$manager = FALSE,$webmaster = FALSE){
		
		if($id):
			if($manager):
				$query = "SELECT platforms.*, users.id AS uid,users.fio,users.login,users.position,0 AS torders,0 AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id WHERE (platforms.id = $id OR platforms.url = '$url') AND platforms.manager = $manager";
			else:
				$query = "SELECT platforms.*, users.id AS uid,users.fio,users.login,users.position,0 AS torders,0 AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id WHERE platforms.id = $id OR platforms.url = '$url'";
			endif;
		else:
			if($manager):
				$query = "SELECT platforms.*, users.id AS uid,users.fio,users.login,users.position,0 AS torders,0 AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id WHERE platforms.url = '$url' AND platforms.manager = $manager";
			else:
				$query = "SELECT platforms.*, users.id AS uid,users.fio,users.login,users.position,0 AS torders,0 AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id WHERE platforms.url = '$url'";
			endif;
		endif;
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_webmaster_jobs($webmaster,$id = FALSE,$url){
		
		if($id):
			$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.webmaster = $webmaster AND (delivesworks.id = $id OR delivesworks.ulrlink = '$url') LIMIT 0,15";
		else:
			$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.webmaster = $webmaster AND delivesworks.ulrlink = '$url' LIMIT 0,15";
		endif;
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_manager_jobs($manager,$id = FALSE,$url){
		
		if($id):
			$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.manager = $manager AND (delivesworks.id = $id OR delivesworks.ulrlink = '$url') LIMIT 0,15";
		else:
			$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.manager = $manager AND delivesworks.ulrlink = '$url' LIMIT 0,15";
		endif;
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_platform_jobs($platform,$id = FALSE,$url){
		
		if($id):
			$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.platform = $platform AND (delivesworks.id = $id OR delivesworks.ulrlink = '$url') LIMIT 0,15";
		else:
			$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.platform = $platform AND delivesworks.ulrlink = '$url' LIMIT 0,15";
		endif;
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_platforms_by_owners(){
		
		$query = "SELECT platforms.id,users.id AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id WHERE platforms.id > 0";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}

	function delivers_works_platform($platform,$count,$from,$filter = '0,1'){
		
		$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.platform = $platform AND delivesworks.status IN ($filter) ORDER BY delivesworks.date DESC,delivesworks.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_delivers_works_platform($platform,$filter = '0,1'){
		
		$query = "SELECT delivesworks.* FROM delivesworks WHERE delivesworks.platform = $platform AND delivesworks.status IN ($filter)";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function services_attached_list($uid){
		
		$query = "SELECT attachedservices.*,services.title AS stitle FROM attachedservices INNER JOIN services ON attachedservices.service=services.id WHERE attachedservices.user = $uid GROUP BY attachedservices.service ORDER BY attachedservices.date DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function services_attached($service,$uid){
		
		$query = "SELECT attachedservices.*,platforms.url AS plurl FROM attachedservices INNER JOIN platforms ON attachedservices.platform=platforms.id WHERE attachedservices.user = $uid AND attachedservices.service = $service AND platforms.locked = 0 AND platforms.status = 1 ORDER BY attachedservices.date DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_markets_by_webmaster($ruid){
		
		$query = "SELECT webmarkets.*,markets.title AS mtitle FROM webmarkets INNER JOIN markets ON webmarkets.market=markets.id WHERE webmarkets.webmaster = $ruid";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}

	function read_events($count,$from){
		
		$query = "SELECT log.*,users.id AS uid, users.fio AS ufio, users.login AS ulogin, users.position AS uposition FROM log INNER JOIN users ON log.user=users.id ORDER BY log.date DESC,log.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}

	function read_srvvalue_service_platform($service,$platform,$uid){
		
		$query = "SELECT attachedservices.*,valuesrv.title AS tsrvval,services.title AS tservice FROM attachedservices INNER JOIN valuesrv ON attachedservices.valuesrv=valuesrv.id INNER JOIN services ON attachedservices.service=services.id WHERE attachedservices.user = $uid AND attachedservices.service = $service AND attachedservices.platform = $platform LIMIT 1";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}

	function read_debetors_list($data,$znak){
		
		$query = "SELECT users.id AS uid, users.fio AS ufio, users.login AS ulogin FROM delivesworks INNER JOIN users ON delivesworks.webmaster=users.id WHERE delivesworks.date $znak '$data' AND status = 0 AND users.antihold = 0 GROUP BY users.id";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function free_platforms($uid){
	
		$query = "SELECT platforms.id,platforms.remoteid,mkplatform.id AS mkid FROM `platforms` LEFT JOIN mkplatform ON platforms.`id` = mkplatform.platform WHERE platforms.webmaster = $uid AND platforms.remoteid !=0 AND platforms.manager = 2 GROUP BY platforms.id;";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function webmaster_locked_platforms(){
	
		$query = "SELECT users.id AS uid,users.fio,users.login,users.cryptpassword,platforms.url FROM `users` INNER JOIN platforms ON users.id = platforms.webmaster WHERE users.locked = 0 AND platforms.tic >= 10 AND platforms.status = 0 AND platforms.locked = 0 ORDER BY users.id,platforms.url";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	/******************************************************** crontab ******************************************************/
	
	function read_managers_platforms($manager){
		
		$query = "SELECT platforms.id,platforms.remoteid,platforms.url,platforms.webmaster,users.login,users.remoteid AS rwmid,users.autopaid FROM platforms INNER JOIN users ON platforms.webmaster=users.id WHERE platforms.manager = $manager AND platforms.remoteid > 0 AND users.remoteid > 0 ORDER BY users.remoteid,platforms.remoteid";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_webmarkets_records(){
		
		$query = "SELECT webmarkets.id,market,webmaster,users.id AS uid FROM webmarkets INNER JOIN users ON webmarkets.webmaster = users.remoteid ORDER BY webmaster,market,id";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}

	function read_pl_price_rid($rplid){
		
		$this->db->where('remoteid',$rplid);
		$query = $this->db->get('platforms',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}

	function valid_exist_works($works){
	
		if(!$works):
			return NULL;
		endif;
		$query = 'SELECT remoteid AS id FROM delivesworks WHERE remoteid IN (';
		for($i=0;$i<count($works);$i++):
			$query .= $works[$i]['id'];
			if($i+1<count($works)):
				$query.=',';
			else:
				$query.=') ORDER BY id';
			endif;
		endfor;
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function works_group_insert($works){
	
		$query = '';
		for($i=0;$i<count($works);$i++):
			$query .= '('.$works[$i]['id'].','.$works[$i]['webmaster'].','.$works[$i]['platform'].',2,'.$works[$i]['type'].','.$works[$i]['market'].',"'.$works[$i]['birzprice'].'","'.$works[$i]['link'].'",'.$works[$i]['size'].','.$works[$i]['client_price'].','.$works[$i]['our_price'].',0,"'.date("Y-m-d").'","0000-00-00") ';
			if($i+1<count($works)):
				$query.=',';
			endif;
		endfor;
		$this->db->query("INSERT INTO delivesworks (remoteid,webmaster,platform,manager,typework,market,mkprice,ulrlink,countchars,wprice,mprice,status,date,datepaid) VALUES ".$query);
		return $this->db->affected_rows();
	}
	
	function works_group_paid($works){
	
		if(!$works):
			return NULL;
		endif;
		$curdate = date("Y-m-d");
		$query = "UPDATE delivesworks SET status = 1,datepaid = '$curdate' WHERE remoteid IN (";
		for($i=0;$i<count($works);$i++):
			$query .= $works[$i]['id'];
			if($i+1<count($works)):
				$query.=',';
			else:
				$query.=')';
			endif;
		endfor;
		$this->db->query($query);
		return $this->db->affected_rows();
	}

	function works_status_ones($rid){
		
		$curdate = date("Y-m-d");
		$query = "UPDATE delivesworks SET status = 1,datepaid = '$curdate' WHERE remoteid = $rid";
		$this->db->query($query);
		return $this->db->affected_rows();
	}
	
	function update_debetors_status($date,$znak,$status){
	
		$query = "UPDATE users SET debetor = $status WHERE users.id IN (SELECT delivesworks.webmaster FROM delivesworks WHERE delivesworks.date $znak '$date' AND delivesworks.status = 0) AND users.antihold = 0 AND debetor = 0";
		$this->db->query($query);
		return $this->db->affected_rows();
	}
	
	function select_debetors($date,$znak){
	
		$query = "SELECT id,login,manager,fio FROM users WHERE users.id IN (SELECT delivesworks.webmaster FROM delivesworks WHERE delivesworks.date $znak '$date' AND delivesworks.status = 0) AND users.antihold = 0 AND debetor = 0";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function debetors_webmarkets(){
	
		$query = "SELECT webmarkets.* FROM users INNER JOIN webmarkets ON users.remoteid = webmarkets.webmaster WHERE users.debetor = 1 AND webmarkets.status = 1 AND users.antihold = 0";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_users_sendmail($type){
		
		$this->db->where('type',$type);
		$this->db->where('locked',0);
		$this->db->where('sendmail',1);
		$query = $this->db->get('users');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function users_debitors_works($webmaster,$date,$znak){
		
		$query = "SELECT COUNT(*) AS cnt FROM delivesworks WHERE delivesworks.webmaster = $webmaster AND delivesworks.date $znak '$date' AND status = 0";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_debitors_works($webmaster,$date,$znak){
		
		$query = "SELECT * FROM delivesworks WHERE delivesworks.webmaster = $webmaster AND delivesworks.date $znak '$date' AND status = 0";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function min_price_debitors_works($webmaster,$date,$znak){
		
		$query = "SELECT MIN(wprice) AS minprice FROM delivesworks WHERE delivesworks.webmaster = $webmaster AND delivesworks.date $znak '$date' AND status = 0";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0]['minprice'];
		return 0;
	}
	
	function read_debetors($data,$znak,$status){
	
		$query = "SELECT delivesworks.webmaster,delivesworks.manager FROM delivesworks WHERE date $znak '$data' and status = $status GROUP BY webmaster ORDER BY webmaster";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}

	function debetors_for_checkout($date){
	
		$query = "SELECT users.id AS uid,users.wmid,SUM(delivesworks.wprice) AS summa,COUNT(delivesworks.id) AS cnt FROM users INNER JOIN delivesworks ON users.id = delivesworks.webmaster WHERE users.debetor = 0 AND delivesworks.date <= '$date' AND status = 0 GROUP BY delivesworks.webmaster ORDER BY delivesworks.webmaster";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_platforms_partners($partner){
	
		$query = "SELECT COUNT(platforms.id) AS cnt FROM users INNER JOIN platforms ON users.id = platforms.webmaster WHERE users.partner_id = $partner";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0]['cnt'];
		return 0;
	}
	
	function count_summa_works_partners($partner){
	
		$query = "SELECT COUNT(delivesworks.id) AS works,SUM(delivesworks.wprice) AS summa FROM users INNER JOIN delivesworks ON users.id = delivesworks.webmaster WHERE users.partner_id = $partner AND delivesworks.status = 1";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if($data[0]['works'] > 0) return $data[0];
		return NULL;
	}
	function list_works_partners($partner){
	
		$query = "SELECT users.id, users.login, users.signdate, COUNT(delivesworks.id) AS works FROM users INNER JOIN delivesworks ON users.id = delivesworks.webmaster WHERE users.partner_id = $partner AND delivesworks.status = 1 GROUP BY delivesworks.webmaster ORDER BY users.login";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
}