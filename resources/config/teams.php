<?php


return [

    'user_model' => Humweb\Teams\Tests\Stubs\User::class,
    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    |
    | Define the events and how many point each event is worth.
    |
    */

    'events' => [
        'assessment_completed'  => 5,
        'instruction_completed' => 10,
    ],

];