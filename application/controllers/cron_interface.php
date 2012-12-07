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
		$this->load->model('mdwebmarkets');
		$this->load->model('mdcheckout');
		$this->load->model('mdlog');
	}
	
	public function import_deliver_work(){
		
		$start_time = microtime(true);
		
		$file_name = getcwd().'/documents/import_'.date("YmdHi").'.log';
		$text = "Файл-лог импорта выполненных работ.\nСоздан: ".$this->current_date_on_time(date("Y-m-d H:i:s"));
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n\n");
		
		$datefrom = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
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
									//игнорирование работ у которых одна из сумм равна нулю
									if(($deliver_works[$i]['our_price'] == 0) || ($deliver_works[$i]['client_price'] == 0)):
										$deliver_works[$i]['delete'] = 1;
									endif;
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
		$debetors = $this->mdunion->read_debetors($date,'<=',0);
		//Автоматическая оплата
		$summa = array('managers' => array(),'admin' => 0);
		echo '<br/>Автоматическая оплата<br/>';
		if($debetors):
			for($i=0;$i<count($debetors);$i++):
				if(!isset($summa['managers'][$debetors[$i]['manager']])):
					$summa['managers'][$debetors[$i]['manager']] = 0;
				endif;
				echo "<hr/>Current webmaster = ".$debetors[$i]['webmaster']."<br/>";
				echo "<hr/>Current manager = ".$debetors[$i]['manager']."<br/>";
				$balance = $this->mdusers->read_field($debetors[$i]['webmaster'],'balance');
				echo "Start Balance = $balance. ";
				$minprice = $this->mdunion->min_price_debitors_works($debetors[$i]['webmaster'],$date,'<=');
				if($balance >= $minprice):
					echo "Balance >= $minprice (minimun price) <br/>";
					$works = $this->mdunion->read_debitors_works($debetors[$i]['webmaster'],$date,'<=');
					for($j=0;$j<count($works);$j++):
						echo "Delivers Work ID: ".$works[$j]['id']."<br/>";
						echo "Price Work: ".$works[$j]['wprice'].". ";
						$balance = $this->mdusers->read_field($debetors[$i]['webmaster'],'balance');
						echo "Current Balance = $balance. <br/>";
						if($balance < $minprice):
							echo "Balance is small. Break webmaster.<br/>";
							break;
						endif;
						if($balance >= $works[$j]['wprice']):
							echo "balance >= ".$works[$j]['wprice']."<br/>";
							$this->mdusers->change_user_balance($debetors[$i]['webmaster'],-$works[$j]['wprice']);
							echo "change webmaster balance= -".$works[$j]['wprice']."<br/>";
							$this->mdunion->works_status_ones($works[$j]['id']);
							echo "change status for works ID=".$works[$j]['id']."<br/>";
							$this->mdusers->change_user_balance($debetors[$i]['manager'],$works[$j]['mprice']);
							$summa['managers'][$debetors[$i]['manager']]+=$works[$j]['mprice'];
							echo "change manager balance= ".$works[$j]['mprice']."<br/>";
							$this->mdusers->change_admins_balance($works[$j]['wprice']-$works[$j]['mprice']);
							$summa['admin']+=($works[$j]['wprice']-$works[$j]['mprice']);
							echo "change admin balance= ".($works[$j]['wprice']-$works[$j]['mprice'])."<br/>";
							$this->mdfillup->insert_record($debetors[$i]['webmaster'],$works[$j]['wprice'],'Оплата за выполненное задание ID='.$works[$j]['id'],0,0);
							$text = 'Оплата за выполненное задание ID='.$works[$j]['id'];
							file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
						endif;
					endfor;
				else:
					echo "Balance is small. Next webmaster.<br/>";
				endif;
			endfor;
		endif;
		echo '<hr/><hr/>';
		echo 'Summa managers= '; print_r($summa['managers']);echo '<br/>Summa admins = '.$summa['admin'].'<br/>';
		echo '<hr/><hr/>';
		//отправка уведомлений должникам
		$debetors = $this->mdunion->select_debetors($date,'<=');
		for($i=0;$i<count($debetors);$i++):
			ob_start();?>
			<img src="<?=base_url();?>images/logo.png" alt="" />
			<p><strong>Здравствуйте, <?=$debetors[$i]['fio'];?></strong></p>
			<p>У Вас есть неоплаченные заявки сроком в 5 дней.<br/>Ваш аккаунт заблокирован по причине задолженности. Оплатите завершенные работы от 5 дней (включительно) для разблокировки.</p>
			<?php $debet = $this->mddelivesworks->calc_webmaster_summ($debetors[$i]['id'],'2012-01-01',0);?>
			<p>Общий долг в системе - <?=$debet['sum']?> рублей</p>
			<br/><br/><p>С Уважением, Команда Bystropost.ru</p><br/>
			<p>Это автоматическое письмо. Если у вас есть идеи, как улучшить проект, <?=anchor('idea','напишите нам');?>.</p>
			<?php
			$mailtext = ob_get_clean();
			$this->email->clear(TRUE);
			$config['smtp_host'] = 'localhost';
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->to($debetors[$i]['login']);
			$this->email->from('admin@bystropost.ru','Bystropost.ru - Система мониторинга и управления');
			$this->email->bcc('');
			$this->email->subject("Остановка обработки заявок в биржах");
			$this->email->message($mailtext);
			if($this->email->send()):
				$text = "\n\nВебмастер: ".$debetors[$i]['login']."\nДолг в системе: ".$debet['sum']." рублей. Сообщение отправлено.";
				file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
			endif;
		endfor;
		echo "Send Mail: ".count($debetors)."<br/>";
		echo '<hr/><hr/>';
		//блокировка должников и их аккаунтов
		echo '<br/>Блокировка должников и их аккаунтов<br/>';
		$total = $this->mdunion->update_debetors_status($date,'<=',1);
		echo "Total debetor: $total<br/>";
		$birzlock = 0;
		if($total):
			$debetors = $this->mdunion->debetors_webmarkets();
			for($i=0;$i<count($debetors);$i++):
				$param = 'accid='.$debetors[$i]['id'].'&birzid='.$debetors[$i]['market'].'&login='.$debetors[$i]['login'].'&pass='.base64_encode($this->encrypt->decode($debetors[$i]['cryptpassword'])).'&act=2';
				$text = "Запрос: ".$param;
				file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n",FILE_APPEND);
				$this->API('UpdateAccount',$param);
				$this->mdwebmarkets->update_record($debetors[$i]['id'],'status',0);
				echo "Blocked webmarkets: ".$debetors[$i]['id'].". Webmaster: ".$debetors[$i]['webmaster']."<br/>";
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
		
		$start_time = microtime(true);
		
		$file_name = getcwd().'/documents/sending_'.date("YmdHi").'.log';
		$text = "Файл-лог автоматического уведомления пользователей.\nСоздан: ".$this->current_date_on_time(date("Y-m-d H:i:s"));
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n\n");
		$mailtext = '';
		$settlement_date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$webmasters = $this->mdunion->read_users_sendmail(1);
		for($uid=0;$uid<count($webmasters);$uid++):
			ob_start();?>
			<img src="<?=base_url();?>images/logo.png" alt="" />
			<p><strong>Здравствуйте, <?=$webmasters[$uid]['fio'];?></strong></p>
			<p>Представляем вам свежий дайджест событий за <?=$this->current_date_on_time(date("Y-m-d H:i:s"));?> для вашего аккаунта <?=$webmasters[$uid]['login'];?> в системе Быстропост.</p>
			<p>Ваш баланс - <?=$webmasters[$uid]['balance'];?> рублей<br/>
			<?php $debet = $this->mddelivesworks->calc_webmaster_summ($webmasters[$uid]['id'],'2012-01-01',0);?>
			<?php if($debet['sum']):?>
			Общий долг в системе - <?=$debet['sum']?> рублей</p>
			<?php endif;?>
			<p>Оплаченных заявок: <?=$this->mddelivesworks->count_records_by_webmaster_status($webmasters[$uid]['id'],1);?><br/>
			Заявок ожидающих оплату: <?=$this->mddelivesworks->count_records_by_webmaster_status($webmasters[$uid]['id'],0);?></p>
			<?php $work3day = $this->mdunion->users_debitors_works($webmasters[$uid]['id'],date("Y-m-d",mktime(0,0,0,date("m"),date("d")-3,date("Y"))),'=');?>
		<?php if($work3day):?>
			<p>По некоторым вашим заявкам образовалась задолженность:</p>
			<p>
				<ul>
					<li><?=$work3day;?> заявок более 3-х суток</li>
					<li><?=$this->mdunion->users_debitors_works($webmasters[$uid]['id'],date("Y-m-d",mktime(0,0,0,date("m"),date("d")-4,date("Y"))),'=');?> заявок более 4-х суток</li>
					<li><?=$this->mdunion->users_debitors_works($webmasters[$uid]['id'],date("Y-m-d",mktime(0,0,0,date("m"),date("d")-5,date("Y"))),'<=');?> заявок более 5-х суток</li>
				</ul>
			</p>
		<?php endif;?>
			<p>Напоминаем, что при наличие неоплаченных заявок более 5 суток, аккаунт блокируется, до пополнения счета.<br/>
			Рекомендуем вам бесплатно подключить в своём <?=anchor('webmaster-panel/actions/profile','профиле');?> режим беззаботный, 
			для автоматического списания средств.<br/>
			WMID указанный в профиле, должен соответствовать WMID с которого будет происходить оплата.</p>
			<p>Данная рассылка носит информационный характер и высылается вам, так как вы зарегистрированы в 
			системе автоматической монетизации Быстропост. Отключить рассылки вы можете в своём профиле.</p>
			Новости проекта - <?=anchor('news','http://bystropost.ru/news');?><br/>
			Страничка FaceBook - <?=anchor(' http://www.facebook.com/Bystropost','http://www.facebook.com/Bystropost');?><br/>
			Наш Твиттер - <?=anchor('https://twitter.com/bystropost','https://twitter.com/bystropost');?>
			<br/><br/><p>С Уважением, Команда Bystropost.ru</p><br/>
			<p>Это автоматическое письмо. Если у вас есть идеи, как улучшить проект, <?=anchor('idea','напишите нам');?>.</p>
			<?
			$mailtext = ob_get_clean();
			$this->email->clear(TRUE);
			$config['smtp_host'] = 'localhost';
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->to($webmasters[$uid]['login']);
			$this->email->from('novosti@bystro.net','Bystropost.ru - Система мониторинга и управления');
			$this->email->bcc('');
			$this->email->subject("Дайджест событий. Обработка заявок от ".$this->current_date_on_time(date("Y-m-d H:i:s")));
			$this->email->message($mailtext);
			if($this->email->send()):
				$text = "\n\nВебмастер: ".$webmasters[$uid]['login']."\nДолг в системе: ".$debet['sum']." рублей. Сообщение отправлено.";
				file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
			else:
				$text = "\n\nВебмастер: ".$webmasters[$uid]['login']."\nСообщение не отправлено.";
				file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
			endif;
		endfor;
		
		$exec_time = round((microtime(true) - $start_time),2);
		$text = "\n\nСкрипт выполнен за: $exec_time сек.\n";
		echo($text);
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
	}
	
	public function users_checkout(){
		
		$start_time = microtime(true);
		
		$file_name = getcwd().'/documents/checkout_'.date("YmdHi").'.log';
		$text = "Файл-лог автоматического выставления счетов на оплату.\nСоздан: ".$this->current_date_on_time(date("Y-m-d H:i:s"));
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n\n");
		$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-5,date("Y")));
		$debetors = $this->mdunion->debetors_for_checkout($date);
		$count_invoice = 0;
		$exec_time = round((microtime(true) - $start_time),2);
		
		for($i=0;$i<count($debetors);$i++):
			$max_invoice = $this->mdcheckout->max_invoce()+1;
			$result = $this->checkout($max_invoice,$debetors[$i]['wmid'],$debetors[$i]['summa']);
			if ( $result ):
				$this->mdcheckout->insert_record($debetors[$i]['uid'],$max_invoice,$debetors[$i]['summa'],$debetors[$i]['wmid']);
				$text = "Cчет выставлен успешно! Вебмастер: ".$debetors[$i]['uid']."\n";
				echo $text.'<br/>';
				$count_invoice++;
			else:
				$text = "Ошибка при выставлении счета! Вебмастер: ".$debetors[$i]['uid']."\n";
				echo $text.'<br/>';
			endif;
			file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
		endfor;
		
		$text = "Скрипт выполнен за: $exec_time сек.\n";
		$text .= "Выставлено: $count_invoice счетов.";
		echo($text);
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
	}
	
	public function users_checkout_now() {
		
		$start_time = microtime(true);
		
		$file_name = getcwd().'/documents/checkout_'.date("YmdHi").'.log';
		$text = "Файл-лог автоматической проверки оплаты считов.\nСоздан: ".$this->current_date_on_time(date("Y-m-d H:i:s"));
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8')."\n\n");
		$paid_invoice = $notpaid_invoice = 0;
		$checkout = $this->mdcheckout->read_records();
		$exec_time = round((microtime(true) - $start_time),2);
		
		for($i=0;$i<count($checkout);$i++):
			$result = $this->checkout_now($checkout[$i]['invoice'],$checkout[$i]['wmid']);
			if($result):
				$this->mdcheckout->update_field($checkout[$i]['id'],'paid',1);
				$balance = $this->mdusers->read_field($checkout[$i]['webmaster'],'balance');
				$this->mdfillup->insert_record($checkout[$i]['webmaster'],$checkout[$i]['summa'],"Оплата счета через WebMoney",0,1);
				$new_balance = $balance+$checkout[$i]['summa'];
				$this->mdusers->update_field($checkout[$i]['webmaster'],'balance',$new_balance);
				$this->mdlog->insert_record($checkout[$i]['webmaster'],'Событие №6: Баланс пополнен');
				$text = "Баланс пополнен! Вебмастер: ".$checkout[$i]['webmaster']."\n";
				echo $text.'<br/>';
				$paid_invoice++;
			else:
				$notpaid_invoice++;
			endif;
			file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
		endfor;
		
		$text = "Скрипт выполнен за: $exec_time сек.\n";
		$text .= "Оплачено: $paid_invoice счетов.\n";
		$text .= "Не оплачено: $notpaid_invoice счетов.\n";
		echo($text);
		file_put_contents($file_name,mb_convert_encoding($text,'Windows-1251','utf-8'),FILE_APPEND);
	}
	
	private function checkout($invoice,$wmid,$summa){
		include(getcwd()."/invoice/main/_header.php");
		$res = $wmxi->X1($invoice,$wmid,PRIMARY_PURSE,$summa,'Обработка заявок в системе Bystropost.ru','Система монетизации Bystropost.ru',0,1);
		$res_status = (string) $res->toObject()->retval;
		if ( $res_status == 0) {
			return true;
		} else {
			return false;
		}
	} // функция выставления счета
	
	private function checkout_now($invoice,$wmid){
	
		include(getcwd()."/invoice/main/_header.php");
		$res = $wmxi->X4(PRIMARY_PURSE,0,$invoice,date("Ymd H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"))),date("Ymd H:i:s"));
		//print_r($res->toObject());
		$res_status = (string) $res->toObject()->retval;
		if ( $res_status == 0) { // получен ответ от webmoney
			$invoice_status = (string) $res->toObject()->outinvoices->outinvoice->state;
			if ( $invoice_status == 0) { // проверяем оплачен ли счет
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	} // функция проверки оплаты
	
	private function current_date_on_time($field){
		
		$months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня",
						"07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
			
		$list = preg_split("/-/",$field);
		$nmonth = $months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 г. \$6:\$8";
		return preg_replace($pattern, $replacement,$field);
	}
	
	private function operation_dot_date($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5.$3.\$1"; 
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