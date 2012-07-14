<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdtypeswork extends CI_Model{

	var $id			= 0;
	var $title 		= '';
	var $wprice 	= 0;
	var $mprice 	= 0;
	var $nickname	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data){
			
		$this->title 	= htmlspecialchars($data['title']);
		$this->wprice 	= $data['wprice'];
		$this->mprice 	= $data['mprice'];
		
		$this->db->insert('typeswork',$this);
		return $this->db->insert_id();
	}
	
	function update_record($data){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('wprice',$data['wprice']);
		$this->db->set('mprice',$data['mprice']);
		$this->db->where('id',$data['tpid']);
		$this->db->update('typeswork');
		return $this->db->affected_rows();
	}
	
	function read_records(){
		
		$this->db->order_by('title');
		$query = $this->db->get('typeswork');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('typeswork',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('typeswork',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('typeswork');
		return $this->db->affected_rows();
	}
	
	function exist_type_work($type){
		
		$this->db->where('title',$type);
		$query = $this->db->get('typeswork',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}

	
}