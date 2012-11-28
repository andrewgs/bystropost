<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdwebmarkets extends CI_Model{

	var $id			= 0;
	var $webmaster	= 0;
	var $market		= 0;
	var $login		= '';
	var $password	= '';
	var $cryptpassword 	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($market,$webmaster,$data){
			
		$this->id			= $market;
		$this->webmaster	= $webmaster;
		$this->market 		= $data['market'];
		$this->login		= $data['login'];
		$this->password 	= md5($data['password']);
		$this->cryptpassword= $this->encrypt->encode($data['password']);
		
		$this->db->insert('webmarkets',$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$urid,$data){
		
		if(isset($data['login']) and !empty($data['login'])):
			$this->db->set('login',$data['login']);
		endif;
		$this->db->set('password',md5($data['password']));
		$this->db->set('cryptpassword',$this->encrypt->encode($data['password']));
		
		$this->db->where('id',$id);
		$this->db->where('webmaster',$urid);
		$this->db->update('webmarkets');
		return $this->db->affected_rows();
	}
	
	function update_status($webmaster,$id,$status){
		
		$this->db->set('status',$status);
		
		$this->db->where('id',$id);
		$this->db->where('webmaster',$webmaster);
		$this->db->update('webmarkets');
		return $this->db->affected_rows();
	}
	
	function read_records($webmaster = FALSE){
		if($webmaster):
			$this->db->where('webmaster',$webmaster);
		endif;
		$query = $this->db->get('webmarkets');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records($webmaster){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('webmaster',$webmaster);
		$query = $this->db->get('webmarkets');
		$data = $query->result_array();
		if(isset($data[0])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('webmarkets',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_owner_market($id,$uid){
		
		$this->db->where('id',$id);
		$this->db->where('webmaster',$uid);
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

	function exist_market($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('webmarkets');
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
}