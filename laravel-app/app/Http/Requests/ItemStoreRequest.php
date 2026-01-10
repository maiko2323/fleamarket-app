<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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
        'item_img' => ['required', 'image', 'max:2048'],
        'name' => ['required', 'string', 'max:255'],
        'brand' => ['nullable', 'string', 'max:255'],
        'categories' => ['required', 'array'],
        'description' => ['required', 'string'],
        'price' => ['required', 'integer', 'min:1'],
        'condition' => ['required'],
        ];
    }
}
