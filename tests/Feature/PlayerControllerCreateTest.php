<?php


// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerCreateTest extends PlayerControllerBaseTest
{
    public function test_can_create_valid_player() {
        $data = [
            "name" => "Test Player",
            "position" => "defender",
            "playerSkills" => [
                [
                    "skill" => "attack",
                    "value" => 60
                ],
                [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];

        $response = $this->postJson(self::REQ_URI, $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
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
                ]);
    }

    public function test_cannot_create_player_with_invalid_position() {
        $data = [
            "name" => "Test Player",
            "position" => "invalid_position",
            "playerSkills" => [
                [
                    "skill" => "attack",
                    "value" => 60
                ]
            ]
        ];

        $response = $this->postJson(self::REQ_URI, $data);

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Invalid value for position: invalid_position'
                ]);
    }

    public function test_cannot_create_player_with_invalid_skill() {
        $data = [
            "name" => "Test Player",
            "position" => "defender",
            "playerSkills" => [
                [
                    "skill" => "invalid_skill",
                    "value" => 60
                ]
            ]
        ];

        $response = $this->postJson(self::REQ_URI, $data);

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Invalid value for skill: invalid_skill'
                ]);
    }
}
