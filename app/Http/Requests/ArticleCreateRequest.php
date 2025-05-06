<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ArticleCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'author']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?\']+$/',
                'unique:articles,title',
            ],
            'content' => [
                'required',
                'string',
                'max:65535',
                'regex:/^(?!.*(?:https?:\/\/|www\.))[\w\s\-\.,:!?\']+$/'
            ],
            'category' => 'required|integer',
        ];
    }
}
