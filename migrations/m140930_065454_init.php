<?php

use yii\db\Schema;
use yii\db\Migration;

class m140930_065454_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        // Create 'page_partials' table
        $this->createTable('{{%page_partials}}', [
            'id'                    => Schema::TYPE_PK,
            'type'                  => "ENUM('system','user-defined') NOT NULL DEFAULT 'user-defined'",
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
        
        // Create 'page_partials_lang' table
        $this->createTable('{{%page_partials_lang}}', [
            'page_partial_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'title'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'content'               => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL'
        ], $tableOptions);
        
        $this->addPrimaryKey('page_partial_id_language', '{{%page_partials_lang}}', ['page_partial_id', 'language']);
        $this->createIndex('language', '{{%page_partials_lang}}', 'language');
        $this->addForeignKey('FK_PAGE_PARTIALS_LANG_PAGE_PARTIAL_ID', '{{%page_partials_lang}}', 'page_partial_id', '{{%page_partials}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('page_partials_lang');
        $this->dropTable('page_partials');
    }
}
