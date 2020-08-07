<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rating Entity
 *
 * @property int $id
 * @property int $biditem_id
 * @property int $rater_id
 * @property int $rate_target_id
 * @property int $rate
 * @property string $comment
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Biditem $biditem
 * @property \App\Model\Entity\Rater $rater
 * @property \App\Model\Entity\RateTarget $rate_target
 */
class Rating extends Entity
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
        'rater_id' => true,
        'rate_target_id' => true,
        'rate' => true,
        'comment' => true,
        'created' => true,
        'biditem' => true,
        'user' => true
    ];
}
