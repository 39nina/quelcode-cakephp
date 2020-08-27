<?php if (!empty($bidinfo) && ($exhibitor_id === $authuser['id'] || $bidder_id === $authuser['id'])): ?>
	<h2>商品「<?=$bidinfo->biditem->name ?>」</h2>
	<!-- 落札者から取引先情報が送信される前 -->
	<?php if (empty($contactEntity)): ?>
		<h3>【取引先連絡】</h3>
		<!-- ログイン者が出品者の場合のみ表示 -->
		<?php if ($exhibitor_id === $authuser['id']): ?>
			<p style="margin-bottom: 3.5em">※ 落札者からの連絡をお待ちください。</p>
		<?php endif; ?>
		<!-- ログイン者が落札者の場合のみ表示 -->
		<?php if ($bidder_id === $authuser['id']): ?>
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
			<p  style="margin-bottom: 2.5em"></p>
		<?php endif; ?>
	<?php endif; ?>

	<!-- 発送状況 -->
	<?php if ($contactEntity['sent_info'] === true && $contactEntity['is_rated_by_bidder'] ===  false): ?>
		<h3>【発送状況】</h3>
		<!-- ログイン者が出品者で発送前の場合のみ表示 -->
		<?php if ($contactEntity['is_shipped'] === false && $exhibitor_id === $authuser['id']): ?>
			<p>※ 発送先情報が通知されました。発送が完了したら、ボタンをおしてください。</p>
			<?= $this->Form->create(null) ?>
			<?= $this->Form->hidden('is_shipped', ['value' => '1']) ?>
			<?= $this->Form->button(__('発送が完了しました')) ?>
			<?= $this->Form->end() ?>
			<p style="margin-bottom: 2.5em"></p>
		<?php endif; ?>
		<!-- ログイン者が落札者で発送前の場合のみ表示 -->
		<?php if ($contactEntity['is_shipped'] === false && $bidder_id === $authuser['id']): ?>
			<p style="margin-bottom: 3.5em">※ 出品者からの発送連絡をお待ちください。</p>
		<?php endif; ?>
	<?php endif; ?>

	<!-- 発送先情報が送信された後の表示 -->
	<!-- ログイン者が落札者・出品者どちらの場合も表示 -->
	<?php if (!empty($contactEntity)): ?>
		<h3>【発送先情報】</h3>
		<table cellpadding="0" cellspacing="0" style="margin-bottom: 3.5em">
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
<?php header('Location: ./'); ?>
<?php endif; ?>
