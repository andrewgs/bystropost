<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdlog extends CI_Model{

	var $id		= 0;
	var $user	= 0;
	var $note 	= '';
	var $date 	= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($user,$note){
			
		$this->user 	= $user;
		$this->note 	= $note;
		$this->date		= date("Y-m-d");
		
		$this->db->insert('log',$this);
		return $this->db->insert_id();
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('log',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_records(){
	
		$this->db->truncate('log');
	}

	function count_records(){
		
		return $this->db->count_all('log');
	}
}