<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdmessages extends CI_Model{

	var $id			= 0;
	var $sender 	= 0;
	var $recipient 	= 0;
	var $date 		= '';
	var $text 		= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$recipient,$text){
			
		$this->sender 		= $uid;
		$this->recipient	= $recipient;
		$this->text 		= $text;
		$this->date 		= date("Y-m-d");
		
		$this->db->insert('messages',$this);
		return $this->db->insert_id();
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
	
	function count_records_by_recipient($recipient){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('recipient',$recipient);
		$query = $this->db->get('messages');
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
	
	function delete_records_by_user($recipient){
	
		$this->db->where('recipient',$recipient);
		$this->db->delete('messages');
		return $this->db->affected_rows();
	}	
}