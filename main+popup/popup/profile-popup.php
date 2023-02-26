<?php
	include("../../main_0.4.php");
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../../main.css">
</head>
<body>

<div class=popup_profile>
	<table class=title>
	<tr>
	<th><div class="avatar_big">
		<img src="http://klavogonki.ru/storage/avatars/<?=$user->id;?>_big.png">
		</div>
	</th>	
	<td><div class="rang<?=$user->level;?> status<?=$user->status;?>"><?=$user->title;?></div>
		<div class="name">user<?=$user->id;?></div>
	</td>
	</tr>
	</table>
</div>

</body>
</html>