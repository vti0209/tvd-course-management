<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectCourseRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'reason' => 'required|string|min:10|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'reason.required' => 'Lý do từ chối là bắt buộc.',
            'reason.min' => 'Lý do từ chối phải có ít nhất 10 ký tự.',
            'reason.max' => 'Lý do từ chối không được vượt quá 1000 ký tự.',
        ];
    }
}