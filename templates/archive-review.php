<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отзывы</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto+Condensed:wght@600&display=swap" rel="stylesheet">
</head>
<body>
<div class="reviews-page">
    <div class="breadcrumb">
        <span class="breadcrumb-item">
            <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
        </span>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-item">Отзывы</span>
    </div>
    
    <h1 class="page-title">ОТЗЫВЫ</h1>
    
    <?php
    $total_reviews = wp_count_posts('review')->publish;
    $filtered_count = $total_reviews; // Will be updated via JS
    ?>
    <p class="reviews-count"><?php echo number_format($total_reviews, 0, ',', ' '); ?></p>
    
    <div class="divider-top"></div>
    
    <!-- Desktop filters -->
    <div class="filters">
        <div class="filter-group">
            <label class="filter-label">Город</label>
            <div class="filter-value" id="filter-city">
                <span class="filter-text">Все города</span>
                <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 9.29289C7.68342 8.90237 8.31658 8.90237 8.70711 9.29289L12 12.5858L15.2929 9.29289C15.6834 8.90237 16.3166 8.90237 16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289Z" fill="#131313"/>
                </svg>
                <div class="filter-dropdown" id="city-dropdown">
                    <div class="filter-option" data-value="">
                        <span>Все города</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                    <?php
                    // Get cities from meta fields instead of taxonomy
                    global $wpdb;
                    $meta_cities = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                        WHERE meta_key = %s AND meta_value != '' 
                        ORDER BY meta_value ASC",
                        '_review_city'
                    ));
                    if (!empty($meta_cities)) {
                        foreach ($meta_cities as $city_name) {
                            $city_slug = sanitize_title($city_name);
                            echo '<div class="filter-option" data-value="' . esc_attr($city_slug) . '">';
                            echo '<span>' . esc_html($city_name) . '</span>';
                            echo '<svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>';
                            echo '</svg>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Изделие</label>
            <div class="filter-value" id="filter-product">
                <span class="filter-text">Все изделия</span>
                <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 9.29289C7.68342 8.90237 8.31658 8.90237 8.70711 9.29289L12 12.5858L15.2929 9.29289C15.6834 8.90237 16.3166 8.90237 16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289Z" fill="#131313"/>
                </svg>
                <div class="filter-dropdown" id="product-dropdown">
                    <div class="filter-option" data-value="">
                        <span>Все изделия</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                    <?php
                    // Get products from meta fields instead of taxonomy
                    global $wpdb;
                    $meta_products = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                        WHERE meta_key = %s AND meta_value != '' 
                        ORDER BY meta_value ASC",
                        '_review_product'
                    ));
                    if (!empty($meta_products)) {
                        foreach ($meta_products as $product_name) {
                            $product_slug = sanitize_title($product_name);
                            echo '<div class="filter-option" data-value="' . esc_attr($product_slug) . '">';
                            echo '<span>' . esc_html($product_name) . '</span>';
                            echo '<svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>';
                            echo '</svg>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Год</label>
            <div class="filter-value" id="filter-year">
                <span class="filter-text">За все время</span>
                <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 9.29289C7.68342 8.90237 8.31658 8.90237 8.70711 9.29289L12 12.5858L15.2929 9.29289C15.6834 8.90237 16.3166 8.90237 16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289Z" fill="#131313"/>
                </svg>
                <div class="filter-dropdown" id="year-dropdown">
                    <div class="filter-option" data-value="">
                        <span>За все время</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                    <?php
                    // Get years from meta fields instead of taxonomy
                    global $wpdb;
                    $meta_years = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                        WHERE meta_key = %s AND meta_value != '' 
                        ORDER BY meta_value DESC",
                        '_review_year'
                    ));
                    if (!empty($meta_years)) {
                        foreach ($meta_years as $year_name) {
                            $year_slug = sanitize_title($year_name);
                            echo '<div class="filter-option" data-value="' . esc_attr($year_slug) . '">';
                            echo '<span>' . esc_html($year_name) . '</span>';
                            echo '<svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>';
                            echo '</svg>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Формат</label>
            <div class="filter-value" id="filter-format">
                <span class="filter-text">Все отзывы</span>
                <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 9.29289C7.68342 8.90237 8.31658 8.90237 8.70711 9.29289L12 12.5858L15.2929 9.29289C15.6834 8.90237 16.3166 8.90237 16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289Z" fill="#131313"/>
                </svg>
                <div class="filter-dropdown" id="format-dropdown">
                    <div class="filter-option" data-value="">
                        <span>Все отзывы</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                    <div class="filter-option" data-value="video">
                        <span>Только с видео</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile filter button -->
    <div class="mobile-filter-btn-wrapper">
        <button class="mobile-filter-btn" id="mobile-filter-btn">
            <span>Фильтровать</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="17" cy="8" r="2" stroke="#131313" stroke-width="2"/>
                <circle cx="7" cy="16" r="2" stroke="#131313" stroke-width="2"/>
                <rect x="4" y="7" width="11" height="2" rx="1" fill="#131313"/>
                <rect x="9" y="15" width="11" height="2" rx="1" fill="#131313"/>
            </svg>
        </button>
    </div>
    <div class="mobile-filter-divider-bottom"></div>
    
    <!-- Mobile filter option bottom sheet (for individual filter selection) -->
    <div class="mobile-filter-bottom-sheet" id="mobile-filter-bottom-sheet">
        <div class="mobile-filter-bottom-sheet-backdrop"></div>
        <div class="mobile-filter-bottom-sheet-content">
            <div class="mobile-filter-bottom-sheet-header">
                <h3 class="mobile-filter-bottom-sheet-title" id="mobile-filter-bottom-sheet-title">Выберите значение</h3>
            </div>
            <div class="mobile-filter-bottom-sheet-options" id="mobile-filter-bottom-sheet-options">
                <!-- Options will be populated dynamically -->
            </div>
        </div>
    </div>
    
    <!-- Mobile filter popup -->
    <div class="mobile-filter-popup" id="mobile-filter-popup">
        <div class="mobile-filter-popup-content">
            <div class="mobile-filter-header">
                <h2 class="mobile-filter-title">ФИЛЬТРЫ</h2>
                <button class="mobile-filter-close" id="mobile-filter-close">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="24" r="24" fill="white"/>
                        <path d="M19 19L29 29M29 19L19 29" stroke="#131313" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            <p class="mobile-filter-count" id="mobile-filter-count"><?php echo number_format($total_reviews, 0, ',', ' '); ?> / <?php echo number_format($total_reviews, 0, ',', ' '); ?></p>
            
            <div class="mobile-filters">
        <div class="filter-group">
            <label class="filter-label">Город</label>
            <div class="filter-value" id="filter-city">
                <span class="filter-text">Все города</span>
                <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 9.29289C7.68342 8.90237 8.31658 8.90237 8.70711 9.29289L12 12.5858L15.2929 9.29289C15.6834 8.90237 16.3166 8.90237 16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289Z" fill="#131313"/>
                </svg>
                <div class="filter-dropdown" id="city-dropdown">
                    <div class="filter-dropdown-header">
                        <span class="filter-dropdown-header-text">Выберите значение</span>
                    </div>
                    <div class="filter-option" data-value="">
                        <span>Все города</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                    <?php
                    // Get cities from meta fields instead of taxonomy
                    global $wpdb;
                    $meta_cities = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                        WHERE meta_key = %s AND meta_value != '' 
                        ORDER BY meta_value ASC",
                        '_review_city'
                    ));
                    if (!empty($meta_cities)) {
                        foreach ($meta_cities as $city_name) {
                            $city_slug = sanitize_title($city_name);
                            echo '<div class="filter-option" data-value="' . esc_attr($city_slug) . '">';
                            echo '<span>' . esc_html($city_name) . '</span>';
                            echo '<svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>';
                            echo '</svg>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Изделие</label>
            <div class="filter-value" id="filter-product">
                <span class="filter-text">Все изделия</span>
                <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 9.29289C7.68342 8.90237 8.31658 8.90237 8.70711 9.29289L12 12.5858L15.2929 9.29289C15.6834 8.90237 16.3166 8.90237 16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289Z" fill="#131313"/>
                </svg>
                <div class="filter-dropdown" id="product-dropdown">
                    <div class="filter-dropdown-header">
                        <span class="filter-dropdown-header-text">Выберите значение</span>
                    </div>
                    <div class="filter-option" data-value="">
                        <span>Все изделия</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                    <?php
                    // Get products from meta fields instead of taxonomy
                    global $wpdb;
                    $meta_products = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                        WHERE meta_key = %s AND meta_value != '' 
                        ORDER BY meta_value ASC",
                        '_review_product'
                    ));
                    if (!empty($meta_products)) {
                        foreach ($meta_products as $product_name) {
                            $product_slug = sanitize_title($product_name);
                            echo '<div class="filter-option" data-value="' . esc_attr($product_slug) . '">';
                            echo '<span>' . esc_html($product_name) . '</span>';
                            echo '<svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>';
                            echo '</svg>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Год</label>
            <div class="filter-value" id="filter-year">
                <span class="filter-text">За все время</span>
                <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 9.29289C7.68342 8.90237 8.31658 8.90237 8.70711 9.29289L12 12.5858L15.2929 9.29289C15.6834 8.90237 16.3166 8.90237 16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289Z" fill="#131313"/>
                </svg>
                <div class="filter-dropdown" id="year-dropdown">
                    <div class="filter-dropdown-header">
                        <span class="filter-dropdown-header-text">Выберите значение</span>
                    </div>
                    <div class="filter-option" data-value="">
                        <span>За все время</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                    <?php
                    // Get years from meta fields instead of taxonomy
                    global $wpdb;
                    $meta_years = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                        WHERE meta_key = %s AND meta_value != '' 
                        ORDER BY meta_value DESC",
                        '_review_year'
                    ));
                    if (!empty($meta_years)) {
                        foreach ($meta_years as $year_name) {
                            $year_slug = sanitize_title($year_name);
                            echo '<div class="filter-option" data-value="' . esc_attr($year_slug) . '">';
                            echo '<span>' . esc_html($year_name) . '</span>';
                            echo '<svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>';
                            echo '</svg>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Формат</label>
            <div class="filter-value" id="filter-format">
                <span class="filter-text">Все отзывы</span>
                <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 9.29289C7.68342 8.90237 8.31658 8.90237 8.70711 9.29289L12 12.5858L15.2929 9.29289C15.6834 8.90237 16.3166 8.90237 16.7071 9.29289C17.0976 9.68342 17.0976 10.3166 16.7071 10.7071L12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289Z" fill="#131313"/>
                </svg>
                <div class="filter-dropdown" id="format-dropdown">
                    <div class="filter-dropdown-header">
                        <span class="filter-dropdown-header-text">Выберите значение</span>
                    </div>
                    <div class="filter-option" data-value="">
                        <span>Все отзывы</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                    <div class="filter-option" data-value="video">
                        <span>Только с видео</span>
                        <svg class="filter-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile filter popup buttons -->
    <div class="mobile-filter-actions">
        <button class="mobile-filter-reset" id="mobile-filter-reset">Сбросить фильтры</button>
        <button class="mobile-filter-apply" id="mobile-filter-apply">Применить</button>
    </div>
    </div>
    </div>
    
    <div class="gallery" id="reviews-gallery">
        <?php
        // Create custom query for reviews
        $reviews_query = new WP_Query(array(
            'post_type' => 'review',
            'post_status' => 'publish',
            'posts_per_page' => 24,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        ));
        
        if ($reviews_query->have_posts()) {
            while ($reviews_query->have_posts()) {
                $reviews_query->the_post();
                $gallery_ids = get_post_meta(get_the_ID(), '_review_gallery', true);
                $gallery_ids = $gallery_ids ? explode(',', $gallery_ids) : array();
                $first_image_id = !empty($gallery_ids) ? $gallery_ids[0] : get_post_thumbnail_id();
                
                if ($first_image_id) {
                    $image_url = wp_get_attachment_image_url($first_image_id, 'medium');
                    $permalink = get_permalink();
                    ?>
                    <a href="<?php echo esc_url($permalink); ?>" class="review-gallery-item">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" />
                    </a>
                    <?php
                }
            }
            wp_reset_postdata();
        } else {
            ?>
            <p style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--dark-03);">
                Отзывы не найдены
            </p>
            <?php
        }
        ?>
    </div>
    
    <!-- Empty state message (hidden by default, shown via JS when filters result in 0 reviews) -->
    <div class="reviews-empty-state" id="reviews-empty-state" style="display: none;">
        <p class="reviews-empty-message">По выбранным фильтрам отзывы не найдены</p>
        <button class="reviews-reset-filters-btn" id="reviews-reset-filters-btn">Сбросить фильтры</button>
    </div>
    
    <?php 
    // Use the custom query for pagination
    if (isset($reviews_query) && $reviews_query->max_num_pages > 1) {
        ?>
        <button class="load-more-btn" id="load-more-reviews" data-page="1" data-max-pages="<?php echo esc_attr($reviews_query->max_num_pages); ?>">Показать еще</button>
        <?php
    } else {
        ?>
        <button class="load-more-btn" id="load-more-reviews" data-page="1" style="display: none;">Показать еще</button>
        <?php
    }
    ?>
    
</div>

<style>
:root {
    --dark-01: #131313;
    --dark-02: #2B2B2B;
    --dark-03: #8C8C8C;
    --dark-04: #E7E7E7;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Моноширинные цифры для всех элементов - используем font-variant-numeric */
/* lining-nums - одинаковую высоту, tabular-nums - одинаковую ширину */
*, *::before, *::after {
    font-variant-numeric: lining-nums tabular-nums;
    -webkit-font-feature-settings: "lnum" 1, "tnum" 1;
    font-feature-settings: "lnum" 1, "tnum" 1;
}

/* Используем monospace шрифт для цифр через unicode-range */
@font-face {
    font-family: 'MonospaceNumbers';
    src: local('Courier New'), local('Monaco'), local('Consolas'), monospace;
    unicode-range: U+0030-0039; /* 0-9 */
}

body {
    font-family: 'Raleway', 'MonospaceNumbers', -apple-system, Roboto, Helvetica, sans-serif;
    background: #FFF;
    color: var(--dark-01);
}

.reviews-page {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 64px;
    background: #FFF;
    border-radius: 16px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 191px 0 32px;
    justify-content: center;
}

.breadcrumb-item {
    color: var(--dark-03);
    font-size: 12px;
    font-weight: 400;
    line-height: 20px;
}

.breadcrumb-item a {
    color: var(--dark-03);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: var(--dark-01);
}

.breadcrumb-separator {
    color: var(--dark-03);
    font-size: 12px;
    font-weight: 400;
    line-height: 20px;
}

.page-title {
    font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
    font-size: 80px;
    font-weight: 600;
    line-height: 80px;
    letter-spacing: 4px;
    text-transform: uppercase;
    text-align: center;
    color: var(--dark-01);
    margin-bottom: 24px;
}

.reviews-count {
    font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
    font-size: 40px;
    font-weight: 600;
    line-height: 40px;
    letter-spacing: 2px;
    text-transform: uppercase;
    text-align: center;
    color: var(--dark-03);
    margin-bottom: 80px;
}

.divider-top,
.divider-bottom {
    width: 100%;
    height: 1px;
    background: var(--dark-04);
    margin-bottom: 32px;
    border: none;
    padding: 0;
    box-sizing: border-box;
}

.divider-bottom {
    margin-top: 32px;
    margin-bottom: 0;
}

.filters {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 40px;
    margin-bottom: 32px;
    width: 100%;
    position: relative;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
    position: relative;
}

.filter-label {
    color: var(--dark-03);
    font-size: 14px;
    font-weight: 500;
    line-height: 24px;
}

.filter-value {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    position: relative;
}

.filter-text {
    color: var(--dark-01);
    font-size: 14px;
    font-weight: 500;
    line-height: 24px;
}

.arrow-icon {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.filter-value.active .arrow-icon {
    transform: rotate(180deg);
}

.filter-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #FFFFFF !important;
    border: 1px solid var(--dark-04);
    border-radius: 0;
    margin-top: 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 100;
    display: none;
    box-shadow: 0 10px 40px 0 rgba(0, 0, 0, 0.1) !important;
    padding: 8px 0;
}

.filter-value.active .filter-dropdown {
    display: block;
}

.filter-option {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 400;
    line-height: 28px;
    color: var(--dark-01);
    transition: background 0.2s ease;
    font-family: 'Raleway', -apple-system, Roboto, Helvetica, sans-serif;
}

.filter-option span {
    font-size: 14px;
    flex: 1;
}

.filter-option:hover {
    background: #F6F4F2;
}

.filter-option.active {
    background: #F6F4F2;
}

.filter-option-check {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
    display: none;
}

.filter-option.active .filter-option-check {
    display: block;
}

.gallery {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 8px;
    margin-bottom: 32px;
}

.gallery-column {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.review-gallery-item {
    display: block;
    width: 100%;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.review-gallery-item:hover {
    transform: scale(1.02);
}

.review-gallery-item img {
    width: 100%;
    height: auto;
    object-fit: cover;
    display: block;
}

.load-more-btn {
    display: flex;
    width: 736px;
    padding: 18px 24px;
    justify-content: center;
    align-items: center;
    border: 1px solid var(--dark-04);
    background: transparent;
    color: var(--dark-02);
    font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
    font-size: 16px;
    font-weight: 600;
    line-height: 28px;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    cursor: pointer;
    margin: 0 auto 32px;
    transition: all 0.3s ease;
}

.load-more-btn:hover {
    background: var(--dark-04);
}

.load-more-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Empty state message */
.reviews-empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;
}

.reviews-empty-message {
    color: var(--dark-03);
    font-family: 'Raleway', -apple-system, Roboto, Helvetica, sans-serif;
    font-size: 16px;
    font-weight: 400;
    line-height: 24px;
    margin: 0;
}

.reviews-reset-filters-btn {
    display: inline-flex;
    padding: 14px 24px;
    justify-content: center;
    align-items: center;
    border: 1px solid var(--dark-02);
    background: transparent;
    color: var(--dark-01);
    font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: 600;
    line-height: 20px;
    letter-spacing: 0.7px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
}

.reviews-reset-filters-btn:hover {
    background: var(--dark-04);
}

.reviews-reset-filters-btn:active {
    background: var(--dark-04);
}

/* Hide mobile filter button on desktop */
.mobile-filter-btn-wrapper,
.mobile-filter-popup,
.mobile-filter-bottom-sheet {
    display: none;
}

/* Ensure mobile bottom sheet is hidden on desktop (above 640px) */
@media screen and (min-width: 641px) {
    .mobile-filter-bottom-sheet,
    .mobile-filter-bottom-sheet.active {
        display: none !important;
    }
}

@media screen and (max-width: 1400px) {
    .reviews-page {
        padding: 0 40px;
    }
    
    .gallery {
        grid-template-columns: repeat(6, 1fr);
    }
    
    .gallery-column:nth-child(n+7) {
        display: none;
    }
}

@media screen and (max-width: 1200px) {
    .page-title {
        font-size: 60px;
        line-height: 60px;
        letter-spacing: 3px;
    }
    
    .reviews-count {
        font-size: 32px;
        line-height: 32px;
        margin-bottom: 60px;
    }
    
    .gallery {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .gallery-column:nth-child(n+5) {
        display: none;
    }
    
    .load-more-btn {
        width: 100%;
        max-width: 600px;
    }
}

@media screen and (max-width: 960px) {
    .reviews-page {
        padding: 0 24px;
    }

    .filters {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    
    .gallery {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .gallery-column:nth-child(n+4) {
        display: none;
    }
}

@media screen and (max-width: 640px) {
    .reviews-page {
        padding: 0 16px;
    }
    
    .breadcrumb {
        padding: 100px 0 24px;
    }
    
    .page-title {
        font-size: 40px;
        line-height: 40px;
        letter-spacing: 2px;
        margin-bottom: 16px;
    }
    
    .reviews-count {
        font-size: 24px;
        line-height: 24px;
        margin-bottom: 40px;
    }
    
    /* Hide desktop filters on mobile */
    .filters {
        display: none;
    }
    
    .gallery {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .gallery-column:nth-child(n+5) {
        display: none;
    }
    
    .load-more-btn {
        width: 100%;
        font-size: 14px;
        padding: 14px 20px;
    }
    
    /* Hide desktop filters on mobile */
    .filters {
        display: none;
    }
    
    /* Show mobile filter button on mobile */
    .mobile-filter-btn-wrapper,
    .mobile-filter-popup {
        display: block;
    }
    
    /* Override divider-top margin on mobile */
    .divider-top {
        margin-bottom: 17px;
    }
    
    /* Mobile filter button wrapper */
    .mobile-filter-btn-wrapper {
        width: 100%;
        max-width: 343px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 0;
        border: none;
        border-top: none;
        border-bottom: none;
    }
    
    .mobile-filter-btn-wrapper::before,
    .mobile-filter-btn-wrapper::after {
        display: none;
    }
    
    .mobile-filter-divider-bottom {
        width: 100%;
        height: 1px;
        background: var(--dark-04);
        margin: 17px 0 48px 0;
        border: none;
        padding: 0;
        box-sizing: border-box;
    }
    
    /* Mobile filter button */
    .mobile-filter-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 0;
        margin: 0;
        background: transparent;
        border: none !important;
        border-top: none !important;
        border-bottom: none !important;
        border-left: none !important;
        border-right: none !important;
        border-radius: 0;
        outline: none !important;
        box-shadow: none !important;
        color: var(--dark-01);
        font-family: 'Raleway', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 14px;
        font-weight: 500;
        font-style: normal;
        line-height: 24px;
        letter-spacing: 0;
        text-transform: none;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .mobile-filter-btn::before,
    .mobile-filter-btn::after {
        display: none !important;
        content: none !important;
    }
    
    .mobile-filter-btn:focus,
    .mobile-filter-btn:active,
    .mobile-filter-btn:hover {
        outline: none !important;
        box-shadow: none !important;
        border-top: none !important;
    }
    
    .mobile-filter-btn:hover {
        opacity: 0.8;
    }
    
    .mobile-filter-btn svg {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }
    
    /* Mobile filter popup */
    .mobile-filter-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow-y: auto;
    }
    
    .mobile-filter-popup.active {
        display: block;
    }
    
    .mobile-filter-popup-content {
        position: relative;
        width: 100%;
        min-height: 100vh;
        background: #FFFFFF;
        border-radius: 0;
        padding: 0 16px 32px;
        margin-top: 0;
    }
    
    .mobile-filter-header {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding-top: 120px;
        margin-bottom: 0;
        min-height: 48px;
    }
    
    .mobile-filter-title {
        font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 40px;
        font-weight: 600;
        line-height: 40px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--dark-01);
        margin: 0;
    }
    
    .mobile-filter-close {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 48px;
        height: 48px;
        background: transparent;
        border: 1px solid #E7E7E7;
        border-radius: 50%;
        cursor: pointer;
        padding: 0;
        z-index: 10;
    }
    
    .mobile-filter-close {
        position: absolute;
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
        width: 48px;
        height: 48px;
        background: transparent;
        border: 1px solid #E7E7E7;
        border-radius: 50%;
        cursor: pointer;
        padding: 0;
        z-index: 10;
    }
    
    .mobile-filter-close svg {
        width: 100%;
        height: 100%;
        display: block;
    }
    
    .mobile-filter-count {
        font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 20px;
        font-weight: 600;
        line-height: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-align: center;
        color: var(--dark-03);
        margin: 16px 0 48px 0;
    }
    
    .mobile-filters {
        display: flex;
        flex-direction: column;
        gap: 0;
        margin-bottom: 24px;
    }
    
    .mobile-filters .filter-group {
        width: 100%;
        position: relative;
    }
    
    .mobile-filters .filter-group::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 1px;
        background: var(--dark-04);
        border: none;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        z-index: 1;
    }
    
    .mobile-filters .filter-label {
        padding-top: 17px;
        margin-bottom: 0;
        color: var(--dark-03);
        font-family: 'Raleway', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 14px;
        font-weight: 500;
        font-style: normal;
        line-height: 24px;
        letter-spacing: 0;
    }
    
    .mobile-filters .filter-value {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 0 16px 0;
        border-bottom: none;
        cursor: pointer;
    }
    
    .mobile-filters .filter-group:last-child .filter-value {
        border-bottom: 1px solid var(--dark-04);
    }
    
    .mobile-filters .filter-text {
        color: var(--dark-01);
        font-family: 'Raleway', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 14px;
        font-weight: 500;
        font-style: normal;
        line-height: 24px;
        letter-spacing: 0;
    }
    
    .mobile-filters .arrow-icon {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }
    
    .mobile-filter-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 24px;
    }
    
    .mobile-filter-apply,
    .mobile-filter-reset {
        width: 100%;
        padding: 14px 24px;
        border: none;
        border-radius: 0;
        font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 14px;
        font-weight: 600;
        line-height: 20px;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .mobile-filter-apply {
        background: var(--dark-01);
        color: #FFFFFF;
    }
    
    .mobile-filter-apply:hover {
        opacity: 0.9;
    }
    
    .mobile-filter-reset {
        background: transparent !important;
        color: var(--dark-01);
        border: 1px solid var(--dark-02);
        height: 48px;
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
    }
    
    .mobile-filter-reset:hover {
        background: var(--dark-04);
    }
    
    .mobile-filter-reset:active,
    .mobile-filter-reset:focus,
    .mobile-filter-reset:active:focus,
    .mobile-filter-reset:focus:active {
        background: transparent !important;
        outline: none !important;
        -webkit-tap-highlight-color: transparent;
    }
    
    .mobile-filter-reset:active:hover,
    .mobile-filter-reset:focus:hover {
        background: var(--dark-04);
    }
    
    .mobile-filter-apply {
        height: 48px;
    }
    
    /* Mobile filter bottom sheet */
    .mobile-filter-bottom-sheet {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 3000;
        display: none;
        pointer-events: none;
    }
    
    .mobile-filter-bottom-sheet.active {
        display: block;
        pointer-events: all;
    }
    
    .mobile-filter-bottom-sheet-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .mobile-filter-bottom-sheet.active .mobile-filter-bottom-sheet-backdrop {
        opacity: 1;
    }
    
    .mobile-filter-bottom-sheet-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: #FFFFFF;
        border-radius: 0;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        transform: translateY(100%);
        transition: transform 0.3s ease;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .mobile-filter-bottom-sheet.active .mobile-filter-bottom-sheet-content {
        transform: translateY(0);
    }
    
    .mobile-filter-bottom-sheet-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 16px 16px;
        border-bottom: 1px solid var(--dark-04);
        flex-shrink: 0;
    }
    
    .mobile-filter-bottom-sheet-title {
        font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 20px;
        font-weight: 600;
        line-height: 24px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--dark-01);
        margin: 0;
    }
    
    .mobile-filter-bottom-sheet-options {
        flex: 1;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0;
    }
    
    .mobile-filter-bottom-sheet-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        cursor: pointer;
        font-family: 'Raleway', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 14px;
        font-weight: 400;
        line-height: 28px;
        color: var(--dark-01);
        border-bottom: 1px solid var(--dark-04);
        transition: background 0.2s ease;
    }
    
    .mobile-filter-bottom-sheet-option:last-child {
        border-bottom: none;
    }
    
    .mobile-filter-bottom-sheet-option:hover,
    .mobile-filter-bottom-sheet-option.active {
        background: #F6F4F2;
    }
    
    .mobile-filter-bottom-sheet-option.hidden {
        display: none;
    }
    
    .mobile-filter-bottom-sheet-option span {
        flex: 1;
    }
    
    .mobile-filter-bottom-sheet-option-check {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
        display: none;
    }
    
    .mobile-filter-bottom-sheet-option.active .mobile-filter-bottom-sheet-option-check {
        display: block;
    }
    
    /* Mobile filter dropdown - hide on mobile, use bottom sheet instead */
    .mobile-filters .filter-group {
        position: relative;
    }
    
    .mobile-filters .filter-dropdown {
        display: none !important;
    }
    
    .mobile-filters .filter-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 10px 16px;
        border-bottom: 1px solid var(--dark-04);
    }
    
    .mobile-filters .filter-dropdown-header-text {
        font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 14px;
        font-weight: 600;
        line-height: 28px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--dark-01);
        text-align: left;
    }
    
    .mobile-filters .filter-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        cursor: pointer;
        font-family: 'Raleway', -apple-system, Roboto, Helvetica, sans-serif;
        font-size: 14px;
        font-weight: 400;
        line-height: 28px;
        color: var(--dark-01);
        border-bottom: 1px solid var(--dark-04);
    }
    
    .mobile-filters .filter-option:last-child {
        border-bottom: none;
    }
    
    .mobile-filters .filter-option span {
        font-size: 14px;
    }
    
    .mobile-filters .filter-option:hover {
        background: #F6F4F2;
    }
    
    .mobile-filters .filter-option.active {
        background: #F6F4F2;
    }
    
    .mobile-filters .filter-option-check {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
        display: none;
    }
    
    .mobile-filters .filter-option.active .filter-option-check {
        display: block;
    }
    
    .mobile-filters .filter-value.active .arrow-icon {
        transform: rotate(180deg);
    }
    
    .mobile-filters .filter-option.active {
        background: #F6F4F2 !important;
    }
    
    .mobile-filters .filter-dropdown .filter-option:first-of-type.active {
        background: #F6F4F2 !important;
    }
    
    .mobile-filters .filter-group:last-child .filter-dropdown {
        border-bottom: none;
    }
    
    .mobile-filters .filter-group:last-child .filter-dropdown .filter-option:last-child {
        border-bottom: none;
        background: #FFFFFF !important;
    }
    
    .mobile-filters .filter-group:last-child .filter-dropdown .filter-option:last-child.active {
        background: #F6F4F2 !important;
    }
    
    .mobile-filters .filter-group:last-child .filter-dropdown .filter-option:last-child:not(.active) {
        background: #FFFFFF !important;
    }
    
    /* Mobile empty state message */
    .reviews-empty-state {
        padding: 60px 16px;
        gap: 20px;
    }
    
    .reviews-empty-message {
        font-size: 14px;
        line-height: 20px;
    }
    
    .reviews-reset-filters-btn {
        width: 100%;
        max-width: 343px;
        font-size: 14px;
        padding: 14px 24px;
    }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var reviewsAjax = {
    ajaxurl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
    nonce: '<?php echo esc_js(wp_create_nonce('reviews_nonce')); ?>',
    totalReviews: <?php echo intval($total_reviews); ?>
};
</script>
<script>
// Mobile filter popup
document.addEventListener('DOMContentLoaded', function() {
    var filterBtn = document.getElementById('mobile-filter-btn');
    var filterPopup = document.getElementById('mobile-filter-popup');
    var filterClose = document.getElementById('mobile-filter-close');
    var filterApply = document.getElementById('mobile-filter-apply');
    var filterReset = document.getElementById('mobile-filter-reset');
    var filterCount = document.getElementById('mobile-filter-count');
    
    if (filterBtn && filterPopup) {
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            filterPopup.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        if (filterClose) {
            filterClose.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                filterPopup.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        filterPopup.addEventListener('click', function(e) {
            if (e.target === filterPopup) {
                filterPopup.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        if (filterApply) {
            filterApply.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // Apply filters logic will be handled by existing filter system
                // Trigger filter change events for mobile filters
                var mobileFilters = document.querySelectorAll('.mobile-filters .filter-value');
                mobileFilters.forEach(function(filter) {
                    var desktopFilter = document.querySelector('.filters .filter-value[id="' + filter.id + '"]');
                    if (desktopFilter) {
                        var selectedOption = filter.querySelector('.filter-option.active') || filter.querySelector('.filter-option');
                        if (selectedOption) {
                            var desktopText = desktopFilter.querySelector('.filter-text');
                            if (desktopText) {
                                desktopText.textContent = selectedOption.textContent;
                            }
                            // Trigger click on desktop filter to apply
                            selectedOption.dispatchEvent(new Event('click'));
                        }
                    }
                });
                filterPopup.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Mobile filter bottom sheet
        var bottomSheet = document.getElementById('mobile-filter-bottom-sheet');
        var bottomSheetTitle = document.getElementById('mobile-filter-bottom-sheet-title');
        var bottomSheetOptions = document.getElementById('mobile-filter-bottom-sheet-options');
        var currentFilterValue = null;
        var currentFilterLabel = null;
        
        function openBottomSheet(filterValue, filterLabel) {
            currentFilterValue = filterValue;
            currentFilterLabel = filterLabel;
            
            // Set title
            if (bottomSheetTitle) {
                bottomSheetTitle.textContent = filterLabel;
            }
            
            // Get options from dropdown
            var dropdown = filterValue.querySelector('.filter-dropdown');
            if (!dropdown) return;
            
            var options = dropdown.querySelectorAll('.filter-option');
            var activeOption = dropdown.querySelector('.filter-option.active');
            var activeValue = activeOption ? activeOption.getAttribute('data-value') : '';
            
            // Clear and populate options
            bottomSheetOptions.innerHTML = '';
            options.forEach(function(option) {
                var optionValue = option.getAttribute('data-value');
                var optionText = option.querySelector('span') ? option.querySelector('span').textContent : option.textContent.trim();
                var isActive = optionValue === activeValue;
                
                var optionEl = document.createElement('div');
                optionEl.className = 'mobile-filter-bottom-sheet-option' + (isActive ? ' active' : '');
                optionEl.setAttribute('data-value', optionValue);
                optionEl.innerHTML = '<span>' + optionText + '</span>' +
                    '<svg class="mobile-filter-bottom-sheet-option-check" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                    '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#131313"/>' +
                    '</svg>';
                
                optionEl.addEventListener('click', function() {
                    selectBottomSheetOption(optionValue, optionText);
                });
                
                bottomSheetOptions.appendChild(optionEl);
            });
            
            // Show bottom sheet
            bottomSheet.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeBottomSheet() {
            bottomSheet.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        function selectBottomSheetOption(value, text) {
            if (!currentFilterValue) return;
            
            // Update filter text
            var filterText = currentFilterValue.querySelector('.filter-text');
            if (filterText) {
                filterText.textContent = text;
            }
            
            // Update active option in dropdown
            var dropdown = currentFilterValue.querySelector('.filter-dropdown');
            if (dropdown) {
                var options = dropdown.querySelectorAll('.filter-option');
                options.forEach(function(opt) {
                    opt.classList.remove('active');
                    if (opt.getAttribute('data-value') === value) {
                        opt.classList.add('active');
                    }
                });
            }
            
            // Sync with desktop filter
            var filterId = currentFilterValue.id;
            var desktopFilter = document.querySelector('.filters .filter-value[id="' + filterId + '"]');
            var selectedDesktopOption = null;
            if (desktopFilter) {
                var desktopText = desktopFilter.querySelector('.filter-text');
                var desktopOptions = desktopFilter.querySelectorAll('.filter-dropdown .filter-option');
                desktopOptions.forEach(function(opt) {
                    opt.classList.remove('active');
                    if (opt.getAttribute('data-value') === value) {
                        opt.classList.add('active');
                        selectedDesktopOption = opt;
                        if (desktopText) {
                            var desktopOptionText = opt.querySelector('span');
                            if (desktopOptionText) {
                                desktopText.textContent = desktopOptionText.textContent;
                            } else {
                                desktopText.textContent = opt.textContent.trim();
                            }
                        }
                    }
                });
            }
            
            closeBottomSheet();
            
            // Apply filters immediately to update count
            // Use jQuery to trigger click on desktop option - this is the most reliable way
            // as it goes through the same handler as desktop filters
            if (window.jQuery && selectedDesktopOption) {
                // Trigger click on desktop option - this will call applyFilters() via jQuery handler
                setTimeout(function() {
                    window.jQuery(selectedDesktopOption).trigger('click');
                }, 100);
            } else {
                // Fallback: wait for applyFilters to be available
                var applyFiltersAttempts = 0;
                var maxAttempts = 20;
                var checkAndApply = function() {
                    applyFiltersAttempts++;
                    if (typeof window.applyFilters === 'function') {
                        window.applyFilters();
                    } else if (applyFiltersAttempts < maxAttempts) {
                        setTimeout(checkAndApply, 50);
                    }
                };
                setTimeout(checkAndApply, 150);
            }
        }
        
        // Bottom sheet close handlers
        if (bottomSheet) {
            bottomSheet.addEventListener('click', function(e) {
                if (e.target === bottomSheet || e.target.classList.contains('mobile-filter-bottom-sheet-backdrop')) {
                    closeBottomSheet();
                }
            });
        }
        
        // Mobile filter value click - open bottom sheet
        var mobileFilterValues = document.querySelectorAll('.mobile-filters .filter-value');
        
        mobileFilterValues.forEach(function(filterValue) {
            // Set first option as active by default
            var dropdown = filterValue.querySelector('.filter-dropdown');
            if (dropdown) {
                var firstOption = dropdown.querySelector('.filter-option');
                if (firstOption && !dropdown.querySelector('.filter-option.active')) {
                    firstOption.classList.add('active');
                }
            }
            
            // Open bottom sheet on filter value click
            filterValue.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get filter label
                var filterGroup = this.closest('.filter-group');
                var filterLabel = filterGroup ? filterGroup.querySelector('.filter-label').textContent : 'Выберите значение';
                
                // Open bottom sheet
                openBottomSheet(this, filterLabel);
            });
        });
        
        // Bottom sheet handles its own closing, no need for outside click handler
        
        if (filterReset) {
            filterReset.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Remove focus and active state immediately to prevent sticking
                var self = this;
                this.blur();
                // Force remove any active state
                setTimeout(function() {
                    self.blur();
                    self.style.background = 'transparent';
                }, 0);
                setTimeout(function() {
                    self.blur();
                }, 100);
                
                // Close bottom sheet if open
                if (bottomSheet && bottomSheet.classList.contains('active')) {
                    closeBottomSheet();
                }
                
                // Close all desktop dropdowns
                var desktopFilterValues = document.querySelectorAll('.filters .filter-value');
                desktopFilterValues.forEach(function(filter) {
                    filter.classList.remove('active');
                });
                
                // Reset all filters
                var filterValues = document.querySelectorAll('.mobile-filters .filter-value, .filters .filter-value');
                filterValues.forEach(function(filter) {
                    var text = filter.querySelector('.filter-text');
                    var dropdown = filter.querySelector('.filter-dropdown');
                    if (text && dropdown) {
                        // Remove active class from all options first
                        var allOptions = dropdown.querySelectorAll('.filter-option');
                        allOptions.forEach(function(opt) {
                            opt.classList.remove('active');
                        });
                        
                        // Set first option as active
                        var firstOption = dropdown.querySelector('.filter-option');
                        if (firstOption) {
                            var firstOptionText = firstOption.querySelector('span');
                            if (firstOptionText) {
                                text.textContent = firstOptionText.textContent;
                            } else {
                                text.textContent = firstOption.textContent.trim();
                            }
                            filter.classList.remove('active');
                            firstOption.classList.add('active');
                        }
                    }
                });
                
                // Reset bottom sheet options active states
                var bottomSheetOptions = document.getElementById('mobile-filter-bottom-sheet-options');
                if (bottomSheetOptions) {
                    var bottomSheetOptionElements = bottomSheetOptions.querySelectorAll('.mobile-filter-bottom-sheet-option');
                    bottomSheetOptionElements.forEach(function(opt) {
                        opt.classList.remove('active');
                    });
                }
                
                // Reset mobile filter count to show total / total
                if (filterCount && typeof reviewsAjax !== 'undefined' && reviewsAjax.totalReviews) {
                    var totalCount = reviewsAjax.totalReviews;
                    if (typeof updateFilterCount === 'function') {
                        updateFilterCount(totalCount, totalCount);
                    } else if (typeof number_format === 'function') {
                        filterCount.textContent = number_format(totalCount, 0, ',', ' ') + ' / ' + number_format(totalCount, 0, ',', ' ');
                    }
                }
                
                // Apply filters to reset gallery
                if (typeof applyFilters === 'function') {
                    applyFilters();
                } else if (window.jQuery) {
                    setTimeout(function() {
                        if (typeof applyFilters === 'function') {
                            applyFilters();
                        }
                    }, 100);
                }
            });
        }
        
        // Desktop filter dropdown toggle
        var desktopFilterValues = document.querySelectorAll('.filters .filter-value');
        var desktopFilterClickInProgress = false; // Flag to prevent document click handler from closing dropdown
        
        desktopFilterValues.forEach(function(filterValue) {
            // Set first option as active by default
            var dropdown = filterValue.querySelector('.filter-dropdown');
            if (dropdown) {
                var firstOption = dropdown.querySelector('.filter-option');
                if (firstOption) {
                    firstOption.classList.add('active');
                }
            }
            
            // Toggle dropdown on filter value click
            filterValue.addEventListener('click', function(e) {
                // Stop event propagation immediately to prevent document click handler from closing dropdown
                e.stopPropagation();
                e.stopImmediatePropagation(); // Prevent other handlers from running
                
                // Don't toggle if clicking on dropdown itself
                if (e.target.closest('.filter-dropdown')) {
                    return;
                }
                e.preventDefault();
                
                // Check if this dropdown is already active by checking both class and dropdown visibility
                var dropdownEl = this.querySelector('.filter-dropdown');
                var isActive = this.classList.contains('active') || (dropdownEl && dropdownEl.style.display === 'block');
                
                // Set flag to prevent document click handler from closing dropdown
                desktopFilterClickInProgress = true;
                
                // Close all other dropdowns (only if opening this one)
                if (!isActive) {
                    desktopFilterValues.forEach(function(fv) {
                        if (fv !== filterValue) {
                            fv.classList.remove('active');
                            // Reset arrow rotation for closed dropdowns
                            var arrowIcon = fv.querySelector('.arrow-icon');
                            if (arrowIcon) {
                                arrowIcon.style.transform = '';
                            }
                            var dropdownEl = fv.querySelector('.filter-dropdown');
                            if (dropdownEl) {
                                dropdownEl.style.display = '';
                                dropdownEl.style.visibility = '';
                                dropdownEl.style.opacity = '';
                            }
                        }
                    });
                }
                
                // Toggle current dropdown
                if (isActive) {
                    // Close dropdown
                    this.classList.remove('active');
                    var dropdownEl = this.querySelector('.filter-dropdown');
                    if (dropdownEl) {
                        dropdownEl.style.display = '';
                        dropdownEl.style.visibility = '';
                        dropdownEl.style.opacity = '';
                    }
                    // Reset arrow rotation
                    var arrowIcon = this.querySelector('.arrow-icon');
                    if (arrowIcon) {
                        arrowIcon.style.transform = '';
                    }
                    // Reset flag after a short delay
                    setTimeout(function() { desktopFilterClickInProgress = false; }, 100);
                } else {
                    this.classList.add('active');
                    var dropdownEl = this.querySelector('.filter-dropdown');
                    // Force display block with inline style to override any conflicting styles
                    if (dropdownEl) {
                        dropdownEl.style.display = 'block';
                        dropdownEl.style.visibility = 'visible';
                        dropdownEl.style.opacity = '1';
                    }
                    // Rotate arrow icon
                    var arrowIcon = this.querySelector('.arrow-icon');
                    if (arrowIcon) {
                        arrowIcon.style.transform = 'rotate(180deg)';
                    }
                    // Reset flag after a short delay to allow dropdown to open
                    setTimeout(function() { desktopFilterClickInProgress = false; }, 100);
                }
            });
            
            // Handle option selection
            var options = filterValue.querySelectorAll('.filter-dropdown .filter-option');
            
            options.forEach(function(option) {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Remove active class from all options
                    options.forEach(function(opt) {
                        opt.classList.remove('active');
                    });
                    
                    // Add active class to selected option
                    this.classList.add('active');
                    
                    // Update filter text - handle both span and direct text
                    var filterText = filterValue.querySelector('.filter-text');
                    if (filterText) {
                        var optionText = this.querySelector('span');
                        if (optionText) {
                            filterText.textContent = optionText.textContent;
                        } else {
                        filterText.textContent = this.textContent.trim();
                        }
                    }
                    
                    // Sync with mobile filter if exists
                    var filterId = filterValue.id;
                    var mobileFilter = document.querySelector('.mobile-filters .filter-value[id="' + filterId + '"]');
                    if (mobileFilter) {
                        var mobileText = mobileFilter.querySelector('.filter-text');
                        var mobileOptions = mobileFilter.querySelectorAll('.filter-dropdown .filter-option');
                        var optionIndex = Array.from(options).indexOf(this);
                        if (mobileText && mobileOptions[optionIndex]) {
                            var mobileOptionText = mobileOptions[optionIndex].querySelector('span');
                            if (mobileOptionText) {
                                mobileText.textContent = mobileOptionText.textContent;
                            } else {
                                mobileText.textContent = mobileOptions[optionIndex].textContent.trim();
                            }
                            mobileOptions.forEach(function(opt) {
                                opt.classList.remove('active');
                            });
                            mobileOptions[optionIndex].classList.add('active');
                        }
                    }
                    
                    // Close dropdown
                    filterValue.classList.remove('active');
                    // Reset arrow rotation
                    var arrowIcon = filterValue.querySelector('.arrow-icon');
                    if (arrowIcon) {
                        arrowIcon.style.transform = '';
                    }
                    var dropdownEl = filterValue.querySelector('.filter-dropdown');
                    if (dropdownEl) {
                        dropdownEl.style.display = '';
                        dropdownEl.style.visibility = '';
                        dropdownEl.style.opacity = '';
                    }
                });
            });
        });
        
        // Close desktop dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            // Use setTimeout to allow filter-value click handler to execute first
            setTimeout(function() {
                // Don't close if a filter click is in progress
                if (desktopFilterClickInProgress) {
                    return;
                }
                var clickedInside = e.target.closest('.filters .filter-value') || e.target.closest('.filters .filter-dropdown');
                if (!clickedInside) {
                    desktopFilterValues.forEach(function(fv) {
                        fv.classList.remove('active');
                        // Reset arrow rotation
                        var arrowIcon = fv.querySelector('.arrow-icon');
                        if (arrowIcon) {
                            arrowIcon.style.transform = '';
                        }
                        var dropdownEl = fv.querySelector('.filter-dropdown');
                        if (dropdownEl) {
                            dropdownEl.style.display = '';
                            dropdownEl.style.visibility = '';
                            dropdownEl.style.opacity = '';
                        }
                    });
                }
            }, 0);
        }); // Use bubbling phase (default)
        
        // Sync is already handled in individual click handlers above
    }
    
    // Update filter count (will be updated when filters are applied)
    function updateFilterCount(filtered, total) {
        if (filterCount) {
            filterCount.textContent = number_format(filtered, 0, ',', ' ') + ' / ' + number_format(total, 0, ',', ' ');
        }
    }
    
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    
    // Expose updateFilterCount globally for use in reviews-script.js
    window.updateFilterCount = updateFilterCount;
});
</script>
<script src="<?php echo esc_url(plugins_url('wordpress-reviews-plugin/assets/js/reviews-script.js')); ?>"></script>
</body>
</html>
