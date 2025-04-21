<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerListingTest extends PlayerControllerBaseTest
{
    public function test_can_list_players() {
        $player = [
            "name" => "Test Player",
            "position" => "defender",
            "playerSkills" => [
                [
                    "skill" => "attack",
                    "value" => 60
                ]
            ]
        ];

        $this->postJson(self::REQ_URI, $player);

        $response = $this->getJson(self::REQ_URI);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'position',
                        'playerSkills' => [
                            '*' => [
                                'id',
                                'skill',
                                'value',
                                'playerId'
                            ]
                        ]
                    ]
                ]);
    }
}
