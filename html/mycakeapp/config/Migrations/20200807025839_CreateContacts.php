<?php
use Migrations\AbstractMigration;

class CreateContacts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('contacts');
        $table->addColumn('biditem_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('sent_info', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('address', 'string', [
            'default' => null,
            'limit' => 200,
            'null' => false,
        ]);
        $table->addColumn('phone_number', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('is_shipped', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('is_rated_by_bidder', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('is_rated_by_exhibitor', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
