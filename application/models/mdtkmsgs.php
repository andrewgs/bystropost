<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdtkmsgs extends CI_Model{

	var $id			= 0;
	var $ticket		= 0;
	var $reply		= 0;
	var $owner 		= 0;
	var $sender		= 0;
	var $recipient 	= 0;
	var $date 		= '';
	var $text 		= 1;
	
	function __construct(){
		parent::__construct();
	}
	
	function insert_record($uid,$ticket,$sender,$recipient,$reply,$text){
			
		$this->ticket 	= $ticket;
		$this->owner 	= $uid;
		$this->sender	= $sender;
		$this->recipient= $recipient;
		$this->reply	= $reply;
		$this->date 	= date("Y-m-d");
		$this->text 	= strip_tags(nl2br($text,'<br/>'));
		
		$this->db->insert('tkmsgs',$this);
		return $this->db->insert_id();
	}
	
	function read_records(){
		
		$this->db->order_by('date');
		$query = $this->db->get('tkmsgs');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_finish_message($owner,$ticket){
		
		$this->db->select('text');
		$this->db->where('owner',$owner);
		$this->db->where('ticket',$ticket);
		$this->db->order_by('date','DESC');
		$this->db->order_by('id','DESC');
		$query = $this->db->get('tkmsgs',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0]['text'];
		return '';
	}
	
	function noowner_finish_message($ticket){
		
		$this->db->select('text');
		$this->db->where('ticket',$ticket);
		$this->db->order_by('date','DESC');
		$this->db->order_by('id','DESC');
		$query = $this->db->get('tkmsgs',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0]['text'];
		return '';
	}
	
	function read_records_by_owner($owner){
		
		$this->db->where('owner',$owner);
		$query = $this->db->get('tkmsgs');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_records_by_owner($owner){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('owner',$owner);
		$query = $this->db->get('tkmsgs');
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_tkmsgs_by_owner_pages($owner,$ticket,$count,$from){
		
		$this->db->order_by('date','DESC');
		$this->db->order_by('id','DESC');
		$this->db->where('owner',$owner);
		$this->db->where('ticket',$ticket);
		$this->db->limit($count,$from);
		$query = $this->db->get('tkmsgs');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function count_tkmsgs_by_owner_pages($owner,$ticket){
		
		$this->db->select('COUNT(*) AS cnt');
		$this->db->where('owner',$owner);
		$this->db->where('ticket',$ticket);
		$query = $this->db->get('tkmsgs');
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_tkmsgs_by_recipient_pages($uid,$ticket,$count,$from){
		
		$query = "SELECT * FROM tkmsgs WHERE recipient = $uid AND ticket = $ticket ORDER BY date DESC,id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return FALSE;
	}
	
	function count_tkmsgs_by_recipient_pages($uid,$ticket){
		
		$query = "SELECT COUNT(*) AS cnt FROM tkmsgs WHERE recipient = $uid AND ticket = $ticket";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_tkmsgs_by_manager_pages($uid,$ticket,$count,$from){
		
		$query = "SELECT * FROM tkmsgs WHERE (recipient = $uid OR sender = $uid) AND ticket = $ticket ORDER BY date DESC,id DESC LIMIT $from,$count";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return FALSE;
	}
	
	function count_tkmsgs_by_manager_pages($uid,$ticket){
		
		$query = "SELECT COUNT(*) AS cnt FROM tkmsgs WHERE (recipient = $uid OR sender = $uid) AND ticket = $ticket";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(isset($data[0]['cnt'])) return $data[0]['cnt'];
		return 0;
	}
	
	function read_record($id,$uid){
		
		$this->db->where('id',$id);
		$this->db->where('owner',$uid);
		$query = $this->db->get('tkmsgs',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('tkmsgs',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('tkmsgs');
		return $this->db->affected_rows();
	}
	
	function delete_records($ticket){
	
		$this->db->where('ticket',$ticket);
		$this->db->delete('tkmsgs');
		return $this->db->affected_rows();
	}
	
	function delete_records_by_user($owner){
	
		$this->db->where('owner',$owner);
		$this->db->delete('tkmsgs');
		return $this->db->affected_rows();
	}

	function ownew_message($recipient){
		
		$this->db->where('recipient',$recipient);
		$this->db->or_where('recipient',0);
		$query = $this->db->get('tkmsgs',1);
		$data = $query->result_array();
		if(count($data)) return TRUE;
		return FALSE;
	}
}