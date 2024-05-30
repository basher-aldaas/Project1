<?php

namespace App\Http\Requests\Course;

use App\Http\Responses\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateCourseRequest extends FormRequest
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
    //make validation for create course for special subject
    public function rules(): array
    {
        return [
            'subject_id' =>['required'],
            'name' =>['required'],
            'content' =>['required'],
<<<<<<< HEAD
            'poster' =>['image'],
            'requirements' =>['required'],
            'price' =>['required'],
=======
<<<<<<< HEAD
            'poster' =>['required'],
            'hour' =>['required'],
            'requirements' =>['required'],
            'price' =>['required'],
            //'valuation' => []
=======
            'poster' =>['image'],
            'requirements' =>['required'],
            'price' =>['required'],
>>>>>>> 5f4ddeb85994744d46e3bca82b42359cff2435b1
>>>>>>> 39c884d2eaa72acbef786d005209749c741d1ed1
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, Response::Validation([],$validator->errors()));
    }
}
