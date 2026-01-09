<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show(Request $request)
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validation rules
            $rules = [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'phone' => ['required', 'string', 'max:20'],
                'date_of_birth' => ['nullable', 'date'],
                'gender' => ['nullable', 'in:male,female,other'],
                'address' => ['nullable', 'string', 'max:500'],
                'university' => ['nullable', 'string', 'max:255'],
                'student_id' => ['nullable', 'string', 'max:50'],
                'faculty' => ['nullable', 'string', 'max:255'],
                'degree_program' => ['nullable', 'string', 'max:255'],
                'academic_year' => ['nullable', 'integer', 'min:1', 'max:5'],
                'expected_graduation' => ['nullable', 'date'],
                'emergency_contact_name' => ['nullable', 'string', 'max:255'],
                'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
                'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
                'emergency_contact_email' => ['nullable', 'email', 'max:255'],
                'emergency_contact_address' => ['nullable', 'string', 'max:500'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ];

            $validatedData = $request->validate($rules);

            // Handle profile image upload
            if ($request->hasFile('avatar')) {
                // Delete old profile image if exists
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                
                // Store new image
                $imagePath = $request->file('avatar')->store('profile-images', 'public');
                $validatedData['profile_image'] = $imagePath;
            }

            // Update user data
            $updateData = [
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'date_of_birth' => $validatedData['date_of_birth'] ?? null,
                'gender' => $validatedData['gender'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'university' => $validatedData['university'] ?? null,
                'student_id' => $validatedData['student_id'] ?? null,
                'faculty' => $validatedData['faculty'] ?? null,
                'degree_program' => $validatedData['degree_program'] ?? null,
                'academic_year' => $validatedData['academic_year'] ?? null,
                'expected_graduation' => $validatedData['expected_graduation'] ?? null,
                'emergency_contact_name' => $validatedData['emergency_contact_name'] ?? null,
                'emergency_contact_relationship' => $validatedData['emergency_contact_relationship'] ?? null,
                'emergency_contact_phone' => $validatedData['emergency_contact_phone'] ?? null,
                'emergency_contact_email' => $validatedData['emergency_contact_email'] ?? null,
                'emergency_contact_address' => $validatedData['emergency_contact_address'] ?? null,
                'updated_at' => now(),
            ];

            // Add profile image if uploaded
            if (isset($validatedData['profile_image'])) {
                $updateData['profile_image'] = $validatedData['profile_image'];
            }

            // Update the user record
            $updated = DB::table('users')
                ->where('id', $user->id)
                ->update($updateData);

            if ($updated) {
                // Log the successful update
                Log::info('Profile updated successfully', [
                    'user_id' => $user->id,
                    'updated_fields' => array_keys($updateData)
                ]);

                // Refresh the user model to get updated data
                $user->refresh();

                // Return success response
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Profile updated successfully!',
                        'user' => $user
                    ]);
                }

                return redirect()->back()->with('success', 'Profile updated successfully!');
            } else {
                throw new \Exception('Failed to update profile in database');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Profile update validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating your profile. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'An error occurred while updating your profile. Please try again.')
                ->withInput();
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user = Auth::user();
            
            $updated = DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($validated['password']),
                    'updated_at' => now(),
                ]);

            if ($updated) {
                Log::info('Password updated successfully', ['user_id' => $user->id]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Password updated successfully!'
                    ]);
                }

                return redirect()->back()->with('success', 'Password updated successfully!');
            } else {
                throw new \Exception('Failed to update password');
            }

        } catch (\Exception $e) {
            Log::error('Password update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update password. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            $user = Auth::user();

            // Delete old profile image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image
            $imagePath = $request->file('avatar')->store('profile-images', 'public');

            // Update user record
            $updated = DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'profile_image' => $imagePath,
                    'updated_at' => now(),
                ]);

            if ($updated) {
                Log::info('Avatar updated successfully', ['user_id' => $user->id]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Profile picture updated successfully!',
                        'avatar_url' => Storage::url($imagePath)
                    ]);
                }

                return redirect()->back()->with('success', 'Profile picture updated successfully!');
            } else {
                throw new \Exception('Failed to update avatar');
            }

        } catch (\Exception $e) {
            Log::error('Avatar update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile picture. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update profile picture. Please try again.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile image if exists
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }

    /**
     * Get profile completion status.
     */
    public function getCompletionStatus(Request $request)
    {
        $user = Auth::user();
        
        $requiredFields = [
            'name', 'email', 'phone', 'date_of_birth', 'address',
            'university', 'student_id', 'faculty', 'degree_program',
            'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_phone'
        ];
        
        $completedFields = 0;
        $totalFields = count($requiredFields);
        
        foreach ($requiredFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }
        
        // Check if profile image exists
        if ($user->profile_image) {
            $completedFields++;
            $totalFields++;
        } else {
            $totalFields++;
        }
        
        $percentage = round(($completedFields / $totalFields) * 100);
        
        return response()->json([
            'completion_percentage' => $percentage,
            'completed_fields' => $completedFields,
            'total_fields' => $totalFields,
            'missing_fields' => array_filter($requiredFields, function($field) use ($user) {
                return empty($user->$field);
            })
        ]);
    }

    /**
     * Quick profile update for essential fields.
     */
    public function quickUpdate(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'phone' => ['nullable', 'string', 'max:20'],
                'university' => ['nullable', 'string', 'max:255'],
                'student_id' => ['nullable', 'string', 'max:50'],
                'emergency_contact_name' => ['nullable', 'string', 'max:255'],
                'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            ]);

            $updateData = array_filter($validated, function($value) {
                return !is_null($value) && $value !== '';
            });

            if (!empty($updateData)) {
                $updateData['updated_at'] = now();
                
                $updated = DB::table('users')
                    ->where('id', $user->id)
                    ->update($updateData);

                if ($updated) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Profile updated successfully!'
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'No changes were made.'
            ]);

        } catch (\Exception $e) {
            Log::error('Quick profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile.'
            ], 500);
        }
    }

    /**
     * Show user settings page.
     */
    public function settings(Request $request)
    {
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update user settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_notifications' => ['boolean'],
            'sms_notifications' => ['boolean'],
            'marketing_emails' => ['boolean'],
            'language' => ['string', 'in:en,si,ta'],
            'timezone' => ['string', 'max:50'],
        ]);

        // Update settings (you might want to store these in a separate settings table)
        $updated = DB::table('users')
            ->where('id', $user->id)
            ->update([
                'settings' => json_encode($validated),
                'updated_at' => now(),
            ]);

        if ($updated) {
            return redirect()->back()->with('success', 'Settings updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update settings.');
    }
}