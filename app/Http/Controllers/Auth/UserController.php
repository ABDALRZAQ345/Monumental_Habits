<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\TimeZoneRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Responses\UserProfileResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class UserController extends BaseController
{
    /**
     * @throws ServerErrorException
     */
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @throws ServerErrorException
     */
    public function profile(): JsonResponse
    {
        try {
            $user = Auth::user();

            return UserProfileResponse::response($user, $this->userService);

        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user = UserService::updateUser($validated);

            return response()->json([
                'status' => true,
                'user' => UserResource::make($user),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function timezone(TimeZoneRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $user = Auth::user();
            $user->update([
                'timezone' => $validated['timezone'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'timezone updated successfully',
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
