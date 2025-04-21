<?php

namespace App\Models;

use App\Enums\PlayerSkill as PlayerSkillEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property PlayerSkillEnum $skill
 * @property int $value
 * @property int $player_id
 * @property Player $player
 */
class PlayerSkill extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'skill',
        'value',
        'player_id'
    ];

    protected $casts = [
        'skill' => PlayerSkillEnum::class,
        'value' => 'integer'
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
