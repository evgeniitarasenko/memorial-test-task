<?php

namespace App\Http\Requests;

use App\Enums\UserPermission;
use Illuminate\Foundation\Http\FormRequest;

class InvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipients' => ['required', 'array'],
            'recipients.*.email' => ['required', 'string', 'email', 'distinct'],
            'recipients.*.name' => ['required', 'string', 'max:50'],
            'recipients.*.role' => ['required', 'string', 'in:' . implode(',', UserPermission::toValues())],
            'message' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'recipients.*.email' => 'email',
            'recipients.*.name' => 'name',
            'recipients.*.role' => 'role',
        ];
    }

}
