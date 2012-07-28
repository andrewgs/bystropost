<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdwebmarkets extends CI_Model{

	var $id			= 0;
	var $webmaster	= 0;
	var $market		= 0;
	var $login		= '';
	var $password	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($market,$webmaster,$data){
			
		$this->id			= $market;
		$this->webmaster	= $webmaster;
		$this->market 		= $data['market'];
		$this->login		= $data['login'];
		$this->password		= $data['password'];
		
		$this->db->insert('webmarkets',$this);
		return $this->db->insert_id();
	}
	
	function update_record($data){
		
		$this->db->set('login',$data['login']);
		$this->db->set('password',$data['password']);
		
		$this->db->where('id',$data['mid']);
		$this->db->update('webmarkets');
		return $this->db->affected_rows();
	}
	
	function read_records($webmaster){
		
		$this->db->where('webmaster',$webmaster);
		$query = $this->db->get('webmarkets');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('webmarkets',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('webmarkets',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($webmaster,$id){
	
		$this->db->where('webmaster',$webmaster);
		$this->db->where('id',$id);
		$this->db->delete('webmarkets');
		return $this->db->affected_rows();
	}
}