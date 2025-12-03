<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix theme_park_wallets.user_id -> should reference guests.id
        DB::statement('ALTER TABLE theme_park_wallets DROP FOREIGN KEY theme_park_wallets_user_id_foreign');
        DB::statement('ALTER TABLE theme_park_wallets ADD CONSTRAINT theme_park_wallets_user_id_foreign FOREIGN KEY (user_id) REFERENCES guests(id) ON DELETE CASCADE');

        // Fix theme_park_wallet_transactions.user_id -> should reference guests.id
        DB::statement('ALTER TABLE theme_park_wallet_transactions DROP FOREIGN KEY theme_park_wallet_transactions_user_id_foreign');
        DB::statement('ALTER TABLE theme_park_wallet_transactions ADD CONSTRAINT theme_park_wallet_transactions_user_id_foreign FOREIGN KEY (user_id) REFERENCES guests(id) ON DELETE CASCADE');

        // Fix theme_park_ticket_redemptions.user_id -> should reference guests.id
        DB::statement('ALTER TABLE theme_park_ticket_redemptions DROP FOREIGN KEY theme_park_ticket_redemptions_user_id_foreign');
        DB::statement('ALTER TABLE theme_park_ticket_redemptions ADD CONSTRAINT theme_park_ticket_redemptions_user_id_foreign FOREIGN KEY (user_id) REFERENCES guests(id) ON DELETE CASCADE');

        // Fix theme_park_ticket_redemptions.validated_by -> should reference staff.id (staff member who validates)
        DB::statement('ALTER TABLE theme_park_ticket_redemptions DROP FOREIGN KEY theme_park_ticket_redemptions_validated_by_foreign');
        DB::statement('ALTER TABLE theme_park_ticket_redemptions ADD CONSTRAINT theme_park_ticket_redemptions_validated_by_foreign FOREIGN KEY (validated_by) REFERENCES staff(id) ON DELETE SET NULL');

        // Fix theme_park_settings.updated_by -> should reference staff.id (staff member who updates settings)
        DB::statement('ALTER TABLE theme_park_settings DROP FOREIGN KEY theme_park_settings_updated_by_foreign');
        DB::statement('ALTER TABLE theme_park_settings ADD CONSTRAINT theme_park_settings_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES staff(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert theme_park_wallets
        DB::statement('ALTER TABLE theme_park_wallets DROP FOREIGN KEY theme_park_wallets_user_id_foreign');
        DB::statement('ALTER TABLE theme_park_wallets ADD CONSTRAINT theme_park_wallets_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');

        // Revert theme_park_wallet_transactions
        DB::statement('ALTER TABLE theme_park_wallet_transactions DROP FOREIGN KEY theme_park_wallet_transactions_user_id_foreign');
        DB::statement('ALTER TABLE theme_park_wallet_transactions ADD CONSTRAINT theme_park_wallet_transactions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');

        // Revert theme_park_ticket_redemptions.user_id
        DB::statement('ALTER TABLE theme_park_ticket_redemptions DROP FOREIGN KEY theme_park_ticket_redemptions_user_id_foreign');
        DB::statement('ALTER TABLE theme_park_ticket_redemptions ADD CONSTRAINT theme_park_ticket_redemptions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');

        // Revert theme_park_ticket_redemptions.validated_by
        DB::statement('ALTER TABLE theme_park_ticket_redemptions DROP FOREIGN KEY theme_park_ticket_redemptions_validated_by_foreign');
        DB::statement('ALTER TABLE theme_park_ticket_redemptions ADD CONSTRAINT theme_park_ticket_redemptions_validated_by_foreign FOREIGN KEY (validated_by) REFERENCES users(id) ON DELETE SET NULL');

        // Revert theme_park_settings
        DB::statement('ALTER TABLE theme_park_settings DROP FOREIGN KEY theme_park_settings_updated_by_foreign');
        DB::statement('ALTER TABLE theme_park_settings ADD CONSTRAINT theme_park_settings_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE CASCADE');
    }
};
