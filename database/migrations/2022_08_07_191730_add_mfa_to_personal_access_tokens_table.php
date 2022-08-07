<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {

            $table->string('mfa_code')->default(
                (string) rand(100000, 999999)
            );

            $table->timestamp('mfa_expires_at')->default(
                now()->addMinutes(config('mfa.expiration'))
                    ->format('Y-m-d h:i:s')
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('mfa_code');
            $table->dropColumn('mfa_expires_at');
        });
    }
};
