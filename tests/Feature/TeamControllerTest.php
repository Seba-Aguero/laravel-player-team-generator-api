<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class TeamControllerTest extends PlayerControllerBaseTest
{
    public function test_can_process_team_selection() {
        $players = [
            [
                "name" => "Fast Defender",
                "position" => "defender",
                "playerSkills" => [
                    ["skill" => "speed", "value" => 80]
                ]
            ],
            [
                "name" => "Strong Defender",
                "position" => "defender",
                "playerSkills" => [
                    ["skill" => "strength", "value" => 90]
                ]
            ]
        ];

        foreach ($players as $player) {
            $this->postJson(self::REQ_URI, $player);
        }

        $requirements = [
            [
                "position" => "defender",
                "mainSkill" => "speed",
                "numberOfPlayers" => 1
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'name',
                        'position',
                        'playerSkills'
                    ]
                ]);
    }

    public function test_team_selection_with_invalid_position() {
        $requirements = [
            [
                "position" => "invalid_position",
                "mainSkill" => "speed",
                "numberOfPlayers" => 1
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Invalid value for position: invalid_position'
                ]);
    }

    public function test_team_selection_with_invalid_skill() {
        $requirements = [
            [
                "position" => "defender",
                "mainSkill" => "invalid_skill",
                "numberOfPlayers" => 1
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Invalid value for skill: invalid_skill'
                ]);
    }

    public function test_team_selection_with_duplicate_position_skill() {
        $players = [
            [
                "name" => "Defender 1",
                "position" => "defender",
                "playerSkills" => [
                    ["skill" => "speed", "value" => 80]
                ]
            ],
            [
                "name" => "Defender 2",
                "position" => "defender",
                "playerSkills" => [
                    ["skill" => "speed", "value" => 70]
                ]
            ]
        ];

        foreach ($players as $player) {
            $this->postJson(self::REQ_URI, $player);
        }

        $requirements = [
            [
                "position" => "defender",
                "mainSkill" => "speed",
                "numberOfPlayers" => 1
            ],
            [
                "position" => "defender",
                "mainSkill" => "speed",
                "numberOfPlayers" => 1
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Duplicate position and skill combination not allowed'
                ]);
    }

    public function test_insufficient_players_available() {
        $players = [
            [
                "name" => "Single Forward",
                "position" => "forward",
                "playerSkills" => [
                    ["skill" => "attack", "value" => 85]
                ]
            ]
        ];

        foreach ($players as $player) {
            $this->postJson(self::REQ_URI, $player);
        }

        $requirements = [
            [
                "position" => "forward",
                "mainSkill" => "attack",
                "numberOfPlayers" => 2
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Insufficient number of players for position: forward'
                ]);
    }
}
