<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_only_see_members_of_their_own_company(): void
    {
        $companyA = Company::factory()->create(['name' => 'Company A']);
        $companyB = Company::factory()->create(['name' => 'Company B']);

        $userA1 = User::factory()->create([
            'company_id' => $companyA->id,
            'name' => 'User One A'
        ]);

        $userA2 = User::factory()->create([
            'company_id' => $companyA->id,
            'name' => 'User Two A'
        ]);

        $userB = User::factory()->create([
            'company_id' => $companyB->id,
            'name' => 'User B'
        ]);

        $this->actingAs($userA1)
            ->get(route('team.index'))
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Team/Index')
                ->has('users', 2)
                ->where('users.0.id', $userA1->id)
                ->where('users.1.id', $userA2->id)
            );
    }

    public function test_user_can_invite_team_member_to_their_company(): void
    {
        $company = Company::factory()->create(['name' => 'Company Name']);
        $admin = User::factory()->create(['company_id' => $company->id]);

        $response = $this->actingAs($admin)->post(route('team.store'), [
            'name' => 'New Team Member',
            'email' => 'member@test.com',
        ]);

        $response->assertRedirect(route('team.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'New Team Member',
            'email' => 'member@test.com',
            'company_id' => $company->id,
        ]);
    }
}
