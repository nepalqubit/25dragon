<?php
/**
 * Booking Form Template
 *
 * @package TZnew
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get post data if available - support both tour_id and trekking_id parameters
$tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : null;
$trekking_id = isset($_GET['trekking_id']) ? intval($_GET['trekking_id']) : null;
$post_id = $tour_id ?: $trekking_id ?: get_the_ID();

$tour_title = isset($_GET['tour_title']) ? sanitize_text_field(urldecode($_GET['tour_title'])) : null;
$trekking_title = isset($_GET['trekking_title']) ? sanitize_text_field(urldecode($_GET['trekking_title'])) : null;
$post_title = $tour_title ?: $trekking_title ?: get_the_title($post_id);

$post_type = get_post_type($post_id);
$cost_info = tznew_get_field_safe('cost_info', '', $post_id);
$price = $cost_info['price_usd'] ?? 0;
?>

<div class="booking-form-container bg-white rounded-3xl shadow-2xl overflow-hidden max-w-4xl mx-auto">
    <!-- Progress Bar -->
    <div class="progress-bar bg-gray-100 h-2">
        <div class="progress-fill bg-gradient-to-r from-blue-500 to-indigo-600 h-full w-0 transition-all duration-500 ease-out"></div>
    </div>
    
    <div class="p-8 lg:p-12">
        <div class="form-header text-center mb-10">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-calendar-check text-white text-3xl"></i>
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-4"><?php esc_html_e('Book Your Adventure', 'tznew'); ?></h2>
            <?php if ($post_title) : ?>
                <div class="bg-blue-50 rounded-xl p-4 mb-4">
                    <p class="text-xl font-semibold text-gray-800"><?php echo esc_html($post_title); ?></p>
                    <?php if ($price > 0) : ?>
                        <div class="text-3xl font-bold text-blue-600 mt-2">$<?php echo esc_html(number_format($price)); ?> <span class="text-lg font-normal text-gray-600"><?php esc_html_e('per person', 'tznew'); ?></span></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <p class="text-gray-600 max-w-2xl mx-auto"><?php esc_html_e('Fill out the form below and we\'ll get back to you within 24 hours with a detailed quote and itinerary.', 'tznew'); ?></p>
        </div>

    <form id="booking-form" class="booking-form space-y-6" method="post" novalidate>
        <?php wp_nonce_field('tznew_booking_nonce', 'booking_nonce'); ?>
        
        <!-- Hidden fields -->
        <input type="hidden" name="action" value="tznew_submit_booking">
        <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
        <input type="hidden" name="post_title" value="<?php echo esc_attr($post_title); ?>">
        <input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>">

        <!-- Step 1: Personal Information -->
        <div class="form-step active" data-step="1">
            <div class="step-header mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                        <?php esc_html_e('Personal Information', 'tznew'); ?>
                    </h3>
                    <span class="text-sm text-gray-500"><?php esc_html_e('Step 1 of 4', 'tznew'); ?></span>
                </div>
                <p class="text-gray-600"><?php esc_html_e('Tell us about yourself so we can personalize your experience.', 'tznew'); ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="first_name" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-user text-blue-500 mr-2"></i>
                        <?php esc_html_e('First Name', 'tznew'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="first_name" name="first_name" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg" placeholder="<?php esc_attr_e('Enter your first name', 'tznew'); ?>" required>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="last_name" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-user text-blue-500 mr-2"></i>
                        <?php esc_html_e('Last Name', 'tznew'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg" placeholder="<?php esc_attr_e('Enter your last name', 'tznew'); ?>" required>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="form-group">
                    <label for="email" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-envelope text-blue-500 mr-2"></i>
                        <?php esc_html_e('Email Address', 'tznew'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg" placeholder="<?php esc_attr_e('your@email.com', 'tznew'); ?>" required>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('We\'ll send your booking confirmation here', 'tznew'); ?></div>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-phone text-blue-500 mr-2"></i>
                        <?php esc_html_e('Phone Number', 'tznew'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg" placeholder="<?php esc_attr_e('+1 (555) 123-4567', 'tznew'); ?>" required>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('For urgent communication during your trip', 'tznew'); ?></div>
                </div>
            </div>
            
            <div class="form-group mt-6">
                <label for="country" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-globe text-blue-500 mr-2"></i>
                    <?php esc_html_e('Country', 'tznew'); ?> <span class="text-red-500">*</span>
                </label>
                <select id="country" name="country" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg" required>
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
                <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <span></span>
                </div>
            </div>
            
            <div class="step-navigation mt-8 flex justify-end">
                <button type="button" class="next-step bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <?php esc_html_e('Next: Trip Details', 'tznew'); ?>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Trip Details -->
        <div class="form-step" data-step="2">
            <div class="step-header mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                        <?php esc_html_e('Trip Details', 'tznew'); ?>
                    </h3>
                    <span class="text-sm text-gray-500"><?php esc_html_e('Step 2 of 4', 'tznew'); ?></span>
                </div>
                <p class="text-gray-600"><?php esc_html_e('When would you like to travel and how many people will join you?', 'tznew'); ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="preferred_date" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-calendar text-green-500 mr-2"></i>
                        <?php esc_html_e('Preferred Start Date', 'tznew'); ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="preferred_date" name="preferred_date" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg" min="<?php echo esc_attr(date('Y-m-d', strtotime('+1 week'))); ?>" required>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('Minimum 1 week advance booking required', 'tznew'); ?></div>
                </div>
                
                <div class="form-group">
                    <label for="group_size" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-users text-green-500 mr-2"></i>
                        <?php esc_html_e('Number of Travelers', 'tznew'); ?> <span class="text-red-500">*</span>
                    </label>
                    <select id="group_size" name="group_size" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg" required>
                        <option value=""><?php esc_html_e('Select group size', 'tznew'); ?></option>
                        <?php for ($i = 1; $i <= 20; $i++) : ?>
                            <option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i . ($i === 1 ? ' person' : ' people')); ?></option>
                        <?php endfor; ?>
                    </select>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                    <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('Group discounts available for 6+ people', 'tznew'); ?></div>
                </div>
            </div>
            
            <div class="form-group mt-6">
                <label for="accommodation_preference" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-bed text-green-500 mr-2"></i>
                    <?php esc_html_e('Accommodation Preference', 'tznew'); ?>
                </label>
                <div class="accommodation-options grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="accommodation-option cursor-pointer">
                        <input type="radio" name="accommodation_preference" value="budget" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-xl p-4 text-center hover:border-green-500 transition-all duration-300">
                            <i class="fas fa-home text-2xl text-gray-400 mb-2"></i>
                            <h4 class="font-semibold text-gray-800"><?php esc_html_e('Budget', 'tznew'); ?></h4>
                            <p class="text-sm text-gray-600"><?php esc_html_e('Tea houses & Basic lodges', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="accommodation-option cursor-pointer">
                        <input type="radio" name="accommodation_preference" value="standard" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-xl p-4 text-center hover:border-green-500 transition-all duration-300">
                            <i class="fas fa-building text-2xl text-gray-400 mb-2"></i>
                            <h4 class="font-semibold text-gray-800"><?php esc_html_e('Standard', 'tznew'); ?></h4>
                            <p class="text-sm text-gray-600"><?php esc_html_e('Comfortable lodges', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="accommodation-option cursor-pointer">
                        <input type="radio" name="accommodation_preference" value="luxury" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-xl p-4 text-center hover:border-green-500 transition-all duration-300">
                            <i class="fas fa-crown text-2xl text-gray-400 mb-2"></i>
                            <h4 class="font-semibold text-gray-800"><?php esc_html_e('Luxury', 'tznew'); ?></h4>
                            <p class="text-sm text-gray-600"><?php esc_html_e('Premium hotels', 'tznew'); ?></p>
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="step-navigation mt-8 flex justify-between">
                <button type="button" class="prev-step bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <?php esc_html_e('Previous', 'tznew'); ?>
                </button>
                <button type="button" class="next-step bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <?php esc_html_e('Next: Additional Info', 'tznew'); ?>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 3: Additional Information -->
        <div class="form-step" data-step="3">
            <div class="step-header mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                        <?php esc_html_e('Additional Information', 'tznew'); ?>
                    </h3>
                    <span class="text-sm text-gray-500"><?php esc_html_e('Step 3 of 4', 'tznew'); ?></span>
                </div>
                <p class="text-gray-600"><?php esc_html_e('Help us customize your experience with your preferences and requirements.', 'tznew'); ?></p>
            </div>
            
            <div class="form-group">
                <label for="dietary_requirements" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-utensils text-purple-500 mr-2"></i>
                    <?php esc_html_e('Dietary Requirements', 'tznew'); ?>
                </label>
                <div class="dietary-options grid grid-cols-2 md:grid-cols-3 gap-3">
                    <label class="dietary-option cursor-pointer">
                        <input type="checkbox" name="dietary_requirements[]" value="vegetarian" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-lg p-3 text-center hover:border-purple-500 transition-all duration-300">
                            <i class="fas fa-leaf text-lg text-gray-400 mb-1"></i>
                            <p class="text-sm font-medium"><?php esc_html_e('Vegetarian', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="dietary-option cursor-pointer">
                        <input type="checkbox" name="dietary_requirements[]" value="vegan" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-lg p-3 text-center hover:border-purple-500 transition-all duration-300">
                            <i class="fas fa-seedling text-lg text-gray-400 mb-1"></i>
                            <p class="text-sm font-medium"><?php esc_html_e('Vegan', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="dietary-option cursor-pointer">
                        <input type="checkbox" name="dietary_requirements[]" value="gluten-free" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-lg p-3 text-center hover:border-purple-500 transition-all duration-300">
                            <i class="fas fa-ban text-lg text-gray-400 mb-1"></i>
                            <p class="text-sm font-medium"><?php esc_html_e('Gluten-free', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="dietary-option cursor-pointer">
                        <input type="checkbox" name="dietary_requirements[]" value="halal" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-lg p-3 text-center hover:border-purple-500 transition-all duration-300">
                            <i class="fas fa-moon text-lg text-gray-400 mb-1"></i>
                            <p class="text-sm font-medium"><?php esc_html_e('Halal', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="dietary-option cursor-pointer">
                        <input type="checkbox" name="dietary_requirements[]" value="kosher" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-lg p-3 text-center hover:border-purple-500 transition-all duration-300">
                            <i class="fas fa-star text-lg text-gray-400 mb-1"></i>
                            <p class="text-sm font-medium"><?php esc_html_e('Kosher', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="dietary-option cursor-pointer">
                        <input type="checkbox" name="dietary_requirements[]" value="none" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-lg p-3 text-center hover:border-purple-500 transition-all duration-300">
                            <i class="fas fa-check text-lg text-gray-400 mb-1"></i>
                            <p class="text-sm font-medium"><?php esc_html_e('No restrictions', 'tznew'); ?></p>
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="form-group mt-6">
                <label for="fitness_level" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-dumbbell text-purple-500 mr-2"></i>
                    <?php esc_html_e('Fitness Level', 'tznew'); ?>
                </label>
                <div class="fitness-options grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="fitness-option cursor-pointer">
                        <input type="radio" name="fitness_level" value="beginner" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-xl p-4 hover:border-purple-500 transition-all duration-300">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-walking text-xl text-gray-400 mr-3"></i>
                                <h4 class="font-semibold text-gray-800"><?php esc_html_e('Beginner', 'tznew'); ?></h4>
                            </div>
                            <p class="text-sm text-gray-600"><?php esc_html_e('Little to no hiking experience', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="fitness-option cursor-pointer">
                        <input type="radio" name="fitness_level" value="moderate" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-xl p-4 hover:border-purple-500 transition-all duration-300">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-hiking text-xl text-gray-400 mr-3"></i>
                                <h4 class="font-semibold text-gray-800"><?php esc_html_e('Moderate', 'tznew'); ?></h4>
                            </div>
                            <p class="text-sm text-gray-600"><?php esc_html_e('Regular exercise, some hiking', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="fitness-option cursor-pointer">
                        <input type="radio" name="fitness_level" value="experienced" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-xl p-4 hover:border-purple-500 transition-all duration-300">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-mountain text-xl text-gray-400 mr-3"></i>
                                <h4 class="font-semibold text-gray-800"><?php esc_html_e('Experienced', 'tznew'); ?></h4>
                            </div>
                            <p class="text-sm text-gray-600"><?php esc_html_e('Regular hiker, good fitness', 'tznew'); ?></p>
                        </div>
                    </label>
                    <label class="fitness-option cursor-pointer">
                        <input type="radio" name="fitness_level" value="expert" class="sr-only">
                        <div class="option-card border-2 border-gray-200 rounded-xl p-4 hover:border-purple-500 transition-all duration-300">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-medal text-xl text-gray-400 mr-3"></i>
                                <h4 class="font-semibold text-gray-800"><?php esc_html_e('Expert', 'tznew'); ?></h4>
                            </div>
                            <p class="text-sm text-gray-600"><?php esc_html_e('Extensive hiking/mountaineering', 'tznew'); ?></p>
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="form-group mt-6">
                <label for="special_requests" class="form-label block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-heart text-purple-500 mr-2"></i>
                    <?php esc_html_e('Special Requests or Medical Conditions', 'tznew'); ?>
                </label>
                <textarea id="special_requests" name="special_requests" rows="4" class="form-input w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 resize-vertical text-lg" placeholder="<?php esc_attr_e('Please let us know about any medical conditions, special requirements, or requests that will help us make your trip more comfortable...', 'tznew'); ?>"></textarea>
                <div class="help-text text-xs text-gray-500 mt-1"><?php esc_html_e('This information helps us ensure your safety and comfort during the trip', 'tznew'); ?></div>
            </div>
            
            <div class="step-navigation mt-8 flex justify-between">
                <button type="button" class="prev-step bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <?php esc_html_e('Previous', 'tznew'); ?>
                </button>
                <button type="button" class="next-step bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <?php esc_html_e('Next: Review & Submit', 'tznew'); ?>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 4: Review & Submit -->
        <div class="form-step" data-step="4">
            <div class="step-header mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                        <?php esc_html_e('Review & Submit', 'tznew'); ?>
                    </h3>
                    <span class="text-sm text-gray-500"><?php esc_html_e('Step 4 of 4', 'tznew'); ?></span>
                </div>
                <p class="text-gray-600"><?php esc_html_e('Review your information and submit your booking request.', 'tznew'); ?></p>
            </div>
            
            <!-- Booking Summary -->
            <div class="booking-summary bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 mb-6">
                <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>
                    <?php esc_html_e('Booking Summary', 'tznew'); ?>
                </h4>
                <div class="summary-content space-y-3 text-sm">
                    <div class="summary-item flex justify-between">
                        <span class="text-gray-600"><?php esc_html_e('Trip:', 'tznew'); ?></span>
                        <span class="font-semibold text-gray-800 summary-trip">-</span>
                    </div>
                    <div class="summary-item flex justify-between">
                        <span class="text-gray-600"><?php esc_html_e('Travelers:', 'tznew'); ?></span>
                        <span class="font-semibold text-gray-800 summary-travelers">-</span>
                    </div>
                    <div class="summary-item flex justify-between">
                        <span class="text-gray-600"><?php esc_html_e('Date:', 'tznew'); ?></span>
                        <span class="font-semibold text-gray-800 summary-date">-</span>
                    </div>
                    <div class="summary-item flex justify-between">
                        <span class="text-gray-600"><?php esc_html_e('Accommodation:', 'tznew'); ?></span>
                        <span class="font-semibold text-gray-800 summary-accommodation">-</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-route text-green-500 mr-2"></i>
                    <?php esc_html_e('What happens next?', 'tznew'); ?>
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="next-step-item text-center">
                        <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">1</span>
                        </div>
                        <p class="text-sm font-medium text-gray-800"><?php esc_html_e('Quick Review', 'tznew'); ?></p>
                        <p class="text-xs text-gray-600"><?php esc_html_e('Within 24 hours', 'tznew'); ?></p>
                    </div>
                    <div class="next-step-item text-center">
                        <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">2</span>
                        </div>
                        <p class="text-sm font-medium text-gray-800"><?php esc_html_e('Detailed Quote', 'tznew'); ?></p>
                        <p class="text-xs text-gray-600"><?php esc_html_e('Itinerary & pricing', 'tznew'); ?></p>
                    </div>
                    <div class="next-step-item text-center">
                        <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">3</span>
                        </div>
                        <p class="text-sm font-medium text-gray-800"><?php esc_html_e('Personal Call', 'tznew'); ?></p>
                        <p class="text-xs text-gray-600"><?php esc_html_e('Discuss details', 'tznew'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Terms and Conditions -->
            <div class="form-group mb-6">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" id="terms_agreement" name="terms_agreement" class="mt-1 w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500" required>
                    <label for="terms_agreement" class="text-sm text-gray-700">
                        <?php esc_html_e('I agree to the', 'tznew'); ?> 
                        <a href="#" class="text-orange-600 hover:text-orange-800 underline"><?php esc_html_e('Terms and Conditions', 'tznew'); ?></a> 
                        <?php esc_html_e('and', 'tznew'); ?> 
                        <a href="#" class="text-orange-600 hover:text-orange-800 underline"><?php esc_html_e('Privacy Policy', 'tznew'); ?></a>
                        <span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div class="step-navigation mt-8 flex justify-between">
                <button type="button" class="prev-step bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <?php esc_html_e('Previous', 'tznew'); ?>
                </button>
                <button type="submit" class="submit-btn bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-bold py-4 px-10 rounded-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-orange-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center" style="background: linear-gradient(to right, #ea580c, #dc2626) !important; color: white !important; border: none !important;">
                    <span class="btn-text flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <?php esc_html_e('Submit Booking Request', 'tznew'); ?>
                    </span>
                    <span class="btn-loading hidden flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        <?php esc_html_e('Submitting...', 'tznew'); ?>
                    </span>
                </button>
            </div>
            
            <div class="form-loading hidden mt-4 text-center">
                <div class="inline-flex items-center space-x-2 text-orange-600">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-orange-600"></div>
                    <span><?php esc_html_e('Submitting your request...', 'tznew'); ?></span>
                </div>
            </div>
            
            <div class="form-messages mt-4"></div>
        </div>

        <!-- Contact Information -->
        <div class="form-footer mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600 mb-2">
                <?php esc_html_e('Need immediate assistance? Contact us directly:', 'tznew'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-6">
                <a href="tel:<?php echo esc_attr(tznew_get_company_phone()); ?>" class="flex items-center space-x-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-phone"></i>
                    <span><?php echo esc_html(tznew_get_company_phone()); ?></span>
                </a>
                <a href="mailto:<?php echo esc_attr(tznew_get_company_email()); ?>" class="flex items-center space-x-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo esc_html(tznew_get_company_email()); ?></span>
                </a>
            </div>
        </div>
    </form>
    
    <!-- Success Message -->
    <div id="booking-success" class="success-message hidden bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-8 mt-8">
        <div class="text-center">
            <div class="w-20 h-20 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-green-800 mb-2"><?php esc_html_e('Booking Request Submitted!', 'tznew'); ?></h3>
            <p class="text-green-700 text-lg mb-4"><?php esc_html_e('Thank you for choosing us for your adventure!', 'tznew'); ?></p>
            <div class="bg-white rounded-xl p-4 text-left max-w-md mx-auto">
                <p class="text-green-700 text-sm"><?php esc_html_e('We\'ll get back to you within 24 hours with:', 'tznew'); ?></p>
                <ul class="text-green-700 text-sm mt-2 space-y-1">
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> <?php esc_html_e('Detailed itinerary', 'tznew'); ?></li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> <?php esc_html_e('Accurate pricing', 'tznew'); ?></li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> <?php esc_html_e('Next steps', 'tznew'); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="booking-error" class="error-message hidden bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-2xl p-8 mt-8">
        <div class="text-center">
            <div class="w-20 h-20 bg-red-500 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-red-800 mb-2"><?php esc_html_e('Submission Failed', 'tznew'); ?></h3>
            <p class="text-red-700 text-lg error-text"><?php esc_html_e('There was an error submitting your booking request. Please try again or contact us directly.', 'tznew'); ?></p>
            <button type="button" class="retry-btn mt-4 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300">
                <?php esc_html_e('Try Again', 'tznew'); ?>
            </button>
        </div>
    </div>
    
    </div>
</div>

<style>
.form-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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