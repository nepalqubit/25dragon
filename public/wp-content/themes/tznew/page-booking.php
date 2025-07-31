<?php
/**
 * Template Name: Booking Page
 *
 * @package TZnew
 * @version 1.0.0
 */

get_header();
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-8">
                    <div class="w-24 h-24 mx-auto mb-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-4xl"></i>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold mb-6">
                        <?php esc_html_e('Book Your Adventure', 'tznew'); ?>
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                        <?php esc_html_e('Ready to embark on an unforgettable journey? Fill out our booking form and let us create the perfect adventure for you.', 'tznew'); ?>
                    </p>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-200 mb-2">24/7</div>
                        <div class="text-blue-100"><?php esc_html_e('Customer Support', 'tznew'); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-200 mb-2">1000+</div>
                        <div class="text-blue-100"><?php esc_html_e('Happy Travelers', 'tznew'); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-200 mb-2">15+</div>
                        <div class="text-blue-100"><?php esc_html_e('Years Experience', 'tznew'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white bg-opacity-10 rounded-full"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white bg-opacity-10 rounded-full"></div>
        </div>
    </section>

    <!-- Booking Process Steps -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?php esc_html_e('Simple Booking Process', 'tznew'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('Follow these easy steps to book your dream adventure', 'tznew'); ?>
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                            1
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php esc_html_e('Fill Form', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Complete the booking form with your travel details', 'tznew'); ?></p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-green-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                            2
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php esc_html_e('Get Quote', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Receive a detailed quote within 24 hours', 'tznew'); ?></p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                            3
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php esc_html_e('Confirm', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Review and confirm your booking details', 'tznew'); ?></p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-orange-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                            4
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php esc_html_e('Adventure!', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Embark on your unforgettable journey', 'tznew'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <?php get_template_part('template-parts/booking-form'); ?>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?php printf(esc_html__('Why Choose %s?', 'tznew'), esc_html(tznew_get_company_name())); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('We are committed to providing exceptional travel experiences', 'tznew'); ?>
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-12 h-12 mb-4 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3"><?php esc_html_e('Safety First', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Your safety is our top priority. We follow strict safety protocols and have experienced guides.', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-12 h-12 mb-4 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3"><?php esc_html_e('Expert Guides', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Our local guides have extensive knowledge and years of experience in the region.', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-12 h-12 mb-4 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cog text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3"><?php esc_html_e('Customizable', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Every trip can be customized to match your preferences and fitness level.', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-12 h-12 mb-4 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3"><?php esc_html_e('Best Value', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Competitive pricing with no hidden costs. What you see is what you pay.', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-12 h-12 mb-4 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-headset text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3"><?php esc_html_e('24/7 Support', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Round-the-clock customer support before, during, and after your trip.', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-12 h-12 mb-4 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-certificate text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3"><?php esc_html_e('Licensed & Insured', 'tznew'); ?></h3>
                        <p class="text-gray-600"><?php esc_html_e('Fully licensed tour operator with comprehensive insurance coverage.', 'tznew'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?php esc_html_e('What Our Travelers Say', 'tznew'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('Read testimonials from our satisfied customers', 'tznew'); ?>
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 italic">"Amazing experience! The team was professional and the trek was well-organized. Highly recommended!"</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                JS
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">John Smith</div>
                                <div class="text-sm text-gray-500">Everest Base Camp Trek</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 italic">"Perfect organization and incredible views. The guides were knowledgeable and friendly throughout the journey."</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                MJ
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">Maria Johnson</div>
                                <div class="text-sm text-gray-500">Annapurna Circuit</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 italic">"Exceeded all expectations! From booking to completion, everything was handled professionally."</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                DL
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">David Lee</div>
                                <div class="text-sm text-gray-500">Manaslu Circuit Trek</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>