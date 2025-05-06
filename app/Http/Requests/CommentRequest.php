<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $commentId = optional($this->route('comment'))->id;

        return [
            'article_id' =>
            [
                'required',
                'exists:articles,id'
            ],
            'user_id' =>
            [
                'required',
                'exists:users,id',
                Rule::unique('comments')
                    ->where('article_id', $this->input('article_id'))
                    ->ignore($commentId)
            ],
            'content' => 'required|string|min:5',
        ];
    }
}
