<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Contact Entity
 *
 * @property int $id
 * @property int $biditem_id
 * @property bool $sent_info
 * @property string $name
 * @property string $address
 * @property string $phone_number
 * @property bool $is_shipped
 * @property bool $is_rated_by_bidder
 * @property bool $is_rated_by_exhibitor
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Biditem $biditem
 */
class Contact extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'biditem_id' => true,
        'sent_info' => true,
        'name' => true,
        'address' => true,
        'phone_number' => true,
        'is_shipped' => true,
        'is_rated_by_bidder' => true,
        'is_rated_by_exhibitor' => true,
        'created' => true,
        'biditem' => true,
    ];
}
