<?php

namespace Starmoozie\LaravelMenuPermission\app\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

use Starmoozie\LaravelMenuPermission\app\Models\Permission;

class PermissionRequest extends FormRequest
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
        return [
            'name' => [
                'max:20',
                'required',
                $this->checkMethod() 
            ]
        ];
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

    private function checkMethod()
    {
        $unique_permission = Rule::unique(Permission::class);

        switch ($this->method()) {
            case 'PUT':
                return $unique_permission->ignore(request()->id);

            default:
                return $unique_permission;
        }
    }
}
