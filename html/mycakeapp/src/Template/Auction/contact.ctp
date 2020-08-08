<?php if (!empty($bidinfo)): ?>
	<h2>商品「<?=$bidinfo->biditem->name ?>」</h2>
	<!-- ログイン者が落札者の場合のみ表示 -->
	<?php if (empty($contactEntity) && $bidder_id === $authuser['id']): ?>
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
		<?= '<br>' ?>
	<?php endif; ?>

	<!-- ログイン者が出品者の場合のみ表示 -->
	<?php if (empty($contactEntity) && $bidder_id !== $authuser['id']): ?>
		<h3>【取引先連絡】</h3>
		<p>※ 落札者からの連絡をお待ちください。</p>
	<?php endif; ?>

	<!-- ログイン者が出品者の場合のみ表示 -->
	<?php if ($contactEntity['sent_info'] === true && $contactEntity['is_shipped'] === false && $bidder_id !== $authuser['id']): ?>
		<h3>【発送連絡】</h3>
		<p>※ 発送が完了したら、ボタンをおしてください。</p>
		<?= $this->Form->create(null) ?>
		<?= $this->Form->hidden('is_shipped', ['value' => 1]) ?>
		<?= $this->Form->button(__('発送しました')) ?>
		<?= $this->Form->end() ?>
		<br><br>
	<?php endif; ?>
	<?php if ($contactEntity['is_shipped'] === true && $contactEntity['is_rated_by_bidder'] === false && $bidder_id !== $authuser['id']): ?>
		<h3>【発送状況】</h3>
		<p>※ 発送が完了しました。落札者からの受取評価をお待ちください。</p>
		<?= '<br>' ?>
	<?php endif; ?>

	<!-- ログイン者が落札者・出品者どちらの場合も表示 -->
	<?php if (!empty($contactEntity)): ?>
		<h3>【発送先情報】</h3>
		<!-- ログイン者が落札者の場合のみ表示 -->
		<?php if($bidder_id === $authuser['id']): ?>
		<h6> ※ 出品者からの発送連絡をお待ちください。</h6>
		<?php endif; ?>
		<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th scope="col">名前</th>
			<th class="main" scope="col">住所</th>
			<th scope="col">電話番号</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?= h($contactEntity->name) ?></td>
			<td><?= h($contactEntity->address) ?></td>
			<td><?= h($contactEntity->phone_number) ?></td>
		</tr>
	</tbody>
	</table>
	<?= '<br><br>' ?>
	<?php endif; ?>

	<!-- 常に表示 -->
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

