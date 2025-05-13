<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function data()
    {
        $loggedInUserId = auth()->id(); // ID user yang sedang login

        $query = User::where('id', '!=', $loggedInUserId)->get(); // Ambil semua user kecuali yang login

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('foto', function ($q) {
                $foto = $q->foto ? Storage::url($q->foto) : asset('AdminLTE/dist/img/avatar3.png');
                return '<img src="' . $foto . '" class="img-thumbnail rounded-circle" width="50" height="50">';
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="resetPassword(' . $q->id . ')" class="btn btn-warning btn-sm">
                    <i class="fas fa-key"></i> Reset Password
                </button>
            ';
            })
            ->rawColumns(['foto', 'aksi']) // Agar HTML bisa dirender
            ->make(true);
    }


    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('123456'); // Ganti dengan password default
        $user->save();

        return response()->json(['message' => 'Password berhasil direset ke default.']);
    }
}
