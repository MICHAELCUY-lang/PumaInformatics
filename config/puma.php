<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Clubs and Communities
    |--------------------------------------------------------------------------
    |
    | Organisations that sit under PUMA Informatics. Shown alongside the cabinet
    | lineage on the homepage, so a visitor sees both what the department runs
    | over time (cabinets) and what runs beneath it (clubs).
    |
    | Kept in config rather than the database: this list changes once every few
    | years, and a config entry needs no migration, no admin screen and no
    | seeding. Add an entry, drop a logo in public/clubs, deploy.
    |
    | url — null renders the entry without a link and marks it as not yet
    |       published, which is honest about a club whose site does not exist
    |       rather than shipping a dead link.
    |
    */

    'clubs' => [
        [
            'name' => 'PURTC',
            'full_name' => 'President University Robotics and Technology Club',
            'logo' => 'clubs/purtc.png',
            'url' => null,
        ],
        [
            'name' => 'PLRC',
            'full_name' => 'President University Literature and Research Club',
            'logo' => 'clubs/plrc.png',
            'url' => '/plrc',
        ],
    ],

];
