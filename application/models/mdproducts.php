<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdproducts extends CI_Model{

	var $id			= 0;
	var $title		= '';
	var $translit	= '';
	var $showitem	= '';
	var $content	= '';
	var $image		= '';
	var $type		= '';
	var $alcohol	= '';
	var $sugar		= '';
	var $category	= 0;
	var $series		= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$translit,$category,$series,$table){
			
		$this->title	= htmlspecialchars($data['title']);
		$this->content	= $data['content'];
		$this->image	= $data['image'];
		$this->type		= $data['type'];
		$this->alcohol	= $data['alcohol'];
		$this->sugar	= $data['sugar'];
		$this->translit	= $translit;
		$this->category	= $category;
		$this->series	= $series;
		
		$this->db->insert($table,$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$data,$translit,$category,$series,$table){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('content',$data['content']);
		if(isset($data['image'])):
			$this->db->set('image',$data['image']);
		endif;
		$this->db->set('showitem',$data['showitem']);
		$this->db->set('type',$data['type']);
		$this->db->set('alcohol',$data['alcohol']);
		$this->db->set('sugar',$data['sugar']);
		$this->db->set('translit',$translit);
		$this->db->set('category',$category);
		$this->db->set('series',$series);
		$this->db->where('id',$id);
		$this->db->update($table);
		return $this->db->affected_rows();
	}
	
	function read_records($table){
		
		$this->db->where('showitem',1);
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_limit_records($table,$count,$from){
		
		$this->db->select('id,title,translit,content,type,alcohol,sugar,category,series,showitem');
		$this->db->limit($count,$from);
		$this->db->where('showitem',1);
		$this->db->order_by('category');
		$this->db->order_by('series');
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_admin_limit_records($table,$count,$from){
		
		$this->db->select('id,title,translit,content,type,alcohol,sugar,category,series,showitem');
		$this->db->limit($count,$from);
		$this->db->order_by('category');
		$this->db->order_by('series');
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records($table){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('showitem',1);
		$query = $this->db->get($table,1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0]['cnt'];
		return 0;
	}
	
	function count_filtr_records($parameter,$field,$table){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where($field,$parameter);
		$this->db->where('showitem',1);
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data[0]['cnt'];
		return 0;
	}
	
	function read_record($id,$table){
		
		$this->db->select('id,title,translit,content,type,alcohol,sugar,category,series,showitem');
		$this->db->where('id',$id);
		$this->db->where('showitem',1);
		$query = $this->db->get($table,1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_admin_record($id,$table){
		
		$this->db->select('id,title,translit,content,type,alcohol,sugar,category,series,showitem');
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