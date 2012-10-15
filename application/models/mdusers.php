<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdusers extends CI_Model{

	var $id   			= 0;
	var $remoteid		= 0;
	var $login 			= '';
	var $cryptpassword 	= '';
	var $password 		= '';
	var $fio  			= '';
	var $wmid  			= '';
	var $knowus			= '';
	var $phones  		= '';
	var $icq  			= '';
	var $skype  		= '';
	var $forum	  		= '';
	var $balance  		= 0;
	var $logo			= '';
	var $signdate  		= '0000-00-00';
	var $lastlogin 		= '0000-00-00';
	var $closedate  	= '0000-00-00';
	var $sendmail 	 	= 0;
	var $type	  		= 1;
	var $manager 		= 0;
	var $position	  	= '';
	var $autopaid	  	= 0;
	var $locked	  		= 0;
	var $debetor  		= 0;

	function __construct(){
		parent::__construct();
	}
	
	function insert_record($data,$utype){

		$this->login 			= $data['login'];
		$this->cryptpassword	= $this->encrypt->encode($data['password']);
		$this->password			= md5($data['password']);
		$this->fio 				= $data['fio'];
		$this->wmid 			= $data['wmid'];
		$this->knowus 			= strip_tags(nl2br($data['knowus'],'<br/>'));
		$this->signdate 		= date("Y-m-d");
		$this->sendmail 		= $data['sendmail'];
		$this->type 			= $utype;
		
		switch ($utype):
			case 1 : $this->position = 'Вебмастер';break;
			case 2 : $this->position = 'Менеджер';break;
			case 3 : $this->position = 'Оптимизатор';break;
			case 4 : $this->position = 'Резерв';break;
			case 5 : $this->position = 'Администратор';break;
			default: $this->position = 'Не определен';break;
		endswitch;
		
		$this->db->insert('users',$this);
		return $this->db->insert_id();
	}
	
	function update_record($data){
		
		if(isset($data['login'])):
			$this->db->set('login',$data['login']);
		endif;
		if(isset($data['password'])):
			$this->db->set('password',md5($data['password']));
			$this->db->set('cryptpassword',$this->encrypt->encode($data['password']));
		endif;
		if(isset($data['wmid'])):
			$this->db->set('wmid',$data['wmid']);
		endif;
		if(isset($data['knowus'])):
			$this->db->set('knowus',strip_tags(nl2br($data['knowus'],'<br/>')));
		endif;
		if(isset($data['fio'])):
			$this->db->set('fio',$data['fio']);
		endif;
		if(isset($data['phones'])):
			$this->db->set('phones',$data['phones']);
		endif;
		if(isset($data['balance'])):
			$this->db->set('balance',$data['balance']);
		endif;
		if(isset($data['icq'])):
			$this->db->set('icq',$data['icq']);
		endif;
		if(isset($data['skype'])):
			$this->db->set('skype',$data['skype']);
		endif;
		if(isset($data['forum'])):
			$this->db->set('forum',$data['forum']);
		endif;
		if(isset($data['logo'])):
			$this->db->set('logo',$data['logo']);
		endif;
		if(isset($data['sendmail'])):
			$this->db->set('sendmail',$data['sendmail']);
		endif;
		if(isset($data['type'])):
			$this->db->set('type',$data['type']);
			switch ($_POST['type']):
				case 1 : $this->db->set('position','Вебмастер');break;
				case 2 : $this->db->set('position','Менеджер');break;
				case 3 : $this->db->set('position','Оптимизатор');break;
				case 4 : $this->db->set('position','Резерв');break;
				case 5 : $this->db->set('position','Администратор');break;
				default: $this->db->set('position','Не определен');break;
			endswitch;
		endif;
		if(isset($data['manager'])):
			$this->db->set('manager',$data['manager']);
		endif;
		if(isset($data['autopaid'])):
			$this->db->set('autopaid',$data['autopaid']);
		endif;
		$this->db->where('id',$data['uid']);
		$this->db->update('users');
		return $this->db->affected_rows();
	}
	
	function read_email_record($email){
		
		$this->db->select('id,login,fio,cryptpassword,signdate,closedate,position');
		$this->db->where('login',$email);
		$this->db->where('closedate =','0000-00-00');
		$query = $this->db->get('users');
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_record($id){
		
		$this->db->where('id',$id);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function read_by_wmid($wmid){
		
		$this->db->where('wmid',$wmid);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0]['id'])) return $data[0]['id'];
		return NULL;
	}
	
	function read_users_by_type($type){
		
		$this->db->where('type',$type);
		$query = $this->db->get('users');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_users_type($type,$count,$from){
		
		$this->db->where('type',$type);
		$this->db->where('locked',0);
		$this->db->limit($count,$from);
		$query = $this->db->get('users');
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_small_info($id){
		
		$this->db->select('id,login,fio,phones,icq,skype,balance,signdate,position');
		$this->db->where('id',$id);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}
	
	function auth_user($login,$password){
		
		$this->db->where('login',$login);
		$this->db->where('password',md5($password));
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0];
		return NULL;
	}

	function user_exist($field,$parameter){
			
		$this->db->where($field,$parameter);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(count($data)) return $data[0]['id'];
		return FALSE;
	}
	
	function user_id($field,$parameter){
			
		$this->db->where($field,$parameter);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(count($data)>0) return $data[0]['id'];
		return FALSE;
	}
	
	function read_field($id,$field){
			
		$this->db->where('id',$id);
		$query = $this->db->get('users',1);
		$data = $query->result_array();
		if(isset($data[0])) return $data[0][$field];
		return FALSE;
	}
	
	function update_field($id,$field,$value){
			
		$this->db->set($field,$value);
		$this->db->where('id',$id);
		$this->db->update('users');
		return $this->db->affected_rows();
	}
	
	function delete_record($id){
	
		$this->db->where('id',$id);
		$this->db->delete('users');
		return $this->db->affected_rows();
	}

	function change_user_balance($id,$summa){
		
		$query = "UPDATE users SET balance = balance+$summa WHERE id = $id";
		$this->db->query($query);
		return $this->db->affected_rows();
	}
	
	function change_admins_balance($summa){
		
		$query = "UPDATE users SET balance = balance+$summa WHERE type = 5";
		$this->db->query($query);
		return $this->db->affected_rows();
	}
	
	function count_all(){
		
		return $this->db->count_all('users');
	}

	function search_users($user){
		
		$query = "SELECT id,login FROM users WHERE login LIKE '%$user%'";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
	
	function read_users($uid){
		
		$query = "SELECT users.*,0 AS torders, 0 AS uporders FROM users WHERE id = $uid";
		$query = $this->db->query($query);
		$data = $query->result_array();
		if(count($data)) return $data;
		return NULL;
	}
}