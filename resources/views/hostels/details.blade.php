<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Details - University Hostel Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --primary-gradient: linear-gradient(135deg, #007bff, #0056b3);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 8px 30px rgba(0, 123, 255, 0.3);
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3') center/cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            border-radius: 20px;
            margin-bottom: 2rem;
        }

        .card-modern {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .card-header-modern {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem;
            border: none;
        }

        .card-body-modern {
            padding: 2rem;
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .gallery-item {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            height: 250px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0, 123, 255, 0.8), rgba(0, 86, 179, 0.8));
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .facility-item {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .facility-item:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-3px);
        }

        .facility-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .facility-item:hover .facility-icon {
            color: white;
        }

        .rating-stars {
            color: #ffc107;
            font-size: 1.2rem;
        }

        .price-tag {
            background: var(--primary-gradient);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .availability-meter {
            background: #e9ecef;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            position: relative;
        }

        .availability-fill {
            background: linear-gradient(45deg, #28a745, #20c997);
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .info-badge {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 0.25rem;
            display: inline-block;
        }

        .review-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .breadcrumb-modern {
            background: white;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            box-shadow: var(--card-shadow);
        }

        @media (max-width: 768px) {
            .hero-section {
                height: 250px;
            }
            
            .image-gallery {
                grid-template-columns: 1fr;
            }
            
            .card-body-modern {
                padding: 1rem;
            }
        }

        .section-divider {
            height: 2px;
            background: var(--primary-gradient);
            border-radius: 2px;
            margin: 2rem 0;
        }

        .contact-card {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            border-radius: 20px;
            padding: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .map-container {
            height: 300px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb breadcrumb-modern">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Hostels</a></li>
                <li class="breadcrumb-item active">Sunrise University Hostel</li>
            </ol>
        </nav>

        <!-- Hero Section -->
        <div class="hero-section">
            <div>
                <h1 class="display-4 fw-bold mb-3">Sunrise University Hostel</h1>
                <p class="lead">Premium accommodation for university students</p>
                <div class="rating-stars mb-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span class="ms-2 text-white">4.5/5 (127 reviews)</span>
                </div>
                <span class="price-tag">LKR 25,000 /month</span>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Image Gallery -->
                <div class="card-modern mb-4">
                    <div class="card-header-modern">
                        <h4 class="mb-0"><i class="fas fa-images me-2"></i>Photo Gallery</h4>
                    </div>
                    <div class="card-body-modern">
                        <div class="image-gallery">
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Hostel Exterior">
                                <div class="gallery-overlay">
                                    <i class="fas fa-search-plus text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Student Room">
                                <div class="gallery-overlay">
                                    <i class="fas fa-search-plus text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Common Area">
                                <div class="gallery-overlay">
                                    <i class="fas fa-search-plus text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Study Room">
                                <div class="gallery-overlay">
                                    <i class="fas fa-search-plus text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1484101403633-562f891dc89a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Dining Area">
                                <div class="gallery-overlay">
                                    <i class="fas fa-search-plus text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1571624436279-b272aff752b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Recreation Area">
                                <div class="gallery-overlay">
                                    <i class="fas fa-search-plus text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="card-modern mb-4">
                    <div class="card-header-modern">
                        <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>About This Hostel</h4>
                    </div>
                    <div class="card-body-modern">
                        <p class="lead">Welcome to Sunrise University Hostel, where comfort meets convenience in the heart of the university district.</p>
                        <p>Our hostel offers modern accommodation designed specifically for university students. With spacious rooms, excellent facilities, and a supportive community environment, we provide everything you need for a successful academic journey.</p>
                        <p>Located just 5 minutes walk from the main university campus, our hostel features state-of-the-art security systems, high-speed Wi-Fi throughout the building, and dedicated study areas to help you focus on your studies.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4 text-center">
                                <div class="facility-item">
                                    <i class="fas fa-home facility-icon"></i>
                                    <h6>120 Rooms</h6>
                                    <small>Modern & Comfortable</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="facility-item">
                                    <i class="fas fa-walking facility-icon"></i>
                                    <h6>5 Min Walk</h6>
                                    <small>To University Campus</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="facility-item">
                                    <i class="fas fa-clock facility-icon"></i>
                                    <h6>24/7 Security</h6>
                                    <small>Safe & Secure</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facilities -->
                <div class="card-modern mb-4">
                    <div class="card-header-modern">
                        <h4 class="mb-0"><i class="fas fa-star me-2"></i>Facilities & Amenities</h4>
                    </div>
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-wifi text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-1">High-Speed Wi-Fi</h6>
                                        <small class="text-muted">100 Mbps throughout the building</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-tshirt text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-1">Laundry Service</h6>
                                        <small class="text-muted">Washing & drying facilities</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-utensils text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-1">Mess Facility</h6>
                                        <small class="text-muted">Nutritious meals daily</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-book text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-1">Study Rooms</h6>
                                        <small class="text-muted">Quiet spaces for focused study</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-dumbbell text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-1">Fitness Center</h6>
                                        <small class="text-muted">Modern gym equipment</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-gamepad text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-1">Recreation Room</h6>
                                        <small class="text-muted">Games & entertainment</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rules & Regulations -->
                <div class="card-modern mb-4">
                    <div class="card-header-modern">
                        <h4 class="mb-0"><i class="fas fa-list-alt me-2"></i>Hostel Rules & Regulations</h4>
                    </div>
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Check-in: 9:00 AM onwards
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Check-out: Before 11:00 AM
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Visitors allowed until 8:00 PM
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        ID card required at all times
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        No smoking inside the building
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        No loud music after 10:00 PM
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        No alcohol or illegal substances
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        Keep common areas clean
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Reviews -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h4 class="mb-0"><i class="fas fa-comments me-2"></i>Student Reviews</h4>
                    </div>
                    <div class="card-body-modern">
                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">Sarah Johnson</h6>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <small class="text-muted">2 days ago</small>
                            </div>
                            <p class="mb-0">"Excellent hostel with great facilities. The staff is very helpful and the location is perfect for university students. Highly recommended!"</p>
                        </div>

                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">Michael Chen</h6>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                </div>
                                <small class="text-muted">1 week ago</small>
                            </div>
                            <p class="mb-0">"Good value for money. The rooms are clean and comfortable. Wi-Fi speed is excellent which is great for online classes."</p>
                        </div>

                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">Priya Patel</h6>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <small class="text-muted">2 weeks ago</small>
                            </div>
                            <p class="mb-0">"The study rooms are fantastic and really help during exam season. The mess food is also quite good with variety."</p>
                        </div>

                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary">Load More Reviews</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">

                <!-- Quick Info -->
                <div class="card-modern mb-4">
                    <div class="card-header-modern">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Info</h5>
                    </div>
                    <div class="card-body-modern">
                        <div class="mb-3">
                            <h4 class="text-primary mb-0">LKR 25,000</h4>
                            <small class="text-muted">per month</small>
                        </div>

                        <div class="mb-3">
                            <div class="info-badge">Male Hostel</div>
                            <div class="info-badge">Monthly Billing</div>
                            <div class="info-badge">AC Rooms</div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Availability</span>
                                <span class="fw-bold text-success">15 / 120 slots</span>
                            </div>
                            <div class="availability-meter">
                                <div class="availability-fill" style="width: 87.5%;"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Type:</span>
                            <strong>Male Hostel</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Capacity:</span>
                            <strong>120 Students</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Available:</span>
                            <strong class="text-success">15 Slots</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Rating:</span>
                            <strong>4.5/5 ⭐</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Distance:</span>
                            <strong>5 min walk</strong>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="contact-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-phone me-2"></i>Contact Information</h5>
                    <div class="contact-item">
                        <i class="fas fa-phone me-3"></i>
                        <div>
                            <strong>Phone:</strong><br>
                            +94 11 234 5678
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope me-3"></i>
                        <div>
                            <strong>Email:</strong><br>
                            info@sunrisehostel.lk
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt me-3"></i>
                        <div>
                            <strong>Address:</strong><br>
                            123 University Road, Colombo 07
                        </div>
                    </div>
                    <div class="d-grid mt-3">
                        <button class="btn btn-light">
                            <i class="fas fa-envelope me-2"></i>Contact Support
                        </button>
                    </div>
                </div>

                <!-- Map -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="mb-0"><i class="fas fa-map me-2"></i>Location</h5>
                    </div>
                    <div class="card-body-modern p-0">
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.798467112976!2d79.86124547499186!3d6.914742618225729!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25964a8d9c8d3%3A0x8a4d8e1f8f9d8e1f!2sUniversity%20of%20Colombo!5e0!3m2!1sen!2slk!4v1703232000000!5m2!1sen!2slk" 
                                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    
    <script>
        // Initialize gallery with lightbox
        document.addEventListener('DOMContentLoaded', function() {
            // Add lightbox attributes to gallery items
            const galleryItems = document.querySelectorAll('.gallery-item');
            galleryItems.forEach((item, index) => {
                const img = item.querySelector('img');
                const link = document.createElement('a');
                link.href = img.src;
                link.setAttribute('data-lightbox', 'hostel-gallery');
                link.setAttribute('data-title', img.alt);
                
                // Wrap image with link
                img.parentNode.insertBefore(link, img);
                link.appendChild(img);
                
                // Move overlay to the link
                const overlay = item.querySelector('.gallery-overlay');
                link.appendChild(overlay);
            });
            
            // Smooth scroll for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Animate availability meter on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const meter = entry.target.querySelector('.availability-fill');
                        if (meter) {
                            meter.style.width = '87.5%';
                        }
                    }
                });
            }, observerOptions);
            
            const availabilitySection = document.querySelector('.availability-meter').parentElement;
            observer.observe(availabilitySection);
            
            // Add hover effects to facility items
            const facilityItems = document.querySelectorAll('.facility-item');
            facilityItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.05)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
        
        // Lazy loading for images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
        
        // Add scroll-to-top button
        const scrollBtn = document.createElement('button');
        scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        scrollBtn.className = 'btn btn-primary-modern position-fixed bottom-0 end-0 m-3 rounded-circle';
        scrollBtn.style.width = '50px';
        scrollBtn.style.height = '50px';
        scrollBtn.style.zIndex = '1060';
        scrollBtn.style.display = 'none';
        
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        
        document.body.appendChild(scrollBtn);
    </script>
</body>
</html>dnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://c