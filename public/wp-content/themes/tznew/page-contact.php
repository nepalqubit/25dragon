<?php
/**
 * Template Name: Contact Page
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
                        <i class="fas fa-envelope text-4xl"></i>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold mb-6">
                        <?php esc_html_e('Contact Us', 'tznew'); ?>
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                        <?php esc_html_e('Get in touch with our team for any questions, bookings, or custom travel plans. We\'re here to make your adventure dreams come true.', 'tznew'); ?>
                    </p>
                </div>
                
                <!-- Contact Methods -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-2xl"></i>
                        </div>
                        <div class="text-blue-100"><?php esc_html_e('Call Us', 'tznew'); ?></div>
                        <div class="text-white font-medium mt-1">
                            <?php echo esc_html(tznew_get_contact_phone()); ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fab fa-whatsapp text-2xl"></i>
                        </div>
                        <div class="text-blue-100"><?php esc_html_e('WhatsApp', 'tznew'); ?></div>
                        <div class="text-white font-medium mt-1">
                            <?php echo esc_html(tznew_get_whatsapp_number()); ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <div class="text-blue-100"><?php esc_html_e('Email Us', 'tznew'); ?></div>
                        <div class="text-white font-medium mt-1">
                            <?php echo esc_html(tznew_get_contact_email()); ?>
                        </div>
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

    <!-- Contact Form Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?php esc_html_e('Send Us a Message', 'tznew'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('We\'ll get back to you as soon as possible', 'tznew'); ?>
                    </p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <form id="contact-form" class="space-y-6">
                        <?php wp_nonce_field('tznew_contact_nonce', 'contact_nonce'); ?>
                        
                        <!-- Form Messages -->
                        <div class="form-messages"></div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e('Your Name', 'tznew'); ?> <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
                                <div class="field-error text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e('Email Address', 'tznew'); ?> <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
                                <div class="field-error text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e('Subject', 'tznew'); ?> <span class="text-red-500">*</span></label>
                            <input type="text" id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
                            <div class="field-error text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1"><?php esc_html_e('Your Message', 'tznew'); ?> <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required placeholder="<?php esc_attr_e('Please provide details about your inquiry (minimum 10 characters)', 'tznew'); ?>"></textarea>
                            <div class="field-error text-red-500 text-sm mt-1 hidden"></div>
                            <div class="character-count text-sm text-gray-500 mt-1">
                                <span class="current-count">0</span> characters
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-paper-plane mr-2"></i> 
                                <span class="button-text"><?php esc_html_e('Send Message', 'tznew'); ?></span>
                                <span class="loading-text hidden"><?php esc_html_e('Sending...', 'tznew'); ?></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?php esc_html_e('Find Us', 'tznew'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('Visit our office or contact us online', 'tznew'); ?>
                    </p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg overflow-hidden">
                    <!-- Replace with actual map embed code -->
                    <div class="aspect-w-16 aspect-h-9">
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                            <p class="text-gray-500"><?php esc_html_e('Map will be displayed here', 'tznew'); ?></p>
                            <!-- Add your Google Maps or other map embed code here -->
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900"><?php esc_html_e('Office Address', 'tznew'); ?></h3>
                                <p class="mt-1 text-gray-600">
                                    123 Adventure Street<br>
                                    Kathmandu, Nepal
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900"><?php esc_html_e('Office Hours', 'tznew'); ?></h3>
                                <p class="mt-1 text-gray-600">
                                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                                    Saturday: 10:00 AM - 4:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();