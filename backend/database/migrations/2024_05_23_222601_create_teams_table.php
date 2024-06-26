<?php

use App\Models\Team;
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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('site')->nullable();
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->unsignedBigInteger('admin_id');
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });

        Team::createElasticsearchIndex();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');

        Team::deleteElasticsearchIndex();
    }
};
