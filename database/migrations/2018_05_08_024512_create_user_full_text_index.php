<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFullTextIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->table('users', function (Blueprint $collection) {
                $collection->index(
                    [
                        "name" => "text",
                        // "description" => "text",
                        // "category" => "text",
                        // "tags" => "text"
                    ],
                    'recipe_full_text',
                    null,
                    [
                        "weights" => [
                            "name" => 32,
                            // "description" => 12,
                            // "category" => 8,
                            // "tags" => 4
                        ],
                        'name' => 'recipe_full_text'
                    ]
                );
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->table('users', function (Blueprint $collection) {
                $collection->dropIndex(['recipe_full_text']);
            });
    }
}
