<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            "caption" => "nullable|string|max:1000",
            "uploads" => "required|array|min:1",
            "uploads.*" => "required|mimetypes:image/jpg,image/jpeg,image/png,image/gif,video/mp4,video/webm,video/mov,video/ogg|max:10000"
        ];
    }
}
