<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about page
     */
    public function index()
    {
        // Sample stats data for the about page
        $stats = [
            'established_year' => '2018',
            'total_students' => '1,200',
            'total_hostels' => '25',
            'success_rate' => '98.5%',
            'cities_covered' => 15,
            'universities_partnered' => 8,
            'total_bookings' => 950,
            'satisfaction_rate' => '96%'
        ];

        return view('about', compact('stats'));
    }
}