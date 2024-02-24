<?php

use App\Models\Payer;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('transaction_amount', 11);
            $table->integer('installments');
            $table->string('token');
            $table->string('payment_method_id');
            $table->foreignIdFor(Payer::class)->constrained();
            $table->string('notification_url');
            $table->timestamps();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
