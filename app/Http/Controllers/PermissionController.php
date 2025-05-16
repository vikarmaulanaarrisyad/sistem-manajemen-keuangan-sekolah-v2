<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissionGroups = PermissionGroup::all();
        return view('admin.user-management.permission.index', compact('permissionGroups'));
    }

    public function data()
    {
        $query = Permission::all();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('permission_group', function ($query) {
                return $query->permission_group->name ?? '';
            })
            ->addColumn('action', function ($query) {
                $aksi = '';
                $user = Auth::user();

                $aksi .= '
                    <button onclick="detailDataPermission(`' . route('permission.detail', $query->id) . '`)" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                    ';

                $aksi .= '
                    <button onclick="editDataPermission(`' . route('permission.edit', $query->id) . '`)" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></button>
                    ';

                $aksi .= '
                    <button onclick="deleteDataPermission(`' . route('permission.destroy', $query->id) . '`, `' . $query->name . '`)" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                    ';

                return $aksi;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:permissions,name,NULL,id,permission_group_id,' . $request->permission_group_id,
            'permission_group_id' => 'required|exists:permission_groups,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Maaf inputan yang anda masukan salah, silahkan periksa kembali dan coba lagi'], 422);
        }

        try {
            DB::beginTransaction();

            $permissionGroup = PermissionGroup::findOrFail($request->permission_group_id);

            $permissions = Permission::create(['name' => $request->name, 'permission_group_id' => $permissionGroup->id]);

            DB::commit();

            return response()->json(['message' => 'Permission berhasil ditambahkan', 'data' => $permissions], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function detail(Permission $permission)
    {
        return response()->json(['data' => $permission]);
    }

    public function edit(Permission $permission)
    {
        return response()->json(['data' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $rules = [
            'name' => 'required|unique:Permissions,name,' . $permission->id, // Ubah menjadi $role->id
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi'], 422);
        }

        $data = [
            'name' => $request->name,
        ];

        $permission->update($data);

        return response()->json(['message' => 'Permission berhasil diupdate', 'data' => $permission], 200);
    }


    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json(['message' => 'Permission berhasil dihapus'], 200);
    }
}
