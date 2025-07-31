<?php
/**
 * Template part for displaying FAQ section on tours and trekking pages
 *
 * @package TZnew
 */

// Get selected FAQs
$selected_faqs = tznew_get_field_safe('selected_faqs');
$show_general_faqs = tznew_get_field_safe('show_general_faqs');
$post_type = get_post_type();

// Collect all FAQs to display
$faqs_to_display = array();

// Add selected FAQs
if ($selected_faqs && is_array($selected_faqs)) {
    foreach ($selected_faqs as $faq_post) {
        if ($faq_post && isset($faq_post->ID)) {
            $faqs_to_display[] = $faq_post;
        }
    }
}

// Add general FAQs if enabled
if ($show_general_faqs) {
    $general_faqs_args = array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'faq_applicable_to',
                'value' => 'general',
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'faq_applicable_to',
                'value' => $post_type,
                'compare' => 'LIKE'
            )
        ),
        'meta_key' => 'faq_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    );
    
    // Exclude already selected FAQs
    if (!empty($faqs_to_display)) {
        $selected_ids = array_map(function($faq) {
            return $faq->ID;
        }, $faqs_to_display);
        $general_faqs_args['post__not_in'] = $selected_ids;
    }
    
    $general_faqs = get_posts($general_faqs_args);
    if ($general_faqs) {
        $faqs_to_display = array_merge($faqs_to_display, $general_faqs);
    }
}

// Display FAQs if any are found
if (!empty($faqs_to_display)) :
?>
<section class="faq-section mt-12 mb-8">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
            <?php esc_html_e('Frequently Asked Questions', 'tznew'); ?>
        </h2>
        
        <div class="max-w-4xl mx-auto">
            <!-- FAQ Navigation -->
            <?php if (count($faqs_to_display) > 1) : ?>
                <div class="faq-navigation flex justify-between items-center mb-6">
                    <button id="faq-prev" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left mr-2"></i>
                        <?php esc_html_e('Previous', 'tznew'); ?>
                    </button>
                    
                    <div class="faq-counter text-gray-600">
                        <span id="current-faq">1</span> / <span id="total-faqs"><?php echo count($faqs_to_display); ?></span>
                    </div>
                    
                    <button id="faq-next" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <?php esc_html_e('Next', 'tznew'); ?>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            <?php endif; ?>
            
            <!-- Single FAQ Display -->
            <div class="faq-container">
                <?php foreach ($faqs_to_display as $index => $faq_post) : 
                    $question = tznew_get_field_safe('faq_question', $faq_post->ID);
                    $answer = tznew_get_field_safe('faq_answer', $faq_post->ID);
                    $category = tznew_get_field_safe('faq_category', $faq_post->ID);
                    
                    if ($question && $answer) :
                ?>
                    <div class="faq-item bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden <?php echo $index === 0 ? '' : 'hidden'; ?>" data-faq-index="<?php echo $index; ?>">
                        <?php if ($category) : ?>
                            <div class="faq-category-tag px-6 pt-4 pb-2">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $category))); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="faq-question p-6 <?php echo $category ? 'pt-2' : ''; ?> font-medium cursor-pointer flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" 
                             data-faq-toggle="faq-<?php echo esc_attr($index); ?>">
                            <h3 class="text-xl font-semibold text-gray-800 m-0 pr-4">
                                <?php echo esc_html($question); ?>
                            </h3>
                            <span class="dashicons dashicons-arrow-down-alt2 text-blue-600 flex-shrink-0 text-xl"></span>
                        </div>
                        
                        <div class="faq-answer hidden border-t border-gray-200" id="faq-<?php echo esc_attr($index); ?>">
                            <div class="p-6 prose max-w-none text-gray-700 leading-relaxed">
                                <?php echo wp_kses_post($answer); ?>
                            </div>
                        </div>
                    </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    const prevBtn = document.getElementById('faq-prev');
    const nextBtn = document.getElementById('faq-next');
    const currentFaqSpan = document.getElementById('current-faq');
    let currentIndex = 0;
    
    // FAQ toggle functionality
    const faqToggles = document.querySelectorAll('[data-faq-toggle]');
    
    faqToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-faq-toggle');
            const targetAnswer = document.getElementById(targetId);
            const arrow = this.querySelector('.dashicons');
            
            if (targetAnswer) {
                if (targetAnswer.classList.contains('hidden')) {
                    targetAnswer.classList.remove('hidden');
                    arrow.classList.remove('dashicons-arrow-down-alt2');
                    arrow.classList.add('dashicons-arrow-up-alt2');
                } else {
                    targetAnswer.classList.add('hidden');
                    arrow.classList.remove('dashicons-arrow-up-alt2');
                    arrow.classList.add('dashicons-arrow-down-alt2');
                }
            }
        });
    });
    
    // Navigation functionality
    function showFAQ(index) {
        // Hide all FAQs
        faqItems.forEach(function(item) {
            item.classList.add('hidden');
            // Reset any open answers
            const answer = item.querySelector('.faq-answer');
            const arrow = item.querySelector('.dashicons');
            if (answer && !answer.classList.contains('hidden')) {
                answer.classList.add('hidden');
                arrow.classList.remove('dashicons-arrow-up-alt2');
                arrow.classList.add('dashicons-arrow-down-alt2');
            }
        });
        
        // Show current FAQ
        if (faqItems[index]) {
            faqItems[index].classList.remove('hidden');
        }
        
        // Update counter
        if (currentFaqSpan) {
            currentFaqSpan.textContent = index + 1;
        }
        
        // Update button states
        if (prevBtn) {
            prevBtn.disabled = index === 0;
        }
        if (nextBtn) {
            nextBtn.disabled = index === faqItems.length - 1;
        }
    }
    
    // Previous button
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                showFAQ(currentIndex);
            }
        });
    }
    
    // Next button
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (currentIndex < faqItems.length - 1) {
                currentIndex++;
                showFAQ(currentIndex);
            }
        });
    }
    
    // Initialize
    if (faqItems.length > 0) {
        showFAQ(0);
    }
});
</script>

<?php endif; ?>