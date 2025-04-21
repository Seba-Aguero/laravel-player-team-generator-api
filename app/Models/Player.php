<?php

namespace App\Models;

use App\Enums\PlayerPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $name
 * @property PlayerPosition $position
 * @property Collection<int, PlayerSkill> $playerSkills
 */
class Player extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['playerSkills'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['name', 'position'];

    protected $casts = [
        'position' => PlayerPosition::class
    ];

    public function playerSkills(): HasMany {
        return $this->hasMany(PlayerSkill::class);
    }
}
