<?php

namespace App\Services;

use App\Repositories\PlayerRepository;
use App\Exceptions\ValidationException;
use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Models\Player;
use Illuminate\Support\Collection;

class PlayerService
{
    public function __construct(
        private PlayerRepository $playerRepository
    ) {}

    public function getAllPlayers(): Collection {
        return $this->playerRepository->getAll();
    }

    public function getPlayerById(int $id): Player {
        $player = $this->playerRepository->findById($id);

        if (!$player) {
            throw new ValidationException("Player not found");
        }

        return $player;
    }

    public function createPlayer(array $data): Player {
        $this->validatePlayerData($data);
        return $this->playerRepository->create($data);
    }

    public function updatePlayer($id, array $data): Player {
        $this->validatePlayerData($data);
        $player = $this->getPlayerById($id);
        return $this->playerRepository->update($player, $data);
    }

    public function deletePlayer($id): bool {
        $player = $this->getPlayerById($id);
        return $this->playerRepository->delete($player);
    }

    public function validatePlayerData(array $data): void {
        if (!PlayerPosition::tryFrom($data['position'])) {
            throw new ValidationException("Invalid value for position: {$data['position']}");
        }

        if (empty($data['playerSkills'])) {
            throw new ValidationException("At least one skill is required");
        }

        foreach ($data['playerSkills'] as $skill) {
            if (!PlayerSkill::tryFrom($skill['skill'])) {
                throw new ValidationException("Invalid value for skill: {$skill['skill']}");
            }

            if ($skill['value'] < 0 || $skill['value'] > 100) {
                throw new ValidationException("Skill value must be between 0 and 100");
            }
        }
    }
}
