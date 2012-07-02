<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdtickets extends CI_Model{

	var $id			= 0;
	var $sender 	= 0;
	var $recipient 	= 0;
	var $title 		= '';
	var $date 		= '';
	var $status 	= 0;
	var $type 		= 1;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$recipient,$data){
			
		$this->sender 		= $uid;
		$this->recipient	= $recipient;
		$this->title		= $data['title'];
		$this->date 		= date("Y-m-d");
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
	
	function count_records_by_sender($sender){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('sender',$sender);
		$query = $this->db->get('tickets');
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
		
		$query = "SELECT COUNT(*) AS cnt FROM tickets WHERE tickets.sender = $user";
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
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('tickets');
		return $this->db->affected_rows();
	}
	
	function delete_records_by_user($recipient){
	
		$this->db->where('recipient',$recipient);
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
}