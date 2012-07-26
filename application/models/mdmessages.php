<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdmessages extends CI_Model{

	var $id			= 0;
	var $system		= 0;
	var $group		= 1;
	var $type		= 1;
	var $sender 	= 0;
	var $recipient 	= 0;
	var $date 		= '';
	var $newmail 	= 1;
	var $text 		= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$recipient,$text){
			
		$this->sender 		= $uid;
		$this->recipient	= $recipient;
		$this->text 		= strip_tags($text);
		$this->date 		= date("Y-m-d");
		
		$this->db->insert('messages',$this);
		return $this->db->insert_id();
	}
	
	function send_noreply_message($uid,$recipient,$type,$group,$text){
			
		$this->sender 		= $uid;
		$this->recipient	= $recipient;
		$this->system 		= 1;
		$this->type			= $type;
		$this->group 		= $group;
		$this->text 		= $text;
		$this->date 		= date("Y-m-d");
		
		$this->db->insert('messages',$this);
		return $this->db->insert_id();
	}
	
	function send_system_message($uid,$data){
			
		$this->sender 	= $uid;
		$this->system 	= 1;
		$this->type		= $data['type'];
		$this->group 	= $data['group'];
		$this->text 	= $data['text'];
		$this->date 	= date("Y-m-d");
		
		$this->db->insert('messages',$this);
		return $this->db->insert_id();
	}
	
	function is_system($mid){
		
		$this->db->where('system',1);
		$this->db->where('id',$mid);
		$query = $this->db->get('messages');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records(){
		
		$this->db->order_by('date');
		$query = $this->db->get('messages');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_by_sender($sender){
		
		$this->db->where('sender',$sender);
		$query = $this->db->get('messages');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records_by_sender($sender){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('sender',$sender);
		$query = $this->db->get('messages');
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_records_by_recipient($recipient){
		
		$this->db->where('recipient',$recipient);
		$query = $this->db->get('messages');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records_by_recipient($recipient,$utype,$date){
		
		$query = "SELECT COUNT(*) AS cnt FROM messages WHERE ((messages.recipient = $recipient AND messages.system = 0) OR (messages.group = $utype AND messages.system = 1 AND messages.recipient = $recipient) OR (messages.group = $utype AND messages.system = 1 AND messages.recipient = 0)) AND messages.date >= '$date'";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function count_records_by_recipient_new($recipient){
		
		$query = "SELECT COUNT(*) AS cnt FROM messages WHERE messages.recipient = $recipient AND newmail = 1";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function count_records_by_admin_new($recipient){
		
		$query = "SELECT COUNT(*) AS cnt FROM messages WHERE (messages.recipient = $recipient OR messages.recipient = 0) AND newmail = 1";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function set_read_mails_by_recipient($recipient){
		
		$this->db->set('newmail',0);
		$this->db->where('recipient',$recipient);
		$this->db->update('messages');
		return $this->db->affected_rows();
	}
	
	function set_read_mails_by_admin($recipient){
		
		$this->db->set('newmail',0);
		$this->db->where('recipient',$recipient);
		$this->db->or_where('recipient',0);
		$this->db->update('messages');
		return $this->db->affected_rows();
	}
	
	function read_mails_user_pages($user){
		
		$query = "SELECT * FROM messages WHERE messages.recipient = $user OR messages.sender = $user ORDER BY date DESC, id DESC";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_mails_user_pages($user){
		
		$query = "SELECT COUNT(*) AS cnt FROM messages WHERE messages.recipient = $user OR messages.sender = $user";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('messages',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('messages',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('messages');
		return $this->db->affected_rows();
	}
	
	function delete_system(){
	
		$this->db->where('system',1);
		$this->db->delete('messages');
		return $this->db->affected_rows();
	}
	
	function delete_records_by_user($uid){
	
		$this->db->where('recipient',$uid);
		$this->db->or_where('sender',$uid);
		$this->db->delete('messages');
		return $this->db->affected_rows();
	}
}