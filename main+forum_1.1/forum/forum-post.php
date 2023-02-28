<?php
	include("../../main_1.3.php");
	$post = (object)[];
	$post->tier = $user->level;
	$post->status = $user->status;
	$post->statusIcon = ($user->statusIcon) ? "<img class=\"status-icon\" src=\"{$user->statusIcon}\"/>" : "";
	$post->style = $user->style;
	$post->rang = $post->statusIcon . $user->title;
	$post->stats = "Рекорд: {$user->best_speed} зн/мин";
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../../main_1.2.css">
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