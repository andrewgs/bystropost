<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdpromocodes extends CI_Model{

	var $id			= 0;
	var $code 		= '';
	var $datefrom 	= '';
	var $dateto 	= '';
	var $active 	= 1;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data){
			
		$this->code 	= $data['code'];
		$this->datefrom = $data['datefrom'];
		$this->dateto	= $data['dateto'];
		
		$this->db->insert('promocodes',$this);
		return $this->db->insert_id();
	}
	
	function update_record($data){
		
		$this->db->set('code',$data['code']);
		$this->db->set('datefrom',$data['datefrom']);
		$this->db->set('dateto',$data['dateto']);
		
		$this->db->where('id',$data['cid']);
		$this->db->update('promocodes');
		return $this->db->affected_rows();
	}
	
	function read_records(){
		
		$this->db->order_by('id');
		$query = $this->db->get('promocodes');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('promocodes',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('promocodes',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('promocodes');
		return $this->db->affected_rows();
	}
	
	function exist_code($code){
		
		$this->db->select('id');
		$this->db->where('code',$code);
		$query = $this->db->get('promocodes',1);
		$data = $query->result_array();
		if(count($data)) return $data[0]['id'];
		return FALSE;
	}

	function count_all(){
		
		return $this->db->count_all('promocodes');
	}
}