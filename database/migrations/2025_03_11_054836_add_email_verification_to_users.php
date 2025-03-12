<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\User;

class AddEmailVerificationToUsers extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        $connection = \DB::connection('mongodb');

        $collection = $connection->getCollection('users');
        
        $collection->updateMany(
            ['email_verification' => ['$exists' => false]],
            ['$set' => ['email_verification' => false]]
        );
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        $connection = \DB::connection('mongodb');
        
        $collection = $connection->getCollection('users');
        
        $collection->updateMany(
            [],
            ['$unset' => ['email_verification' => '']]
        );
    }
}