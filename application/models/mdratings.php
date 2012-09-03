<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdratings extends CI_Model{

	var $id		= 0;
	var $title 	= '';
	var $resource 	= '';
	var $text 	= 0;
	var $date 	= '';
	var $avatar = '';
	var $type 	= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$type){
			
		$this->title 	= htmlspecialchars($data['title']);
		$this->resource = $data['resource'];
		$this->text 	= strip_tags($data['text']);
		$this->avatar	= $data['avatar'];
		$this->date		= date("Y-m-d");
		$this->type		= $type;
		
		$this->db->insert('ratings',$this);
		return $this->db->insert_id();
	}
	
	function update_record($data){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('resource',$data['resource']);
		$this->db->set('text',strip_tags($data['text']));
		if(isset($data['avatar'])):
			$this->db->set('avatar',$data['avatar']);
		endif;
		$this->db->where('id',$data['rid']);
		$this->db->update('ratings');
		return $this->db->affected_rows();
	}
	
	function read_records($type){
		
		$this->db->select('id,title,resource,text,date');
		$this->db->order_by('date','DESC');
		$this->db->where('type',$type);
		$query = $this->db->get('ratings');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->select('id,title,resource,text,date');
		$this->db->where('id',$id);
		$query = $this->db->get('ratings',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('ratings',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('ratings');
		return $this->db->affected_rows();
	}
	
	function exist_rating($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('ratings',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}

	function get_image($mid){
		
		$this->db->where('id',$mid);
		$this->db->select('avatar');
		$query = $this->db->get('ratings');
		$data = $query->result_array();
		return $data[0]['avatar'];
	}
}