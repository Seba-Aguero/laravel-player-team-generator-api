<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerUpdateTest extends PlayerControllerBaseTest
{
    public function test_can_update_player() {
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

        $createResponse = $this->postJson(self::REQ_URI, $player);
        $playerId = $createResponse->json('id');

        $updateData = [
            "name" => "Updated Player",
            "position" => "midfielder",
            "playerSkills" => [
                [
                    "skill" => "speed",
                    "value" => 85
                ]
            ]
        ];

        $response = $this->putJson(self::REQ_URI . $playerId, $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'name' => 'Updated Player',
                    'position' => 'midfielder'
                ]);
    }

    public function test_cannot_update_player_with_invalid_data() {
        $updateData = [
            "name" => "Updated Player",
            "position" => "invalid_position",
            "playerSkills" => [
                [
                    "skill" => "speed",
                    "value" => 85
                ]
            ]
        ];

        $response = $this->putJson(self::REQ_URI . '1', $updateData);

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Invalid value for position: invalid_position'
                ]);
    }
}
