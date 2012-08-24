<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdmagazines extends CI_Model{

	var $id			= 0;
	var $title		= '';
	var $translit	= '';
	var $address	= '';
	var $phones		= '';
	var $country	= 0;
	var $city		= 0;
	var $type		= 1;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$translit,$country,$city,$table){
			
		$this->title	= htmlspecialchars($data['title']);
		$this->address	= htmlspecialchars($data['address']);
		$this->phones	= htmlspecialchars($data['phones']);
		$this->translit	= $translit;
		$this->country	= $country;
		$this->city		= $city;
		$this->type		= $data['type'];
		
		$this->db->insert($table,$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$data,$translit,$country,$city,$table){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('address',htmlspecialchars($data['address']));
		$this->db->set('phones',htmlspecialchars($data['phones']));
		$this->db->set('translit',$translit);
		$this->db->set('country',$country);
		$this->db->set('city',$city);
		$this->db->set('type',$data['type']);
		$this->db->where('id',$id);
		$this->db->update($table);
		return $this->db->affected_rows();
	}
	
	function read_records($table){
		
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records($table){
		
		return $this->db->count_all($table);;
	}
	
	function read_record($id,$table){
		
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
	
	function read_field_translit($country,$city,$translit,$field,$table){
			
		$this->db->where('country',$country);
		$this->db->where('city',$city);
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
	
	function delete_records_city($city,$table){
	
		$this->db->where('city',$city);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}

	function read_shops($type,$country,$city,$table){
		
		if($country):
			$this->db->where('country',$country);
		endif;
		if($city):
			$this->db->where('city',$city);
		endif;
		$this->db->where('type',$type);
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
}