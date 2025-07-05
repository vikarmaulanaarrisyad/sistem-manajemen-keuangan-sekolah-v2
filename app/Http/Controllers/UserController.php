<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user-management.user.index');
    }

    public function data()
    {
        $query = User::orderBy('id', 'DESC')->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('users.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('users.destroy', $q->id) . '`, `' . $q->nama . '`)" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Maaf inputan yang anda masukan salah, silahkan periksa kembali dan coba lagi'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $roles = Role::find($request->roles);

            $user->assignRole($roles);

            DB::commit();

            return response()->json([
                'message' => 'User berhasil disimpan',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function detail(Request $request, User $users)
    {
        $users->load(['roles']);
        return response()->json([
            'data' => $users
        ]);
    }

    public function edit(Request $request, User $users)
    {
        $users->load(['roles']);
        return response()->json([
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::findOrfail($id);

        $user->load(['roles']);
        return response()->json([
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrfail($id);

        // Pastikan roles adalah sebuah array
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Maaf inputan yang anda masukan salah, silahkan periksa kembali dan coba lagi'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'email' => $request->email,
            ];

            $user->update($data);

            // Lanjutkan dengan proses update jika roles adalah array
            $roles = Role::find($request->roles);

            $user->syncRoles($roles);

            DB::commit();

            return response()->json([
                'message' => 'User berhasil disimpan',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrfail($id);
        $siswa = Siswa::where('user_id', $user->id)->first();
        $guru = Guru::where('user_id', $user->id)->first();

        $user->delete();
        $siswa->delete();
        $guru->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ], 200);
    }

    public function roleSearch(Request $request)
    {
        $keyword = request()->get('q');

        $result = Role::where('name', "LIKE", "%$keyword%")
            ->get();

        return $result;
    }
}
