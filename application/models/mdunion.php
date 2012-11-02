<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdunion extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function delivers_works_manager($uid,$count,$from){
		
		$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.manager = $uid ORDER BY delivesworks.date DESC,delivesworks.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_delivers_works_manager($uid){
		
		$query = "SELECT delivesworks.* FROM delivesworks WHERE delivesworks.manager = $uid";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
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
	
	function read_tickets_by_recipient($recipient,$count,$from){
		
		$query = "SELECT tickets.*,tkmsgs.text,platforms.id AS plid,platforms.url FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket LEFT JOIN platforms ON tickets.platform=platforms.id WHERE tickets.recipient = $recipient GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_tickets_by_sender($sender,$count,$from){
		
		$query = "SELECT tickets.*,	tkmsgs.text,platforms.url FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket LEFT JOIN platforms ON tickets.platform=platforms.id WHERE tickets.sender = $sender GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC LIMIT $from,$count";
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
	
	function count_tickets_by_sender($sender){
		
		$query = "SELECT tickets.*,tkmsgs.text FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket WHERE tickets.sender = $sender GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function count_tickets_by_recipient($recipient){
		
		$query = "SELECT tickets.*,tkmsgs.text FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket WHERE tickets.recipient = $recipient GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC";
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
	
	function read_all_tickets($count,$from){
		
		$query = "SELECT tickets.*, users.id AS uid,users.fio,users.login,users.type AS utype,users.position,platforms.id AS plid, platforms.url FROM tickets INNER JOIN users ON tickets.sender=users.id INNER JOIN platforms ON (tickets.platform=platforms.id || tickets.platform=0) GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_all_tickets(){
		
		$query = "SELECT tickets.* FROM tickets";
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
		
		$query = "SELECT platforms.*, users.id AS uid,users.fio,users.login,users.position,0 AS torders,0 AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id ORDER BY users.login,platforms.date DESC,platforms.id DESC LIMIT $from,$count";
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
		
		$query = "SELECT platforms.id,users.id AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id";
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
	
	function update_debetors_status($data,$znak,$status){
	
		$query = "UPDATE users SET debetor = $status WHERE users.id IN (SELECT delivesworks.webmaster FROM delivesworks  WHERE delivesworks.date $znak '$data' AND delivesworks.status = 0) AND users.antihold = 0";
		$this->db->query($query);
		return $this->db->affected_rows();
	}
	
	function free_platforms($uid){
	
		$query = "SELECT platforms.id,platforms.remoteid,mkplatform.id AS mkid FROM `platforms` LEFT JOIN mkplatform ON platforms.`id` = mkplatform.platform WHERE platforms.webmaster = $uid AND platforms.remoteid !=0 AND platforms.manager = 2 GROUP BY platforms.id;";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function debetors_webmarkets(){
	
		$query = "SELECT webmarkets.* FROM users INNER JOIN webmarkets ON users.remoteid = webmarkets.webmaster WHERE users.debetor = 1 AND users.antihold = 0";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
}