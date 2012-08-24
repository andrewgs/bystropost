<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdcategory extends CI_Model{

	var $id			= 0;
	var $title		= '';
	var $translit	= '';
	var $icon		= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$translit,$table){
			
		$this->title	= htmlspecialchars($data['title']);
		$this->translit	= $translit;
		$this->icon		= $data['icon'];
		
		$this->db->insert($table,$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$data,$translit,$table){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('translit',$translit);
		if(isset($data['icon'])):
			$this->db->set('icon',$data['icon']);
		endif;
		$this->db->where('id',$id);
		$this->db->update($table);
		return $this->db->affected_rows();
	}
	
	function read_records($table){
		
		$this->db->select('id,title,translit');
		$this->db->order_by('id');
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
	
	function get_image($mid,$table){
		
		$this->db->where('id',$mid);
		$this->db->select('icon');
		$query = $this->db->get($table);
		$data = $query->result_array();
		return $data[0]['icon'];
	}
}