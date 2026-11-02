<?php

namespace Tests\Feature;

use App\Models\absence as AbsenceRecord;
use App\Models\User;
use App\Models\users as UserRecord;
use App\Policies\AbsencePolicy;
use App\Policies\UsersPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationPoliciesTest extends TestCase
{
    use RefreshDatabase;

    public function test_absence_policy_allows_owners_and_managers_but_never_restores_or_force_deletes(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $manager = User::factory()->create();
        $manager->assign('admin');
        $absence = AbsenceRecord::factory()->create(['user_id' => $owner->getKey()]);
        $policy = new AbsencePolicy;

        $this->assertTrue($policy->viewAny($owner));
        $this->assertTrue($policy->create($owner));
        $this->assertTrue($policy->view($owner, $absence));
        $this->assertTrue($policy->update($owner, $absence));
        $this->assertTrue($policy->delete($owner, $absence));
        $this->assertFalse($policy->view($otherUser, $absence));
        $this->assertFalse($policy->update($otherUser, $absence));
        $this->assertFalse($policy->delete($otherUser, $absence));
        $this->assertTrue($policy->view($manager, $absence));
        $this->assertTrue($policy->update($manager, $absence));
        $this->assertTrue($policy->delete($manager, $absence));
        $this->assertFalse($policy->restore($manager, $absence));
        $this->assertFalse($policy->forceDelete($manager, $absence));
    }

    public function test_user_policy_limits_writes_to_managers_and_views_to_owners_or_managers(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $manager = User::factory()->create();
        $manager->assign('admin');
        $ownRecord = new UserRecord;
        $ownRecord->setAttribute($ownRecord->getKeyName(), $owner->getKey());
        $otherRecord = UserRecord::factory()->create();
        $policy = new UsersPolicy;

        $this->assertTrue($policy->viewAny($owner));
        $this->assertTrue($policy->view($owner, $ownRecord));
        $this->assertFalse($policy->view($owner, $otherRecord));
        $this->assertFalse($policy->create($owner));
        $this->assertFalse($policy->update($owner, $ownRecord));
        $this->assertTrue($policy->view($manager, $otherRecord));
        $this->assertTrue($policy->create($manager));
        $this->assertTrue($policy->update($manager, $otherRecord));
        $this->assertFalse($policy->delete($manager, $otherRecord));
        $this->assertFalse($policy->restore($manager, $otherRecord));
        $this->assertFalse($policy->forceDelete($manager, $otherRecord));
        $this->assertFalse($policy->view($otherUser, $otherRecord));
    }
}
