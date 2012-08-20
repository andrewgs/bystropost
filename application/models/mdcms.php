<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdcms extends CI_Model{

	var $id		= 0;
	var $title 	= '';
	var $price 	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($id,$title,$price){
			
		$this->id 	= $id;
		$this->title= $title;
		$this->price= $price;
		
		$this->db->insert('cms',$this);
		return $this->db->insert_id();
	}
	
	function read_records(){
		
		$this->db->order_by('title');
		$query = $this->db->get('cms');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('cms',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
}