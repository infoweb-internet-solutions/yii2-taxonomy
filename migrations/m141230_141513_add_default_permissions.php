<?php

use yii\db\Schema;
use yii\db\Migration;

class m141230_141513_add_default_permissions extends Migration
{
    public function up()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showTaxonomyModule',
            'type'          => 2,
            'description'   => 'Show taxonomy module in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);

        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showTaxonomyModule'
        ]);
    }

    public function down()
    {
        // Delete the auth item relation
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showTaxonomyModule'
        ]);

        // Delete the auth items
        $this->delete('{{%auth_item}}', [
            'name'          => 'showTaxonomyModule',
            'type'          => 2,
        ]);
    }
}
