<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection('mongodb')->table('users', function (Blueprint $collection) {
            $collection->string('status')->default('To Review');
        });
    }
    
    public function down()
    {
        Schema::connection('mongodb')->table('users', function (Blueprint $collection) {
            $collection->dropColumn('status');
        });
    }
    
};
