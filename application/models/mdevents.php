<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdevents extends CI_Model{

	var $id			= 0;
	var $title		= '';
	var $type_event = '';
	var $translit	= '';
	var $content 	= '';
	var $date		= '';
	var $image		= '';
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$translit,$table){
			
		$this->title	= htmlspecialchars($data['title']);
		$this->type_event= $data['type_event'];
		$this->translit	= $translit;
		$this->content	= $data['content'];
		$this->date		= date("Y-m-d");
		$this->image	= $data['image'];
		
		$this->db->insert($table,$this);
		return $this->db->insert_id();
	}
	
	function update_record($id,$data,$translit,$table){
		
		$this->db->set('title',htmlspecialchars($data['title']));
		$this->db->set('translit',$translit);
		$this->db->set('content',$data['content']);
		if(isset($data['image'])):
			$this->db->set('image',$data['image']);
		endif;
		$this->db->where('id',$id);
		$this->db->update($table);
		return $this->db->affected_rows();
	}
	
	function read_records($types,$table){
		
		$this->db->select('id,title,content,date,translit');
		$this->db->where_in('type_event',$types);
		$this->db->order_by('date','DESC');
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_records_limit($types,$table,$count,$from){
		
		$this->db->select('id,title,content,date,translit');
		$this->db->where_in('type_event',$types);
		$this->db->order_by('date','DESC');
		$this->db->limit($count,$from);
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records($types,$table){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where_in('type_event',$types);
		$this->db->order_by('date','DESC');
		$query = $this->db->get($table);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_record($id,$table){
		
		$this->db->select('id,title,content,date,translit');
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
	
	function read_field_translit($translit,$field,$table){
			
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
	
	function get_image($mid,$table){
		
		$this->db->where('id',$mid);
		$this->db->select('image');
		$query = $this->db->get($table);
		$data = $query->result_array();
		return $data[0]['image'];
	}
}