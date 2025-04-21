<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerDeleteTest extends PlayerControllerBaseTest
{
    public function test_can_delete_player_with_valid_token() {
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

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SkFabTZibXE1aE14ckpQUUxHc2dnQ2RzdlFRTTM2NFE2cGI4d3RQNjZmdEFITmdBQkE='
        ])->deleteJson(self::REQ_URI . $playerId);

        $response->assertStatus(204);
    }

    public function test_cannot_delete_player_without_token() {
        $response = $this->deleteJson(self::REQ_URI . '1');

        $response->assertStatus(401);
    }
}
