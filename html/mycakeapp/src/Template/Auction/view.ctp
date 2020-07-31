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
	<td><img src="http://localhost:10080/<?php echo $biditem['image_path'] ?>" width="400px" height="400px"></td>
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
<script type="js" src="view.js"></script>
<tr>
	<th scope="row">オークション残り時間</th>
	<td><span id="timer"></span></td>
	<script>
		var endtime = '<?php echo $endtime; ?>';
		var now = '<?php echo $now; ?>';
		var lefttime = endtime - now; //ページを開いた時の残り時間

		function timeConversion() {
			var days = Math.floor(lefttime / 60 / 60 / 24);
            var hours = Math.floor(lefttime / 60 / 60) % 24;
            var min = Math.floor(lefttime / 60) % 60;
            var sec = Math.floor(lefttime % 60);
            var count = [days, hours, min, sec];

            return count;
        }

		function countdown() {
			// 残り時間が0になったら終了メッセージを表示
			if (!(lefttime > 0)) {
				lefttime = 'このオークションは終了しました。';
				document.getElementById('timer').textContent = lefttime;
			} else { // それ以外の場合はカウントダウンを表示

				// 残り時間を1秒減らす処理
				lefttime--;
				// 残り時間を表示
				var counter = timeConversion();
				var time = (counter[0] + '日' + counter[1] + '時間' + counter[2] + '分' + counter[3] + '秒');
				document.getElementById('timer').textContent = time;
			}
		}

		// １秒ごとに実行
		setInterval('countdown()', 1000);
	</script>
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
	<h6><a href="<?=$this->Url->build(['action'=>'bid', $biditem->id]) ?>">《入札する！》</a></h6>
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
	<?php else: ?>
	<p><?='※入札は、終了しました。' ?></p>
	<?php endif; ?>
</div>
