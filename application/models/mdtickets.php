<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdtickets extends CI_Model{

	var $id			= 0;
	var $sender 	= 0;
	var $platform 	= 0;
	var $recipient 	= 0;
	var $title 		= '';
	var $date 		= '';
	var $status 	= 0;
	var $sender_answer 	= 0;
	var $recipient_answer 	= 0;
	var $sender_reading 	= 0;
	var $recipient_reading 	= 0;
	var $importance = 0;
	var $type 		= 1;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$recipient,$data){
			
		$this->sender 		= $uid;
		$this->recipient	= $recipient;
		$this->platform		= $data['platform'];
		$this->title		= $data['title'];
		$this->importance	= $data['importance'];
		$this->date 		= date("Y-m-d H:i:s");
		$this->type 		= $data['type'];
		
		$this->db->insert('tickets',$this);
		return $this->db->insert_id();
	}
	
	function is_replied($tid){
		
		$this->db->where('status',1);
		$this->db->where('id',$tid);
		$query = $this->db->get('tickets');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records(){
		
		$this->db->order_by('date');
		$query = $this->db->get('tickets');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_by_sender($sender){
		
		$this->db->where('sender',$sender);
		$query = $this->db->get('tickets');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records_by_sender($user){
		
		$query = "SELECT COUNT(*) AS cnt FROM tickets WHERE tickets.sender = $user";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_records_by_recipient($recipient){
		
		$this->db->where('recipient',$recipient);
		$this->db->where('sender',$recipient);
		$query = $this->db->get('tickets');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records_by_recipient($user){
		
		$query = "SELECT COUNT(*) AS cnt FROM tickets WHERE tickets.recipient = $user";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('tickets',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('tickets',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function update_field($id,$field,$value){
			
		$this->db->set($field,$value);
		$this->db->where('id',$id);
		$this->db->update('tickets');
		return $this->db->affected_rows();
	}
	
	function open_ticket($id,$sender = FALSE){
			
		$this->db->set('status',0);
		if($sender):
			$this->db->where('sender',$sender);
		endif;
		$this->db->where('id',$id);
		$this->db->update('tickets');
		return $this->db->affected_rows();
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('tickets');
		return $this->db->affected_rows();
	}
	
	function delete_records_by_user($uid){
	
		$this->db->where('sender',$uid);
		$this->db->or_where('recipient',$uid);
		$this->db->delete('tickets');
		return $this->db->affected_rows();
	}
	
	function delete_records_by_platform($platform){
	
		$this->db->where('platform',$platform);
		$this->db->delete('tickets');
		return $this->db->affected_rows();
	}
	
	function ownew_ticket($sender,$id){
		
		$this->db->where('id',$id);
		$this->db->where('sender',$sender);
		$query = $this->db->get('tickets',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
	
	function ownew_ticket_or_recipient($uid,$id){
		
		$query = "SELECT * FROM tickets WHERE id = $id AND (sender = $uid OR recipient = $uid) LIMIT 1";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}

	function change_sender_recipient_by_new_manager($new_recipient,$old_recipient,$platform){
		
		$this->db->set('recipient',$new_recipient);
		$this->db->where('platform',$platform);
		$this->db->where('recipient',$old_recipient);
		$this->db->update('tickets');
		return $this->db->affected_rows();
	}
}