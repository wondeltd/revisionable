<?php

namespace Venturecraft\Revisionable\Tests;

use Carbon\Carbon;
use Venturecraft\Revisionable\Tests\Models\User;
use PHPUnit\Framework\Attributes\Test;

class DateRevisionTest extends TestCase
{
    #[Test]
    public function revision_is_stored_when_date_attribute_is_date_string()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path' => realpath(__DIR__.'/migrations'),
        ]);

        $user = User::create([
            'name' => 'James Judd',
            'email' => 'james.judd@revisionable.test',
            'date' => '2025-12-18',
            'password' => \Hash::make('456'),
        ]);

        // Change date
        $user->update([
            'date' => '2025-12-19'
        ]);

        // we should have 1 revision to the date
        $this->assertCount(1, $user->revisionHistory);
        $this->assertEquals('2025-12-18', $user->revisionHistory->first()['old_value']);
    }


     #[Test]
    public function revision_is_stored_when_date_attribute_is_carbon()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path' => realpath(__DIR__.'/migrations'),
        ]);

        $user = User::create([
            'name' => 'James Judd',
            'email' => 'james.judd@revisionable.test',
            'date' => '2025-12-18',
            'password' => \Hash::make('456'),
        ]);

        // Change date
        $user->update([
            'date' => Carbon::parse('2025-12-19'),
        ]);

        // we should have 1 revision to the date
        $this->assertCount(1, $user->revisionHistory);
        $this->assertEquals('2025-12-19', $user->revisionHistory->first()['new_value']);
    }


    #[Test]
    public function revision_is_stored_when_date_attribute_is_date_time_string()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path' => realpath(__DIR__.'/migrations'),
        ]);

        $user = User::create([
            'name' => 'James Judd',
            'email' => 'james.judd@revisionable.test',
            'date' => '2025-12-18',
            'password' => \Hash::make('456'),
        ]);

        // Set casts on date attribute
        $user->mergeCasts([
            'date' => 'datetime',
        ]);

        // Change date
        $user->update([
            'date' => '2025-12-19 01:00:00',
        ]);

        // we should have 1 revision to the date
        $this->assertCount(1, $user->revisionHistory);
        $this->assertEquals('2025-12-19', $user->revisionHistory->first()['new_value']);
    }

    #[Test]
    public function revision_is_not_stored_when_date_attribute_is_carbon_but_date_is_same()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path' => realpath(__DIR__.'/migrations'),
        ]);

        $user = User::create([
            'name' => 'James Judd',
            'email' => 'james.judd@revisionable.test',
            'date' => '2025-12-18',
            'password' => \Hash::make('456'),
        ]);

        // Change date
        $user->update([
            'date' => Carbon::parse('2025-12-18'),
        ]);

        // we should have no revisions to the date
        $this->assertCount(0, $user->revisionHistory);
    }


    #[Test]
    public function revision_is_not_stored_when_date_attribute_is_datetime_string_but_date_is_same()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path' => realpath(__DIR__.'/migrations'),
        ]);

        $user = User::create([
            'name' => 'James Judd',
            'email' => 'james.judd@revisionable.test',
            'date' => '2025-12-18',
            'password' => \Hash::make('456'),
        ]);

        // Set casts on date attribute
        $user->mergeCasts([
            'date' => 'date',
        ]);

        // Change date
        $user->update([
            'date' => '2025-12-18 23:59:59',
        ]);

        // we should have no revisions to the date
        $this->assertCount(0, $user->revisionHistory);
    }
}
