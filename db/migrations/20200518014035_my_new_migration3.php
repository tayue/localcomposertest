<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration3 extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $table = $this->table('user_logins');
        $table->changeColumn('age', 'smallinteger',['length'=>2,'default'=>0])
//            ->addColumn('created', 'datetime',['default'=>'2015-05-15 12:00:00'])
//            ->addColumn('name', 'string',['length'=>12,'default'=>'tayue'])
//            ->addIndex("user_id",['name'=>'user_id'])
            ->update();
    }
}
