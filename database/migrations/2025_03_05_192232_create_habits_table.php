<?php

use App\Models\User;
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
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('days')->default(0)
                ->comment('mask define the days of week to do that habit for example if the mask is 0000,0001 then that habit should be done only on sunday if the mask is 0001,0101 then that habit should be done  on sunday , tuesday and thursday , so on');
            $table->time('reminder_time')->nullable();
            $table->boolean('notifications_enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
