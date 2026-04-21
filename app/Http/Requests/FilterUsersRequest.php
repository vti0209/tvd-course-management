<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FilterUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
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
            'role' => 'nullable|string|in:user,provider,admin',
            'status' => 'nullable|string|in:active,blocked',
            'search' => 'nullable|string|max:100',
            'page' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'role.in' => 'Vai trò không hợp lệ.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'search.max' => 'Tìm kiếm không được vượt quá 100 ký tự.',
            'page.integer' => 'Trang phải là một số nguyên.',
        ];
    }
}
