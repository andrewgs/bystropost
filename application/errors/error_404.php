<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Страница не найдена</title>
	<meta name="author" content="Realitygroup.ru">
	<link href="http://<?=$_SERVER['SERVER_NAME']?>/css/style.css" rel="stylesheet">
	<link rel="shortcut icon" href="http://<?=$_SERVER['SERVER_NAME']?>/images/favicon.ico">
</head>

<body>
	<div class="center">
		<div id="not_found">
			<h1>Упс, страница не найдена</h1>
			<div id="left">
				<big>Возможные причины ошибки:</big> <br>
				— Страница недоступна или удалена<br>
				— Страницы не существует<br>
				— Введен неправильный URL <br>
			</div>
			<div id="right">
				<big> Вы можете:</big><br>
				— <a href="javascript: history.go(-1)">Вернуться обратно</a> <br>
				— <a href="/">Перейти на главную</a>
			</div>
		</div>
	</div>
</body>
</html>