<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Contact $contact
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Contact'), ['action' => 'edit', $contact->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Contact'), ['action' => 'delete', $contact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contact->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Contacts'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Contact'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Biditems'), ['controller' => 'Biditems', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Biditem'), ['controller' => 'Biditems', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="contacts view large-9 medium-8 columns content">
    <h3><?= h($contact->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Biditem') ?></th>
            <td><?= $contact->has('biditem') ? $this->Html->link($contact->biditem->name, ['controller' => 'Biditems', 'action' => 'view', $contact->biditem->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($contact->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address') ?></th>
            <td><?= h($contact->address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone Number') ?></th>
            <td><?= h($contact->phone_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($contact->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($contact->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sent Info') ?></th>
            <td><?= $contact->sent_info ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Shipped') ?></th>
            <td><?= $contact->is_shipped ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Rated By Bidder') ?></th>
            <td><?= $contact->is_rated_by_bidder ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Rated By Exhibitor') ?></th>
            <td><?= $contact->is_rated_by_exhibitor ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
