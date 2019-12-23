<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'site_title' => 'required|string|max:255',
            'site_description' => 'required|string|max:1000',
            'google_analytics_code' => 'nullable|max:1000',
            'site_logo' => 'nullable|image',
            'site_favicon' => 'nullable|image'
        ];
    }
}
