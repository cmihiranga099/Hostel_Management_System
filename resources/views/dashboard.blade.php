{{-- resources/views/student/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Student Dashboard - University Hostel Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Welcome Section --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 mb-8 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-16 w-16 rounded-full border-4 border-white/30 object-cover" 
                             src="{{ Auth::user()->profile_image_url ?? asset('images/default-avatar.png') }}" 
                             alt="Profile">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                        <p class="text-blue-100">{{ Auth::user()->university ?? 'Student' }} • {{ Auth::user()->student_id ?? 'ID not set' }}</p>
                    </div>
                </div>
                <div class="hidden md:block text-right">
                    <p class="text-sm text-blue-100">Today's Date</p>
                    <p class="text-lg font-semibold">{{ now()->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Quick Actions Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Quick Actions
                        </h2>
                    </div>

                    {{-- Actions List --}}
                    <div class="divide-y divide-gray-100">
                        {{-- Find Available Hostels --}}
                        <a href="{{ route('hostels') }}" 
                           class="flex items-center px-6 py-4 hover:bg-blue-50 transition-all duration-200 group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600">Find Available Hostels</p>
                                <p class="text-sm text-gray-500">Search and book hostels</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        {{-- View My Bookings --}}
                        <a href="{{ route('student.bookings') }}" 
                           class="flex items-center px-6 py-4 hover:bg-gray-50 transition-all duration-200 group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center group-hover:bg-gray-600 transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-gray-600">View My Bookings</p>
                                <p class="text-sm text-gray-500">Manage your reservations</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(isset($stats['pending_bookings']) && $stats['pending_bookings'] > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $stats['pending_bookings'] }}
                                    </span>
                                @endif
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>

                        {{-- Complete Profile --}}
                        <a href="{{ route('student.profile.edit') }}" 
                           class="flex items-center px-6 py-4 hover:bg-gray-50 transition-all duration-200 group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center group-hover:bg-gray-600 transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-gray-600">Complete Profile</p>
                                <p class="text-sm text-gray-500">Update your information</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @php
                                    $completionPercentage = 75; // Calculate based on filled fields
                                @endphp
                                <div class="w-12 h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-green-500 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $completionPercentage }}%</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>

                        {{-- Read Reviews --}}
                        <a href="{{ route('reviews') }}" 
                           class="flex items-center px-6 py-4 hover:bg-gray-50 transition-all duration-200 group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center group-hover:bg-yellow-600 transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-yellow-600">Read Reviews</p>
                                <p class="text-sm text-gray-500">Check hostel reviews</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Profile Completion Card --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Completion</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Basic Info</span>
                            <span class="text-sm font-medium text-green-600">✓ Complete</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">University Details</span>
                            <span class="text-sm font-medium {{ Auth::user()->university ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ Auth::user()->university ? '✓ Complete' : '⚠ Incomplete' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Emergency Contact</span>
                            <span class="text-sm font-medium {{ Auth::user()->emergency_contact_name ? 'text-green-600' : 'text-red-600' }}">
                                {{ Auth::user()->emergency_contact_name ? '✓ Complete' : '✗ Missing' }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('student.profile.edit') }}" 
                       class="mt-4 w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center inline-block">
                        Complete Profile
                    </a>
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Total Bookings --}}
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Active Bookings --}}
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Active Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['active_bookings'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Pending Payments --}}
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_payments'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Bookings --}}
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                            <a href="{{ route('student.bookings') }}" 
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</a>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @forelse($recentBookings ?? [] as $booking)
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <img class="h-10 w-10 rounded-lg object-cover" 
                                                     src="{{ $booking->hostelPackage->hostel->image_url ?? asset('images/hostel-placeholder.jpg') }}" 
                                                     alt="Hostel">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $booking->hostelPackage->hostel->name }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $booking->check_in_date->format('M d') }} - {{ $booking->check_out_date->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">LKR {{ number_format($booking->total_amount, 2) }}</p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $booking->booking_status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->booking_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            {{-- Edit Booking --}}
                                            @if($booking->booking_status === 'pending')
                                                <a href="{{ route('student.bookings.edit', $booking->id) }}" 
                                                   class="text-blue-600 hover:text-blue-700 p-1 rounded-full hover:bg-blue-50 transition-colors"
                                                   title="Edit Booking">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                
                                                {{-- Delete Booking --}}
                                                <form method="POST" action="{{ route('student.bookings.destroy', $booking->id) }}" 
                                                      class="inline-block" 
                                                      onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors"
                                                            title="Cancel Booking">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            {{-- View Details --}}
                                            <a href="{{ route('student.bookings.show', $booking->id) }}" 
                                               class="text-gray-600 hover:text-gray-700 p-1 rounded-full hover:bg-gray-50 transition-colors"
                                               title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by booking your first hostel.</p>
                                <div class="mt-6">
                                    <a href="{{ route('hostels') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Actions Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Payment History --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Payment History</h3>
                            <a href="{{ route('payments.history') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentPayments ?? [] as $payment)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $payment->booking->hostelPackage->hostel->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">LKR {{ number_format($payment->amount, 2) }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No payments yet</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Notifications --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                3 New
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">Booking confirmation received</p>
                                    <p class="text-xs text-gray-500">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">Payment processed successfully</p>
                                    <p class="text-xs text-gray-500">1 day ago</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">Complete your profile for better service</p>
                                    <p class="text-xs text-gray-500">3 days ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Book Modal --}}
<div id="quickBookModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Quick Book Hostel</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeQuickBookModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="quickBookForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <select name="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select City</option>
                            <option value="Colombo">Colombo</option>
                            <option value="Kandy">Kandy</option>
                            <option value="Galle">Galle</option>
                            <option value="Jaffna">Jaffna</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hostel Type</label>
                        <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Any Type</option>
                            <option value="boys">Boys Only</option>
                            <option value="girls">Girls Only</option>
                            <option value="mixed">Mixed</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Check-in</label>
                            <input type="date" name="check_in" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Check-out</label>
                            <input type="date" name="check_out" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitQuickBook()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Search Hostels
                </button>
                <button type="button" onclick="closeQuickBookModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum dates for date inputs
    const today = new Date().toISOString().split('T')[0];
    const checkInInput = document.querySelector('input[name="check_in"]');
    const checkOutInput = document.querySelector('input[name="check_out"]');
    
    if (checkInInput) {
        checkInInput.min = today;
        checkInInput.addEventListener('change', function() {
            if (checkOutInput) {
                checkOutInput.min = this.value;
                if (checkOutInput.value && checkOutInput.value <= this.value) {
                    checkOutInput.value = '';
                }
            }
        });
    }
});

// Quick Book Modal Functions
function openQuickBookModal() {
    document.getElementById('quickBookModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeQuickBookModal() {
    document.getElementById('quickBookModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function submitQuickBook() {
    const form = document.getElementById('quickBookForm');
    const formData = new FormData(form);
    
    // Build query string
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    // Redirect to hostels page with filters
    window.location.href = '{{ route("hostels") }}?' + params.toString();
}

// Close modal when clicking outside
document.getElementById('quickBookModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuickBookModal();
    }
});

// Booking Actions
function editBooking(bookingId) {
    window.location.href = `/bookings/${bookingId}/edit`;
}

function cancelBooking(bookingId, bookingReference) {
    if (confirm(`Are you sure you want to cancel booking ${bookingReference}? This action cannot be undone.`)) {
        fetch(`/bookings/${bookingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Booking cancelled successfully', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showNotification(data.error || 'Failed to cancel booking', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while cancelling the booking', 'error');
        });
    }
}

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    notification.className += ` ${colors[type] || colors.info}`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Profile completion checker
function checkProfileCompletion() {
    const user = @json(Auth::user());
    const requiredFields = ['name', 'email', 'phone', 'university', 'faculty', 'student_id', 'emergency_contact_name', 'emergency_contact_phone'];
    const completedFields = requiredFields.filter(field => user[field] && user[field].trim() !== '');
    const completionPercentage = Math.round((completedFields.length / requiredFields.length) * 100);
    
    return {
        percentage: completionPercentage,
        missingFields: requiredFields.filter(field => !user[field] || user[field].trim() === '')
    };
}

// Update profile completion on page load
document.addEventListener('DOMContentLoaded', function() {
    const completion = checkProfileCompletion();
    const progressBars = document.querySelectorAll('.w-12.h-2.bg-gray-200');
    
    progressBars.forEach(bar => {
        const fill = bar.querySelector('.h-2.bg-green-500');
        if (fill) {
            fill.style.width = completion.percentage + '%';
        }
    });
    
    // Show completion reminder if profile is incomplete
    if (completion.percentage < 80) {
        setTimeout(() => {
            showNotification(`Profile ${completion.percentage}% complete. Complete your profile for better service!`, 'warning');
        }, 3000);
    }
});

// Real-time updates (optional - requires WebSocket or polling)
function updateDashboardStats() {
    fetch('/api/dashboard-stats', {
        headers: {
            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]')?.getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        // Update stats cards
        document.querySelector('[data-stat="total-bookings"]').textContent = data.total_bookings || 0;
        document.querySelector('[data-stat="active-bookings"]').textContent = data.active_bookings || 0;
        document.querySelector('[data-stat="pending-payments"]').textContent = data.pending_payments || 0;
    })
    .catch(error => console.error('Failed to update dashboard stats:', error));
}

// Update stats every 30 seconds
setInterval(updateDashboardStats, 30000);
</script>
@endpush