<?php

use yii\db\Schema;
use yii\db\Migration;

class m141230_141411_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * Terms
         */

        // Create 'terms' table
        $this->createTable('{{%terms}}', [
            'id'                    => Schema::TYPE_PK,
            'root'                  => Schema::TYPE_INTEGER . ' NOT NULL',
            'lft'                   => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'rgt'                   => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'level'                 => Schema::TYPE_SMALLINT . ' UNSIGNED NOT NULL',
            'active'                => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('root', '{{%terms}}', 'root');
        $this->createIndex('lft', '{{%terms}}', 'lft');
        $this->createIndex('rgt', '{{%terms}}', 'rgt');
        $this->createIndex('level', '{{%terms}}', 'level');

        // Create 'terms_lang' table
        $this->createTable('{{%terms_lang}}', [
            'term_id'               => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'content'               => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('term_id_langauge', '{{%terms_lang}}', ['term_id', 'language']);
        $this->createIndex('language', '{{%terms_lang}}', 'language');
        $this->addForeignKey('FK_TERMS_LANG_TERM_ID', '{{%terms_lang}}', 'term_id', '{{%terms}}', 'id', 'CASCADE', 'NO ACTION');

    }

    public function down()
    {
        /**
         * Terms
         */

        $this->dropTable('terms_lang');
        $this->dropTable('terms');
    }
}