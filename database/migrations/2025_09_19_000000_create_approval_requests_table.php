<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('changes')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('requested_by');
            $table->string('action_type');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('approval_requests');
    }
};
