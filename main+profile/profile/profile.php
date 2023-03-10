<?php
	include("../../main_0.4.php");	
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../../main.css">
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js" type="text/javascript"></script>
	<style>
		.title {
			position: absolute;
			left: 90px;
			top:  15px;
		}
		.username {
			position: absolute;
			left: 90px;
			top:  30px;
		}
	</style>
</head>
<body ng-app>
<div ng-controller="controller">
	<div class="profile-container container">
		<div class="profile-header">
			<img src="https://klavogonki.ru/storage/avatars/{{data.summary.id}}_big.png" style="height: 60px; width: 60px;">
			<div class="rang{{data.summary.level}} status{{data.summary.status}} title">{{data.summary.title}}</div>
			<div class="username"><span class="name" style="font-size: 30px;">{{data.summary.user.login}}</span></div>
		</div>
	</div>
</div>

<script>
	function controller($scope){
		$scope.data = {summary: {
			id: <?=$user->id;?>,
			level: <?=$user->level;?>,
			status: '<?=$user->status;?>',
			title: '<?=$user->title;?>',
			user: {login: 'user<?=$user->id;?>'}
		}};
	}
</script>
</body>
</html>