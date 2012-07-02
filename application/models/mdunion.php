<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdunion extends CI_Model{

	function __construct(){
		parent::__construct();
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
	
	function read_mails_by_recipient($recipient,$utype){
		
		$query = "SELECT messages.*, users.fio,users.login FROM messages INNER JOIN users ON messages.sender=users.id WHERE (messages.recipient = $recipient && messages.system = 0) OR (messages.group = $utype AND messages.system = 1) ORDER BY messages.date DESC,messages.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_tickets_by_recipient($recipient){
		
		$query = "SELECT tickets.*, users.fio,users.login FROM tickets INNER JOIN users ON tickets.sender=users.id WHERE tickets.recipient = $recipient ORDER BY tickets.date DESC,tickets.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_tickets_by_sender($sender){
		
		$query = "SELECT tickets.*, users.fio,users.login,tkmsgs.text FROM tickets INNER JOIN users ON tickets.sender=users.id INNER JOIN tkmsgs ON tickets.id=tkmsgs.ticket WHERE tickets.sender = $sender ORDER BY tickets.date DESC,tickets.id DESC,tkmsgs.date DESC,tkmsgs.id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_mails_by_recipient_pages($recipient,$count,$from){
		
		$query = "SELECT messages.*, users.fio,users.login FROM messages INNER JOIN users ON messages.sender=users.id WHERE messages.recipient = $recipient ORDER BY messages.date DESC,messages.id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_mails_by_recipient_pages($recipient){
		
		$query = "SELECT messages.*, users.fio,users.login FROM messages INNER JOIN users ON messages.sender=users.id WHERE messages.recipient = $recipient";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return count($data);
		return NULL;
	}
}