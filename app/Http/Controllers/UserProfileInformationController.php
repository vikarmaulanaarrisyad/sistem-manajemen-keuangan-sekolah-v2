<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileInformationController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.show', [
            'request' => $request,
            'user' => $request->user(),
        ]);
    }
}
