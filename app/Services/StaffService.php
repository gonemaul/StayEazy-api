<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffService
{
    public static function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => User::ROLE_STAFF
            ]);
            DB::commit();
            return response()->json(['message' => 'Akun Staff berhasil dibuat.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Akun Staff gagal dibuat.'], 500);
        }
    }
    public static function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            DB::commit();

            return response()->json(['message' => 'Akun Staff berhasil diperbarui.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Akun Staff gagal diperbarui.'], 500);
        }
    }
    public static function delete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            User::where('role', 'staff')->findOrFail($id)->delete();
            DB::commit();
            return response()->json(['message' => 'Akun Staff berhasil dihapus.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Akun Staff gagal dihapus.'], 500);
        }
    }
}
