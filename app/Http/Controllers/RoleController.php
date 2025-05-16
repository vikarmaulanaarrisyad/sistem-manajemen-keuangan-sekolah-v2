<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $permissionGroups = PermissionGroup::with('permissions')->get();

        return view('admin.user-management.role.index', compact('permissionGroups'));
    }

    public function data()
    {
        $query = Role::all();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                $aksi = '';
                $user = Auth::user();

                $aksi .= '<button onclick="detailDataRole(`' . route('role.detail', $query->id) . '`)" class="btn btn-sm mr-1 btn-info"><i class="fas fa-eye"></i></button>';

                $aksi .= '<button onclick="editDataRole(`' . route('role.edit', $query->id) . '`)" class="btn btn-sm mr-1 btn-primary"><i class="fas fa-pencil-alt"></i></button>';

                $aksi .= '<button onclick="deleteDataRole(`' . route('role.destroy', $query->id) . '`, `' . $query->name . '`)" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>';

                return $aksi;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id', // Check if each permission ID exists
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Maaf inputan yang anda masukan salah, silahkan periksa kembali dan coba lagi'], 422);
        }

        try {
            DB::beginTransaction();

            // Create the role
            $role = Role::create(['name' => $request->name]);

            // Sync permissions only if they exist for the 'web' guard
            $role->syncPermissions(Permission::where('guard_name', 'web')->whereIn('id', $request->permission_ids)->pluck('id')->toArray());

            DB::commit();

            return response()->json(['message' => 'Role berhasil disimpan', 'data' => $role], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function detail(Role $role)
    {
        $role->load('permissions');
        return response()->json(['data' => $role]);
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        return response()->json(['data' => $role]);
    }

    public function update(Request $request, Role $role)
    {
        $rules = [
            'name' => 'required|unique:roles,name,' . $role->id, // Ubah menjadi $role->id
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id', // Check if each permission ID exists
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi'], 422);
        }

        $data = [
            'name' => $request->name,
        ];

        try {
            DB::beginTransaction();

            $role->update($data);

            // Sync permissions only if they exist for the 'web' guard
            $role->syncPermissions(Permission::where('guard_name', 'web')->whereIn('id', $request->permission_ids)->pluck('id')->toArray());

            DB::commit();

            return response()->json(['message' => 'Role berhasil disimpan', 'data' => $role], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Role $role)
    {
        $user = Auth::user();
        $user->hasAllRoles(Role::all());
        if ($user->hasRole($role->name)) {
            return response()->json(['message' => 'Maaf, Anda tidak memiliki izin untuk menghapus role ini'], 403);
        }
        $role->syncPermissions();
        $role->delete();

        return response()->json(['message' => 'Role berhasil dihapus'], 200);
    }
}
