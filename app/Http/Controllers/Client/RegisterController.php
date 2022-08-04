<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use A2Workspace\SocialEntry\SocialEntry;
use A2Workspace\SocialEntry\AccessTokenPayload;
use A2Workspace\SocialEntry\Concerns\ValidatesUserModels;
use A2Workspace\SocialEntry\Http\Requests\AccessTokenRequest;
use A2Workspace\SocialEntry\Http\Controllers\AbstractGrantController;

class RegisterController extends AbstractGrantController
{
    use ValidatesUserModels;

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if ($request->has('access_token')) {
            // Super calls, then jump to self::completeRequestFromToken()
            return parent::__invoke(AccessTokenRequest::createFrom($request));
        }

        $newUser = $this->createUserFromRequest($request);

        return new UserResource($newUser);
    }

    /**
     * Create a new user record from given form request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User
     */
    protected function createUserFromRequest(Request $request): User
    {
        $inputs = $request->validate($this->rules(), $this->messages());

        return User::create([
            'username' => $inputs['username'],
            'display_name' => $inputs['nickname'],
            'password' => bcrypt($inputs['password']),
        ]);
    }

    /**
     * @return array
     */
    protected function rules(): array
    {
        return [
            'username' => 'bail|required|alpha_dash|min:3|max:200|unique:users',
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

    /**
     * {@inheritDoc}
     */
    protected function completeRequestFromToken(Request $request, AccessTokenPayload $accessTokenPayload)
    {
        $this->assertValidUserAuthorized($request);

        $isAlreadyConnected = SocialEntry::identifiers()->exists(
            $accessTokenPayload->identifier,
            $accessTokenPayload->provider,
            $accessTokenPayload->local_user_type,
        );

        if ($isAlreadyConnected) {
            abort(400, 'The social account is already connected to a different user');
        }

        $newUser = $this->createUserFromRequest($request);

        $identifier = SocialEntry::identifiers()->newIdentifierFor(
            $newUser,
            $accessTokenPayload->identifier,
            $accessTokenPayload->provider,
        );

        $identifier->save();

        return new UserResource($newUser);
    }
}
