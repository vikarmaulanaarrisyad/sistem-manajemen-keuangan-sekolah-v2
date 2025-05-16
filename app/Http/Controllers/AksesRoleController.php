<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AksesRoleController extends Controller
{
    public function index()
    {
        return view('admin.user-management.aksesrole.index');
    }

    public function data()
    {
        $query = Role::all();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return '
                    <button onclick="dataAksesRole(`' . route('aksesrole.edit', $query->id) . '`, `' . $query->id . '`)" class="btn btn-sm btn-primary"><i class="fas fa-user-lock"></i></button>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function edit(Request $request) {}
}
