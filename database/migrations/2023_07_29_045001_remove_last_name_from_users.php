<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database backup only onecollum
//        // coloum data backup
//        $data = DB::table('users')->select('id', 'lastName')->get();
//        file_put_contents('backup/lastName_data.json', json_encode($data));
//
//        // remove column
//        Schema::table('users', function (Blueprint $table) {
//            $table->dropColumn(['lastName']);
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
//            $table->after('fristName', function (Blueprint $table) {
//                $table->string('lastName', 20);
//            });
        });
    }
};
