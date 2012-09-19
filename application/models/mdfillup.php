<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdfillup extends CI_Model{

	var $id		= 0;
	var $user 	= 0;
	var $summa 	= 0;
	var $date 	= '';
	var $result = '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($user,$summa,$result){
			
		$this->user 	= $user;
		$this->summa	= $summa;
		$this->date		= date("Y-m-d");
		$this->result	= $result;
		
		$this->db->insert('fillup',$this);
		return $this->db->insert_id();
	}
	
	function read_records(){
		
		$this->db->order_by('date','DESC');
		$query = $this->db->get('fillup');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('fillup',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('fillup',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('fillup');
		return $this->db->affected_rows();
	}
}