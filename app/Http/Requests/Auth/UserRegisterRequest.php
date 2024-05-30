<?php

namespace App\Http\Requests\Auth;

use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserRegisterRequest extends FormRequest
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
    //make validation for register page
    public function rules(): array
    {
        return [
            'full_name' => ['required','string','min:10'],
            'email' => ['required','unique:users,email','email'],
            'phone' => ['digits:10'],
            'password' => ['required','confirmed','min:8'],
<<<<<<< HEAD
            'birthday' => ['date_format:Y-m-d'],
            'address' => [],
            'type' => [],
            'image' => [],
=======
            'birthday' => ['required','date','date_format:Y-m-d'],
            'address' => ['required'],
            'type' => [],
            'image' => 'image',
>>>>>>> 5f4ddeb85994744d46e3bca82b42359cff2435b1
        ];
    }

    //This function to get the wrong validation with my template
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, Response::Validation([],$validator->errors()));
    }


}
