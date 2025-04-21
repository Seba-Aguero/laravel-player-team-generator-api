<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Repositories\PlayerRepository;
use Illuminate\Support\Collection;

class TeamService
{
    protected $playerRepository;
    private Collection $usedPlayerIds;

    public function __construct(PlayerRepository $playerRepository) {
        $this->playerRepository = $playerRepository;
        $this->usedPlayerIds = collect();
    }

    public function selectBestTeam(array $requirements): array {
        $this->validateRequirements($requirements);
        $selectedPlayers = [];

        foreach ($requirements as $requirement) {
            $position = $requirement['position'];
            $skill = $requirement['mainSkill'];
            $numberOfPlayers = $requirement['numberOfPlayers'];

            $players = $this->playerRepository->getPlayersByPositionAndSkill($position, $skill);

            // If no players with the desired skill, get all players with the desired position
            if ($players->isEmpty()) {
                $players = $this->playerRepository->getPlayersByPosition($position);
            }

            // Filter out players already used
            $players = $players->reject(fn($player) => $this->usedPlayerIds->contains($player->id));

            if ($players->count() < $numberOfPlayers) {
                throw new ValidationException("Insufficient number of players for position: $position");
            }

            // Order by highest skill value
            $players = $players->sortByDesc(fn($player) => $player->playerSkills->max('value'));

            // Select the best players
            $selectedForThisRequirement = $players->take($numberOfPlayers);

            $this->usedPlayerIds = $this->usedPlayerIds->merge($selectedForThisRequirement->pluck('id'));

            // Format and add to the selected players
            $selectedPlayers = array_merge(
                $selectedPlayers,
                $this->formatPlayers($selectedForThisRequirement)
            );
        }

        return $selectedPlayers;
    }

    private function validateRequirements(array $requirements): void {
        $usedCombinations = [];

        foreach ($requirements as $requirement) {
            if (!PlayerPosition::tryFrom($requirement['position'])) {
                throw new ValidationException("Invalid value for position: {$requirement['position']}");
            }

            if (!PlayerSkill::tryFrom($requirement['mainSkill'])) {
                throw new ValidationException("Invalid value for skill: {$requirement['mainSkill']}");
            }

            $combination = $requirement['position'] . '-' . $requirement['mainSkill'];
            if (in_array($combination, $usedCombinations)) {
                throw new ValidationException("Duplicate position and skill combination not allowed");
            }
            $usedCombinations[] = $combination;
        }
    }

    private function formatPlayers(Collection $players): array {
        return $players->map(function ($player) {
            return [
                'name' => $player->name,
                'position' => $player->position,
                'playerSkills' => $player->playerSkills->map(function ($skill) {
                    return [
                        'skill' => $skill->skill,
                        'value' => $skill->value
                    ];
                })->toArray()
            ];
        })->toArray();
    }
}
