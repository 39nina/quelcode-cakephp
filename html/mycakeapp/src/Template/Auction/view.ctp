<?php $this->assign('title', 'オークション｜商品画面'); ?>
<script type="text/javascript">
	var endtime = '<?php echo $endtime; ?>';
	var now = '<?php echo $now; ?>';
</script>

<?php echo $this->Html->script('view'); ?>

<h2>「<?= $biditem->name ?>」の情報</h2>
<table class="vertical-table">
<tr>
	<th class="small" scope="row">出品者</th>
	<td><?= $biditem->has('user') ? $biditem->user->username : '' ?></td>
</tr>
<tr>
	<th scope="row">商品名</th>
	<td><?= h($biditem->name) ?></td>
</tr>
<tr>
	<th scope="row">商品画像</th>
	<td><img src="http://<?php echo $_SERVER["HTTP_HOST"] . '/' .  $biditem['image_path'] ?>" maxwidth="400px" maxheight="400px"></td>
</tr>
<tr>
	<th scope="row">商品情報詳細</th>
	<td><?= h($biditem->detail) ?></td>
</tr>
<tr>
	<th scope="row">商品ID</th>
	<td><?= $this->Number->format($biditem->id) ?></td>
</tr>
<tr>
	<th scope="row">終了時間</th>
	<td><?= h($biditem->endtime) ?></td>
</tr>
<tr>
	<th scope="row">投稿時間</th>
	<td><?= h($biditem->created) ?></td>
</tr>
<tr>
	<th scope="row">オークション残り時間</th>
	<td><span id="timer"></span></td>
</tr>
<tr>
	<th scope="row"><?= __('終了した？') ?></th>
	<td><?= $biditem->finished ? __('Yes') : __('No'); ?></td>
</tr>
</table>
<div class="related">
	<h4><?= __('落札情報') ?></h4>
	<?php if (!empty($biditem->bidinfo)): ?>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th scope="col">落札者</th>
		<th scope="col">落札金額</th>
		<th scope="col">落札日時</th>
	</tr>
	<tr>
		<td><?= h($biditem->bidinfo->user->username) ?></td>
		<td><?= h($biditem->bidinfo->price) ?>円</td>
		<td><?= h($biditem->endtime) ?></td>
	</tr>
	</table>
	<?php else: ?>
	<p><?='※落札情報は、ありません。' ?></p>
	<?php endif; ?>
</div>
<div class="related">
	<h4><?= __('入札情報') ?></h4>
	<?php if (!$biditem->finished): ?>
	<?php if ($biditem->user->id !== $authuser['id']): ?>
		<h6><a href="<?=$this->Url->build(['action'=>'bid', $biditem->id]) ?>">《入札する！》</a></h6>
	<?php endif; ?>
		<?php if (!empty($bidrequests)): ?>
		<table cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th scope="col">入札者</th>
			<th scope="col">金額</th>
			<th scope="col">入札日時</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($bidrequests as $bidrequest): ?>
		<tr>
			<td><?= h($bidrequest->user->username) ?></td>
			<td><?= h($bidrequest->price) ?>円</td>
			<td><?=$bidrequest->created ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
		</table>
		<?php else: ?>
		<p><?='※入札は、まだありません。' ?></p>
		<?php endif; ?>
	<!-- オークションが落札済かつ、ログイン者が落札者もしくは出品者の場合のみ、取引ページリンクを表示 -->
	<?php elseif (!empty($biditem->bidinfo)): ?>
		<?php if ($biditem->finished && ($login_id === $biditem->bidinfo->user_id || $login_id === $biditem->user_id)): ?>
		<p><?='※入札は、終了しました。取引ページは'?><?= $this->Html->link('こちら', ['action' => 'contact', $biditem->bidinfo->id]) ?></p>
		<?php else: ?>
			<p><?='※入札は、終了しました。' ?></p>
		<?php endif; ?>
	<?php else: ?>
		<p><?='※入札は、終了しました。' ?></p>
	<?php endif; ?>
</div>
