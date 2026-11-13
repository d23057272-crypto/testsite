<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->makeNullableReference('transactions', 'user_id', 'users');
        $this->makeNullableReference(
            'transactions',
            'cryptoasset_id',
            'crypto_assets'
        );
        $this->makeNullableReference(
            'card_transactions',
            'card_id',
            'cards'
        );
    }

    private function makeNullableReference(
        string $tableName,
        string $columnName,
        string $referencedTable
    ): void {
        if (
            !Schema::hasTable($tableName) ||
            !Schema::hasColumn($tableName, $columnName)
        ) {
            return;
        }

        $foreignKey = collect(Schema::getForeignKeys($tableName))
            ->first(
                fn (array $key): bool => in_array(
                    $columnName,
                    $key['columns'],
                    true
                )
            );

        Schema::table($tableName, function (Blueprint $table) use (
            $columnName,
            $referencedTable,
            $foreignKey
        ): void {
            if ($foreignKey) {
                $table->dropForeign(
                    $foreignKey['name'] ?: [$columnName]
                );
            }

            $table->unsignedBigInteger($columnName)
                ->nullable()
                ->change();
            $table->foreign($columnName)
                ->references('id')
                ->on($referencedTable)
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Anonymized history must remain nullable to preserve account deletion records.
    }
};
