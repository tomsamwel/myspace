<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\User;

class UpdateUser extends FormRequest
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
		//get current user
		$user = User::findOrFail(auth()->user()->id);

        return [
			'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user),],
			'first_name' => ['nullable','string', 'max:255'],
			'last_name' => ['nullable','string', 'max:255'],
			'relation_status' => ['nullable','integer'],
			'address' => ['nullable','string', 'max:255',],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user),],
            'profile_image'     =>  'image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}
