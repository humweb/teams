<?php

namespace Humweb\Teams\Tests;

use Humweb\Teams\Events\UserJoinedTeam;
use Humweb\Teams\Events\UserLeftTeam;
use Humweb\Teams\Tests\Helpers\CreatesTeams;
use Humweb\Teams\Tests\Stubs\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;

/**
 * Class AddTransactionTest
 *
 * @package Humweb\Teams\Tests
 */
class TeamUserTraitTest extends TestCase
{
    use CreatesTeams, DatabaseTransactions;

    protected $user;


    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * @method hasTeams
     * @test
     */
    public function it_can_check_if_user_belongs_to_any_teams()
    {
        $this->assertFalse($this->user->hasTeams());
        $team = $this->createTeam($this->user);
        $this->assertTrue($this->user->hasTeams());
    }


    /**
     * @method isTeamOwner
     * @test
     */
    public function it_can_check_if_user_owns_a_given_team()
    {
        $team = $this->createTeam($this->user);

        $this->assertTrue($this->user->isTeamOwner($team));
        $this->assertEquals(1, $this->user->ownedTeams->count());

        $team->owner_id = $this->user->id + 1;
        $team->save();

        // Refresh relationships
        $this->user = $this->user->fresh();

        $this->assertFalse($this->user->isTeamOwner($team));
        $this->assertEquals(0, $this->user->ownedTeams->count());
    }


    /**
     * @test
     */
    public function it_returns_the_active_team_for_the_user()
    {
        $team = $this->createTeam($this->user);
        $this->assertEquals($team->id, $this->user->currentTeam->id);
    }


    /**
     * @method switchTeam
     * @test
     */
    public function it_allows_users_to_switch_teams()
    {
        $this->user = factory(User::class)->create();
        $team1      = $this->createTeam($this->user);
        $team2      = $this->createTeam($this->user);

        $this->user->forceFill(['current_team_id' => $team1->id])->save();
        $this->assertEquals($team1->id, $this->user->currentTeam->id);

        $this->user->switchTeam($team2);
        $this->assertEquals($team2->id, $this->user->currentTeam->id);
    }


    /**
     * @method ownsAnyTeam
     * @test
     */
    public function it_can_check_if_user_owns_teams()
    {
        $this->assertFalse($this->user->ownsAnyTeams());
        $team = $this->createTeam($this->user);
        $this->assertTrue($this->user->ownsAnyTeams());
        $this->assertTrue($this->user->isOwnerOfTeam($team));
    }


    /**
     * @method joinTeam
     * @test
     */
    public function it_allows_user_to_join_a_team()
    {
        Event::fake();
        $this->assertFalse($this->user->ownsAnyTeams());

        $team = $this->createTeam();

        $this->assertFalse($team->isMember($this->user));
        $this->user->joinTeam($team);
        $this->assertTrue($team->isMember($this->user));
        Event::assertDispatched(UserJoinedTeam::class);
    }


    /**
     * @method isOwnerOfTeam
     * @test
     */
    public function it_allows_user_to_leave_a_team()
    {
        Event::fake();
        $team = $this->createTeam($this->user);
        $this->assertTrue($this->user->isOwnerOfTeam($team));
        $this->user->leaveTeam($team);
        $this->assertFalse($this->user->ownsAnyTeams());
        Event::assertDispatched(UserLeftTeam::class);
    }


    /**
     * Removes orphaned entires from pivot table
     *
     * @test
     */
    public function it_deletes_entries_from_pivot_table_when_a_user_is_deleted()
    {
        $team = $this->createTeam($this->user);

        $this->assertDatabaseHas('team_members', [
            'team_id' => $team->id,
            'user_id' => $this->user->id
        ]);

        $this->user->delete();

        $this->assertDatabaseMissing('team_members', [
            'team_id' => $team->id,
            'user_id' => $this->user->id
        ]);
    }
}
