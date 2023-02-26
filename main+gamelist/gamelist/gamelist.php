<?php
	include("../../main_0.4.php");
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../../main.css">
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js" type="text/javascript"></script>
	<style>
		.name i {
			position: absolute;
			top: 10px;
			left: 10px;
			box-sizing: border-box;
			width: 16px;
			height: 16px;			
		}	
		.name a {
			text-decoration: none;
			border-bottom: 1px dashed;
			position: absolute;
			left: 30px;
		}
	</style>
</head>
<body ng-app>

<div ng-controller="controller">
	<div class="name">		
		<i style="background-image: url('http://klavogonki.ru/storage/avatars/{{player.user.id}}.png')"></i>
		<a href="https://klavogonki.ru/profile/{{player.user.id}}/" class="rang{{player.level}} status{{player.status}} profile">{{player.name}}</a>
	</div>
</div>

<script>
	function controller($scope){
		$scope.player = {
			user: { id: <?=$user->id;?> },
			level: <?=$user->level;?>,
			status: '<?=$user->status;?>',
			name: 'user<?=$user->id;?>'
		};
	}
</script>
</body>
</html>