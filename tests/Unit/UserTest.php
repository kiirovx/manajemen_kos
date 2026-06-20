<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test user can be created with valid data
     */
    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->id);
        $this->assertIsInt($user->id);
    }

    /**
     * Test user has required attributes
     */
    public function test_user_has_required_attributes(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
    }

    /**
     * Test user password is hashed
     */
    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test user email is unique
     */
    public function test_user_email_is_unique(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create([
            'email' => 'unique_test@example.com',
        ]);

        User::factory()->create([
            'email' => 'unique_test@example.com',
        ]);
    }

    /**
     * Test user fillable attributes
     */
    public function test_user_fillable_attributes(): void
    {
        $user = new User();
        $fillable = $user->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
    }

    /**
     * Test user hidden attributes
     */
    public function test_user_hidden_attributes(): void
    {
        $user = User::factory()->create();
        $hidden = $user->getHidden();

        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }

    /**
     * Test user can be updated
     */
    public function test_user_can_be_updated(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
        ]);

        $user->update(['name' => 'Updated Name']);

        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('Updated Name', User::find($user->id)->name);
    }

    /**
     * Test user can be deleted
     */
    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertNull(User::find($userId));
    }

    /**
     * Test user can be retrieved by email
     */
    public function test_user_can_be_retrieved_by_email(): void
    {
        $email = 'test@example.com';
        $user = User::factory()->create(['email' => $email]);

        $retrievedUser = User::where('email', $email)->first();

        $this->assertEquals($user->id, $retrievedUser->id);
    }

    /**
     * Test user timestamps are set
     */
    public function test_user_timestamps_are_set(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    /**
     * Test user with no email verification
     */
    public function test_user_email_verified_at_is_nullable(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->assertNull($user->email_verified_at);
    }

    /**
     * Test multiple users can be created
     */
    public function test_multiple_users_can_be_created(): void
    {
        $users = User::factory()->count(5)->create();

        $this->assertCount(5, $users);
        
        // Verify each user was created with proper attributes
        foreach ($users as $user) {
            $this->assertNotNull($user->id);
            $this->assertNotNull($user->email);
            $this->assertNotNull($user->name);
        }
    }
}
