<h2 style="margin: 20px 0px;"><?= $authuser['username'] ?>さんの評価：<?=$avg ?></h2>
<h4>評価一覧</h4>
<table cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th style="width:15%"><?= $this->Paginator->sort('商品名') ?></th>
		<th style="width:10%"><?= $this->Paginator->sort('取引相手') ?></th>
		<th style="width:8%"><?= $this->Paginator->sort('評価') ?></th>
        <th style="width:45%"><?= $this->Paginator->sort('コメント') ?></th>
        <th style="width:22%"><?= $this->Paginator->sort('評価日時') ?></th>
	</tr>
</thead>
<tbody>
	<?php foreach ($reviews as $review): ?>
	<tr>
		<td><?= $this->Html->link(h($review->biditem->name), '/auction/view/' . h($review->biditem_id)) ?></td>
        <td><?= h($review->user->username) ?></td>
        <td><?= h($review->rate) ?></td>
        <td><?= h($review->comment) ?></td>
        <td><?= date('Y年n月j日 H時i分', strtotime($review->created)) ?></td>
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
