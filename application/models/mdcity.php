<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdcity extends CI_Model{

	var $id			= 0;
	var $title		= '';
	var $translit	= '';
	var $country	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($title,$translit,$country,$table){
			
		$this->title	= htmlspecialchars($title);
		$this->translit	= $translit;
		$this->country	= $country;
		
		$this->db->insert($table,$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$title,$translit,$table){
		
		$this->db->set('title',htmlspecialchars($title));
		$this->db->set('translit',$translit);
		$this->db->where('id',$id);
		$this->db->update($table);
		return $this->db->affected_rows();
	}
	
	function read_records($table){
	
		$this->db->order_by('country');
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records($table){
		
		return $this->db->count_all($table);;
	}
	
	function read_record($id,$table){
		
		$this->db->select('id,title,translit');
		$this->db->where('id',$id);
		$query = $this->db->get($table,1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field,$table){
			
		$this->db->where('id',$id);
		$query = $this->db->get($table,1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function read_field_translit($translit,$field,$table){
			
		$this->db->where('translit',$translit);
		$query = $this->db->get($table,1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id,$table){
	
		$this->db->where('id',$id);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}

	function delete_records_country($country,$table){
		
		$this->db->where('country',$country);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}
}