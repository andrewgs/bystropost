<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Cron_interface extends CI_Controller{

	
	function __construct(){
		
		parent::__construct();
		$this->load->model('mdusers');
		$this->load->model('mdunion');
		$this->load->model('mdmarkets');
		$this->load->model('mdplatforms');
		$this->load->model('mdmkplatform');
		$this->load->model('mdfillup');
		$this->load->model('mdtypeswork');
		$this->load->model('mddelivesworks');
	}
	
	public function import_deliver_work(){
		
		$start_time = microtime(true);
		
		$file_name = getcwd().'/documents/import_'.date("YmdHi").'.log';
		$text = "Файл-лог импорта выполненных работ.\nСоздан: ".$this->current_date_on_time(date("Y-m-d H:i:s"));
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n\n");
		
		$datefrom = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
//		$datefrom = "2012-10-15";
		$dateto = date("Y-m-d");
		$platforms = $this->mdunion->read_managers_platforms(2);
		if(count($platforms)):
			$typeswork = $this->mdtypeswork->read_records_id();
			$webmarkets = $this->mdunion->read_webmarkets_records();
			$wm = $platforms[0]['rwmid']; $index = -1; $wplatforms = array();
			foreach($platforms AS $key => $value):
				if($value['rwmid'] == $wm):
					$index++;
					$wplatforms[$value['rwmid']][$index] = array('id'=>$value['id'],'remoteid'=>$value['remoteid'],
					'webmaster'=>$value['webmaster'],'url'=>$value['url'],'login'=>$value['login'],'autopaid'=>$value['autopaid']);
				else:
					$index = 0;
					$wm = $value['rwmid'];
					$wplatforms[$value['rwmid']][$index] = array('id'=>$value['id'],'remoteid'=>$value['remoteid'],
					'webmaster'=>$value['webmaster'],'url'=>$value['url'],'login'=>$value['login'],'autopaid'=>$value['autopaid']);
				endif;
			endforeach;
			$platforms = $wplatforms;
			unset($wplatforms);
			
			$wm = $webmarkets[0]['webmaster']; $index = -1; $wmarkets = array();
			foreach($webmarkets AS $key => $value):
				if($value['webmaster'] == $wm):
					$index++;
					$wmarkets[$value['webmaster']][$index] = array('id'=>$value['id'],'market'=>$value['market']);
				else:
					$index = 0;
					$wm = $value['webmaster'];
					$wmarkets[$value['webmaster']][$index] = array('id'=>$value['id'],'market'=>$value['market']);
				endif;
			endforeach;
			$webmarkets = $wmarkets;
			unset($wmarkets);
			$total = 0;
			foreach($platforms AS $plwbid => $pl_mass):
				$webmaster = $pl_mass[0]['webmaster'];
				$text = "----------------------- Вебмастер: ".$pl_mass[0]['login']." -----------------------------";
				file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n\n",FILE_APPEND);
				if(isset($webmarkets[$plwbid])):
					$deliver_works = array();
					foreach($webmarkets[$plwbid] AS $wmid => $wm_mass):
						$param = 'birzid='.$wm_mass['market'].'&accid='.$wm_mass['id'].'&datefrom='.$datefrom.'&dateto='.$dateto;
						$text = "Запрос: ".$param;
						file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
						$dl_works = $this->API('GetFinishedOrder',$param);
						$import_error = FALSE;
						if(isset($dl_works['error'])):
							$import_error = TRUE;
							$text = "Ошибка при получении данных: ".$dl_works['error'];
						elseif(!count($dl_works)):
							$import_error = TRUE;
							$text = "Получено работ: 0 шт. ";
						else:
							$text = "Получено работ: ".count($dl_works)." шт. ";
						endif;
						file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
						if(!$import_error):
							$dwd = 0;$dw_data = array();
							foreach($dl_works as $key => $value):
								$dw_data[$dwd] = $value;
								$dw_data[$dwd]['id'] = $key;
								$dwd++;
							endforeach;
							for($i=0;$i<count($dw_data);$i++):
								$dw_data[$i]['market'] = $wm_mass['market'];
								$deliver_works[] = $dw_data[$i];
							endfor;
						endif;
					endforeach;
					if($deliver_works):
						for($i=0;$i<count($deliver_works);$i++):
							for($j=0;$j<count($pl_mass);$j++):
								if($deliver_works[$i]['siteid'] == $pl_mass[$j]['remoteid']):
									$deliver_works[$i]['platform'] = $pl_mass[$j]['id'];
									$deliver_works[$i]['webmaster'] = $webmaster;
								endif;
								if($deliver_works[$i]['type'] <= 7):
									$deliver_works[$i]['birzprice'] = (isset($deliver_works[$i]['birzprice']) && !is_null($deliver_works[$i]['birzprice']))?$deliver_works[$i]['birzprice']:0;
									$deliver_works[$i]['size'] = (isset($deliver_works[$i]['size']) && !is_null($deliver_works[$i]['size']))?$deliver_works[$i]['size']:0;
									if(!isset($deliver_works[$i]['our_price']) && !isset($deliver_works[$i]['client_price'])):
										$deliver_works[$i]['client_price'] = $this->mdunion->read_pl_price_rid($deliver_works[$i]['siteid'],'c'.$typeswork[$deliver_works[$i]['type']-1]['nickname']);
										$deliver_works[$i]['our_price'] = $this->mdunion->read_pl_price_rid($deliver_works[$i]['siteid'],'m'.$typeswork[$deliver_works[$i]['type']-1]['nickname']);
									endif;
									$deliver_works[$i]['delete'] = 0;
								endif;
							endfor;
						endfor;
						$work_exist = $this->mdunion->valid_exist_works($deliver_works);
						if(count($work_exist)):
							for($i=0;$i<count($deliver_works);$i++):
								for($j=0;$j<count($work_exist);$j++):
									if($deliver_works[$i]['id'] == $work_exist[$j]['id']):
										$deliver_works[$i]['delete'] = 1;
									endif;
								endfor;
							endfor;
						endif;
						$temp_works = array();
						for($i=0;$i<count($deliver_works);$i++):
							if(!isset($deliver_works[$i]['webmaster']) || !isset($deliver_works[$i]['platform'])):
								$deliver_works[$i]['delete'] = 1;
								$text = "Работа: ".$deliver_works[$i]['id']." отсутствует менеджер или прощадка.\nТекущий менеджер: $webmaster, текущий сайт: ".$deliver_works[$i]['siteid'];
								file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
							endif;
							if(!$deliver_works[$i]['delete']):
								$temp_works[] = $deliver_works[$i];
							endif;
						endfor;
						$deliver_works = $temp_works;
						unset($temp_works);
						if($deliver_works):
							$works = $this->mdunion->works_group_insert($deliver_works);
							$total+=$works;
							if($works && $pl_mass[0]['autopaid']):
								echo "$webmaster autopaid = 1<br/>";
								$wprice = $mprice = $aprice = 0;
								for($i=0;$i<count($deliver_works);$i++):
									$wprice += $deliver_works[$i]['client_price'];
									$mprice += $deliver_works[$i]['our_price'];
								endfor;
								$aprice = $wprice-$mprice;
								echo "aprice = $aprice; wprice = $wprice; mprice = $mprice<br/>";
								$balance = $this->mdusers->read_field($webmaster,'balance');
								echo "balance = $balance;<br/>";
								if($balance > 0):
									echo "balance > 0<br/>";
									if($balance >= $wprice):
										echo "balance >= $wprice<br/>";
										$result = $this->mdunion->works_group_paid($deliver_works);
										echo "result = $result<br/>";
										if($result):
											$this->mdusers->change_user_balance($webmaster,-$wprice);
											echo "change webmaster balance= -$wprice<br/>";
											$this->mdusers->change_user_balance(2,$mprice);
											echo "change manager balance= $mprice<br/>";
											$this->mdusers->change_admins_balance($aprice);
											echo "change admin balance= $aprice<br/>";
											$this->mdfillup->insert_record($webmaster,$wprice,'Автоматическое списание за выполненные задания',0,0);
											$text = "Автоматическое списание за выполненные задания на сумму: $wprice руб.";
											file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
										endif;
									else:
										echo "balance < $wprice<br/>";
										for($i=0;$i<count($deliver_works);$i++):
											$balance = $this->mdusers->read_field($webmaster,'balance');
											echo "Current balance = $balance<br/>";
											if($balance >= $deliver_works[$i]['client_price']):
												echo "balance >= ".$deliver_works[$i]['client_price']."<br/>";
												$this->mdusers->change_user_balance($webmaster,-$deliver_works[$i]['client_price']);
												echo "change webmaster balance= -".$deliver_works[$i]['client_price']."<br/>";
												$this->mdunion->works_status_ones($deliver_works[$i]['id']);
												echo "change status for works ID=".$deliver_works[$i]['id']."<br/>";
												$this->mdusers->change_user_balance(2,$deliver_works[$i]['our_price']);
												echo "change manager balance= ".$deliver_works[$i]['our_price']."<br/>";
												$this->mdusers->change_admins_balance($deliver_works[$i]['our_price']-$deliver_works[$i]['our_price']);
												echo "change admin balance= ".($deliver_works[$i]['client_price']-$deliver_works[$i]['our_price'])."<br/>";
												$this->mdfillup->insert_record($webmaster,$deliver_works[$i]['client_price'],'Оплата за выполненное задание ID='.$deliver_works[$i]['id'],0,0);
												$text = 'Оплата за выполненное задание ID='.$deliver_works[$i]['id'];
												file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
											endif;
										endfor;
									endif;
								endif;
							endif;
						endif;
					endif;
				else:
					$text = "Отсутствуют биржевые аккануты!";
					file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
				endif;
			endforeach;
		else:
			$text = "Площадки отсутствуют!";
			file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n");
		endif;
		
		$exec_time = round((microtime(true) - $start_time),2);
		
		$text = "Скрипт выполнен за: $exec_time сек.\n";
		$text .= "Импортировано: $total выполненных работ.";
		echo($text);
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
	}
	
	public function debitors_auto_blocking(){
		
		$start_time = microtime(true);
		
		$file_name = getcwd().'/documents/bloking_'.date("YmdHi").'.log';
		$text = "Файл-лог автоматического блокирования пользователей за не оплату.\nСоздан: ".$this->current_date_on_time(date("Y-m-d H:i:s"));
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n\n");
		
		$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-5,date("Y")));
		$total = $this->mdunion->update_debetors_status($date,'=',1);
		$birzlock = 0;
		if($total):
			$debetors = $this->mdunion->debetors_webmarkets();
			for($i=0;$i<count($debetors);$i++):
				$param = 'accid='.$debetors[$i]['id'].'&birzid='.$debetors[$i]['market'].'&login='.$debetors[$i]['login'].'&pass='.base64_encode($this->encrypt->decode($debetors[$i]['cryptpassword'])).'&act=2';
				$text = "Запрос: ".$param;
				file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
				$this->API('UpdateAccount',$param);
				$birzlock++;
			endfor;
		else:
			$text = "Должники отсутствуют!";
			file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n");
		endif;
		
		$exec_time = round((microtime(true) - $start_time),2);
		
		$text = "Скрипт выполнен за: $exec_time сек.\n";
		$text .= "Заблокировано: $total должников.\n";
		$text .= "Приостановлено: $birzlock биржевых аккаунтов.";
		echo($text);
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
	}
	
	public function users_sending_mail(){
		
		/*$start_time = microtime(true);
		
		$file_name = getcwd().'/documents/sending_'.date("YmdHi").'.log';
		$text = "Файл-лог автоматического уведомления пользователей.\nСоздан: ".$this->current_date_on_time(date("Y-m-d H:i:s"));
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n\n");
		$mailtext = '';
		$curdate = date("Y-m-d");
		//Уведомление о наличии новых неоплаченных работ за текущий день.
		$result = $this->mdunion->users_delives_works($curdate,0);
		if($result):
			$mailtext .= "У Вас есть $result неоплаченных работ за текущий день.\n";
		endif;
		$result = $this->mdunion->users_delives_works($curdate,1);
		if($result):
			$mailtext .= "За текущий день было оплачено $result выполненных работ.\n";
		endif;
		
		$exec_time = round((microtime(true) - $start_time),2);
		
		$text = "Скрипт выполнен за: $exec_time сек.\n";
		$text .= "Заблокировано: $total должников.\n";
		$text .= "Приостановлено: $birzlock биржевых аккаунтов.";
		echo($text);
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);*/
	}
	
	private function current_date_on_time($field){
		
		$months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня",
						"07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
			
		$list = preg_split("/-/",$field);
		$nmonth = $months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 г. \$6:\$8";
		return preg_replace($pattern, $replacement,$field);
	}
	
	private function API($action,$param){
	
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
			endif;
		else:
			return FALSE;
		endif;
	}
}