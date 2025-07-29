<?php
/**
 * Template Name: Testimonials Landing Page
 * 
 * @package TZnew
 */

get_header();
?>

<main class="testimonials-landing-page">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-green-600 via-teal-600 to-blue-600 py-20 lg:py-32">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative container mx-auto px-4 text-center text-white">
            <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                What Our <span class="text-yellow-300">Trekkers Say</span>
            </h1>
            <p class="text-xl lg:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
                Read authentic reviews from adventurers who have experienced the magic of Nepal with us.
            </p>
            <div class="flex items-center justify-center gap-8 text-lg">
                <div class="flex items-center gap-2">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="font-semibold">4.9/5</span>
                </div>
                <div class="text-yellow-300">
                    <i class="fas fa-users mr-2"></i>
                    500+ Happy Trekkers
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-center gap-4">
                <button class="testimonial-filter active" data-filter="all">
                    <i class="fas fa-globe mr-2"></i>All Reviews
                </button>
                <button class="testimonial-filter" data-filter="trekking">
                    <i class="fas fa-mountain mr-2"></i>Trekking
                </button>
                <button class="testimonial-filter" data-filter="tours">
                    <i class="fas fa-route mr-2"></i>Tours
                </button>
                <button class="testimonial-filter" data-filter="featured">
                    <i class="fas fa-star mr-2"></i>Featured
                </button>
                <button class="testimonial-filter" data-filter="recent">
                    <i class="fas fa-clock mr-2"></i>Recent
                </button>
            </div>
        </div>
    </section>

    <!-- Testimonials Grid Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- All Testimonials -->
            <div id="testimonials-all" class="testimonials-section active">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Customer Reviews
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Discover what makes our trekking and touring experiences unforgettable through the words of our guests.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <?php echo do_shortcode('[testimonials limit="9" layout="grid" columns="3" show_rating="true" show_date="true"]'); ?>
                </div>
                
                <div class="text-center">
                    <button id="load-more-testimonials" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
                        <i class="fas fa-plus mr-2"></i>Load More Reviews
                    </button>
                </div>
            </div>

            <!-- Trekking Testimonials -->
            <div id="testimonials-trekking" class="testimonials-section">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-mountain text-green-600 mr-3"></i>
                        Trekking Reviews
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Hear from trekkers who conquered the Himalayas with us.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php 
                    // Get trekking testimonials
                    $trekking_posts = get_posts(array(
                        'post_type' => 'trekking',
                        'posts_per_page' => -1,
                        'fields' => 'ids'
                    ));
                    
                    if (!empty($trekking_posts)) {
                        $trekking_ids = implode(',', $trekking_posts);
                        echo do_shortcode('[testimonials limit="9" layout="grid" columns="3" show_rating="true" show_date="true"]');
                    }
                    ?>
                </div>
            </div>

            <!-- Tours Testimonials -->
            <div id="testimonials-tours" class="testimonials-section">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-route text-blue-600 mr-3"></i>
                        Tour Reviews
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Read about amazing cultural and sightseeing experiences.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php 
                    // Get tour testimonials
                    $tour_posts = get_posts(array(
                        'post_type' => 'tours',
                        'posts_per_page' => -1,
                        'fields' => 'ids'
                    ));
                    
                    if (!empty($tour_posts)) {
                        $tour_ids = implode(',', $tour_posts);
                        echo do_shortcode('[testimonials limit="9" layout="grid" columns="3" show_rating="true" show_date="true"]');
                    }
                    ?>
                </div>
            </div>

            <!-- Featured Testimonials -->
            <div id="testimonials-featured" class="testimonials-section">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-star text-yellow-500 mr-3"></i>
                        Featured Reviews
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Our most outstanding customer experiences and testimonials.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php echo do_shortcode('[testimonials limit="9" featured="true" layout="grid" columns="3" show_rating="true" show_date="true"]'); ?>
                </div>
            </div>

            <!-- Recent Testimonials -->
            <div id="testimonials-recent" class="testimonials-section">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-clock text-teal-600 mr-3"></i>
                        Recent Reviews
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Latest feedback from our recent adventurers.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php echo do_shortcode('[testimonials limit="9" layout="grid" columns="3" show_rating="true" show_date="true" orderby="date" order="DESC"]'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-green-600 to-teal-600">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center text-white">
                <div class="stat-item">
                    <div class="text-4xl lg:text-5xl font-bold mb-2">
                        <?php 
                        $total_testimonials = wp_count_posts('testimonial');
                        echo $total_testimonials->publish;
                        ?>+
                    </div>
                    <div class="text-lg opacity-90">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="text-4xl lg:text-5xl font-bold mb-2">4.9</div>
                    <div class="text-lg opacity-90">Average Rating</div>
                </div>
                <div class="stat-item">
                    <div class="text-4xl lg:text-5xl font-bold mb-2">15+</div>
                    <div class="text-lg opacity-90">Years Experience</div>
                </div>
                <div class="stat-item">
                    <div class="text-4xl lg:text-5xl font-bold mb-2">50+</div>
                    <div class="text-lg opacity-90">Destinations</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                Ready to Create Your Own Adventure Story?
            </h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Join thousands of satisfied trekkers and create memories that will last a lifetime.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo esc_url(home_url('/trekking')); ?>" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
                    <i class="fas fa-mountain mr-2"></i>Browse Trekking
                </a>
                <a href="<?php echo esc_url(home_url('/tours')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
                    <i class="fas fa-route mr-2"></i>Explore Tours
                </a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="border-2 border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
                    <i class="fas fa-envelope mr-2"></i>Contact Us
                </a>
            </div>
        </div>
    </section>
</main>

<style>
.testimonials-landing-page .testimonial-filter {
    @apply bg-white hover:bg-green-50 text-gray-700 hover:text-green-600 px-6 py-3 rounded-lg font-semibold transition-all duration-300 border border-gray-200 hover:border-green-300;
}

.testimonials-landing-page .testimonial-filter.active {
    @apply bg-green-600 text-white border-green-600;
}

.testimonials-landing-page .testimonials-section {
    @apply hidden;
}

.testimonials-landing-page .testimonials-section.active {
    @apply block;
}

.testimonials-landing-page .stat-item {
    @apply transform hover:scale-105 transition-transform duration-300;
}

/* Enhanced testimonial cards */
.testimonials-landing-page .testimonial-card {
    @apply bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100;
}

.testimonials-landing-page .testimonial-card .testimonial-rating {
    @apply mb-4;
}

.testimonials-landing-page .testimonial-card .star-rating {
    @apply flex gap-1;
}

.testimonials-landing-page .testimonial-card .star-rating .filled {
    @apply text-yellow-400;
}

.testimonials-landing-page .testimonial-card .star-rating .empty {
    @apply text-gray-300;
}

.testimonials-landing-page .testimonial-card .testimonial-excerpt {
    @apply text-gray-700 mb-4 italic leading-relaxed;
}

.testimonials-landing-page .testimonial-card .author-name {
    @apply font-semibold text-gray-900 mb-1;
}

.testimonials-landing-page .testimonial-card .author-location {
    @apply text-sm text-gray-600 flex items-center gap-1;
}

.testimonials-landing-page .testimonial-card .visit-date {
    @apply text-sm text-gray-500 flex items-center gap-1;
}

.testimonials-landing-page .testimonial-card.featured {
    @apply ring-2 ring-yellow-400 relative;
}

.testimonials-landing-page .testimonial-card .featured-badge {
    @apply absolute -top-3 -right-3 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('.testimonial-filter');
    const testimonialSections = document.querySelectorAll('.testimonials-section');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Show/hide sections
            testimonialSections.forEach(section => {
                section.classList.remove('active');
            });
            
            const targetSection = document.getElementById(`testimonials-${filter}`);
            if (targetSection) {
                targetSection.classList.add('active');
            }
        });
    });
    
    // Load more functionality (placeholder)
    const loadMoreBtn = document.getElementById('load-more-testimonials');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // This would typically load more testimonials via AJAX
            alert('Load more functionality would be implemented here');
        });
    }
});
</script>

<?php get_footer(); ?>