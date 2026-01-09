<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create demo student user
        User::create([
            'name' => 'Kasun Perera',
            'email' => 'student@universityhostel.lk',
            'password' => Hash::make('student123'),
            'phone' => '0771234567',
            'address' => 'No. 45, Galle Road, Colombo 03, Sri Lanka',
            'nic' => '199712345678',
            'university' => 'University of Colombo',
            'faculty' => 'Faculty of Science',
            'student_id' => 'CS/2020/001',
            'year_of_study' => 3,
            'gender' => 'male',
            'emergency_contact_name' => 'Sunil Perera',
            'emergency_contact_phone' => '0719876543',
            'email_verified_at' => now(),
        ]);

        // Create additional sample students
        User::create([
            'name' => 'Nimesha Silva',
            'email' => 'nimesha.silva@universityhostel.lk',
            'password' => Hash::make('password123'),
            'phone' => '0762345678',
            'address' => 'No. 23, Kandy Road, Peradeniya, Sri Lanka',
            'nic' => '199823456789',
            'university' => 'University of Peradeniya',
            'faculty' => 'Faculty of Engineering',
            'student_id' => 'ENG/2019/025',
            'year_of_study' => 4,
            'gender' => 'female',
            'emergency_contact_name' => 'Chandani Silva',
            'emergency_contact_phone' => '0708765432',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sahan Fernando',
            'email' => 'sahan.fernando@universityhostel.lk',
            'password' => Hash::make('password123'),
            'phone' => '0753456789',
            'address' => 'No. 67, High Level Road, Nugegoda, Sri Lanka',
            'nic' => '199934567890',
            'university' => 'University of Sri Jayewardenepura',
            'faculty' => 'Faculty of Management Studies',
            'student_id' => 'MGT/2021/089',
            'year_of_study' => 2,
            'gender' => 'male',
            'emergency_contact_name' => 'Priyantha Fernando',
            'emergency_contact_phone' => '0717654321',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Thilini Rajapaksha',
            'email' => 'thilini.rajapaksha@universityhostel.lk',
            'password' => Hash::make('password123'),
            'phone' => '0744567890',
            'address' => 'No. 12, Temple Road, Kelaniya, Sri Lanka',
            'nic' => '200045678901',
            'university' => 'University of Kelaniya',
            'faculty' => 'Faculty of Social Sciences',
            'student_id' => 'SS/2020/156',
            'year_of_study' => 3,
            'gender' => 'female',
            'emergency_contact_name' => 'Kumari Rajapaksha',
            'emergency_contact_phone' => '0726543210',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Amila Wickramasinghe',
            'email' => 'amila.wickramasinghe@universityhostel.lk',
            'password' => Hash::make('password123'),
            'phone' => '0735678901',
            'address' => 'No. 89, Galle Road, Moratuwa, Sri Lanka',
            'nic' => '199856789012',
            'university' => 'University of Moratuwa',
            'faculty' => 'Faculty of Information Technology',
            'student_id' => 'IT/2018/204',
            'year_of_study' => 5,
            'gender' => 'male',
            'emergency_contact_name' => 'Lalith Wickramasinghe',
            'emergency_contact_phone' => '0715432109',
            'email_verified_at' => now(),
        ]);

        // Assign student role to all users
        $users = User::all();
        foreach ($users as $user) {
            $user->assignRole('student');
        }
    }
}