<?php
	include("../../main_1.2.php");
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../../main_1.1.css">
</head>
<body>

<div class=popup_profile>
	<table class=title>
	<tr>
	<th><div class="avatar_big">
		<img src="http://klavogonki.ru/storage/avatars/<?=$user->id;?>_big.png">
		</div>
	</th>	
	<td><div class="rang<?=$user->level;?> status<?=$user->status;?>" style="<?=$user->style;?>"><?=$user->title;?></div>
		<div class="name">user<?=$user->id;?></div>
	</td>
	</tr>
	</table>
</div>

</body>
</html>