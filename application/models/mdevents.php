<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdevents extends CI_Model{

	var $id			= 0;
	var $title		= '';
	var $announcement= '';
	var $translit	= '';
	var $text 		= '';
	var $date		= '';
	var $image		= '';
	var $noimage	= 0;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$translit){
			
		$this->title		= htmlspecialchars($data['title']);
		$this->translit		= $translit;
		$this->announcement	= strip_tags(nl2br($data['announcement']),'<br>');
		$this->text			= strip_tags(nl2br($data['text']),'<br>');
		$this->date			= date("Y-m-d");
		if($data['image']):
			$this->image	= $data['image'];
			$this->noimage = 0;
		else:
			$this->image = '';
			$this->noimage = 1;
		endif;
		
		$this->db->insert('events',$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$data,$translit,$noimage){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('translit',$translit);
		$this->db->set('text',strip_tags(nl2br($data['text']),'<br>'));
		$this->db->set('announcement',strip_tags(nl2br($data['announcement']),'<br>'));
		$this->db->set('noimage',$noimage);
		if(isset($data['image'])):
			$this->db->set('image',$data['image']);
		endif;
		$this->db->where('id',$id);
		$this->db->update('events');
		return $this->db->affected_rows();
	}
	
	function read_records(){
		
		$this->db->select('id,title,text,date,translit,noimage');
		$this->db->order_by('date','DESC');
		$query = $this->db->get('events');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_limit($count,$from){
		
		$this->db->select('id,title,text,date,translit,noimage,announcement');
		$this->db->order_by('date','DESC');
		$this->db->limit($count,$from);
		$query = $this->db->get('events');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records(){
		
		return $this->db->count_all('events');
	}
	
	function read_record($id){
		
		$this->db->select('id,title,text,date,translit,noimage,announcement');
		$this->db->where('id',$id);
		$query = $this->db->get('events',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('events',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function read_field_translit($translit,$field){
			
		$this->db->where('translit',$translit);
		$query = $this->db->get('events',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('events');
		return $this->db->affected_rows();
	}
	
	function get_image($mid){
		
		$this->db->where('id',$mid);
		$this->db->select('image');
		$query = $this->db->get('events');
		$data = $query->result_array();
		return $data[0]['image'];
	}
}