<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $inputs = $this->validateRequest($request);

        $inputs['password'] = bcrypt($inputs['password']);

        $newRecord = User::create($inputs);

        return new UserResource($newRecord);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        return $this->validate($request, $this->rules(), $this->messages());
    }

    /**
     * @return array
     */
    protected function rules(): array
    {
        return [
            'username' => 'required|alpha_dash|min:3|max:50|unique:users',
            'nickname' => 'nullable|min:3|max:50',
            'password' => Password::min(6),
        ];
    }

    /**
     * @return array
     */
    protected function messages(): array
    {
        return [
            // ...
        ];
    }
}
