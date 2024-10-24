<?php

namespace Migrations;

use Horizon\Core\Commands\Migrations\AbstractMigration;
use Horizon\Core\Commands\Migrations\Schema;

class Version20241024160929User extends AbstractMigration {

    public function up(): void {
        Schema::newTable("_users", function($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void {
        // Revert migration here
    }
}