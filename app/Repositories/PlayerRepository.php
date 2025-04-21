<?php

namespace App\Repositories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PlayerRepository
{
    public function getAll(): Collection {
        return Player::with('playerSkills')->get();
    }

    public function findById($id): Player {
        return Player::with('playerSkills')->find($id);
    }

    public function create(array $data): Player {
        return DB::transaction(function() use ($data) {
            $player = Player::create([
                'name' => $data['name'],
                'position' => $data['position']
            ]);

            foreach ($data['playerSkills'] as $skill) {
                $player->playerSkills()->create($skill);
            }

            return $player->load('playerSkills');
        });
    }

    public function update(Player $player, array $data): Player {
        return DB::transaction(function() use ($player, $data) {
            $player->update([
                'name' => $data['name'],
                'position' => $data['position']
            ]);

            $player->playerSkills()->delete();
            $player->playerSkills()->createMany($data['playerSkills']);

            return $player->load('playerSkills');
        });
    }

    public function delete(Player $player): bool {
        return $player->delete();
    }

    public function getPlayersByPositionAndSkill(string $position, string $skill): Collection {
        $players = Player::where('position', $position)
            ->whereHas('playerSkills', function($query) use ($skill) {
                $query->where('skill', $skill);
            })
            ->with('playerSkills')
            ->get();

        return $players;
    }

    public function getPlayersByPosition(string $position): Collection {
        return Player::where('position', $position)
            ->with(['playerSkills' => function ($query) {
                $query->orderBy('value', 'desc');
            }])
            ->get();
    }
}
