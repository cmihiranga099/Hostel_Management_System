<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - University Hostel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'light-blue': '#87CEEB',
                        'light-orange': '#FFB347',
                        'primary-blue': '#4A90E2',
                        'primary-orange': '#FF8C42'
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4A90E2 0%, #FF8C42 100%);
        }
        .rainbow-gradient {
            background: linear-gradient(135deg, #4A90E2, #87CEEB, #FFB347, #FF8C42);
            background-size: 300% 300%;
            animation: rainbow 3s ease infinite;
        }
        @keyframes rainbow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .form-section {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(135, 206, 235, 0.3);
        }
        .form-section:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(74, 144, 226, 0.4);
            border-color: rgba(74, 144, 226, 0.6);
        }
        .progress-bar {
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(90deg, #4A90E2, #87CEEB, #FFB347, #FF8C42);
        }
        .upload-zone {
            border: 3px dashed #87CEEB;
            transition: all 0.4s ease;
            background: linear-gradient(45deg, rgba(135, 206, 235, 0.1), rgba(255, 179, 71, 0.1));
        }
        .upload-zone:hover {
            border-color: #4A90E2;
            background: linear-gradient(45deg, rgba(74, 144, 226, 0.2), rgba(135, 206, 235, 0.2));
            transform: scale(1.05);
        }
        .upload-zone.dragover {
            border-color: #FF8C42;
            background: linear-gradient(45deg, rgba(255, 140, 66, 0.2), rgba(255, 179, 71, 0.2));
            transform: scale(1.08);
        }
        .input-focus {
            transition: all 0.3s ease;
            border-color: #87CEEB;
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(74, 144, 226, 0.3);
            border-color: #4A90E2;
        }
        .btn-glow {
            position: relative;
            overflow: hidden;
        }
        .btn-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        .btn-glow:hover::before {
            left: 100%;
        }
        .status-icon {
            animation: pulse 2s infinite;
        }
        .floating-animation {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(135, 206, 235, 0.3);
        }
        .section-complete {
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(135, 206, 235, 0.1));
        }
        .section-incomplete {
            background: linear-gradient(135deg, rgba(255, 179, 71, 0.1), rgba(255, 140, 66, 0.1));
        }
        .section-missing {
            background: linear-gradient(135deg, rgba(255, 140, 66, 0.15), rgba(255, 179, 71, 0.15));
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body class="min-h-screen" style="background: linear-gradient(135deg, rgba(135, 206, 235, 0.1) 0%, rgba(255, 179, 71, 0.1) 100%)">
    <!-- Header -->
    <header class="gradient-bg text-white py-6 shadow-2xl">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="floating-animation">
                        <i class="fas fa-university text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold tracking-wide">University Hostel Management</h1>
                </div>
                <nav class="flex items-center space-x-4">
                    <a href="/student/dashboard" class="hover:text-light-blue transition-all duration-300 transform hover:scale-105 px-4 py-2 rounded-full bg-white bg-opacity-20 backdrop-blur">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <!-- Success/Error Messages -->
        <div id="message-container"></div>

        <!-- Profile Completion Progress -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8 mb-8 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-center mb-6">
                <div class="floating-animation">
                    <i class="fas fa-user-circle text-6xl text-primary-blue mb-4"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-center bg-gradient-to-r from-primary-blue to-primary-orange bg-clip-text text-transparent mb-6">Complete Your Profile</h2>
            <div class="flex items-center justify-between mb-4">
                <span class="text-lg font-semibold text-primary-blue">Profile Completion</span>
                <span class="text-xl font-bold text-primary-orange px-4 py-2 rounded-full bg-light-orange bg-opacity-20" id="completion-percentage">65%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                <div class="progress-bar h-4 rounded-full shadow-lg" style="width: 65%" id="completion-bar"></div>
            </div>
            <p class="text-center text-primary-blue mt-4 font-medium">Complete all sections to unlock premium features</p>
        </div>

        <form id="profile-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="csrf-token">
            <input type="hidden" name="_method" value="PATCH">

            <!-- Basic Information Section -->
            <div class="form-section glass-effect section-complete rounded-2xl shadow-2xl p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-primary-blue bg-opacity-20 p-3 rounded-full mr-4 status-icon">
                        <i class="fas fa-check text-primary-blue text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-primary-blue">Basic Information</h3>
                    <span class="ml-auto text-primary-blue font-bold px-4 py-2 rounded-full bg-primary-blue bg-opacity-20">Complete</span>
                </div>

                <!-- Profile Picture Upload -->
                <div class="mb-8">
                    <label class="block text-lg font-semibold text-primary-blue mb-4">Profile Picture</label>
                    <div class="flex items-center space-x-8">
                        <div class="shrink-0">
                            <img class="h-24 w-24 object-cover rounded-full border-4 shadow-xl transform hover:scale-110 transition-all duration-300" 
                                 style="border-color: #4A90E2"
                                 src="https://via.placeholder.com/150/4A90E2/ffffff?text=Profile" 
                                 alt="Current profile photo" id="profile-preview">
                        </div>
                        <div class="upload-zone p-6 rounded-2xl cursor-pointer flex-1" onclick="document.getElementById('avatar').click()">
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-primary-blue mb-3"></i>
                                <p class="text-primary-blue font-semibold">Click to upload or drag and drop</p>
                                <p class="text-primary-orange text-sm mt-2">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-lg font-semibold text-primary-blue mb-3">First Name *</label>
                        <input type="text" id="first_name" name="first_name" value="John" required
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-blue focus:ring-opacity-30 text-lg">
                    </div>
                    <div>
                        <label for="last_name" class="block text-lg font-semibold text-primary-blue mb-3">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" value="Doe" required
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-blue focus:ring-opacity-30 text-lg">
                    </div>
                    <div>
                        <label for="email" class="block text-lg font-semibold text-primary-blue mb-3">Email Address *</label>
                        <input type="email" id="email" name="email" value="john.doe@university.edu" required
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-blue focus:ring-opacity-30 text-lg">
                    </div>
                    <div>
                        <label for="phone" class="block text-lg font-semibold text-primary-blue mb-3">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" value="+94 77 123 4567" required
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-blue focus:ring-opacity-30 text-lg">
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-lg font-semibold text-primary-blue mb-3">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="2000-05-15"
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-blue focus:ring-opacity-30 text-lg">
                    </div>
                    <div>
                        <label for="gender" class="block text-lg font-semibold text-primary-blue mb-3">Gender</label>
                        <select id="gender" name="gender"
                                class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-blue focus:ring-opacity-30 text-lg">
                            <option value="">Select Gender</option>
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="address" class="block text-lg font-semibold text-primary-blue mb-3">Home Address</label>
                    <textarea id="address" name="address" rows="3"
                              class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-blue focus:ring-opacity-30 text-lg"
                              placeholder="Enter your complete home address">123 Main Street, Colombo 03, Sri Lanka</textarea>
                </div>
            </div>

            <!-- University Details Section -->
            <div class="form-section glass-effect section-incomplete rounded-2xl shadow-2xl p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-light-orange bg-opacity-30 p-3 rounded-full mr-4 status-icon">
                        <i class="fas fa-exclamation-triangle text-primary-orange text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-primary-orange">University Details</h3>
                    <span class="ml-auto text-primary-orange font-bold px-4 py-2 rounded-full bg-light-orange bg-opacity-30">Incomplete</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="university" class="block text-lg font-semibold text-primary-orange mb-3">University *</label>
                        <select id="university" name="university" required
                                class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-light-orange">
                            <option value="">Select University</option>
                            <option value="university_of_colombo">University of Colombo</option>
                            <option value="university_of_peradeniya">University of Peradeniya</option>
                            <option value="university_of_moratuwa">University of Moratuwa</option>
                            <option value="university_of_kelaniya">University of Kelaniya</option>
                            <option value="university_of_sri_jayewardenepura">University of Sri Jayewardenepura</option>
                        </select>
                    </div>
                    <div>
                        <label for="student_id" class="block text-lg font-semibold text-primary-orange mb-3">Student ID *</label>
                        <input type="text" id="student_id" name="student_id" value="" placeholder="e.g., CS/2023/001" required
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-light-orange">
                    </div>
                    <div>
                        <label for="faculty" class="block text-lg font-semibold text-primary-orange mb-3">Faculty</label>
                        <select id="faculty" name="faculty"
                                class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-light-orange">
                            <option value="">Select Faculty</option>
                            <option value="science">Faculty of Science</option>
                            <option value="engineering">Faculty of Engineering</option>
                            <option value="medicine">Faculty of Medicine</option>
                            <option value="arts">Faculty of Arts</option>
                            <option value="management">Faculty of Management</option>
                            <option value="law">Faculty of Law</option>
                        </select>
                    </div>
                    <div>
                        <label for="degree_program" class="block text-lg font-semibold text-primary-orange mb-3">Degree Program</label>
                        <input type="text" id="degree_program" name="degree_program" value="" placeholder="e.g., Computer Science"
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-light-orange">
                    </div>
                    <div>
                        <label for="academic_year" class="block text-lg font-semibold text-primary-orange mb-3">Academic Year</label>
                        <select id="academic_year" name="academic_year"
                                class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-light-orange">
                            <option value="">Select Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                            <option value="5">5th Year</option>
                        </select>
                    </div>
                    <div>
                        <label for="expected_graduation" class="block text-lg font-semibold text-primary-orange mb-3">Expected Graduation</label>
                        <input type="month" id="expected_graduation" name="expected_graduation" value=""
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-light-orange">
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Section -->
            <div class="form-section glass-effect section-missing rounded-2xl shadow-2xl p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-primary-orange bg-opacity-30 p-3 rounded-full mr-4 status-icon">
                        <i class="fas fa-times text-primary-orange text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-primary-orange">Emergency Contact</h3>
                    <span class="ml-auto text-primary-orange font-bold px-4 py-2 rounded-full bg-primary-orange bg-opacity-30">Missing</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="block text-lg font-semibold text-primary-orange mb-3">Contact Name *</label>
                        <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="" placeholder="Full name" required
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-primary-orange">
                    </div>
                    <div>
                        <label for="emergency_contact_relationship" class="block text-lg font-semibold text-primary-orange mb-3">Relationship *</label>
                        <select id="emergency_contact_relationship" name="emergency_contact_relationship" required
                                class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-primary-orange">
                            <option value="">Select Relationship</option>
                            <option value="parent">Parent</option>
                            <option value="guardian">Guardian</option>
                            <option value="sibling">Sibling</option>
                            <option value="spouse">Spouse</option>
                            <option value="relative">Relative</option>
                            <option value="friend">Friend</option>
                        </select>
                    </div>
                    <div>
                        <label for="emergency_contact_phone" class="block text-lg font-semibold text-primary-orange mb-3">Phone Number *</label>
                        <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="" placeholder="+94 77 123 4567" required
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-primary-orange">
                    </div>
                    <div>
                        <label for="emergency_contact_email" class="block text-lg font-semibent text-primary-orange mb-3">Email Address</label>
                        <input type="email" id="emergency_contact_email" name="emergency_contact_email" value="" placeholder="email@example.com"
                               class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-primary-orange">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="emergency_contact_address" class="block text-lg font-semibold text-primary-orange mb-3">Address</label>
                    <textarea id="emergency_contact_address" name="emergency_contact_address" rows="3"
                              class="input-focus w-full px-4 py-3 border-2 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-orange focus:ring-opacity-30 text-lg border-primary-orange"
                              placeholder="Emergency contact's address"></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-end">
                <button type="button" onclick="window.history.back()" 
                        class="btn-glow px-8 py-4 border-2 border-primary-blue rounded-xl shadow-lg bg-white text-primary-blue font-bold text-lg hover:bg-primary-blue hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" 
                        class="btn-glow px-8 py-4 gradient-bg text-white font-bold text-lg rounded-xl shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105 hover:rotate-1">
                    <i class="fas fa-save mr-2"></i>Save Profile
                </button>
            </div>
        </form>
    </div>

    <script>
        // Set CSRF token
        document.addEventListener('DOMContentLoaded', function() {
            // Get CSRF token from meta tag or create one
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 'demo-token';
            document.getElementById('csrf-token').value = csrfToken;
            
            // Initialize other functionality
            updateProgress();
            createFloatingParticles();
            initializeScrollAnimations();
        });

        // Image preview functionality
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
                updateProgress();
            }
        }

        // Drag and drop functionality
        const uploadZone = document.querySelector('.upload-zone');
        
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('avatar').files = files;
                previewImage(document.getElementById('avatar'));
            }
        });

        // Progress calculation
        function updateProgress() {
            const totalFields = 13;
            let completedFields = 0;

            // Check basic info
            if (document.getElementById('first_name').value) completedFields++;
            if (document.getElementById('last_name').value) completedFields++;
            if (document.getElementById('email').value) completedFields++;
            if (document.getElementById('phone').value) completedFields++;
            if (document.getElementById('date_of_birth').value) completedFields++;
            if (document.getElementById('address').value) completedFields++;

            // Check university details
            if (document.getElementById('university').value) completedFields++;
            if (document.getElementById('student_id').value) completedFields++;
            if (document.getElementById('faculty').value) completedFields++;
            if (document.getElementById('degree_program').value) completedFields++;

            // Check emergency contact
            if (document.getElementById('emergency_contact_name').value) completedFields++;
            if (document.getElementById('emergency_contact_relationship').value) completedFields++;
            if (document.getElementById('emergency_contact_phone').value) completedFields++;

            // Check profile picture
            if (document.getElementById('avatar').files.length > 0) completedFields++;

            const percentage = Math.round((completedFields / totalFields) * 100);
            document.getElementById('completion-percentage').textContent = percentage + '%';
            document.getElementById('completion-bar').style.width = percentage + '%';

            updateSectionStatus(completedFields, totalFields);
        }

        function updateSectionStatus(completed, total) {
            const basicFields = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'address'];
            const basicCompleted = basicFields.filter(field => document.getElementById(field).value).length;
            
            const uniFields = ['university', 'student_id', 'faculty', 'degree_program'];
            const uniCompleted = uniFields.filter(field => document.getElementById(field).value).length;
            
            const emergencyFields = ['emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_phone'];
            const emergencyCompleted = emergencyFields.filter(field => document.getElementById(field).value).length;
            
            updateSectionVisual(0, basicCompleted, basicFields.length);
            updateSectionVisual(1, uniCompleted, uniFields.length);
            updateSectionVisual(2, emergencyCompleted, emergencyFields.length);
        }

        function updateSectionVisual(sectionIndex, completed, total) {
            const sections = document.querySelectorAll('.form-section');
            const section = sections[sectionIndex];
            const statusSpan = section.querySelector('span');
            const icon = section.querySelector('i');
            const iconContainer = section.querySelector('div');
            
            if (completed === total) {
                statusSpan.textContent = 'Complete';
                statusSpan.className = 'ml-auto text-primary-blue font-bold px-4 py-2 rounded-full bg-primary-blue bg-opacity-20';
                icon.className = 'fas fa-check text-primary-blue text-xl';
                iconContainer.className = 'bg-primary-blue bg-opacity-20 p-3 rounded-full mr-4 status-icon';
                section.className = section.className.replace(/section-(incomplete|missing)/, 'section-complete');
            } else if (completed > 0) {
                statusSpan.textContent = 'Incomplete';
                statusSpan.className = 'ml-auto text-primary-orange font-bold px-4 py-2 rounded-full bg-light-orange bg-opacity-30';
                icon.className = 'fas fa-exclamation-triangle text-primary-orange text-xl';
                iconContainer.className = 'bg-light-orange bg-opacity-30 p-3 rounded-full mr-4 status-icon';
                section.className = section.className.replace(/section-(complete|missing)/, 'section-incomplete');
            } else {
                statusSpan.textContent = 'Missing';
                statusSpan.className = 'ml-auto text-primary-orange font-bold px-4 py-2 rounded-full bg-primary-orange bg-opacity-30';
                icon.className = 'fas fa-times text-primary-orange text-xl';
                iconContainer.className = 'bg-primary-orange bg-opacity-30 p-3 rounded-full mr-4 status-icon';
                section.className = section.className.replace(/section-(complete|incomplete)/, 'section-missing');
            }
        }

        // Show message function
        function showMessage(message, type = 'success') {
            const messageContainer = document.getElementById('message-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            messageContainer.innerHTML = `
                <div class="alert ${alertClass} flex items-center">
                    <i class="fas ${icon} mr-3"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                messageContainer.innerHTML = '';
            }, 5000);
        }

        // Real-time progress updates
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        });

        // Enhanced form submission with proper Laravel integration
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving Profile...';
            submitBtn.disabled = true;
            submitBtn.classList.add('animate-pulse');

            // Prepare form data
            const formData = new FormData(this);
            
            // For demo purposes - in real Laravel app, this would submit to your actual route
            // fetch('/student/profile', {
            //     method: 'POST',
            //     body: formData,
            //     headers: {
            //         'X-CSRF-TOKEN': document.getElementById('csrf-token').value,
            //         'X-Requested-With': 'XMLHttpRequest'
            //     }
            // })
            
            // Demo simulation
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Profile Saved!';
                submitBtn.classList.remove('animate-pulse');
                submitBtn.classList.add('bg-green-500');
                
                // Show success message
                showMessage('Profile updated successfully! Your information has been saved to the database.', 'success');
                
                // Demo: Log form data to console
                console.log('Form data that would be sent to database:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }
                
                // Reset button after delay
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('bg-green-500');
                }, 3000);
                
            }, 2000);
        });

        // Enhanced input focus effects
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
                this.style.boxShadow = '0 15px 35px -5px rgba(74, 144, 226, 0.4)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0px) scale(1)';
                this.style.boxShadow = '';
            });
        });

        // Add floating particles animation
        function createFloatingParticles() {
            const particleCount = 15;
            const colors = ['#4A90E2', '#87CEEB', '#FFB347', '#FF8C42'];
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'fixed';
                particle.style.width = Math.random() * 6 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                particle.style.borderRadius = '50%';
                particle.style.left = Math.random() * window.innerWidth + 'px';
                particle.style.top = window.innerHeight + 'px';
                particle.style.pointerEvents = 'none';
                particle.style.opacity = '0.6';
                particle.style.zIndex = '-1';
                
                document.body.appendChild(particle);
                
                const duration = Math.random() * 10000 + 10000;
                const drift = (Math.random() - 0.5) * 200;
                
                particle.animate([
                    { transform: `translateY(0px) translateX(0px)`, opacity: 0.6 },
                    { transform: `translateY(-${window.innerHeight + 100}px) translateX(${drift}px)`, opacity: 0 }
                ], {
                    duration: duration,
                    iterations: Infinity,
                    delay: Math.random() * 5000
                });
            }
        }

        // Initialize scroll animations
        function initializeScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0px)';
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.form-section').forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(50px)';
                section.style.transition = `all 0.6s ease ${index * 0.1}s`;
                observer.observe(section);
            });
        }
    </script>
</body>
</html>