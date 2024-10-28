<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class RoleController extends Controller
{
    public function index()
    {
        $roles = role::all();
        return response()->json($roles);
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //     ]);

    //     try {
    //         $roles = role::create([
    //             'name' => $request->name,

    //         ]);

    //         return response()->json([
    //             'message' => 'Berhasil Menambahkan Role',
    //             'role' => $roles
    //         ], 201);
    //     } catch (QueryException $e) {
    //         Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
    //         return response()->json([
    //             'message' => 'Database error occurred while creating role',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     } catch (Exception $e) {
    //         Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
    //         return response()->json([
    //             'message' => 'An error occurred while creating the role',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function show($id)
    // {
    //     $roles = role::find($id);
    //     if (!$roles) {
    //         return response()->json([
    //             'message' => 'Role not found'
    //         ], 404);
    //     }
    //     return response()->json([
    //         'role' => $roles
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $roles = role::find($id);
    //     if (!$roles) {
    //         return response()->json([
    //             'message' => 'Role not found'
    //         ], 404);
    //     }

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //     ]);

    //     try {
    //         $roles->fill([
    //             'name' => $request->name,
    //         ])->save();

    //         return response()->json([
    //             'message' => 'Berhasil memperbarui data role'
    //         ]);
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //         return response()->json([
    //             'message' => 'Ada yang tidak beres saat memperbarui data role'
    //         ], 500);
    //     }
    // }

    // public function destroy($id)
    // {
    //     $roles = role::find($id);
    //     if (!$roles) {
    //         return response()->json([
    //             'message' => 'Role not found'
    //         ], 404);
    //     }

    //     try {
    //         $roles->delete();
    //         return response()->json([
    //             'message' => 'Berhasil menghapus data role'
    //         ]);
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage());
    //         return response()->json([
    //             'message' => 'Ada yang tidak beres saat menghapus data role'
    //         ], 500);
    //     }
    // }
}
