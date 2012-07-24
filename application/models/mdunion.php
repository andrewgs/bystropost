<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdunion extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function devivers_works_manager($uid,$count,$from){
		
		$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.manager = $uid ORDER BY delivesworks.date DESC,delivesworks.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_devivers_works_manager($uid){
		
		$query = "SELECT delivesworks.* FROM delivesworks WHERE delivesworks.manager = $uid";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
	function devivers_works_webmaster($uid,$count,$from){
		
		$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.webmaster = $uid ORDER BY delivesworks.date DESC,delivesworks.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_devivers_works_webmaster($uid){
		
		$query = "SELECT delivesworks.* FROM delivesworks WHERE delivesworks.webmaster = $uid";
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
		
		$query = "SELECT tickets.*,	tkmsgs.text FROM tickets LEFT JOIN tkmsgs ON tickets.id=tkmsgs.ticket WHERE tickets.sender = $sender GROUP BY tickets.id ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC";
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
	
	function count_platforms_by_owners(){
		
		$query = "SELECT platforms.id,users.id AS uporders FROM platforms LEFT JOIN users ON platforms.webmaster=users.id";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}

	function devivers_works_platform($platform,$count,$from){
		
		$query = "SELECT delivesworks.*, platforms.url AS ptitle,typeswork.title AS twtitle,markets.title AS mtitle FROM delivesworks INNER JOIN platforms ON delivesworks.platform=platforms.id INNER JOIN typeswork ON delivesworks.typework=typeswork.id INNER JOIN markets ON delivesworks.market=markets.id WHERE delivesworks.platform = $platform ORDER BY delivesworks.date DESC,delivesworks.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_devivers_works_platform($platform){
		
		$query = "SELECT delivesworks.* FROM delivesworks WHERE delivesworks.platform = $platform";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
	
}