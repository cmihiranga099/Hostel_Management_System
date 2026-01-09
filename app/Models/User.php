<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'nic',
        'university',
        'faculty',
        'student_id',
        'year_of_study',
        'academic_year',
        'degree_program',
        'expected_graduation',
        'gender',
        'date_of_birth',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'emergency_contact_email',
        'emergency_contact_address',
        'profile_image',
        'avatar',
        'settings',
        'preferences',
        'status',
        'profile_completed',
        'profile_completed_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'expected_graduation' => 'date',
        'profile_completed' => 'boolean',
        'profile_completed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'year_of_study' => 'integer',
        'settings' => 'array',
        'preferences' => 'array',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get user's bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get user's reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get user's payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get user's notifications
     */
    public function notifications()
    {
        return $this->morphMany('Illuminate\Notifications\DatabaseNotification', 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        if ($this->first_name || $this->last_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        }
        return $this->name;
    }

    /**
     * Get the user's first name
     */
    public function getFirstNameAttribute()
    {
        if (isset($this->attributes['first_name'])) {
            return $this->attributes['first_name'];
        }
        return explode(' ', $this->name)[0] ?? '';
    }

    /**
     * Get the user's last name
     */
    public function getLastNameAttribute()
    {
        if (isset($this->attributes['last_name'])) {
            return $this->attributes['last_name'];
        }
        $nameParts = explode(' ', $this->name);
        return count($nameParts) > 1 ? end($nameParts) : '';
    }

    /**
     * Get user's initials for avatar
     */
    public function getInitialsAttribute()
    {
        if ($this->first_name || $this->last_name) {
            $initials = '';
            if ($this->first_name) $initials .= strtoupper(substr($this->first_name, 0, 1));
            if ($this->last_name) $initials .= strtoupper(substr($this->last_name, 0, 1));
            return $initials ?: 'U';
        }
        
        $names = explode(' ', trim($this->name));
        $initials = '';
        
        foreach (array_slice($names, 0, 2) as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return $initials ?: 'U';
    }

    /**
     * Get user's profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        // Check for avatar field first
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
        
        // Check for profile_image field
        if ($this->profile_image) {
            if (strpos($this->profile_image, 'profile-images/') === 0) {
                return Storage::url($this->profile_image);
            }
            return Storage::url('profile-images/' . $this->profile_image);
        }
        
        // Generate avatar with initials
        $displayName = $this->full_name ?: $this->name ?: 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . 
               '&background=667eea&color=fff&size=150&bold=true';
    }

    /**
     * Get avatar URL (alias for profile_image_url)
     */
    public function getAvatarUrlAttribute()
    {
        return $this->profile_image_url;
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;
        
        $phone = $this->phone;
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            return substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
        }
        
        return $phone;
    }

    /**
     * Get user's age
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) return null;
        return $this->date_of_birth->age;
    }

    /**
     * Get formatted year of study
     */
    public function getFormattedYearOfStudyAttribute()
    {
        $year = $this->academic_year ?: $this->year_of_study;
        
        if (!$year) return null;

        $ordinals = [
            1 => '1st Year',
            2 => '2nd Year', 
            3 => '3rd Year',
            4 => '4th Year',
            5 => '5th Year',
            6 => '6th Year'
        ];

        return $ordinals[$year] ?? $year . 'th Year';
    }

    /**
     * Get academic year display (alias)
     */
    public function getAcademicYearDisplayAttribute()
    {
        return $this->formatted_year_of_study;
    }

    /**
     * Get the user's university display name
     */
    public function getUniversityDisplayNameAttribute()
    {
        $universities = [
            'university_of_colombo' => 'University of Colombo',
            'university_of_peradeniya' => 'University of Peradeniya',
            'university_of_moratuwa' => 'University of Moratuwa',
            'university_of_kelaniya' => 'University of Kelaniya',
            'university_of_sri_jayewardenepura' => 'University of Sri Jayewardenepura',
        ];

        return $universities[$this->university] ?? $this->university;
    }

    /**
     * Get the user's faculty display name
     */
    public function getFacultyDisplayNameAttribute()
    {
        $faculties = [
            'science' => 'Faculty of Science',
            'engineering' => 'Faculty of Engineering',
            'medicine' => 'Faculty of Medicine',
            'arts' => 'Faculty of Arts',
            'management' => 'Faculty of Management',
            'law' => 'Faculty of Law',
        ];

        return $faculties[$this->faculty] ?? $this->faculty;
    }

    /**
     * Get profile completion percentage
     */
    public function getProfileCompletionAttribute()
    {
        $requiredFields = [
            'name',
            'email',
            'phone',
            'date_of_birth',
            'address',
            'gender',
            'university',
            'student_id',
            'faculty',
            'degree_program',
            'academic_year',
            'emergency_contact_name',
            'emergency_contact_relationship',
            'emergency_contact_phone',
        ];
        
        $completedFields = 0;
        $totalFields = count($requiredFields);

        // Check for first_name + last_name OR name
        $hasName = ($this->first_name && $this->last_name) || $this->name;
        if ($hasName) $completedFields++;

        // Check other fields (skip 'name' since we handled it above)
        foreach (array_slice($requiredFields, 1) as $field) {
            if (!empty($this->$field)) {
                $completedFields++;
            }
        }

        // Add profile image as bonus
        if ($this->profile_image || $this->avatar) {
            $completedFields++;
            $totalFields++;
        } else {
            $totalFields++;
        }

        return round(($completedFields / $totalFields) * 100);
    }

    /**
     * Alternative method name for compatibility
     */
    public function getProfileCompletionPercentage()
    {
        return $this->profile_completion;
    }

    /**
     * Get user's total spending
     */
    public function getTotalSpendingAttribute()
    {
        try {
            return $this->payments()->where('status', 'completed')->sum('amount');
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get user's average review rating
     */
    public function getAverageReviewRatingAttribute()
    {
        try {
            return $this->reviews()->avg('rating') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // ==========================================
    // MUTATORS
    // ==========================================

    /**
     * Auto-update name field when first_name or last_name changes
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value;
        $this->updateNameField();
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = $value;
        $this->updateNameField();
    }

    /**
     * Update name field based on first_name and last_name
     */
    private function updateNameField()
    {
        if (isset($this->attributes['first_name']) || isset($this->attributes['last_name'])) {
            $firstName = $this->attributes['first_name'] ?? '';
            $lastName = $this->attributes['last_name'] ?? '';
            $this->attributes['name'] = trim($firstName . ' ' . $lastName);
        }
    }

    /**
     * Format Sri Lankan phone numbers
     */
    public function setPhoneAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['phone'] = null;
            return;
        }
        
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $value);
        
        // Handle Sri Lankan phone number formatting
        if (strlen($phone) === 9) {
            $phone = '0' . $phone; // Add leading zero
        } elseif (strlen($phone) === 12 && substr($phone, 0, 3) === '940') {
            $phone = '0' . substr($phone, 3); // Convert +94 format
        } elseif (strlen($phone) === 11 && substr($phone, 0, 2) === '94') {
            $phone = '0' . substr($phone, 2); // Convert 94 format
        }
        
        $this->attributes['phone'] = $phone;
    }

    /**
     * Format NIC number
     */
    public function setNicAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['nic'] = null;
            return;
        }
        
        $this->attributes['nic'] = strtoupper(trim(str_replace(' ', '', $value)));
    }

    /**
     * Format student ID
     */
    public function setStudentIdAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['student_id'] = null;
            return;
        }
        
        $this->attributes['student_id'] = strtoupper(trim($value));
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope to get only students
     */
    public function scopeStudents($query)
    {
        try {
            return $query->role('student');
        } catch (\Exception $e) {
            // Fallback to checking student_id if roles aren't set up
            return $query->whereNotNull('student_id');
        }
    }

    /**
     * Scope to get only active/verified users
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope to filter users by university
     */
    public function scopeByUniversity($query, $university)
    {
        return $query->where('university', $university);
    }

    /**
     * Scope to filter users by faculty
     */
    public function scopeByFaculty($query, $faculty)
    {
        return $query->where('faculty', $faculty);
    }

    /**
     * Scope to filter users by year of study
     */
    public function scopeByYearOfStudy($query, $year)
    {
        return $query->where(function ($q) use ($year) {
            $q->where('year_of_study', $year)
              ->orWhere('academic_year', $year);
        });
    }

    /**
     * Scope to get users by gender
     */
    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * Scope to get users with complete profiles
     */
    public function scopeWithCompleteProfile($query)
    {
        return $query->whereNotNull([
            'phone',
            'date_of_birth',
            'address',
            'university',
            'student_id',
            'emergency_contact_name',
            'emergency_contact_phone'
        ]);
    }

    /**
     * Scope to get users with incomplete profiles
     */
    public function scopeWithIncompleteProfile($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('phone')
              ->orWhereNull('date_of_birth')
              ->orWhereNull('address')
              ->orWhereNull('university')
              ->orWhereNull('student_id')
              ->orWhereNull('emergency_contact_name')
              ->orWhereNull('emergency_contact_phone');
        });
    }

    // ==========================================
    // UTILITY METHODS
    // ==========================================

    /**
     * Check if the user's profile is complete
     */
    public function isProfileComplete()
    {
        $requiredFields = [
            'name',
            'email',
            'phone',
            'date_of_birth',
            'address',
            'gender',
            'university',
            'student_id',
            'faculty',
            'degree_program',
            'emergency_contact_name',
            'emergency_contact_relationship',
            'emergency_contact_phone',
        ];

        // Check for first_name + last_name OR name
        $hasName = ($this->first_name && $this->last_name) || $this->name;
        if (!$hasName) return false;

        foreach (array_slice($requiredFields, 1) as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Alternative method name for compatibility
     */
    public function hasCompleteProfile()
    {
        return $this->isProfileComplete();
    }

    /**
     * Get missing profile fields
     */
    public function getMissingProfileFields()
    {
        $requiredFields = [
            'phone' => 'Phone Number',
            'date_of_birth' => 'Date of Birth',
            'address' => 'Home Address',
            'gender' => 'Gender',
            'university' => 'University',
            'student_id' => 'Student ID',
            'faculty' => 'Faculty',
            'degree_program' => 'Degree Program',
            'emergency_contact_name' => 'Emergency Contact Name',
            'emergency_contact_relationship' => 'Emergency Contact Relationship',
            'emergency_contact_phone' => 'Emergency Contact Phone',
        ];

        $missingFields = [];

        // Check name fields
        $hasName = ($this->first_name && $this->last_name) || $this->name;
        if (!$hasName) {
            $missingFields['name'] = 'Full Name';
        }

        foreach ($requiredFields as $field => $label) {
            if (empty($this->$field)) {
                $missingFields[$field] = $label;
            }
        }

        return $missingFields;
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Get user's settings with defaults
     */
    public function getSetting($key = null, $default = null)
    {
        $settings = $this->settings ?? [];
        
        $defaultSettings = [
            'email_notifications' => true,
            'sms_notifications' => true,
            'marketing_emails' => false,
            'language' => 'en',
            'timezone' => 'Asia/Colombo',
        ];

        $settings = array_merge($defaultSettings, $settings);

        if ($key === null) {
            return $settings;
        }

        return $settings[$key] ?? $default;
    }

    /**
     * Update user setting
     */
    public function updateSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        return $this->update(['settings' => $settings]);
    }

    /**
     * Generate a verification token
     */
    public function generateVerificationToken()
    {
        return hash('sha256', $this->email . now()->timestamp);
    }

    // ==========================================
    // ROLE & PERMISSION HELPERS
    // ==========================================

    /**
     * Check if user has a specific role (with fallback)
     */
    public function hasRole($role)
    {
        try {
            return parent::hasRole($role);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    /**
     * Check if user is student
     */
    public function isStudent()
    {
        return $this->hasRole('student') || (!$this->hasRole('admin') && !$this->hasRole('super-admin'));
    }

    /**
     * Check if user is hostel owner/manager
     */
    public function isHostelManager()
    {
        return $this->hasRole('hostel-manager') || $this->hasRole('hostel-owner');
    }

    // ==========================================
    // BOOKING & REVIEW HELPERS
    // ==========================================

    /**
     * Get user's active bookings
     */
    public function activeBookings()
    {
        return $this->bookings()->whereIn('status', ['confirmed', 'checked_in'])->get();
    }

    /**
     * Get user's booking history
     */
    public function bookingHistory()
    {
        return $this->bookings()->orderBy('created_at', 'desc');
    }

    /**
     * Check if user has booked a specific hostel
     */
    public function hasBookedHostel($hostelId)
    {
        return $this->bookings()
                    ->where('hostel_id', $hostelId)
                    ->whereIn('status', ['confirmed', 'checked_in', 'completed'])
                    ->exists();
    }

    // ==========================================
    // MODEL EVENTS
    // ==========================================

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Update profile completion status when user is saved
        static::saving(function ($user) {
            $user->profile_completed = $user->isProfileComplete();
        });

        // Update profile completion timestamp when profile is completed
        static::saved(function ($user) {
            if ($user->isProfileComplete() && !$user->profile_completed_at) {
                $user->update(['profile_completed_at' => now()]);
            }
        });

        // Delete avatar file when user is deleted
        static::deleting(function ($user) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
        });
    }
}