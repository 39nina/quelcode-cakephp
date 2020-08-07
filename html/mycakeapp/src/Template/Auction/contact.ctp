<?php if (!empty($bidinfo)): ?>
<h2>商品「<?=$bidinfo->biditem->name ?>」</h2>
<?php if (empty($contactEntity)): ?>
	<h3>【取引先連絡】</h3>
	<?= $this->Form->create('',[
	'enctype' => 'multipart/form-data',
	'type' => 'post'
	]) ?>
	<fieldset>
		<legend>落札者の連絡先を入力：</legend>
		<?php
			echo $this->Form->control('name');
			echo $this->Form->control('address',[
				'type' => 'textarea',
				'maxlength' => 200
			]);
			echo $this->Form->control('phone_number');
		?>
	</fieldset>
	<?= $this->Form->button(__('Submit')) ?>
	<?= $this->Form->end() ?>
<?php endif; ?>
<?= '<br>' ?>
<h3>【メッセージ】</h3>
<h6>※ メッセージを送信する</h6>
<?= $this->Form->create($bidmsg) ?>
<?= $this->Form->hidden('bidinfo_id', ['value' => $bidinfo->id]) ?>
<?= $this->Form->hidden('user_id', ['value' => $authuser['id']]) ?>
<?= $this->Form->textarea('message', ['rows'=>2]); ?>
<?= $this->Form->button('Submit') ?>
<?= $this->Form->end() ?>
<table cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th scope="col">送信者</th>
		<th class="main" scope="col">メッセージ</th>
		<th scope="col">送信時間</th>
	</tr>
</thead>
<tbody>
<?php if (!empty($bidmsgs)): ?>
	<?php foreach ($bidmsgs as $msg): ?>
	<tr>
		<td><?= h($msg->user->username) ?></td>
		<td><?= h($msg->message) ?></td>
		<td><?= h($msg->created) ?></td>
	</tr>
	<?php endforeach; ?>
<?php else: ?>
	<tr><td colspan="3">※メッセージがありません。</td></tr>
<?php endif; ?>
</tbody>
</table>
<?php else: ?>
<h2>※落札情報はありません。</h2>
<?php endif; ?>

