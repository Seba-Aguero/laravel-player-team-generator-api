<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Exceptions\ValidationException;
use \Illuminate\Contracts\Validation\Validator;

class PlayerStoreRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'in:' . implode(',', PlayerPosition::values())],
            'playerSkills' => ['required', 'array', 'min:1'],
            'playerSkills.*.skill' => ['required', 'string', 'in:' . implode(',', PlayerSkill::values())],
            'playerSkills.*.value' => ['required', 'integer', 'between:0,100'],
        ];
    }

    protected function failedValidation(Validator $validator) {
        $errors = $validator->errors();

        if ($errors->has('playerSkills')) {
            throw new ValidationException('At least one skill is required');
        }

        if ($errors->has('position')) {
            throw new ValidationException("Invalid value for position: {$this->input('position')}");
        }

        // Iterate over playerSkills to check for specific errors
        foreach ($this->input('playerSkills', []) as $index => $skill) {
            if ($errors->has("playerSkills.{$index}.skill")) {
                throw new ValidationException("Invalid value for skill: {$skill['skill']}");
            }
            if ($errors->has("playerSkills.{$index}.value")) {
                throw new ValidationException("Skill value must be between 0 and 100");
            }
        }

        parent::failedValidation($validator);
    }
}
