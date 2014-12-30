<?php

use yii\db\Schema;
use yii\db\Migration;

class m141127_145114_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * Attributes
         */

        // Create 'ecommerce_attributes' table
        $this->createTable('{{%ecommerce_attributes}}', [
            'id'                    => Schema::TYPE_PK,
            'translateable'         => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        // Create 'ecommerce_attributes_lang' table
        $this->createTable('{{%ecommerce_attributes_lang}}', [
            'attribute_id'          => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('attribute_id', '{{%ecommerce_attributes_lang}}', ['attribute_id', 'language']);
        $this->createIndex('language', '{{%ecommerce_attributes_lang}}', 'language');
        $this->addForeignKey('FK_ECOMMERCE_ATTRIBUTES_LANG_ATTRIBUTE_ID', '{{%ecommerce_attributes_lang}}', 'attribute_id', '{{%ecommerce_attributes}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_attribute_groups' table
        $this->createTable('{{%ecommerce_attribute_groups}}', [
            'id'                    => Schema::TYPE_PK,
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        // Create 'ecommerce_attribute_groups_attributes' table
        $this->createTable('{{%ecommerce_attribute_groups_attributes}}', [
            'attribute_group_id'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'attribute_id'          => Schema::TYPE_INTEGER . ' NOT NULL',
            'position'              => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT \'0\'',
        ], $tableOptions);

        $this->addPrimaryKey('attribute_group_id_attribute_id', '{{%ecommerce_attribute_groups_attributes}}', ['attribute_group_id', 'attribute_id']);
        $this->createIndex('attribute_id', '{{%ecommerce_attribute_groups_attributes}}', 'attribute_id');
        $this->addForeignKey('FK_ECOMMERCE_ATTRIBUTE_GROUPS_ATTRIBUTES_ATTRIBUTE_GROUP_ID', '{{%ecommerce_attribute_groups_attributes}}', 'attribute_group_id', '{{%ecommerce_attribute_groups}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('FK_ECOMMERCE_ATTRIBUTE_GROUPS_ATTRIBUTES_ATTRIBUTE_ID', '{{%ecommerce_attribute_groups_attributes}}', 'attribute_id', '{{%ecommerce_attributes}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_attribute_groups_lang' table
        $this->createTable('{{%ecommerce_attribute_groups_lang}}', [
            'attribute_group_id'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('attribute_group_id_language', '{{%ecommerce_attribute_groups_lang}}', ['attribute_group_id', 'language']);
        $this->createIndex('language', '{{%ecommerce_attribute_groups_lang}}', 'language');
        $this->addForeignKey('FK_ATTRIBUTE_GROUPS_LANG_ATTRIBUTE_GROUP_ID', '{{%ecommerce_attribute_groups_lang}}', 'attribute_group_id', '{{%ecommerce_attribute_groups}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_attribute_sets' table
        $this->createTable('{{%ecommerce_attribute_sets}}', [
            'id'    => Schema::TYPE_PK,
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        // Create 'ecommerce_attribute_sets_group' table
        $this->createTable('{{%ecommerce_attribute_sets_group}}', [
            'attribute_set_id'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'attribute_group_id'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'position'              => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT \'0\'',
        ], $tableOptions);

        $this->addPrimaryKey('attribute_set_id_attribute_group_id', '{{%ecommerce_attribute_sets_group}}', ['attribute_set_id', 'attribute_group_id']);
        $this->createIndex('attribute_group_id', '{{%ecommerce_attribute_sets_group}}', 'attribute_group_id');
        $this->addForeignKey('FK_ECOMMERCE_ATTRIBUTE_SETS_GROUPS_ATTRIBUTE_SET_ID', '{{%ecommerce_attribute_sets_group}}', 'attribute_set_id', '{{%ecommerce_attribute_sets}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('FK_ECOMMERCE_ATTRIBUTE_SETS_GROUPS_ATTRIBUTE_GROUP_ID', '{{%ecommerce_attribute_sets_group}}', 'attribute_group_id', '{{%ecommerce_attribute_groups}}', 'id', 'CASCADE', 'NO ACTION');

        /**
         * Categories
         */

        // Create 'ecommerce_categories' table
        $this->createTable('{{%ecommerce_categories}}', [
            'id'                    => Schema::TYPE_PK,
            'root'                  => Schema::TYPE_INTEGER . ' NOT NULL',
            'lft'                   => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'rgt'                   => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'level'                 => Schema::TYPE_SMALLINT . ' UNSIGNED NOT NULL',
            'active'                => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('root', '{{%ecommerce_categories}}', 'root');
        $this->createIndex('lft', '{{%ecommerce_categories}}', 'lft');
        $this->createIndex('rgt', '{{%ecommerce_categories}}', 'rgt');
        $this->createIndex('level', '{{%ecommerce_categories}}', 'level');

        // Create 'ecommerce_categories_lang' table
        $this->createTable('{{%ecommerce_categories_lang}}', [
            'category_id'           => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('category_id_langauge', '{{%ecommerce_categories_lang}}', ['category_id', 'language']);
        $this->createIndex('language', '{{%ecommerce_categories_lang}}', 'language');
        $this->addForeignKey('FK_ECOMMERCE_CATEGORIES_LANG_CATEGORY_ID', '{{%ecommerce_categories_lang}}', 'category_id', '{{%ecommerce_categories}}', 'id', 'CASCADE', 'NO ACTION');

        /**
         * Customers
         */

        // Create 'ecommerce_customer_groups' table
        $this->createTable('{{%ecommerce_customer_groups}}', [
            'id'                    => Schema::TYPE_PK,
            'position'              => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT \'0\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        // Create 'ecommerce_customers' table
        $this->createTable('{{%ecommerce_customers}}', [
            'id'                    => Schema::TYPE_PK,
            'group_id'              => Schema::TYPE_INTEGER . ' NOT NULL',
            'firstname'             => Schema::TYPE_STRING . '(255) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'company'               => Schema::TYPE_STRING . '(255) NOT NULL',
            'address'               => Schema::TYPE_STRING . '(255) NOT NULL',
            'zipcode'               => Schema::TYPE_STRING . '(20) NOT NULL',
            'city'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'email'                 => Schema::TYPE_STRING . '(255) NOT NULL',
            'phone'                 => Schema::TYPE_STRING . '(255) NOT NULL',
            'mobile'                => Schema::TYPE_STRING . '(255) NOT NULL',
            'fax'                   => Schema::TYPE_STRING . '(255) NOT NULL',
            'password'              => Schema::TYPE_STRING . '(255) NOT NULL',
            'active'                => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('group_id', '{{%ecommerce_customers}}', 'group_id');
        $this->createIndex('email', '{{%ecommerce_customers}}', 'email', true);
        $this->addForeignKey('FK_ECOMMERCE_CUSTOMERS_GROUP_ID', '{{%ecommerce_customers}}', 'group_id', '{{%ecommerce_customer_groups}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_customer_groups_lang' table
        $this->createTable('{{%ecommerce_customer_groups_lang}}', [
            'customer_group_id'     => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'description'           => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('customer_group_id_language', '{{%ecommerce_customer_groups_lang}}', ['customer_group_id', 'language']);
        $this->createIndex('language', '{{%ecommerce_customer_groups_lang}}', 'language');
        $this->addForeignKey('FK_ECOMMERCE_CUSTOMER_GROUPS_LANG_CUSTOMER_GROUP_ID', '{{%ecommerce_customer_groups_lang}}', 'customer_group_id', '{{%ecommerce_customer_groups}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_customer_sessions' table
        $this->createTable('{{%ecommerce_customer_sessions}}', [
            'id'                    => Schema::TYPE_PK,
            'customer_id'           => Schema::TYPE_INTEGER . ' NOT NULL',
            'session'               => Schema::TYPE_STRING . '(255) NOT NULL',
            'ip'                    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'referrer'              => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('customer_id', '{{%ecommerce_customer_sessions}}', 'customer_id');
        $this->createIndex('session', '{{%ecommerce_customer_sessions}}', 'session');
        $this->createIndex('ip', '{{%ecommerce_customer_sessions}}', 'ip');
        $this->addForeignKey('FK_ECOMMERCE_CUSTOMER_SESSIONS_CUSTOMER_ID', '{{%ecommerce_customer_sessions}}', 'customer_id', '{{%ecommerce_customers}}', 'id', 'CASCADE', 'NO ACTION');

        /**
         * Manufacturers
         */

        // Create 'ecommerce_manufacturers' table
        $this->createTable('{{%ecommerce_manufacturers}}', [
            'id'                    => Schema::TYPE_PK,
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'position'              => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'active'                => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        /**
         * Options
         */

        // Create 'ecommerce_option_types' table
        $this->createTable('{{%ecommerce_option_types}}', [
            'id'                    => Schema::TYPE_PK,
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);

        // Create 'ecommerce_options' table
        $this->createTable('{{%ecommerce_options}}', [
            'id'                    => Schema::TYPE_PK,
            'type_id'               => Schema::TYPE_INTEGER . ' NOT NULL',
            'required'              => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('type_id', '{{%ecommerce_options}}', 'type_id');
        $this->addForeignKey('FK_ECOMMERCE_OPTIONS_TYPE_ID', '{{%ecommerce_options}}', 'type_id', '{{%ecommerce_option_types}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_options_lang' table
        $this->createTable('{{%ecommerce_options_lang}}', [
            'option_id'             => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('option_id_language', '{{%ecommerce_options_lang}}', ['option_id', 'language']);
        $this->createIndex('language', '{{%ecommerce_options_lang}}', 'language');
        $this->addForeignKey('FK_ECOMMERCE_OPTIONS_OPTION_ID', '{{%ecommerce_options_lang}}', 'option_id', '{{%ecommerce_options}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_option_sets' table
        $this->createTable('{{%ecommerce_option_sets}}', [
            'id'                    => Schema::TYPE_PK,
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        // Create 'ecommerce_option_sets_options' table
        $this->createTable('{{%ecommerce_option_sets_options}}', [
            'option_set_id'         => Schema::TYPE_INTEGER . ' NOT NULL',
            'option_id'             => Schema::TYPE_INTEGER . ' NOT NULL',
            'position'              => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT \'0\'',
        ], $tableOptions);

        $this->addPrimaryKey('option_set_id_option_id', '{{%ecommerce_option_sets_options}}', ['option_set_id', 'option_id']);
        $this->createIndex('option_id', '{{%ecommerce_option_sets_options}}', 'option_id');
        $this->addForeignKey('FK_ECOMMERCE_OPTION_OPTION_SET_ID', '{{%ecommerce_option_sets_options}}', 'option_set_id', '{{%ecommerce_option_sets}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('FK_ECOMMERCE_OPTION_OPTION_ID', '{{%ecommerce_option_sets_options}}', 'option_id', '{{%ecommerce_options}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_option_values' table
        $this->createTable('{{%ecommerce_option_values}}', [
            'id'                    => Schema::TYPE_PK,
            'option_id'             => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'label'                 => Schema::TYPE_STRING . '(255) NOT NULL',
            'data'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('option_id_language', '{{%ecommerce_option_values}}', ['option_id', 'language'], true);
        $this->createIndex('language', '{{%ecommerce_option_values}}', 'language');
        $this->addForeignKey('FK_ECOMMERCE_OPTION_VALUES_OPTION_ID', '{{%ecommerce_option_values}}', 'option_id', '{{%ecommerce_options}}', 'id', 'CASCADE', 'NO ACTION');

        /**
         * Products
         */

        // Create 'ecommerce_products' table
        $this->createTable('{{%ecommerce_products}}', [
            'id'                    => Schema::TYPE_PK,
            'attribute_set_id'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'option_set_id'         => Schema::TYPE_INTEGER . ' NOT NULL COMMENT \'The option_set_id is mandatory because there is a foreign key attached to it. But because it is possible that no option-set has to be attached to the product, a default set with id 0 should be created and used in such a case.\'',
            'manufacturer_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'price'                 => Schema::TYPE_DECIMAL . '(14,2) NOT NULL DEFAULT \'0.00\'',
            'quantity'              => Schema::TYPE_SMALLINT . '(5) NOT NULL DEFAULT \'0\'',
            'active'                => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('attribute_set_id', '{{%ecommerce_products}}', 'attribute_set_id');
        $this->createIndex('option_set_id', '{{%ecommerce_products}}', 'option_set_id');
        $this->createIndex('manufacturer_id', '{{%ecommerce_products}}', 'manufacturer_id');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCTS_ATTRIBUTE_SET_ID', '{{%ecommerce_products}}', 'attribute_set_id', '{{%ecommerce_attribute_sets}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCTS_OPTION_SET_ID', '{{%ecommerce_products}}', 'option_set_id', '{{%ecommerce_option_sets}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCTS_MANUFACTURER_ID', '{{%ecommerce_products}}', 'manufacturer_id', '{{%ecommerce_manufacturers}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_products_categories' table
        $this->createTable('{{%ecommerce_products_categories}}', [
            'product_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'category_id'           => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('product_id_category_id', '{{%ecommerce_products_categories}}', ['product_id', 'category_id']);
        $this->createIndex('category_id', '{{%ecommerce_products_categories}}', 'category_id');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCT_PRODUCT_ID', '{{%ecommerce_products_categories}}', 'product_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCT_CATEGORY_ID', '{{%ecommerce_products_categories}}', 'category_id', '{{%ecommerce_categories}}', 'id', 'CASCADE', 'NO ACTION');


        // Create 'ecommerce_products_lang' table
        $this->createTable('{{%ecommerce_products_lang}}', [
            'product_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'description'           => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('product_id_language', '{{%ecommerce_products_lang}}', ['product_id', 'language']);
        $this->createIndex('language', '{{%ecommerce_products_lang}}', 'language');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCTS_LANG_PRODUCT_ID', '{{%ecommerce_products_lang}}', 'product_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_product_attributes_values' table
        $this->createTable('{{%ecommerce_product_attributes_values}}', [
            'id'                    => Schema::TYPE_PK,
            'product_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'attribute_id'          => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'value'                 => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('product_id_attribute_id_language', '{{%ecommerce_product_attributes_values}}', ['product_id', 'attribute_id', 'language'], true);
        $this->createIndex('attribute_id', '{{%ecommerce_product_attributes_values}}', 'attribute_id');
        $this->createIndex('language', '{{%ecommerce_product_attributes_values}}', 'language');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCT_ATTRIBUTES_VALUES_PRODUCT_ID', '{{%ecommerce_product_attributes_values}}', 'product_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCT_ATTRIBUTES_VALUES_attribute_ID', '{{%ecommerce_product_attributes_values}}', 'attribute_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_product_discounts' table
        $this->createTable('{{%ecommerce_product_discounts}}', [
            'id'                    => Schema::TYPE_PK,
            'product_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'quantity'              => Schema::TYPE_SMALLINT . '(5) NOT NULL DEFAULT \'0\'',
            'price'                 => Schema::TYPE_DECIMAL . '(14,2) NOT NULL DEFAULT \'0.00\'',
            'start_date'            => Schema::TYPE_DATE,
            'end_date'              => Schema::TYPE_DATE,
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('product_id', '{{%ecommerce_product_discounts}}', 'product_id');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCT_DISCOUNTS_PRODUCT_ID', '{{%ecommerce_product_discounts}}', 'product_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_product_options_values' table
        $this->createTable('{{%ecommerce_product_options_values}}', [
            'id'                    => Schema::TYPE_PK,
            'product_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'option_id'             => Schema::TYPE_INTEGER . ' NOT NULL',
            'option_value_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'quantity'              => Schema::TYPE_SMALLINT . '(5) NOT NULL DEFAULT \'0\'',
            'price_operator'        => 'ENUM(\'+\',\'-\') NOT NULL DEFAULT \'+\'',
            'price_difference'      => Schema::TYPE_DECIMAL . ' NOT NULL DEFAULT \'0.00\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createIndex('product_id_option_id_option_value_id', '{{%ecommerce_product_options_values}}', ['product_id', 'option_id', 'option_value_id'], true);
        $this->createIndex('option_id', '{{%ecommerce_product_options_values}}', 'option_id');
        $this->createIndex('option_value_id', '{{%ecommerce_product_options_values}}', 'option_value_id');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCTS_OPTIONS_VALUES_PRODUCT_ID', '{{%ecommerce_product_discounts}}', 'product_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');

        // Create 'ecommerce_product_stats' table
        $this->createTable('{{%ecommerce_product_stats}}', [
            'product_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'viewed'                => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT \'0\'',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('product_id', '{{%ecommerce_product_stats}}', 'product_id');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCT_STATS_PRODUCT_ID', '{{%ecommerce_product_stats}}', 'product_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');

        /**
         * Related products
         */

        // Create 'ecommerce_related_products' table
        $this->createTable('{{%ecommerce_related_products}}', [
            'product_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'related_product_id'                => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('product_id_related_product_id', '{{%ecommerce_related_products}}', ['product_id', 'related_product_id']);
        $this->createIndex('related_product_id', '{{%ecommerce_related_products}}', 'related_product_id');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCTS_PRODUCT_ID', '{{%ecommerce_related_products}}', 'product_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('FK_ECOMMERCE_PRODUCTS_RELATED_PRODUCT_ID', '{{%ecommerce_related_products}}', 'product_id', '{{%ecommerce_products}}', 'id', 'CASCADE', 'NO ACTION');

    }

    public function down()
    {
        /**
         * Related products
         */

        $this->dropTable('ecommerce_related_products');

        /**
         * Manufacturers
         */

        $this->dropTable('ecommerce_manufacturers');

        /**
         * Products
         */

        $this->dropTable('ecommerce_product_stats');
        $this->dropTable('ecommerce_product_options_values');
        $this->dropTable('ecommerce_product_discounts');
        $this->dropTable('ecommerce_product_attributes_values');
        $this->dropTable('ecommerce_products_lang');
        $this->dropTable('ecommerce_products_categories');
        $this->dropTable('ecommerce_products');

        /**
         * Options
         */

        $this->dropTable('ecommerce_option_sets_options');
        $this->dropTable('ecommerce_option_sets');
        $this->dropTable('ecommerce_option_values');
        $this->dropTable('ecommerce_options_lang');
        $this->dropTable('ecommerce_options');
        $this->dropTable('ecommerce_option_types');

        /**
         * Customers
         */
        $this->dropTable('ecommerce_customer_sessions');
        $this->dropTable('ecommerce_customers');
        $this->dropTable('ecommerce_customer_groups_lang');
        $this->dropTable('ecommerce_customer_groups');

        /**
         * Categories
         */

        $this->dropTable('ecommerce_categories_lang');
        $this->dropTable('ecommerce_categories');

        /**
         * Attributes
         */

        $this->dropTable('ecommerce_attribute_sets_group');
        $this->dropTable('ecommerce_attribute_sets');
        $this->dropTable('ecommerce_attribute_groups_lang');
        $this->dropTable('ecommerce_attribute_groups_attributes');
        $this->dropTable('ecommerce_attribute_groups');
        $this->dropTable('ecommerce_attributes_lang');
        $this->dropTable('ecommerce_attributes');
    }
}
