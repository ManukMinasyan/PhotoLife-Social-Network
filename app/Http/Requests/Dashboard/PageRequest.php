<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
        $page = $this->route('page');
        $alisRule = Rule::unique('pages');

        if($page) {
            $alisRule->ignore($page->id);
        }

        return [
            'title' => 'required',
            'alias' => [
                'required', $alisRule
            ],
            'description' => 'required',
            'keywords' => 'required',
            'content' => 'required',
        ];
    }
}
