<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\EmailValidatorService;
use App\Services\PasswordValidatorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class UserController extends Controller
{

    protected $emailValidator;
    protected $passwordValidator;

    public function __construct(EmailValidatorService $emailValidator, PasswordValidatorService $passwordValidator)
    {
        $this->emailValidator = $emailValidator;
        $this->passwordValidator = $passwordValidator;
    }

    public function store(StoreUserRequest $request): JsonResponse 
    {
        try
        {
            $this->emailValidator->validate($request->input('email'));
            $this->passwordValidator->validate($request->input('password'));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        }
        catch(InvalidArgumentException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => "Error while creating admin: "] . $e->getMessage(), 500);
        }
    }

    public function update(StoreUserRequest $request, $id): JsonResponse
    {
        try
        {
            $this->emailValidator->validate($request->input('email'));
            $this->passwordValidator->validate($request->input('password'));

            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            $user->update($request->only([
                'name',
                'email',
                'password'
            ]));

            return response()->json($user, 200);

        }
        catch (InvalidArgumentException $e) 
        {
            return response()->json(['error' => $e->getMessage()], 422);
        } 
        catch (Exception $e) 
        {
            return response()->json(['error' => 'Error updating admin.'], 500);
        }
    }

    public function show(Request $request): JsonResponse 
    {
        try
        {
            return response()->json($request->user());
        }
        catch (Exception $e) 
        {
            return response()->json(['error' => 'Error getting admin.'], 500);
        }
    }

    public function destroy($id): JsonResponse 
    {
        try
        {
            $user = User::find($id);

            if(!$user)
            {
                return response()->json(['error' => 'User not found.'], 404);
            }

            $user->delete();
            return response()->json(null, 204);
        }
        catch (Exception $e) 
        {
            return response()->json(['error' => 'Error getting admin.'], 500);
        }
    }
}
