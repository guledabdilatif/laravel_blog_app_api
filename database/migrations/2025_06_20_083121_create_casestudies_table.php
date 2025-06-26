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
        Schema::create('casestudies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('role')->nullable();
            $table->text('goal')->nullable();
            $table->string('wireframe_image')->nullable();
            $table->string('mockup_image')->nullable();
            $table->string('prototype_image')->nullable();
            $table->string('final_design_image')->nullable();
            $table->text('what_i_learned')->nullable();
            $table->text('tech_stack')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casestudies');
    }
};
