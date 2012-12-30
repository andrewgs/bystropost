<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
	
	var $months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		$this->load->model('mdunion');
		$this->load->model('mdmessages');
		$this->load->model('mdmarkets');
		$this->load->model('mdplatforms');
		$this->load->model('mdmkplatform');
		$this->load->model('mdtickets');
		$this->load->model('mdtkmsgs');
		$this->load->model('mdtypeswork');
		$this->load->model('mddelivesworks');
		$this->load->model('mdservices');
		$this->load->model('mdattachedservices');
		$this->load->model('mdfillup');
		$this->load->model('mdwebmarkets');
		$this->load->model('mdlog');
		$this->load->model('mdthematic');
		$this->load->model('mdcms');
		$this->load->model('mdvaluesrv');
	}
	
	public function pagination($url,$uri_segment,$total_rows,$per_page){
		
		$config['base_url'] 		= base_url()."$url/from/";
		$config['uri_segment'] 		= $uri_segment;
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $per_page;
		$config['num_links'] 		= 4;
		$config['first_link']		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open']		= '<li class="active"><a href="#">';
		$config['cur_tag_close'] 	= '</a></li>';
		$config['full_tag_open'] 	= '<div class="pagination"><ul>';
		$config['full_tag_close'] 	= '</ul></div>';
		$config['first_tag_open'] 	= '<li>';
		$config['first_tag_close'] 	= '</li>';
		$config['last_tag_open'] 	= '<li>';
		$config['last_tag_close'] 	= '</li>';
		$config['next_tag_open'] 	= '<li>';
		$config['next_tag_close'] 	= '</li>';
		$config['prev_tag_open'] 	= '<li>';
		$config['prev_tag_close'] 	= '</li>';
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		
		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}
	
	public function send_mail($to,$from_mail,$from_name,$subject,$text){
		
		$this->email->clear(TRUE);
		$config['smtp_host'] = 'localhost';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		
		$this->email->initialize($config);
		$this->email->to($to);
		$this->email->from($from_mail,$from_name);
		$this->email->bcc('');
		$this->email->subject($subject);
		$this->email->message($text);
		if($this->email->send()):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function viewimage(){
		
		$section = $this->uri->segment(1);
		$id = $this->uri->segment(3);
		switch ($section):
			case 'markets'	:	$image = $this->mdmarkets->get_image($id); break;
			default			: 	show_404();break;
		endswitch;
		header('Content-type: image/gif');
		echo $image;
	}
	
	public function fileupload($userfile,$overwrite,$catalog){
		
		$config['upload_path'] 		= './documents/'.$catalog.'/';
		$config['allowed_types'] 	= 'doc|docx|xls|xlsx|txt|pdf';
		$config['remove_spaces'] 	= TRUE;
		$config['overwrite'] 		= $overwrite;
		
		$this->load->library('upload',$config);
		
		if(!$this->upload->do_upload($userfile)):
			return FALSE;
		endif;
		
		return TRUE;
	}

	public function filedelete($file){
		
		if(is_file($file)):
			@unlink($file);
			return TRUE;
		else:
			return FALSE;
		endif;
	}

	public function operation_date($field){
			
		$list = preg_split("/-/",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 г."; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function operation_date_on_time($field){
			
		$list = preg_split("/-/",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 г. \$6:\$8";
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function split_date($field){
			
		$list = preg_split("/-/",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5 $nmonth \$1"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function split_dot_date($field){
			
		$list = preg_split("/\./",$field);
		$nmonth = $this->months[$list[1]];
		$pattern = "/(\d+)(\.)(\w+)(\.)(\d+)/i";
		$replacement = "\$1 $nmonth \$5"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function operation_dot_date($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5.$3.\$1"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function operation_dot_date_on_time($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5.$3.\$1 \$6:\$8";
		return preg_replace($pattern, $replacement,$field);
	}

	public function operation_dot_date_not_time($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5.$3.\$1";
		return preg_replace($pattern, $replacement,$field);
	}
	
	public function randomPassword($length,$allow="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ0123456789"){
	
		$i = 1;
		$ret = '';
		while($i<=$length):
			$max   = strlen($allow)-1;
			$num   = rand(0, $max);
			$temp  = substr($allow, $num, 1);
			$ret  .= $temp;
			$i++;
		endwhile;
		return $ret;
	}
	
	public function translite($string){
		
		$rus = array("1","2","3","4","5","6","7","8","9","0","ё","й","ю","ь","ч","щ","ц","у","к","е","н","г","ш","з","х","ъ","ф","ы","в","а","п","р","о","л","д","ж","э","я","с","м","и","т","б","Ё","Й","Ю","Ч","Ь","Щ","Ц","У","К","Е","Н","Г","Ш","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","С","М","И","Т","Б"," ");
		$eng = array("1","2","3","4","5","6","7","8","9","0","yo","iy","yu","","ch","sh","c","u","k","e","n","g","sh","z","h","","f","y","v","a","p","r","o","l","d","j","е","ya","s","m","i","t","b","Yo","Iy","Yu","CH","","SH","C","U","K","E","N","G","SH","Z","H","","F","Y","V","A","P","R","O","L","D","J","E","YA","S","M","I","T","B","-");
		$string = str_replace($rus,$eng,$string);
		if(!empty($string)):
			$string = preg_replace('/[^a-z0-9,-]/','',strtolower($string));
			$string = preg_replace('/[-]+/','-',$string);
			$string = preg_replace('/[\.\?\!\)\(\,\:\;]/','',$string);
			return $string;
		else:
			return FALSE;
		endif;
	}

	public function english_symbol($string){
		
		if(!empty($string)):
			$string = preg_replace('/[ ]+/','-',strtolower($string));
			$string = preg_replace('/[^a-z,-]/','',$string);
			$string = preg_replace('/[-]+/','-',$string);
			return $string;
		else:
			return FALSE;
		endif;
	}
	
	public function valid_url_symbol($string){
		
		if(!empty($string)):
			$string = preg_replace('/[ ]+/','-',strtolower($string));
			$string = preg_replace('/[^a-z0-9,-\/\?\&\$\#\@]/','',strtolower($string));
			$string = preg_replace('/[-]+/','-',$string);
			$string = preg_replace('/[\.\?\!\)\(\,\:\;]/','',$string);
			return $string;
		else:
			return FALSE;
		endif;
	}

	public function link_cabinet($uid,$plus=0){
		
		$utype = $this->mdusers->read_field($uid,'type');
		switch ($utype+$plus):
			case 1 : return '<a href="'.base_url().'webmaster-panel/actions/control">личный кабинет</a>';break;
			case 2 : return '<a href="'.base_url().'manager-panel/actions/control">личный кабинет</a>';break;
			case 3 : return '<a href="'.base_url().'optimizator-panel/actions/control">личный кабинет</a>';break;
			case 4 : return 'личный кабинет';break;
			case 5 : return '<a href="'.base_url().'admin-panel/management/users/all">личный кабинет</a>';break;
			case 0 : return '<a href="'.base_url().'admin-panel/management/users/all">личный кабинет</a>';break;
			
			case 11 : return '<a href="'.base_url().'webmaster-panel/actions/mails">Читать сообщение &raquo;</a>';break;
			case 12 : return '<a href="'.base_url().'manager-panel/actions/mails">Читать сообщение &raquo;</a>';break;
			case 13 : return '<a href="'.base_url().'optimizator-panel/actions/mails">Читать сообщение &raquo;</a>';break;
			case 14 : return 'личный кабинет';break;
			case 15 : return '<a href="'.base_url().'admin-panel/management/mails">Читать сообщение &raquo;</a>';break;
			case 10 : return '<a href="'.base_url().'admin-panel/management/mails">Читать сообщение &raquo;</a>';break;
			
			case 21 : return '<a href="'.base_url().'webmaster-panel/actions/tickets">Читать сообщение &raquo;</a>';break;
			case 22 : return '<a href="'.base_url().'manager-panel/actions/tickets/inbox">Читать сообщение &raquo;</a>';break;
			case 23 : return '<a href="'.base_url().'optimizator-panel/actions/tickets">Читать сообщение &raquo;</a>';break;
			case 24 : return 'личный кабинет';break;
			case 25 : return '<a href="'.base_url().'admin-panel/messages/tickets">Читать сообщение &raquo;</a>';break;
			case 20 : return '<a href="'.base_url().'admin-panel/messages/tickets">Читать сообщение &raquo;</a>';break;
			default: return 'личный кабинет';break;
		endswitch;
	}
	
	public function sub_mailtext($text,$uid){
		
		$text = strip_tags($text);
		if(mb_strlen($text,'UTF-8') > 150):
			$text = mb_substr($text,0,150,'UTF-8');
			$pos = mb_strrpos($text,' ',0,'UTF-8');
			$text = mb_substr($text,0,$pos,'UTF-8');
			$text .= ' ...<br/>'.$this->link_cabinet($uid,10);
		endif;
		return $text;
	}

	public function sub_tickettext($text,$uid){
		
		$text = strip_tags($text);
		if(mb_strlen($text,'UTF-8') > 150):
			$text = mb_substr($text,0,150,'UTF-8');
			$pos = mb_strrpos($text,' ',0,'UTF-8');
			$text = mb_substr($text,0,$pos,'UTF-8');
			$text .= ' ...<br/>'.$this->link_cabinet($uid,20);
		endif;
		return $text;
	}
	
	public function webmaster_manager($webmaster = FALSE,$platform = FALSE){
		
		$manager = $user_manager = $platform_manager = FALSE;
		if($webmaster):
			$user_manager = $this->mdusers->read_field($webmaster,'manager');
		endif;
		if($platform):
			$platform_manager = $this->mdplatforms->read_field($platform,'manager');
		endif;
		if($user_manager || $platform_manager):
			$manager = ($platform_manager)?$platform_manager:$user_manager;
		endif;
		return $manager;
	}
	
	/******************************************************** Расчет парсинга ПР и ТИЦ ******************************************************/
	
	public function StrToNum($Str, $Check, $Magic){
	
		$Int32Unit = 4294967296;
		$length = strlen($Str);
		for($i=0;$i<$length;$i++):
			$Check *= $Magic;
			if($Check>=$Int32Unit):
				$Check = ($Check-$Int32Unit*(int)($Check/$Int32Unit));
				$Check = ($Check<-2147483648)?($Check+$Int32Unit):$Check;
			endif;
			$Check += ord($Str{$i});
		endfor;
		return $Check;
	}

	public function HashURL($String){
	
		$Check1 = $this->StrToNum($String,0x1505,0x21);
		$Check2 = $this->StrToNum($String,0,0x1003F);
		$Check1 >>= 2;
		$Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
		$Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
		$Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
		
		$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 &	0xF0F );
		$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
		return ($T1 | $T2);
	}
	
	public function CheckHash($Hashnum){
	
		$CheckByte = 0;
		$Flag = 0;
		$HashStr = sprintf('%u', $Hashnum) ;
		$length = strlen($HashStr);
		for($i=$length-1;$i>=0;$i--):
			$Re = $HashStr{$i};
			if(1===($Flag % 2)):
				$Re += $Re;
				$Re = (int)($Re/10)+($Re%10);
			endif;
			$CheckByte += $Re;
			$Flag++;
		endfor;
		$CheckByte %= 10;
		if(0!== $CheckByte):
			$CheckByte = 10 - $CheckByte;
			if(1===($Flag%2)):
				if(1===($CheckByte%2)):
					$CheckByte += 9;
				endif;
				$CheckByte >>= 1;
			endif;
		endif;
		
		return '7'.$CheckByte.$HashStr;
	}
	
	public function getpagerank($url){
		
		$pagerank = 0;
		$fp = fsockopen("toolbarqueries.google.com", 80, $errno, $errstr, 30);
		if(!$fp):
			
		else:
			$out = "GET /tbr?features=Rank&sourceid=navclient-ff&client=navclient-auto-ff&ch=" .$this->CheckHash($this->HashURL($url)) . "&q=info:" . $url . " HTTP/1.1\r\n";
			$out .= "Host: toolbarqueries.google.com\r\n";
			$out .= "User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big;
			Windows XP 5.1)\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			while(!feof($fp)):
				$data = fgets($fp, 128);
				$pos = strpos($data, "Rank_");
				if($pos === false):
					
				else:
					$pagerank = substr($data, $pos + 9);
				endif;
			endwhile;
			fclose($fp);
		endif;
		return $pagerank;
	}
	
	public function getTIC($url){
		
		$str = @file("http://bar-navig.yandex.ru/u?ver=2&show=32&url=".$url);
		if($str == false):
			$cy = false;
		else:
			$result = preg_match("/value=\"(.\d*)\"/", join("",$str), $tic);
			if($result<1):
				$cy = 0;
			else:
				$cy = $tic[1];
			endif;
		endif;
		return $cy;
	}
	
	public function SQL_TIC_PR($tic,$platform){
	
		$addwtic = $addmtic = 0;
		if($tic >= 30):
			$addwtic = 5;$addmtic = 2;
		endif;
		$sqlquery = "UPDATE platforms SET ";
		$works = $this->mdtypeswork->read_records();
		$arr_works = $this->mdtypeswork->read_ticpr_records();
		foreach($arr_works AS $key=>$value):
			$tic_array[] = $value['id'];
		endforeach;
		for($j=0;$j<count($works);$j++):
			$wadd = $madd = 0;
			if(in_array($works[$j]['id'],$tic_array)):
				$wadd = $addwtic;
				$madd = $addmtic;
			endif;
			$sqlquery .= 'c'.$works[$j]['nickname'].' = '.($works[$j]['wprice']+$wadd).', m'.$works[$j]['nickname'].' = '.($works[$j]['mprice']+$madd);
			if(isset($works[$j+1])):
				$sqlquery .= ', ';
			endif;
		endfor;
		$sqlquery .= ' WHERE platforms.id = '.$platform;
		return $sqlquery;
	}
	
	/******************************************************** Функции API *******************************************************************/
	
	public function API($action,$param){
	
		$post = array('hash'=>'fe162efb2429ef9e83e42e43f8195148','action'=>$action,'param'=>$param);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,'http://megaopen.ru/api.php');
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		$data = curl_exec($ch);
		curl_close($ch);
		 if($data!==false):
			$res = json_decode($data, true);
			if((int)$res['status']==1):
				return $res['data'];
			else:
				return $res;
//				return FALSE;
			endif;
		else:
			return FALSE;
		endif;
	}
}