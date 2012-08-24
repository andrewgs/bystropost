<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdquote extends CI_Model{

	var $id		= 0;
	var $image	= '';
	var $name	= '';
	var $text	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$table){
			
		$this->name		= htmlspecialchars($data['name']);
		$this->image	= $data['image'];
		$this->text		= $data['text'];
		
		$this->db->insert($table,$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$data,$table){
		
		$this->db->set('name',htmlspecialchars($data['name']));
		$this->db->set('text',$data['text']);
		if(isset($data['image'])):
			$this->db->set('image',$data['image']);
		endif;
		$this->db->where('id',$id);
		$this->db->update($table);
		return $this->db->affected_rows();
	}
	
	function read_records($table){
		
		$this->db->select('id,name,text');
		$this->db->order_by('id');
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_limit_records($table,$count,$from){
		
		$this->db->select('id,name,text');
		$this->db->order_by('id');
		$this->db->limit($count,$from);
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records($table){
		
		return $this->db->count_all($table);;
	}
	
	function read_record($id,$table){
		
		$this->db->select('id,name,text');
		$this->db->where('id',$id);
		$query = $this->db->get($table,1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_random_record($table,$count){
		
		$query = "SELECT id,name,text FROM $table ORDER BY RAND() LIMIT $count";
		$query = $this->db->query($query);
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
	
	function get_image($mid,$table){
		
		$this->db->where('id',$mid);
		$this->db->select('image');
		$query = $this->db->get($table);
		$data = $query->result_array();
		return $data[0]['image'];
	}
}