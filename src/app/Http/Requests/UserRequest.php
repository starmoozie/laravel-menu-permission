<?php

namespace Starmoozie\LaravelMenuPermission\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use Starmoozie\LaravelMenuPermission\app\Models\User;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return starmoozie_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'PUT':
                $id = request()->id;
                return [
                    'name' => [
                        'max:50',
                        'required',
                        'regex:/^[a-z A-Z]+$/'
                    ],
                    'email' => [
                        'max:50',
                        'required',
                        'email',
                        Rule::unique(User::class)->ignore($id)
                    ],
                    'mobile' => [
                        'required',
                        'regex:/(08)[0-9]{6,15}/',
                        Rule::unique(User::class)->ignore($id)
                    ],
                    'password' => 'confirmed',
                    'role' => 'required'
                ];
            
            default:

                return [
                    'name' => [
                        'max:50',
                        'required',
                        'regex:/^[a-z A-Z]+$/'
                    ],
                    'email' => [
                        'max:50',
                        'required',
                        'email',
                        Rule::unique(User::class)
                    ],
                    'mobile' => [
                        'required',
                        'regex:/(08)[0-9]{6,15}/',
                        Rule::unique(User::class)
                    ],
                    'password' => 'required|confirmed',
                    'role' => 'required'
                ];
        }
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
