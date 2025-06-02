<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        $rules = [
            'department_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:100'],
            'user' => ['sometimes', 'string', 'nullable', 'max:100'],
            'group_id' => ['sometimes', 'integer', 'max:100'],
            'ip' => ['required', 'string','ipv4'],
            'mac' => ['required', 'string', 'regex:#^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$#']

        ];

        if ($this->isMethod('post')) {
            $rules['ip'][] = 'unique:hosts';
            $rules['mac'][] = 'unique:hosts';
        } else {
            
            $rules['ip'][] = Rule::unique('hosts')->ignore($this->host->id);
            $rules['mac'][] = Rule::unique('hosts')->ignore($this->host->id);
        }

        return $rules;

    }
}
