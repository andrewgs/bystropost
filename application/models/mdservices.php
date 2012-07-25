<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdservices extends CI_Model{

	var $id		= 0;
	var $title 	= '';
	var $price 	= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data){
			
		$this->title 	= htmlspecialchars($data['title']);
		$this->price 	= $data['price'];
		
		$this->db->insert('services',$this);
		return $this->db->insert_id();
	}
	
	function update_record($data){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('price',$data['price']);
		$this->db->where('id',$data['sid']);
		$this->db->update('services');
		return $this->db->affected_rows();
	}
	
	function read_records(){
		
		$this->db->order_by('title');
		$query = $this->db->get('services');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('services',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('services',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('services');
		return $this->db->affected_rows();
	}
	
	function exist_market($market){
		
		$this->db->where('title',$market);
		$query = $this->db->get('services',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
}