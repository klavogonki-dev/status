<?php
	include("../../main.php");
	$post = (object)[];
	$post->tier = $user->level;
	$post->status = $user->id == 668817 ? 'Personal' : 'Normal';
	$post->rang = $user->title;
	$post->stats = "Рекорд: $user->best_speed зн/мин";
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../../main.css">
</head>
<body>

<div class="avatar_big">
    <?php if($post->tier && $post->rang): ?>
        <div class="rang rang<?=$post->tier;?> status<?=$post->status;?>"<?php if($post->stats): ?> title='<?=$post->stats;?>'<?php endif; ?>><?=$post->rang;?></div>
    <?php endif; ?>
    <img src="http://klavogonki.ru/storage/avatars/<?=$user->id;?>_big.png">
</div>

</body>
</html>