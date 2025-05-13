<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'foto' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        if ($validator->fails()) {
            back()
                ->withInput()
                ->withErrors($validator->errors());
            return;
        }

        if (isset($input['foto'])) {
            // Check if $user['foto'] is not null or empty before checking existence
            if (!empty($user['foto']) && Storage::disk('public')->exists($user['foto'])) {
                Storage::disk('public')->delete($user['foto']);
            }

            $input['foto'] = upload('user', $input['foto'], 'user');
        }

        $user->update($input);

        Session::flash('message', 'Profil berhasil diperbarui');
        Session::flash('success', true);
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
