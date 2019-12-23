<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
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
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique('members')->ignore(Auth::id())
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('members')->ignore(Auth::id())
            ],
            'website' => 'nullable|url',
            'phone_number' => 'nullable|phone:AUTO',
            'bio' => 'nullable|max:400',
            'avatar' => 'sometimes|image|mimes:jpg,jpeg,png|max:20000'
        ];
    }
}
