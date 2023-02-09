<?php
	include("../../main_1.2.php");
	$post = (object)[];
	$post->tier = $user->level;
	$post->status = $user->status;
	$post->style = $user->style;
	$post->rang = $user->title;
	$post->stats = "Рекорд: {$user->best_speed} зн/мин";
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../../main_1.1.css">
</head>
<body>

<div class="avatar_big">
    <?php if($post->tier && $post->rang): ?>
        <div class="rang rang<?=$post->tier;?> status<?=$post->status;?>" style="<?=$post->style;?>"<?php if($post->stats): ?> title='<?=$post->stats;?>'<?php endif; ?>><?=$post->rang;?></div>
    <?php endif; ?>
    <img src="http://klavogonki.ru/storage/avatars/<?=$user->id;?>_big.png">
</div>

</body>
</html>