<?php $this->assign('title', 'オークション｜取引画面'); ?>
<?php if (!empty($bidinfo) && ($exhibitor_id === $authuser['id'] || $bidder_id === $authuser['id'])): ?>
	<h2>商品「<?=$bidinfo->biditem->name ?>」</h2>

	<!-- 配送状況 -->
	<?php if ($contactEntity['sent_info'] === true && $contactEntity['is_rated_by_bidder'] ===  false): ?>
		<h3>【発送状況】</h3>
		<!-- ログイン者が出品者で発送後の場合のみ表示 -->
		<?php if ($contactEntity['is_shipped'] === true && $contactEntity['is_rated_by_bidder'] === false && $exhibitor_id === $authuser['id']): ?>
			<p style="margin-bottom: 3.5em">※ 発送が完了しました。落札者からの受取評価をお待ちください。</p>
		<?php endif; ?>
		<!-- ログイン者が落札者で発送後の場合のみ表示 -->
		<?php if ($contactEntity['is_shipped'] === true && $contactEntity['is_rated_by_bidder'] === false && $bidder_id === $authuser['id']): ?>
			<p>※ 商品が発送されました。受け取った後に取引評価をしてください。</p>
			<?= $this->Form->create('',[
			'enctype' => 'multipart/form-data',
			'type' => 'post'
			]) ?>
			<fieldset>
				<legend>出品者の取引評価を入力：</legend>
				<?php
					echo 'rate';
					echo $this->Form->select('rate',[1=>'1（とても悪い）', 2=>'2（悪い）', 3=>'3（普通）', 4=>'4（良い）', 5=>'5（とても良い）'],['default'=>3]);
					echo $this->Form->control('comment',[
						'type' => 'textarea',
						'maxlength' => 200
					]);
				?>
			</fieldset>
			<?= $this->Form->button(__('商品を受け取りました')) ?>
			<?= $this->Form->end() ?>
			<p style="margin-bottom: 2.5em"></p>
		<?php endif; ?>
	<?php endif; ?>

	<!-- 配送状況 -->
	<?php if ($contactEntity['is_rated_by_bidder'] ===  true ): ?>
		<h3>【取引状況】</h3>
		<!-- ログイン者が落札者で、落札者のみ評価終了している場合のみ表示 -->
		<?php if ($contactEntity['is_rated_by_exhibitor'] ===  false && $bidder_id === $authuser['id']): ?>
			<p style="margin-bottom: 3.5em">※ 受取評価が完了しました。出品者からの評価をお待ちください。</p>
		<?php endif; ?>
		<!-- ログイン者が出品者で、落札者のみ評価終了している場合のみ表示 -->
		<?php if ($contactEntity['is_rated_by_exhibitor'] ===  false && $exhibitor_id === $authuser['id']): ?>
			<p>※ 落札者が商品を受け取りました。落札者の評価をしてください。</p>
			<?= $this->Form->create('',[
			'enctype' => 'multipart/form-data',
			'type' => 'post'
			]) ?>
			<fieldset>
				<legend>落札者の取引評価を入力：</legend>
				<?php
					echo 'rate';
					echo $this->Form->select('rate2',[1=>'1（とても悪い）', 2=>'2（悪い）', 3=>'3（普通）', 4=>'4（良い）', 5=>'5（とても良い）'],['default'=>3]);
					echo $this->Form->control('comment',[
						'type' => 'textarea',
						'maxlength' => 200
					]);
				?>
			</fieldset>
			<?= $this->Form->button(__('評価を送信します')) ?>
			<p style="margin-bottom: 2.5em"></p>
			<?= $this->Form->end() ?>
		<?php endif; ?>
		<!-- ログイン者が落札者か出品者で、両者の評価が完了した場合のみ表示 -->
		<?php if ($contactEntity['is_rated_by_exhibitor'] ===  true): ?>
		<p style="margin-bottom: 3.5em">※ 取引が完了しました。</p>
		<?php endif; ?>
	<?php endif; ?>

	<!-- 発送先情報が送信された後の表示 -->
	<!-- ログイン者が落札者・出品者どちらの場合も表示 -->
	<?php if (!empty($contactEntity)): ?>
		<h3>【発送先情報】</h3>
		<table cellpadding="0" cellspacing="0"  style="margin-bottom: 3.5em">
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
