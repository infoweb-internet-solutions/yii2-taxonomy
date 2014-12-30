<?php

use yii\db\Schema;
use yii\db\Migration;

class m141001_132859_add_default_permissions extends Migration
{
    public function up()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showPagePartialsModule',
            'type'          => 2,
            'description'   => 'Show partials module in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showPagePartialsModule'
        ]);
    }

    public function down()
    {
        // Delete the auth item relation
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showPagePartialsModule'
        ]);
        
        // Delete the auth items
        $this->delete('{{%auth_item}}', [
            'name'          => 'showPagePartialsModule',
            'type'          => 2,
        ]);
    }
}
