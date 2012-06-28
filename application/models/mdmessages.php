<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdmessages extends CI_Model{

    var $id   		= 0;
    var $sender 	= 0;
    var $recipient 	= 0;
    var $date 		= '';
    var $text 		= '';

    function __construct(){
        parent::__construct();
    }
	
	function insert_record($iud,$data){
			
		$this->sender 		= $iud;
		$this->recipient 	= $data['uid'];
		$this->date 		= date("Y-m-d");
		$this->text 		= strip_tags($data['text']);
		
		$this->db->insert('messages',$this);
		return $this->db->insert_id();
	}
	
	function read_records(){
		
		$this->db->order_by('date');
		$query = $this->db->get('messages');
		$data = $query->result_array();
		if(count($data)>0) return $data;
		return NULL;
	}
	
	function read_date(){
		
		$this->db->select('date');
		$this->db->order_by('date');
		$query = $this->db->get('messages');
		$data = $query->result_array();
		if(count($data)>0) return $data;
		return NULL;
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
	
	function exist_date($date){
		
		$this->db->where('date',$date);
		$query = $this->db->get('messages',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
}