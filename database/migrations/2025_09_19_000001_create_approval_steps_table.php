<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_request_id')->constrained('approval_requests')->cascadeOnDelete();
            $table->integer('step');
            $table->string('name');
            $table->string('type');
            $table->json('identifier');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('approval_steps');
    }
};
