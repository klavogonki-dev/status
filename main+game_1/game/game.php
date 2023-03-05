<?php
	include("../../main_1.4.php");
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../../main_1.2.css">
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js" type="text/javascript"></script>
	<style>
		.nick_content a {
			text-decoration: none;
			border-bottom: 1px dashed;
		}
	</style>
</head>
<body ng-app>
<div ng-controller="PlayersList">
	<table>
	<tr><td><img src="http://klavogonki.ru/storage/avatars/{{user.id}}.png"></td>
	<td><div class="nick_content">
		<a href="https://klavogonki.ru/profile/{{user.id}}/" class="{{user.colored_rang}} {{user.status}} profile" style="{{user.style}}">{{name}}</a> 
	</div></td>
	</tr>
	</table>
</div>

<script>
	function PlayersList($scope){
		$scope.user = {
			id: <?=$user->id;?>,
			colored_rang: 'rang<?=$user->level;?>',
			status: '<?=$user->status;?>',
			style: '<?=$user->style;?>'
		};
		$scope.name = 'user<?=$user->id;?>';
	}
</script>
</body>
</html>