<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdmedals extends CI_Model{

	var $id			= 0;
	var $title		= '';
	var $image		= '';
	var $product	= 0;
	var $category	= 0;
	var $series		= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$product,$category,$series,$table){
			
		$this->title	= htmlspecialchars($data['title']);
		$this->image	= $data['image'];
		$this->product	= $product;
		$this->category	= $category;
		$this->series	= $series;
		
		$this->db->insert($table,$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$data,$product,$category,$series,$table){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		if(isset($data['image'])):
			$this->db->set('image',$data['image']);
		endif;
		$this->db->set('product',$product);
		$this->db->set('category',$category);
		$this->db->set('series',$series);
		$this->db->where('id',$id);
		$this->db->update($table);
		return $this->db->affected_rows();
	}
	
	function read_records($product,$table){
		
		$this->db->where('product',$product);
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
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
	
	function read_field_translit($category,$series,$translit,$field,$table){
			
		$this->db->where('category',$category);
		$this->db->where('series',$series);
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
	
	function delete_records_series($series,$table){
	
		$this->db->where('series',$series);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}
	
	function get_image($pid,$table){
		
		$this->db->where('id',$pid);
		$this->db->select('image');
		$query = $this->db->get($table);
		$data = $query->result_array();
		return $data[0]['image'];
	}
}