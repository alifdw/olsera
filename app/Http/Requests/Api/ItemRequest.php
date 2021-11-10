<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest; 

class ItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama' => 'required|max:1',
        ];
    }

    public function messages()

    {
        return [
            'nama.required' => 'Nama Item Harus Di Isi',
            'nama.max' => 'Tidak Boleh Leibh dari 1 karakter',
        ];
    }
}
