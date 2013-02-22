<head>
	<title>Print</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="print.css">
</head>
<body>
<div id="hall" class="header"><div class="title">Зал : </div><div class="value">
	<?php
	 	switch ($_GET['table']) {
	 		case 'red':
	 			echo 'Красный';
	 			break;
	 		case 'blue':
	 			echo 'Синий';
	 			break;
	 		case 'gold':
	 			echo 'Бар';
	 			break;
	 		default:
	 			echo 'Ошибка';
	 			break;
	 	}
	?>
</div>
</div>
<div id="game" class="header">
	<div class="title">Номер игры :</div>
	<div class="value"><?php echo $_GET['game']+1; ?></div>
</div>
<div id="time" class="header">
	<div class="title">Время :</div>
	<div class="value"><?php echo date('H:i'); ?></div>
</div>

<table cellspacing="0">
<thead>
	<tr>
		<th>№</th>
		<th>Карта</th>
		<th>Имя</th>
		<th>Убит</th>
		<th>Штраф</th>
	</tr>
</thead>
<tbody>
<?php
$table = $_GET['table'];

include '../../engine/bd.php';
$query = "select * from queue where tableval='$table' and status='in play'";
$q = mysql_query($query, $db);
if (!$q) {
	die('Неверный запрос: ' . mysql_error());
}

while ($res = mysql_fetch_array($q)) {
	echo "<tr>
			<td>$res[nn]</td>
			<td>$res[card]</td>
			<td>$res[name]</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>";
}
?>
</tbody>
</table>
</body>