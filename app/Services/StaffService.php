<?php

namespace App\Services;

use App\Http\Resources\StaffResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaffService
{
    public static function listStaffs()
    {
        try {
            $staffs = User::with('hotel')->where('role', User::ROLE_STAFF)->get();
            if ($staffs->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data staff masih kosong.',
                    'data' => [],
                    'errors' => null
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data staff berhasil diambil',
                'data' => StaffResource::collection($staffs),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public static function create(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'hotel_id' => 'required|exists:hotels,id'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sesuai.',
                'data' => null,
                'errors' => $validatedData->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => User::ROLE_STAFF,
                'hotel_id' => $request->hotel_id
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Akun staff berhasil ditambahkan.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'hotel' => $user->hotel->name
                    ]
                ],
                'errors' => null
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Akun staff gagal ditambahkan.',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public static function update(Request $request, $staffId)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'hotel_id' => 'required|exists:hotels,id'
        ]);

        DB::beginTransaction();
        try {
            $staff = User::where('role', User::ROLE_STAFF)->findOrFail($staffId);
            $staff->update($request->only(['name', 'email', 'hotel_id']));
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Akun staff berhasil diperbarui.',
                'data' => [
                    'user' => [
                        'id' => $staff->id,
                        'name' => $staff->name,
                        'email' => $staff->email,
                        'role' => $staff->role,
                        'hotel' => $staff->hotel->name
                    ]
                ],
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Akun staff gagal diperbarui.',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public static function delete(Request $request, $staffId)
    {
        DB::beginTransaction();
        try {
            User::where('role', User::ROLE_STAFF)->findOrFail($staffId)->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Akun staff berhasil dihapus.',
                'data' => [],
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Akun staff gagal dihapus.',
                'data' => [],
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
