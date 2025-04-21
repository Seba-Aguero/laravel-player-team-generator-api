<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class TeamProcessRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            '*.position' => ['required', 'string', 'in:' . implode(',', PlayerPosition::values())],
            '*.mainSkill' => ['required', 'string', 'in:' . implode(',', PlayerSkill::values())],
            '*.numberOfPlayers' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function failedValidation(Validator $validator) {
        $errors = $validator->errors();

        foreach ($this->all() as $index => $requirement) {
            if (isset($requirement['position']) && $errors->has("$index.position")) {
                throw new ValidationException("Invalid value for position: {$requirement['position']}");
            }

            if (isset($requirement['mainSkill']) && $errors->has("$index.mainSkill")) {
                throw new ValidationException("Invalid value for skill: {$requirement['mainSkill']}");
            }

            if (isset($requirement['numberOfPlayers']) && $errors->has("$index.numberOfPlayers")) {
                throw new ValidationException("Number of players must be at least 1");
            }
        }

        parent::failedValidation($validator);
    }
}
