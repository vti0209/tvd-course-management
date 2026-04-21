<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled in the controller
        $user = Auth::user();
        return $user && $user->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:active,blocked',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
