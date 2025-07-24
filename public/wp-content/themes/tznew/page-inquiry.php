<?php
/**
 * Template Name: Inquiry Page
 *
 * @package TZnew
 * @version 1.0.0
 */

get_header();
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-to-br from-green-600 via-green-700 to-teal-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-8">
                    <div class="w-24 h-24 mx-auto mb-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-question-circle text-4xl"></i>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold mb-6">
                        <?php esc_html_e('Have Questions?', 'tznew'); ?>
                    </h1>
                    <p class="text-xl md:text-2xl text-green-100 mb-8 leading-relaxed">
                        <?php esc_html_e('We\'re here to help! Send us your questions and we\'ll get back to you with detailed information about your dream adventure.', 'tznew'); ?>
                    </p>
                </div>
                
                <!-- Contact Methods -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <div class="text-green-100"><?php esc_html_e('Email Response', 'tznew'); ?></div>
                        <div class="text-sm text-green-200 mt-1"><?php esc_html_e('Within 2 hours', 'tznew'); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-2xl"></i>
                        </div>
                        <div class="text-green-100"><?php esc_html_e('Phone Consultation', 'tznew'); ?></div>
                        <div class="text-sm text-green-200 mt-1"><?php esc_html_e('Free callback', 'tznew'); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-alt text-2xl"></i>
                        </div>
                        <div class="text-green-100"><?php esc_html_e('Detailed Itinerary', 'tznew'); ?></div>
                        <div class="text-sm text-green-200 mt-1"><?php esc_html_e('Custom proposals', 'tznew'); ?></div>
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

    <!-- FAQ Preview Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?php esc_html_e('Frequently Asked Questions', 'tznew'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('Quick answers to common questions about our services', 'tznew'); ?>
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-mountain text-green-600 mr-3"></i>
                            <?php esc_html_e('What\'s included in the trek packages?', 'tznew'); ?>
                        </h3>
                        <p class="text-gray-600"><?php esc_html_e('Our packages typically include accommodation, meals, permits, guides, porters, and transportation as specified in each itinerary.', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-calendar-days text-green-600 mr-3"></i>
                            <?php esc_html_e('When is the best time to trek?', 'tznew'); ?>
                        </h3>
                        <p class="text-gray-600"><?php esc_html_e('The best seasons are pre-monsoon (March-May) and post-monsoon (September-November) for clear mountain views and stable weather.', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-heart text-green-600 mr-3"></i>
                            <?php esc_html_e('Do I need travel insurance?', 'tznew'); ?>
                        </h3>
                        <p class="text-gray-600"><?php esc_html_e('Yes, comprehensive travel insurance including helicopter evacuation coverage is mandatory for all high-altitude treks.', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-dumbbell text-green-600 mr-3"></i>
                            <?php esc_html_e('What fitness level is required?', 'tznew'); ?>
                        </h3>
                        <p class="text-gray-600"><?php esc_html_e('Fitness requirements vary by trek. We recommend regular cardio exercise and hiking practice 2-3 months before your trip.', 'tznew'); ?></p>
                    </div>
                </div>
                
                <div class="text-center mt-8">
                    <a href="#inquiry-form" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-300">
                        <?php esc_html_e('Ask Your Question', 'tznew'); ?>
                        <i class="fas fa-arrow-down ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Inquiry Form Section -->
    <section id="inquiry-form" class="py-16">
        <div class="container mx-auto px-4">
            <?php get_template_part('template-parts/inquiry-form'); ?>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?php esc_html_e('Other Ways to Reach Us', 'tznew'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('Choose the method that works best for you', 'tznew'); ?>
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php esc_html_e('Call Us', 'tznew'); ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?php esc_html_e('Speak directly with our travel experts', 'tznew'); ?></p>
                        <a href="tel:<?php echo esc_attr(tznew_get_company_phone()); ?>" class="text-blue-600 font-semibold hover:text-blue-700"><?php echo esc_html(tznew_get_company_phone()); ?></a>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php esc_html_e('Email Us', 'tznew'); ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?php esc_html_e('Send us detailed questions', 'tznew'); ?></p>
                        <a href="mailto:<?php echo esc_attr(tznew_get_company_email()); ?>" class="text-green-600 font-semibold hover:text-green-700"><?php echo esc_html(tznew_get_company_email()); ?></a>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center">
                            <i class="fab fa-whatsapp text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php esc_html_e('WhatsApp', 'tznew'); ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?php esc_html_e('Quick messages and photos', 'tznew'); ?></p>
                        <?php if (tznew_get_company_whatsapp()): ?>
                        <a href="https://wa.me/<?php echo esc_attr(str_replace(['+', '-', ' '], '', tznew_get_company_whatsapp())); ?>" class="text-purple-600 font-semibold hover:text-purple-700" target="_blank"><?php echo esc_html(tznew_get_company_whatsapp()); ?></a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-location-dot text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php esc_html_e('Visit Office', 'tznew'); ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?php esc_html_e('Meet us in person', 'tznew'); ?></p>
                        <p class="text-orange-600 font-semibold text-sm"><?php echo esc_html(tznew_get_company_address()); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Response Time Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl p-8 md:p-12 text-white text-center">
                    <div class="mb-6">
                        <i class="fas fa-clock text-4xl mb-4"></i>
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">
                            <?php esc_html_e('Quick Response Guarantee', 'tznew'); ?>
                        </h2>
                        <p class="text-xl text-green-100 mb-8">
                            <?php esc_html_e('We understand that planning your adventure is exciting. That\'s why we guarantee a response to all inquiries within 2 hours during business hours.', 'tznew'); ?>
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <div class="text-2xl font-bold mb-2">< 2 Hours</div>
                            <div class="text-green-100"><?php esc_html_e('Email Response', 'tznew'); ?></div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <div class="text-2xl font-bold mb-2">< 30 Min</div>
                            <div class="text-green-100"><?php esc_html_e('WhatsApp Reply', 'tznew'); ?></div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <div class="text-2xl font-bold mb-2">24/7</div>
                            <div class="text-green-100"><?php esc_html_e('Emergency Support', 'tznew'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Destinations Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?php esc_html_e('Popular Inquiry Topics', 'tznew'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('Most common questions we receive from travelers', 'tznew'); ?>
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-mountain text-green-600 text-xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-800"><?php esc_html_e('Everest Base Camp', 'tznew'); ?></h3>
                        </div>
                        <p class="text-gray-600 text-sm"><?php esc_html_e('Duration, difficulty, best season, and preparation tips', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-route text-green-600 text-xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-800"><?php esc_html_e('Annapurna Circuit', 'tznew'); ?></h3>
                        </div>
                        <p class="text-gray-600 text-sm"><?php esc_html_e('Itinerary options, accommodation, and transportation', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-passport text-green-600 text-xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-800"><?php esc_html_e('Permits & Visas', 'tznew'); ?></h3>
                        </div>
                        <p class="text-gray-600 text-sm"><?php esc_html_e('Required documents, processing time, and costs', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-backpack text-green-600 text-xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-800"><?php esc_html_e('Packing Lists', 'tznew'); ?></h3>
                        </div>
                        <p class="text-gray-600 text-sm"><?php esc_html_e('Essential gear, clothing, and equipment recommendations', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-dollar-sign text-green-600 text-xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-800"><?php esc_html_e('Pricing & Packages', 'tznew'); ?></h3>
                        </div>
                        <p class="text-gray-600 text-sm"><?php esc_html_e('Cost breakdown, group discounts, and payment options', 'tznew'); ?></p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-users text-green-600 text-xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-800"><?php esc_html_e('Group vs Solo', 'tznew'); ?></h3>
                        </div>
                        <p class="text-gray-600 text-sm"><?php esc_html_e('Advantages of group treks vs private arrangements', 'tznew'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>