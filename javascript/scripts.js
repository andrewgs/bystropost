/*  Author: Reality Group
 *  http://realitygroup.ru/
 */
 
function isValidEmailAddress(emailAddress){
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
};

function isValidPhone(phoneNumber){
	var pattern = new RegExp(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i);
	return pattern.test(phoneNumber);
};

function isValidDomen(domen){
	var pattern = new RegExp(/^([\da-z\.-]+)\.([a-z\.]{2,6})$/);
	return pattern.test(domen);
};

function isFindDomenToURL(platform,url){
	var pos = url.indexOf(platform);
	if(pos>0){return true;}else{return false;}
};

(function($){
	var baseurl = "http://bystropost/";
	
	$("#msgeclose").click(function(){$("#msgdealert").fadeOut(1000,function(){$(this).remove();});});
	$("#msgsclose").click(function(){$("#msgdsalert").fadeOut(1000,function(){$(this).remove();});});
	$(".digital").keypress(function(e){if(e.which!=8 && e.which!=46 && e.which!=0 && (e.which<48 || e.which>57)){return false;}});
	$(".none").click(function(event){event.preventDefault();});
	
	$("#userRegister").click(function(event){
		var err = false;
		$(".ErrImg").remove();
		$("#registration .inpval").each(function(i, element){
			if($(this).val() == ''){
				$(this).after('<img class="ErrImg" src="'+baseurl+'/images/icons/exclamation.png" title="Поле не может быть пустым">');
				err = true;
			}
		});
		if(!err ){
			if($("#registration #password").val() != $("#registration #confpass").val()){
				$("#registration #password").after('<img class="ErrImg" src="'+baseurl+'/images/icons/exclamation.png" title="Пароли не совпадают">');
				$("#registration #confpass").after('<img class="ErrImg" src="'+baseurl+'/images/icons/exclamation.png" title="Пароли не совпадают">');
				err = true;
			}
		};
		if(!err){
			if($("#wmid").val().length != 12){
				$("#wmid").after('<img class="ErrImg" src="'+baseurl+'/images/icons/exclamation.png" title="Должно быть 12 цифр">');
				err = true;
			}
		};
		if(err){
			event.preventDefault();
		}else if(!isValidEmailAddress($("#email").val())){
			$("#email").after('<img class="ErrImg" src="'+baseurl+'/images/icons/exclamation.png" title="Не верный адрес E-Mail">');
			event.preventDefault();
		};
	});
	
	$("#btnRestore").click(function(event){
		$(".ErrImg").remove();
		$("#RestoreEmail").css('border-color','#38BEDF');
		if($("#RestoreEmail").val()==''){
			$("#RestoreEmail").css('border-color','#ff8080');
			$("#RestoreEmail").after('<img class="ErrImg" src="'+baseurl+'/images/icons/exclamation.png" style="margin-top:10px;" title="Поле не может быть пустым">');
			event.preventDefault();
		}else if(!isValidEmailAddress($("#RestoreEmail").val())){
			$("#RestoreEmail").css('border-color','#ff8080');
			$("#RestoreEmail").after('<img class="ErrImg" src="'+baseurl+'/images/icons/exclamation.png" style="margin-top:10px;" title="Не верный адрес E-Mail">');
			event.preventDefault();
		};
	});
})(window.jQuery);