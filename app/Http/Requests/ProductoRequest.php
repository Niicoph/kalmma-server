<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
    $rules = [
        'name' => 'required|string|max:40',
        'description' => 'required|string|max:255',
        'SKU' => 'required|string|max:15',
        'espacio' => 'required|string|max:40',
        'dimensiones' => 'required|string|max:40',
        'categoria_id' => 'required|exists:categorias,id',
    ];

    if ($this->isMethod('put') || $this->isMethod('patch')) {
        $rules['name'] = 'sometimes|string|max:40';
        $rules['description'] = 'sometimes|string|max:255';
        $rules['SKU'] = 'sometimes|string|max:15';
        $rules['espacio'] = 'sometimes|string|max:40';
        $rules['dimensiones'] = 'sometimes|string|max:40';
        $rules['categoria_id'] = 'sometimes|exists:categorias,id';
    }

    return $rules;
}

    public function messages() {
        return [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no debe exceder los 40 caracteres',
            'description.required' => 'La descripción es requerida',
            'description.max' => 'La descripción no debe exceder los 255 caracteres',
            'SKU.required' => 'El SKU es requerido',
            'SKU.max' => 'El SKU no debe exceder los 15 caracteres',
            'espacio.required' => 'El espacio es requerido',
            'espacio.max' => 'El espacio no debe exceder los 40 caracteres',
            'dimensiones.required' => 'Las dimensiones son requeridas',
            'dimensiones.max' => 'Las dimensiones no deben exceder los 40 caracteres',
            'categoria_id.required' => 'La categoría es requerida',
        ];
    }
}
