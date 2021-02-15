<?php

namespace App\Http\Requests;

use App\Rules\ISBN;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => ['required', 'min:10', new ISBN],
            'published_at' => 'required|date_format:Y-m-d',
            'status' => 'required|in:CHECKED_OUT,AVAILABLE'
        ];
    }
}
