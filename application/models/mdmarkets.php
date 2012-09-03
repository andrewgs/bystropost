<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdmarkets extends CI_Model{

	var $id		= 0;
	var $title 	= '';
	var $url 	= 0;
	var $icon 	= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data){
			
		$this->title 	= htmlspecialchars($data['title']);
		$this->url 		= $data['url'];
		$this->icon		= $data['icon'];
		
		$this->db->insert('markets',$this);
		return $this->db->insert_id();
	}
	
	function update_record($data){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('url',$data['url']);
		if(isset($data['icon'])):
			$this->db->set('icon',$data['icon']);
		endif;
		$this->db->where('id',$data['mid']);
		$this->db->update('markets');
		return $this->db->affected_rows();
	}
	
	function read_records(){
		
		$this->db->select('id,title,url');
		$this->db->order_by('id');
		$query = $this->db->get('markets');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->select('id,title,url');
		$this->db->where('id',$id);
		$query = $this->db->get('markets',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('markets',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('markets');
		return $this->db->affected_rows();
	}
	
	function exist_market($market){
		
		$this->db->where('title',$market);
		$query = $this->db->get('markets',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}

	function get_image($mid){
		
		$this->db->where('id',$mid);
		$this->db->select('icon');
		$query = $this->db->get('markets');
		$data = $query->result_array();
		return $data[0]['icon'];
	}

	function count_all(){
		
		return $this->db->count_all('markets');
	}
}