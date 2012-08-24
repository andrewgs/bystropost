<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdtypeevents extends CI_Model{

	var $id		= 0;
	var $title	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function read_records($table){
		
		$this->db->select('id,title');
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id,$table){
		
		$this->db->select('id,title,content,date,translit');
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
}