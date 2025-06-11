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
        // First, check if the 'name' column exists in the users table
        if (Schema::hasColumn('users', 'name') && !Schema::hasColumn('users', 'first_name')) {
            // Add the new columns if they don't exist
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name')->nullable()->after('id');
                $table->string('last_name')->nullable()->after('first_name');
            });

            // Migrate existing data from 'name' to 'first_name' and 'last_name'
            $users = DB::table('users')->whereNotNull('name')->get();
            
            foreach ($users as $user) {
                $nameParts = explode(' ', trim($user->name), 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';
                
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                    ]);
            }

            // Make the columns required after data migration
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name')->nullable(false)->change();
                $table->string('last_name')->nullable(false)->change();
            });

            // Drop the old 'name' column
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }

        // Add any missing columns from the enhanced users table structure
        Schema::table('users', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('users', 'occupation')) {
                $table->string('occupation')->nullable();
            }
            if (!Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable();
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->default('Vietnam');
            }
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->enum('user_type', ['buyer', 'seller', 'agent', 'admin'])->default('buyer');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            }
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
            if (!Schema::hasColumn('users', 'license_number')) {
                $table->string('license_number')->nullable();
            }
            if (!Schema::hasColumn('users', 'agency_name')) {
                $table->string('agency_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('users', 'preferences')) {
                $table->json('preferences')->nullable();
            }
            if (!Schema::hasColumn('users', 'receive_notifications')) {
                $table->boolean('receive_notifications')->default(true);
            }
            if (!Schema::hasColumn('users', 'receive_marketing')) {
                $table->boolean('receive_marketing')->default(false);
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip')->nullable();
            }
            if (!Schema::hasColumn('users', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add indexes if they don't exist
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('users');
        $indexNames = array_keys($indexes);

        if (!in_array('users_phone_index', $indexNames) && Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('phone');
            });
        }
        
        if (!in_array('users_user_type_index', $indexNames)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('user_type');
            });
        }
        
        if (!in_array('users_status_index', $indexNames)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('status');
            });
        }
        
        if (!in_array('users_is_verified_index', $indexNames)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('is_verified');
            });
        }
        
        if (!in_array('users_city_index', $indexNames)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('city');
            });
        }
        
        if (!in_array('users_last_login_at_index', $indexNames)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('last_login_at');
            });
        }
        
        if (!in_array('users_first_name_last_name_index', $indexNames)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['first_name', 'last_name']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the 'name' column
        if (!Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });

            // Migrate data back from first_name and last_name to name
            $users = DB::table('users')->whereNotNull('first_name')->get();
            
            foreach ($users as $user) {
                $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['name' => $fullName]);
            }

            // Make name column required
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->nullable(false)->change();
            });
        }

        // Remove the new columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'date_of_birth', 'gender', 'avatar', 'bio',
                'occupation', 'company', 'city', 'state', 'postal_code', 'country',
                'user_type', 'status', 'is_verified', 'license_number', 'agency_name',
                'commission_rate', 'preferences', 'receive_notifications', 'receive_marketing',
                'last_login_at', 'last_login_ip', 'password_changed_at'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
