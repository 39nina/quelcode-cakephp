<?php $this->assign('title', 'オークション｜評価一覧'); ?>
<h2><?= $authuser['username'] ?>さんの評価：<?=$avg ?></h2>
<h4>評価一覧</h4>
<table cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th scope="col"><?= $this->Paginator->sort('rater') ?></th>
		<th scope="col"><?= $this->Paginator->sort('rating') ?></th>
        <th class="main" scope="col"><?= $this->Paginator->sort('comment') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
	</tr>
</thead>
<tbody>
	<?php foreach ($reviews as $review): ?>
	<tr>
        <td><?= h($review->rater_user->username) ?></td>
        <td><?= h($review->rate) ?></td>
        <td><?= h($review->comment) ?></td>
        <td><?= date('Y/n/j H:i', strtotime(h($review->created))) ?></td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>

<div class="paginator">
	<ul class="pagination">
		<?= $this->Paginator->first('<< ' . __('first')) ?>
		<?= $this->Paginator->prev('< ' . __('previous')) ?>
		<?= $this->Paginator->numbers() ?>
		<?= $this->Paginator->next(__('next') . ' >') ?>
		<?= $this->Paginator->last(__('last') . ' >>') ?>
	</ul>
</div>
