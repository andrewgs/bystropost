<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdwhereby extends CI_Model{

	var $id			= 0;
	var $category	= 0;
	var $series		= 0;
	var $product	= 0;
	var $country	= 0;
	var $magazine	= 0;
	var $city		= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($category,$series,$product,$country,$magazine,$city,$table){
			
		$this->category = $category;
		$this->series	= $series;
		$this->product	= $product;
		$this->country	= $country;
		$this->magazine = $magazine;
		$this->city		= $city;
		
		$this->db->insert($table,$this);
		return $this->db->insert_id();
	}
	
	function update_record($country,$magazine,$city,$table){
		
		$this->db->set('country',$country);
		$this->db->set('magazine',$magazine);
		$this->db->set('city',$city);
		$this->db->where('id',$id);
		$this->db->update($table);
		return $this->db->affected_rows();
	}
	
	function count_records($table){
		
		return $this->db->count_all($table);;
	}
	
	function read_record($id,$table){
		
		$this->db->select('id,title');
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
	
	function delete_record($id,$table){
	
		$this->db->where('id',$id);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}

	function delete_records_products($product,$table){
	
		$this->db->where('product',$product);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}

	function delete_records_category($category,$table){
	
		$this->db->where('category',$category);
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
	
	function delete_records_series($series,$table){
	
		$this->db->where('series',$series);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}

	function delete_records_shop($magazine,$table){
	
		$this->db->where('magazine',$magazine);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}
}