<?php
	session_start();
	$adm = array(1, 4, 5, 29);
	if (isset($_SESSION['id']) && (in_array($_SESSION['id'], $adm))) :
?>
<!DOCTYPE html>
<html>
<head>
	<title>Рассадка</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script src="http://code.jquery.com/jquery-1.7.1.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>
    <script src="http://comp345.awardspace.com/sortable/js/ui.multisortable.js" type="text/javascript"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://code.jquery.com/ui/1.8.2/jquery-ui.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="all">

		<div id="table-red" class="table" data-status="free" data-counter="0">
			<div class="title">Красный стол (<div class="counter">0</div>)</div>
			<table class="timing">
				<thead>
					<tr>
						<th colspan="2">Первая игра</td>
						<th colspan="2">Вторая игра</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="start1">-</td>
						<td class="end1">-</td>
						<td class="start2">-</td>
						<td class="end2">-</td>
					</tr>
				</tbody>
			</table>
			<div class="inner"></div>
			<div class="control">
				<button class="fill" onclick="FillTable('red')">Заполнить</button>
				<button class="begin">Игра началась</button>
				<button class="end" data-table="red" disabled>Игра окончена</button>
				<button class="shuffle">Shuffle</button>
				<button class="list">Список</button>
			</div>
		</div>
		<div id="table-blue" class="table" data-status="free" data-counter="0">
			<div class="title">Синий стол (<div class="counter">0</div>)</div>
			<table class="timing">
				<thead>
					<tr>
						<th colspan="2">Первая игра</td>
						<th colspan="2">Вторая игра</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="start1">-</td>
						<td class="end1">-</td>
						<td class="start2">-</td>
						<td class="end2">-</td>
					</tr>
				</tbody>
			</table>
			<div class="inner"></div>
			<div class="control">
				<button class="fill" onclick="FillTable('blue')">Заполнить</button>
				<button class="begin">Игра началась</button>
				<button class="end" data-table="blue" disabled>Игра окончена</button>
				<button class="shuffle">Shuffle</button>
				<button class="list">Список</button>
			</div>
		</div>
		<div id="table-gold" class="table" data-status="free" data-counter="0">
			<div class="title">Бар (<div class="counter">0</div>)</div>
			<table class="timing">
				<thead>
					<tr>
						<th colspan="2">Первая игра</td>
						<th colspan="2">Вторая игра</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="start1">-</td>
						<td class="end1">-</td>
						<td class="start2">-</td>
						<td class="end2">-</td>
					</tr>
				</tbody>
			</table>
			<div class="inner"></div>
			<div class="control">
				<button class="fill" onclick="FillTable('gold')">Заполнить</button>
				<button class="begin">Игра началась</button>
				<button class="end" data-table="gold" disabled>Игра окончена</button>
				<button class="shuffle">Shuffle</button>
				<button class="list">Список</button>
			</div>
		</div>
		<div class="act">
			<button id='responce'>Сохранить очередь</button>
			<button id="clear-all">Очистить все</button>
		</div>
		<div id="sort-test">
			<div class="title">Очередь
				<div class="action">
					<input id="autosort" type="checkbox" name="autosort"><label for="autosort">Автосортировка</label>
					<input id="useabon" type="checkbox" name="useabon"><label for="useabon">С учетом абонимента</label>
					<button id="sort">Сортировать</button>
				</div>
			</div>

			<div class="inner"></div>
		</div>

		<div id="prepeare">
			<div class="title">Предпросмотр
				<div class="action-wrap">
				<div class="action">
					
				</div>
				</div>
			</div>
			<button id="aprove">Подтвердить</button>
			<button id="prep-clear">Очистить</button>
			<div class="content"></div>
		</div>

		<div id="queue" style="display:none">
			<button id='deselect'>Обновить список</button>
			<button id='addPlayer'>Добавить игрока</button>
			<form class="users-search">
			    <div class="title">Введите первые буквы имени, фамилии или номер карты : </div>
			    <input type="text" id="item" onclick="this.focus()" onkeydown="doSearch(event)" />
			</form>
			<div class="inner">

<?php
	
		include '../../engine/bd.php';
		mysql_set_charset('utf8');
		//$query = 'select card_number as card, name, surname, aboniment as abon from dengi order by card asc';
		$query = "select 
			card_number as card, name as name, surname as surname, aboniment as abon 
			from dengi
			where card_number not in
			(select card from queue)
			order by card_number asc";
		$q = mysql_query($query, $db);
		if (!$q) {
			die('Неверный запрос: ' . mysql_error());
		}
		while($res = mysql_fetch_array($q))
		{
			$abon_class = ($res['abon'] != 'Нет')?'item abon':'item'; //Проверить!!!
			$string = "$res[name] $res[surname]";
			if(stristr($string, '(ВИП)') == TRUE){
				$abon_class .= ' vip';
			}
			echo "<div 
					id='item-$res[card]' 
					data-id='$res[card]' 
					data-name='$res[name] $res[surname]' 
					data-table='none' 
					data-status='waiting' 
					data-limit='0' 
					data-played='0'
					data-money='0' 
					data-time='00:00:00' 
					class='$abon_class'>
					<div class='nn'>0</div>
					<div class='name'>$res[card]:$res[name] $res[surname]</div>
					<div class='played'>0</div>
					<button class='freeze' onclick='Freeze(this)'>*</button>
					<button class='penalty' onclick='Penalty(this)'>p</button>
					<select name='prefer' class='prefer' onchange='SetData()'>
						<option value='none' selected>---</option>
						<option value='red'>Красный</option><option value='blue'>Синий</option>
						<option value='gold'>Бар</option>
					</select>
					<button class='fb' onclick='FirstBlood(this)' disabled>1</button>
					<input id='newbie-$res[card]' type='checkbox' name='newbie' onclick='SortQueue();SetData()'>
					<label for='newbie-$res[card]'>Новичок</label>
					<div class='time'>00:00</div>
				</div>";
		}
		echo "</div>";
		

	else:
		include '../../includes/enter.php';
	endif;
	
?>
</div>
</div>
<div id="overlay">Loading</div>
</body>
</html>