<?php
/**
 * Inquiry Form Template
 *
 * @package TZnew
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get post data if available
$post_id = get_the_ID();
$post_title = get_the_title();
$post_type = get_post_type();

// Get tour information from URL parameters
$tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : $post_id;
$tour_title = isset($_GET['tour_title']) ? sanitize_text_field(urldecode($_GET['tour_title'])) : $post_title;
?>

<div class="inquiry-form-container bg-white rounded-3xl shadow-2xl overflow-hidden max-w-4xl mx-auto">
    <!-- Progress Bar -->
    <div class="progress-bar bg-gray-100 h-2">
        <div class="progress-fill bg-gradient-to-r from-green-500 to-emerald-600 h-full w-0 transition-all duration-500 ease-out"></div>
    </div>
    
    <div class="p-8 lg:p-12">
        <div class="form-header text-center mb-10">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-envelope text-white text-3xl"></i>
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-4"><?php esc_html_e('Send Inquiry', 'tznew'); ?></h2>
            <?php if ($tour_title) : ?>
                <div class="bg-green-50 rounded-xl p-4 mb-4">
                    <p class="text-xl font-semibold text-gray-800"><?php echo esc_html($tour_title); ?></p>
                </div>
            <?php endif; ?>
            <p class="text-gray-600 max-w-2xl mx-auto"><?php esc_html_e('Have questions about this trip? Send us your inquiry and we\'ll get back to you with detailed information within 24 hours.', 'tznew'); ?></p>
        </div>

    <form id="inquiry-form" class="inquiry-form space-y-6" method="post" novalidate>
        <?php wp_nonce_field('tznew_inquiry_nonce', 'inquiry_nonce'); ?>
        
        <!-- Hidden fields -->
        <input type="hidden" name="action" value="tznew_submit_inquiry">
        <input type="hidden" name="post_id" value="<?php echo esc_attr($tour_id); ?>">
        <input type="hidden" name="post_title" value="<?php echo esc_attr($tour_title); ?>">
        <input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>">

        <!-- Step 1: Personal Information -->
        <div class="form-step active" data-step="1">
            <div class="step-header mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                        <?php esc_html_e('Personal Information', 'tznew'); ?>
                    </h3>
                    <span class="text-sm text-gray-500"><?php esc_html_e('Step 1 of 3', 'tznew'); ?></span>
                </div>
                <p class="text-gray-600"><?php esc_html_e('Tell us about yourself so we can provide personalized information.', 'tznew'); ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="inquiry_name" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-user text-green-500 mr-2"></i>
                        <?php esc_html_e('Full Name', 'tznew'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="inquiry_name" name="inquiry_name" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg" placeholder="<?php esc_attr_e('Enter your full name', 'tznew'); ?>" required>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="inquiry_email" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-envelope text-green-500 mr-2"></i>
                        <?php esc_html_e('Email Address', 'tznew'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="inquiry_email" name="inquiry_email" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg" placeholder="<?php esc_attr_e('your@email.com', 'tznew'); ?>" required>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('We\'ll send detailed information to this email', 'tznew'); ?></div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="form-group">
                    <label for="inquiry_phone" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-phone text-green-500 mr-2"></i>
                        <?php esc_html_e('Phone Number', 'tznew'); ?>
                    </label>
                    <input type="tel" id="inquiry_phone" name="inquiry_phone" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg" placeholder="<?php esc_attr_e('+1 (555) 123-4567', 'tznew'); ?>">
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('Optional - for faster communication', 'tznew'); ?></div>
                </div>
                
                <div class="form-group">
                    <label for="inquiry_country" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-globe text-green-500 mr-2"></i>
                        <?php esc_html_e('Country', 'tznew'); ?>
                    </label>
                    <select id="inquiry_country" name="inquiry_country" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg">
                        <option value=""><?php esc_html_e('Select your country', 'tznew'); ?></option>
                        <option value="US"><?php esc_html_e('🇺🇸 United States', 'tznew'); ?></option>
                        <option value="UK"><?php esc_html_e('🇬🇧 United Kingdom', 'tznew'); ?></option>
                        <option value="CA"><?php esc_html_e('🇨🇦 Canada', 'tznew'); ?></option>
                        <option value="AU"><?php esc_html_e('🇦🇺 Australia', 'tznew'); ?></option>
                        <option value="DE"><?php esc_html_e('🇩🇪 Germany', 'tznew'); ?></option>
                        <option value="FR"><?php esc_html_e('🇫🇷 France', 'tznew'); ?></option>
                        <option value="JP"><?php esc_html_e('🇯🇵 Japan', 'tznew'); ?></option>
                        <option value="IN"><?php esc_html_e('🇮🇳 India', 'tznew'); ?></option>
                        <option value="NP"><?php esc_html_e('🇳🇵 Nepal', 'tznew'); ?></option>
                        <option value="other"><?php esc_html_e('🌍 Other', 'tznew'); ?></option>
                    </select>
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('Helps us provide region-specific information', 'tznew'); ?></div>
                </div>
            </div>
            
            <div class="step-navigation mt-8 flex justify-end">
                <button type="button" class="next-step bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <?php esc_html_e('Next: Travel Details', 'tznew'); ?>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Travel Details -->
        <div class="form-step" data-step="2">
            <div class="step-header mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                        <?php esc_html_e('Travel Details', 'tznew'); ?>
                    </h3>
                    <span class="text-sm text-gray-500"><?php esc_html_e('Step 2 of 3', 'tznew'); ?></span>
                </div>
                <p class="text-gray-600"><?php esc_html_e('Help us understand your travel preferences and requirements.', 'tznew'); ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="travel_date" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-calendar text-green-500 mr-2"></i>
                        <?php esc_html_e('Preferred Travel Date', 'tznew'); ?>
                    </label>
                    <input type="date" id="travel_date" name="travel_date" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg">
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('When would you like to start your journey?', 'tznew'); ?></div>
                </div>
                
                <div class="form-group">
                    <label for="group_size" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-users text-green-500 mr-2"></i>
                        <?php esc_html_e('Group Size', 'tznew'); ?>
                    </label>
                    <select id="group_size" name="group_size" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg">
                        <option value=""><?php esc_html_e('Select group size', 'tznew'); ?></option>
                        <option value="1"><?php esc_html_e('👤 1 person (Solo)', 'tznew'); ?></option>
                        <option value="2"><?php esc_html_e('👥 2 people (Couple)', 'tznew'); ?></option>
                        <option value="3-5"><?php esc_html_e('👨‍👩‍👧 3-5 people (Small group)', 'tznew'); ?></option>
                        <option value="6-10"><?php esc_html_e('👨‍👩‍👧‍👦 6-10 people (Medium group)', 'tznew'); ?></option>
                        <option value="11+"><?php esc_html_e('🏢 11+ people (Large group)', 'tznew'); ?></option>
                    </select>
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('Group size affects pricing and logistics', 'tznew'); ?></div>
                </div>
            </div>
            
            <div class="form-group mt-6">
                <label for="budget_range" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-dollar-sign text-green-500 mr-2"></i>
                    <?php esc_html_e('Budget Range (per person)', 'tznew'); ?>
                </label>
                <select id="budget_range" name="budget_range" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg">
                    <option value=""><?php esc_html_e('Select your budget range', 'tznew'); ?></option>
                    <option value="under-500"><?php esc_html_e('💰 Under $500', 'tznew'); ?></option>
                    <option value="500-1000"><?php esc_html_e('💰💰 $500 - $1,000', 'tznew'); ?></option>
                    <option value="1000-2000"><?php esc_html_e('💰💰💰 $1,000 - $2,000', 'tznew'); ?></option>
                    <option value="2000-5000"><?php esc_html_e('💰💰💰💰 $2,000 - $5,000', 'tznew'); ?></option>
                    <option value="over-5000"><?php esc_html_e('💎 Over $5,000', 'tznew'); ?></option>
                    <option value="flexible"><?php esc_html_e('🤝 Flexible', 'tznew'); ?></option>
                </select>
                <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('Helps us recommend suitable options', 'tznew'); ?></div>
            </div>
            
            <div class="step-navigation mt-8 flex justify-between">
                <button type="button" class="prev-step bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <?php esc_html_e('Previous', 'tznew'); ?>
                </button>
                <button type="button" class="next-step bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <?php esc_html_e('Next: Your Message', 'tznew'); ?>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 3: Your Message -->
        <div class="form-step" data-step="3">
            <div class="step-header mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                        <?php esc_html_e('Your Message', 'tznew'); ?>
                    </h3>
                    <span class="text-sm text-gray-500"><?php esc_html_e('Step 3 of 3', 'tznew'); ?></span>
                </div>
                <p class="text-gray-600"><?php esc_html_e('Tell us about your interests, questions, or any specific requirements.', 'tznew'); ?></p>
            </div>
            
            <div class="form-group">
                <label for="inquiry_message" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-comment text-green-500 mr-2"></i>
                    <?php esc_html_e('Your Message', 'tznew'); ?> <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <textarea id="inquiry_message" name="inquiry_message" rows="8" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 resize-vertical text-lg" placeholder="<?php esc_attr_e('Example: I\'m interested in the Everest Base Camp trek. Could you provide more details about the difficulty level, what\'s included in the package, and the best time to go? I have some dietary restrictions...', 'tznew'); ?>" required></textarea>
                    <div class="absolute bottom-3 right-3 character-count text-sm text-gray-400">
                        <span class="current-count">0</span> / <span class="max-count">1000</span>
                    </div>
                </div>
                <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <span></span>
                </div>
                <div class="help-text text-xs text-gray-500 mt-2">
                    <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                    <?php esc_html_e('Tip: The more details you provide, the better we can assist you!', 'tznew'); ?>
                </div>
            </div>
            
            <!-- Quick Questions -->
            <div class="quick-questions mt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4"><?php esc_html_e('Quick Questions (Optional)', 'tznew'); ?></h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all duration-300 cursor-pointer">
                        <input type="checkbox" name="questions[]" value="accommodation" class="mr-3 text-green-600 focus:ring-green-500">
                        <span class="text-gray-700"><?php esc_html_e('Accommodation details', 'tznew'); ?></span>
                    </label>
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all duration-300 cursor-pointer">
                        <input type="checkbox" name="questions[]" value="meals" class="mr-3 text-green-600 focus:ring-green-500">
                        <span class="text-gray-700"><?php esc_html_e('Meal arrangements', 'tznew'); ?></span>
                    </label>
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all duration-300 cursor-pointer">
                        <input type="checkbox" name="questions[]" value="equipment" class="mr-3 text-green-600 focus:ring-green-500">
                        <span class="text-gray-700"><?php esc_html_e('Equipment & gear', 'tznew'); ?></span>
                    </label>
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all duration-300 cursor-pointer">
                        <input type="checkbox" name="questions[]" value="permits" class="mr-3 text-green-600 focus:ring-green-500">
                        <span class="text-gray-700"><?php esc_html_e('Permits & documentation', 'tznew'); ?></span>
                    </label>
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all duration-300 cursor-pointer">
                        <input type="checkbox" name="questions[]" value="fitness" class="mr-3 text-green-600 focus:ring-green-500">
                        <span class="text-gray-700"><?php esc_html_e('Fitness requirements', 'tznew'); ?></span>
                    </label>
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all duration-300 cursor-pointer">
                        <input type="checkbox" name="questions[]" value="weather" class="mr-3 text-green-600 focus:ring-green-500">
                        <span class="text-gray-700"><?php esc_html_e('Weather conditions', 'tznew'); ?></span>
                    </label>
                </div>
            </div>
            
            <div class="step-navigation mt-8 flex justify-between">
                <button type="button" class="prev-step bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <?php esc_html_e('Previous', 'tznew'); ?>
                </button>
                <button type="submit" class="submit-inquiry bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-10 rounded-xl transition-all duration-300 flex items-center text-lg shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <?php esc_html_e('Send Inquiry', 'tznew'); ?>
                </button>
            </div>
        </div>

        <!-- Travel Preferences (Optional) -->
        <div class="form-section">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-map-marked-alt mr-2 text-purple-500"></i>
                <?php esc_html_e('Travel Preferences', 'tznew'); ?> 
                <span class="text-sm font-normal text-gray-500 ml-2">(<?php esc_html_e('Optional', 'tznew'); ?>)</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="travel_dates" class="form-label block text-sm font-medium text-gray-700 mb-2">
                        <?php esc_html_e('Preferred Travel Dates', 'tznew'); ?>
                    </label>
                    <input type="text" id="travel_dates" name="travel_dates" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300" placeholder="<?php esc_attr_e('e.g., March 2024 or Spring 2024', 'tznew'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="group_size_inquiry" class="form-label block text-sm font-medium text-gray-700 mb-2">
                        <?php esc_html_e('Group Size', 'tznew'); ?>
                    </label>
                    <select id="group_size_inquiry" name="group_size_inquiry" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
                        <option value=""><?php esc_html_e('Select group size', 'tznew'); ?></option>
                        <option value="1"><?php esc_html_e('Solo traveler', 'tznew'); ?></option>
                        <option value="2"><?php esc_html_e('2 people', 'tznew'); ?></option>
                        <option value="3-5"><?php esc_html_e('3-5 people', 'tznew'); ?></option>
                        <option value="6-10"><?php esc_html_e('6-10 people', 'tznew'); ?></option>
                        <option value="11-15"><?php esc_html_e('11-15 people', 'tznew'); ?></option>
                        <option value="16+"><?php esc_html_e('16+ people', 'tznew'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <label for="budget_range" class="form-label block text-sm font-medium text-gray-700 mb-2">
                    <?php esc_html_e('Budget Range (per person)', 'tznew'); ?>
                </label>
                <select id="budget_range" name="budget_range" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
                    <option value=""><?php esc_html_e('Select budget range', 'tznew'); ?></option>
                    <option value="under-500"><?php esc_html_e('Under $500', 'tznew'); ?></option>
                    <option value="500-1000"><?php esc_html_e('$500 - $1,000', 'tznew'); ?></option>
                    <option value="1000-2000"><?php esc_html_e('$1,000 - $2,000', 'tznew'); ?></option>
                    <option value="2000-3000"><?php esc_html_e('$2,000 - $3,000', 'tznew'); ?></option>
                    <option value="3000-5000"><?php esc_html_e('$3,000 - $5,000', 'tznew'); ?></option>
                    <option value="over-5000"><?php esc_html_e('Over $5,000', 'tznew'); ?></option>
                    <option value="flexible"><?php esc_html_e('Flexible', 'tznew'); ?></option>
                </select>
            </div>
        </div>

        <!-- Contact Preferences -->
        <div class="form-section">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-phone mr-2 text-indigo-500"></i>
                <?php esc_html_e('Contact Preferences', 'tznew'); ?>
            </h3>
            
            <div class="form-group">
                <label class="form-label block text-sm font-medium text-gray-700 mb-3">
                    <?php esc_html_e('How would you prefer to be contacted?', 'tznew'); ?>
                </label>
                <div class="space-y-2">
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="contact_preference" value="email" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" checked>
                        <span class="text-sm text-gray-700"><?php esc_html_e('Email (Recommended)', 'tznew'); ?></span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="contact_preference" value="phone" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <span class="text-sm text-gray-700"><?php esc_html_e('Phone Call', 'tznew'); ?></span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="contact_preference" value="whatsapp" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <span class="text-sm text-gray-700"><?php esc_html_e('WhatsApp', 'tznew'); ?></span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="contact_preference" value="any" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <span class="text-sm text-gray-700"><?php esc_html_e('Any method is fine', 'tznew'); ?></span>
                    </label>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <label for="response_urgency" class="form-label block text-sm font-medium text-gray-700 mb-2">
                    <?php esc_html_e('Response Urgency', 'tznew'); ?>
                </label>
                <select id="response_urgency" name="response_urgency" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
                    <option value="normal"><?php esc_html_e('Normal (within 24 hours)', 'tznew'); ?></option>
                    <option value="urgent"><?php esc_html_e('Urgent (within 4 hours)', 'tznew'); ?></option>
                    <option value="flexible"><?php esc_html_e('Flexible (within 48 hours)', 'tznew'); ?></option>
                </select>
            </div>
        </div>

        <!-- Newsletter Subscription -->
        <div class="form-section">
            <div class="flex items-start space-x-3">
                <input type="checkbox" id="newsletter_subscription" name="newsletter_subscription" class="mt-1 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500" value="1">
                <label for="newsletter_subscription" class="text-sm text-gray-700">
                    <?php esc_html_e('Subscribe to our newsletter for travel tips, special offers, and updates on new destinations', 'tznew'); ?>
                </label>
            </div>
        </div>

    </form>

    <!-- Success Message -->
    <div id="inquiry-success" class="success-message hidden bg-green-50 border-2 border-green-200 rounded-2xl p-8 mt-8">
        <div class="flex items-center">
            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mr-6 shadow-lg">
                <i class="fas fa-check text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-green-800 mb-2"><?php esc_html_e('Inquiry Sent Successfully!', 'tznew'); ?></h3>
                <p class="text-green-700 text-lg"><?php esc_html_e('Thank you for your inquiry. We will get back to you within 24 hours with detailed information.', 'tznew'); ?></p>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="inquiry-error" class="error-message hidden bg-red-50 border-2 border-red-200 rounded-2xl p-8 mt-8">
        <div class="flex items-center">
            <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mr-6 shadow-lg">
                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-red-800 mb-2"><?php esc_html_e('Error Sending Inquiry', 'tznew'); ?></h3>
                <p class="text-red-700 text-lg error-text"><?php esc_html_e('There was an error sending your inquiry. Please try again or contact us directly.', 'tznew'); ?></p>
            </div>
        </div>
    </div>
</div>

<style>
.form-input:focus {
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.form-group.error .form-input {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-group.success .form-input {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.error-message {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>