<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserDetail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    public function ajax_get_users(Request $request): JsonResponse
    {
        $users = User::with('userDetail', 'skills')->latest()->get();
        $users = UserResource::collection($users);

        return response()->json([
            'status' => ResponseAlias::HTTP_OK,
            'statusText' => 'Success',
            'data' => $users
        ]);
    }

    public function ajax_user_store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required',
            'skills' => 'required|array'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => ResponseAlias::HTTP_NOT_ACCEPTABLE,
                'statusText' => 'failed',
                'message' => 'Validation error',
                'errors' => $validator->getMessageBag()->getMessages()
            ]);
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make(12345678)
            ]);

            if (empty($user)){
                throw new Exception('Could not create user');
            }

            if ($request->hasFile('image')) {

            $file_path = Storage::disk('public')->put('user_images', $request->image);
            $userDetail = $user->userDetail()->create([
                'image_path' => $file_path,
                'gender' => !empty($request->get('gender')) ? $request->get('gender') : UserDetail::Gender['Male']
            ]);
        } else {
                $userDetail = $user->userDetail()->create([
                    'gender' => !empty($request->get('gender')) ? $request->get('gender') : UserDetail::Gender['Male']
                ]);
            }
            if (empty($userDetail)) {
                throw new Exception('Could not create user detail');
            }

            $user->skills()->sync($request->skills);

            DB::commit();
            $user = new UserResource($user->load('userDetail', 'skills'));

            return response()->json([
                'status' => ResponseAlias::HTTP_OK,
                'statusText' => 'failed',
                'message' => 'Store successful',
                'data' => $user
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => ResponseAlias::HTTP_BAD_REQUEST,
                'statusText' => 'failed',
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function ajax_user_update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required',
            'skills' => 'required|array'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => ResponseAlias::HTTP_NOT_ACCEPTABLE,
                'statusText' => 'failed',
                'message' => 'Validation error',
                'errors' => $validator->getMessageBag()->getMessages()
            ]);
        }

        try {
            DB::beginTransaction();
            $user = User::where('id', $id)->first();
            if (empty($user)){
                throw new Exception('User not found');
            }
            $userU = $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email')
            ]);

            if (empty($userU)){
                throw new Exception('Could not update user');
            }

            if ($request->hasFile('image')) {

                $file_path = Storage::disk('public')->put('user_images', $request->image);
                $userDetail = $user->userDetail()->update([
                    'image_path' => $file_path,
                    'gender' => !empty($request->get('gender')) ? $request->get('gender') : UserDetail::Gender['Male']
                ]);
            } else {
                $userDetail = $user->userDetail()->update([
                    'gender' => !empty($request->get('gender')) ? $request->get('gender') : UserDetail::Gender['Male']
                ]);
            }
            if (empty($userDetail)) {
                throw new Exception('Could not update user detail');
            }

            $user->skills()->sync($request->skills);

            DB::commit();
            $user = $user->fresh();
            $user = new UserResource($user->load('userDetail', 'skills'));

            return response()->json([
                'status' => ResponseAlias::HTTP_OK,
                'statusText' => 'success',
                'message' => 'Updated successful',
                'data' => $user
            ]);

        } catch (Exception $exception){
            DB::rollBack();
            return response()->json([
                'status' => ResponseAlias::HTTP_BAD_REQUEST,
                'statusText' => 'failed',
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function ajax_user_delete($id): JsonResponse
    {
        try {
            $user = User::where('id', $id)->first();
            if (empty($user)){
                throw new Exception('User not found');
            }
            $user->delete();
            return response()->json([
                'status' => ResponseAlias::HTTP_OK,
                'statusText' => 'success',
                'message' => 'Deleted successful',
                'data' => ''
            ]);

        } catch (Exception $exception){
            return response()->json([
                'status' => ResponseAlias::HTTP_BAD_REQUEST,
                'statusText' => 'failed',
                'message' => $exception->getMessage()
            ]);
        }
    }

}
