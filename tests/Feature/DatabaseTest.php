<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test users table can be created and migrated
     */
    public function test_users_table_exists(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasTable('users')
        );
    }

    /**
     * Test users table has required columns
     */
    public function test_users_table_has_required_columns(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasColumns('users', [
                'id',
                'name',
                'email',
                'password',
                'created_at',
                'updated_at',
            ])
        );
    }

    /**
     * Test password reset tokens table exists
     */
    public function test_password_reset_tokens_table_exists(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasTable('password_reset_tokens')
        );
    }

    /**
     * Test sessions table exists
     */
    public function test_sessions_table_exists(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasTable('sessions')
        );
    }

    /**
     * Test user can be inserted into database
     */
    public function test_user_can_be_inserted_into_database(): void
    {
        User::factory()->create([
            'name' => 'Database Test User',
            'email' => 'dbtest@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Database Test User',
            'email' => 'dbtest@example.com',
        ]);
    }

    /**
     * Test user can be retrieved from database
     */
    public function test_user_can_be_retrieved_from_database(): void
    {
        $user = User::factory()->create([
            'name' => 'Retrieve Test User',
        ]);

        $retrievedUser = User::find($user->id);

        $this->assertEquals('Retrieve Test User', $retrievedUser->name);
        $this->assertEquals($user->email, $retrievedUser->email);
    }

    /**
     * Test user can be updated in database
     */
    public function test_user_can_be_updated_in_database(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
        ]);

        $user->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Test user can be deleted from database
     */
    public function test_user_can_be_deleted_from_database(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $userId,
        ]);
    }

    /**
     * Test multiple users can be created in database
     */
    public function test_multiple_users_can_be_created_in_database(): void
    {
        User::factory()->count(10)->create();

        $this->assertDatabaseCount('users', 10);
    }

    /**
     * Test email unique constraint
     */
    public function test_email_unique_constraint(): void
    {
        $email = 'unique@example.com';

        User::factory()->create(['email' => $email]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create(['email' => $email]);
    }
}
