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
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // Find or create "থ্রি পিছ" category
        $threePiece = DB::table('categories')->where('name', 'থ্রি পিছ')->first();

        if (!$threePiece) {
            $threePieceId = DB::table('categories')->insertGetId([
                'name' => 'থ্রি পিছ',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $threePieceId = $threePiece->id;
        }

        // Update all other categories to have parent_id = $threePieceId
        DB::table('categories')
            ->where('id', '!=', $threePieceId)
            ->whereNull('parent_id')
            ->update(['parent_id' => $threePieceId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
