<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class UserController extends BaseController
{
    /**
     * @throws ServerErrorException
     */
    public function profile(): JsonResponse
    {
        try {
            return response()->json([
                'status' => true,
                'user' => UserResource::make(Auth::user()),
            ]);
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
            db::beginTransaction();
            $user = UserService::updateUser($validated);
            db::commit();

            return response()->json([
                'status' => true,
                'user' => UserResource::make($user),
            ]);
        } catch (Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }

    }
}
