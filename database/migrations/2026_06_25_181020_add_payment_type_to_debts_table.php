<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->enum('payment_type', ['bebas', 'cicilan_tetap'])->default('bebas')->after('type');
            $table->decimal('installment_amount', 15, 2)->nullable()->after('remaining_amount');
        });

        Schema::table('debt_payments', function (Blueprint $table) {
            $table->integer('installment_number')->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'installment_amount']);
        });

        Schema::table('debt_payments', function (Blueprint $table) {
            $table->dropColumn('installment_number');
        });
    }
};
