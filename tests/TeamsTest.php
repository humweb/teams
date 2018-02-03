<?php

namespace Humweb\Teams\Tests;

use Humweb\Teams\Tests\Helpers\CreatesTeams;
use Humweb\Teams\Tests\Stubs\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class AddTransactionTest
 *
 * @package Humweb\Teams\Tests
 */
class TeamsTest extends TestCase
{
    use CreatesTeams, DatabaseTransactions;

    protected $user;


    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * @test
     */
    public function it_can_check_if_user_belongs_to_any_teams()
    {

        $this->assertFalse($this->user->hasTeams());
        $team = $this->createTeam($this->user);
        $this->assertTrue($this->user->hasTeams());
    }

}
