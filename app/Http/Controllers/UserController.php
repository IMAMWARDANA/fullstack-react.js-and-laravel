<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);

            return response()->json([
                'message' => 'Berhasil Menambahkan User',
                'user' => $user
            ], 201);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Database error occurred while creating user',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while creating the user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        return response()->json([
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $user->fill([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ])->save();

            return response()->json([
                'message' => 'Berhasil memperbarui data user'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat memperbarui data user'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        try {
            $user->delete();
            return response()->json([
                'message' => 'Berhasil menghapus data user'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat menghapus data user'
            ], 500);
        }
    }
}
