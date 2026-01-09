<?php

namespace Database\Seeders;

use App\Models\HostelPackage;
use Illuminate\Database\Seeder;

class HostelPackageSeeder extends Seeder
{
    public function run()
    {
        // Boys Hostels
        HostelPackage::create([
            'name' => 'Colombo Boys Hostel - Premium',
            'type' => 'boys',
            'description' => 'Premium accommodation facility for male students in the heart of Colombo with modern amenities and excellent connectivity to major universities.',
            'price' => 25000.00,
            'duration' => 'monthly',
            'capacity' => 100,
            'available_slots' => 85,
            'facilities' => [
                'Air Conditioned Rooms',
                'Free Wi-Fi',
                '3 Meals Per Day',
                'Laundry Service',
                'Study Room',
                'Gym Facility',
                'Security 24/7',
                'Parking Space',
                'Common Room with TV',
                'Water Purifier'
            ],
            'rules' => [
                'No smoking in rooms',
                'Visitors allowed until 8 PM',
                'Maintain cleanliness',
                'No loud music after 10 PM',
                'ID card required for entry',
                'No alcohol allowed',
                'Respect other residents'
            ],
            'image' => 'hostels/boys-premium-colombo.jpg',
            'is_active' => true,
        ]);

        HostelPackage::create([
            'name' => 'Kandy Boys Hostel - Standard',
            'type' => 'boys',
            'description' => 'Comfortable and affordable accommodation for male students near University of Peradeniya with beautiful hill country views.',
            'price' => 18000.00,
            'duration' => 'monthly',
            'capacity' => 80,
            'available_slots' => 65,
            'facilities' => [
                'Fan Rooms',
                'Free Wi-Fi',
                '3 Meals Per Day',
                'Shared Bathrooms',
                'Study Area',
                'Sports Ground',
                'Security 24/7',
                'Common Kitchen',
                'Reading Room',
                'Hot Water Facility'
            ],
            'rules' => [
                'No smoking anywhere',
                'Visitors allowed until 7 PM',
                'Keep common areas clean',
                'Quiet hours: 10 PM - 6 AM',
                'Register all visitors',
                'No outside food after 9 PM',
                'Attend monthly meetings'
            ],
            'image' => 'hostels/boys-standard-kandy.jpg',
            'is_active' => true,
        ]);

        HostelPackage::create([
            'name' => 'Moratuwa Boys Hostel - Engineering',
            'type' => 'boys',
            'description' => 'Specialized accommodation for engineering students near University of Moratuwa with tech-friendly facilities.',
            'price' => 22000.00,
            'duration' => 'monthly',
            'capacity' => 120,
            'available_slots' => 95,
            'facilities' => [
                'AC Study Rooms',
                'High-Speed Internet',
                '3 Meals Per Day',
                'Attached Bathrooms',
                'Computer Lab',
                'Project Workshop Area',
                'Security 24/7',
                'Parking for Bikes',
                'Recreation Room',
                'Backup Power'
            ],
            'rules' => [
                'No smoking in premises',
                'Visitors until 8 PM only',
                'Clean your workspace',
                'No loud activities after 10 PM',
                'Proper ID required',
                'No unauthorized modifications',
                'Respect study environment'
            ],
            'image' => 'hostels/boys-engineering-moratuwa.jpg',
            'is_active' => true,
        ]);

        // Girls Hostels
        HostelPackage::create([
            'name' => 'Colombo Girls Hostel - Premium',
            'type' => 'girls',
            'description' => 'Luxurious and secure accommodation facility for female students in Colombo with top-notch safety measures and modern amenities.',
            'price' => 28000.00,
            'duration' => 'monthly',
            'capacity' => 90,
            'available_slots' => 72,
            'facilities' => [
                'Air Conditioned Rooms',
                'Free Wi-Fi',
                '3 Meals Per Day',
                'Laundry Service',
                'Beauty Salon',
                'Fitness Center',
                'Security 24/7',
                'CCTV Monitoring',
                'Ladies Common Room',
                'Medical Facility'
            ],
            'rules' => [
                'No male visitors in rooms',
                'Visitors allowed until 7 PM',
                'Maintain personal hygiene',
                'No loud music after 9 PM',
                'Biometric entry system',
                'No smoking or alcohol',
                'Emergency contact required',
                'Respect privacy of others'
            ],
            'image' => 'hostels/girls-premium-colombo.jpg',
            'is_active' => true,
        ]);

        HostelPackage::create([
            'name' => 'Peradeniya Girls Hostel - Garden View',
            'type' => 'girls',
            'description' => 'Peaceful accommodation for female students with beautiful garden views and a homely atmosphere near University of Peradeniya.',
            'price' => 20000.00,
            'duration' => 'monthly',
            'capacity' => 70,
            'available_slots' => 58,
            'facilities' => [
                'Garden View Rooms',
                'Free Wi-Fi',
                '3 Meals Per Day',
                'Shared Kitchen',
                'Study Garden',
                'Yoga Area',
                'Security 24/7',
                'Female Guards',
                'Common Hall',
                'Prayer Room'
            ],
            'rules' => [
                'No male visitors after 6 PM',
                'Family visitors welcome',
                'Keep garden areas clean',
                'Meditation hours: 6-7 AM',
                'Register all guests',
                'No parties without permission',
                'Maintain hostel traditions',
                'Monthly cultural programs'
            ],
            'image' => 'hostels/girls-garden-peradeniya.jpg',
            'is_active' => true,
        ]);

        HostelPackage::create([
            'name' => 'Jayewardenepura Girls Hostel - Modern',
            'type' => 'girls',
            'description' => 'Modern accommodation facility for female students near University of Sri Jayewardenepura with contemporary amenities.',
            'price' => 24000.00,
            'duration' => 'monthly',
            'capacity' => 85,
            'available_slots' => 70,
            'facilities' => [
                'Modern Furnished Rooms',
                'High-Speed Wi-Fi',
                '3 Meals Per Day',
                'Personal Lockers',
                'Study Pods',
                'Indoor Games',
                'Security 24/7',
                'Elevator Access',
                'Sky Lounge',
                'Water Filtration'
            ],
            'rules' => [
                'No male visitors in rooms',
                'Visitors until 7:30 PM',
                'Use personal lockers',
                'Quiet study hours',
                'Card access required',
                'No outside food delivery',
                'Participate in hostel events',
                'Monthly room inspection'
            ],
            'image' => 'hostels/girls-modern-jayewardenepura.jpg',
            'is_active' => true,
        ]);

        // Additional Hostels for variety
        HostelPackage::create([
            'name' => 'Kelaniya Boys Hostel - Budget',
            'type' => 'boys',
            'description' => 'Affordable accommodation option for male students near University of Kelaniya with basic amenities.',
            'price' => 15000.00,
            'duration' => 'monthly',
            'capacity' => 60,
            'available_slots' => 45,
            'facilities' => [
                'Fan Rooms',
                'Free Wi-Fi',
                '2 Meals Per Day',
                'Shared Facilities',
                'Common Study Area',
                'Bicycle Parking',
                'Basic Security',
                'Common Kitchen',
                'TV Room',
                'First Aid Kit'
            ],
            'rules' => [
                'No smoking',
                'Visitors until 6 PM',
                'Clean after use',
                'No noise after 10 PM',
                'Bring your bedding',
                'Monthly dues on time',
                'Help in cleaning'
            ],
            'image' => 'hostels/boys-budget-kelaniya.jpg',
            'is_active' => true,
        ]);

        HostelPackage::create([
            'name' => 'Ruhuna Girls Hostel - Coastal',
            'type' => 'girls',
            'description' => 'Coastal accommodation for female students near University of Ruhuna with ocean breeze and peaceful environment.',
            'price' => 19000.00,
            'duration' => 'monthly',
            'capacity' => 75,
            'available_slots' => 60,
            'facilities' => [
                'Ocean View Rooms',
                'Free Wi-Fi',
                '3 Meals Per Day',
                'Beach Access',
                'Reading Nook',
                'Handicraft Center',
                'Security 24/7',
                'Female Warden',
                'Cultural Hall',
                'Herbal Garden'
            ],
            'rules' => [
                'No male visitors after sunset',
                'Beach trips in groups',
                'Respect local culture',
                'Traditional dress preferred',
                'Evening roll call',
                'No late night outings',
                'Cultural program participation'
            ],
            'image' => 'hostels/girls-coastal-ruhuna.jpg',
            'is_active' => true,
        ]);
    }
}