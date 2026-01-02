<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница отзыва</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto+Condensed:wght@600&display=swap" rel="stylesheet">
</head>
<body>
<?php
$gallery_ids = get_post_meta(get_the_ID(), '_review_gallery', true);
$gallery_ids = $gallery_ids ? explode(',', $gallery_ids) : array();
$main_image_id = !empty($gallery_ids) ? $gallery_ids[0] : get_post_thumbnail_id();

$reviewer_name = get_post_meta(get_the_ID(), '_reviewer_name', true);
$city = get_post_meta(get_the_ID(), '_review_city', true);
$video_url = get_post_meta(get_the_ID(), '_review_video_url', true);

// Get previous and next posts for review post type
global $post;

$args = array(
    'post_type' => 'review',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);

$all_reviews = get_posts($args);
$current_index = -1;

// Find current post index
foreach ($all_reviews as $index => $review) {
    if ($review->ID == $post->ID) {
        $current_index = $index;
        break;
    }
}

$prev_post = null;
$next_post = null;

if ($current_index >= 0) {
    // Get previous post (next in array since ordered DESC)
    if ($current_index < count($all_reviews) - 1) {
        $prev_post = $all_reviews[$current_index + 1];
    }
    // Get next post (previous in array since ordered DESC)
    if ($current_index > 0) {
        $next_post = $all_reviews[$current_index - 1];
    }
}
?>

<div class="review-detail-page">
    <a href="<?php echo esc_url(home_url('/reviews/')); ?>" class="close-btn">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="24" fill="white"/>
            <path d="M19 19L29 29M29 19L19 29" stroke="#131313" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
    </a>
    <span class="close-btn-spacer"></span>
    
    <div class="review-container">
        <div class="image-section" id="image-section-slider">
            <?php if ($main_image_id) : ?>
                <?php
                $main_image_url = wp_get_attachment_image_url($main_image_id, 'large');
                ?>
                <img src="<?php echo esc_url($main_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="main-image" id="main-review-image" />
            <?php endif; ?>
            <div class="slider-click-zone slider-click-zone-prev" id="slider-click-prev"></div>
            <div class="slider-click-zone slider-click-zone-next" id="slider-click-next"></div>
            
            <?php if (!empty($gallery_ids)) : ?>
                <div class="image-overlay">
                    <div class="thumbnails">
                        <?php
                        foreach ($gallery_ids as $index => $image_id) {
                            if ($image_id) {
                                $thumb_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                                $full_url = wp_get_attachment_image_url($image_id, 'large');
                                ?>
                                <img 
                                    src="<?php echo esc_url($thumb_url); ?>" 
                                    alt="Thumbnail <?php echo $index + 1; ?>" 
                                    class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>"
                                    data-full="<?php echo esc_url($full_url); ?>"
                                    data-index="<?php echo esc_attr($index); ?>"
                                />
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="slider-dots">
                        <?php 
                        // Count total images: count thumbnails (which include all gallery images)
                        // This ensures dots count matches thumbnails count
                        $total_images = 0;
                        foreach ($gallery_ids as $image_id) {
                            if ($image_id) {
                                $total_images++;
                            }
                        }
                        // If no gallery but has main image, create one dot
                        if ($total_images === 0 && $main_image_id) {
                            $total_images = 1;
                        }
                        for ($i = 0; $i < $total_images; $i++) : ?>
                            <button 
                                type="button" 
                                class="slider-dot <?php echo $i === 0 ? 'active' : ''; ?>" 
                                data-index="<?php echo esc_attr($i); ?>"
                                aria-label="Показать фото <?php echo esc_attr($i + 1); ?>"
                            ></button>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="content-section">
            <div class="review-header">
                <?php if ($city) : ?>
                    <p class="city"><?php echo esc_html($city); ?></p>
                <?php endif; ?>
                <h1 class="reviewer-name">
                    <?php echo $reviewer_name ? esc_html($reviewer_name) : get_the_title(); ?>
                </h1>
            </div>
            
            <div class="review-content">
                <div class="review-text">
                    <?php the_content(); ?>
                </div>
                
                <?php if ($video_url) : ?>
                    <div class="review-video">
                        <?php
                        // Try to embed video
                        echo wp_oembed_get($video_url);
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php
            // Product information
            $product_name = get_post_meta(get_the_ID(), '_product_name', true);
            $product_price = get_post_meta(get_the_ID(), '_product_price', true);
            $product_image_id = get_post_meta(get_the_ID(), '_product_image_id', true);
            $product_url = get_post_meta(get_the_ID(), '_product_url', true);
            
            if ($product_name || $product_price || $product_image_id) :
            ?>
            <?php if ($product_url) : ?>
                <a href="<?php echo esc_url($product_url); ?>" target="_blank" rel="noopener noreferrer" class="product-info">
            <?php else : ?>
                <div class="product-info">
            <?php endif; ?>
                <?php if ($product_image_id) : ?>
                    <div class="product-image">
                        <?php echo wp_get_attachment_image($product_image_id, array(96, 96)); ?>
                    </div>
                <?php endif; ?>
                
                <div class="product-details">
                    <?php if ($product_name) : ?>
                        <div class="product-name"><?php echo esc_html($product_name); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($product_price) : ?>
                        <?php
                        // Format price: extract numbers, add thousand separators, add ruble sign
                        $price_clean = preg_replace('/[^0-9]/', '', $product_price);
                        if ($price_clean) {
                            $price_formatted = number_format((int)$price_clean, 0, '', ' ') . ' ₽';
                        } else {
                            $price_formatted = $product_price;
                        }
                        ?>
                        <div class="product-price"><?php echo esc_html($price_formatted); ?></div>
                    <?php endif; ?>
                </div>
            <?php if ($product_url) : ?>
                </a>
            <?php else : ?>
                </div>
            <?php endif; ?>
            <?php endif; ?>
            
            <div class="navigation-buttons">
                <?php if ($prev_post) : ?>
                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="nav-btn prev-btn">Предыдущий</a>
                <?php else : ?>
                    <span class="nav-btn prev-btn disabled">Предыдущий</span>
                <?php endif; ?>
                
                <?php if ($next_post) : ?>
                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="nav-btn next-btn">Следующий</a>
                <?php else : ?>
                    <span class="nav-btn next-btn disabled">Следующий</span>
                <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Моноширинные цифры для всех элементов сайта */
*, *::before, *::after {
    font-variant-numeric: tabular-nums;
    -webkit-font-feature-settings: "tnum" 1;
    font-feature-settings: "tnum" 1;
}

/* Используем monospace шрифт для цифр через unicode-range */
@font-face {
    font-family: 'MonospaceNumbers';
    src: local('Courier New'), local('Monaco'), local('Consolas'), monospace;
    unicode-range: U+0030-0039; /* 0-9 */
}

:root {
    --white-01: #FFF;
    --white-02: #F6F6F6;
    --dark-01: #131313;
    --dark-02: #2B2B2B;
    --gray-01: #8C8C8C;
    --gray-02: #D9D9D9;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    /* Моноширинные цифры для всех элементов */
    font-variant-numeric: tabular-nums;
    -webkit-font-feature-settings: "tnum";
    font-feature-settings: "tnum";
}

html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

body {
    font-family: 'Raleway', 'MonospaceNumbers', -apple-system, Roboto, Helvetica, sans-serif;
    background: var(--white-02);
    color: var(--dark-01);
}

.review-detail-page {
    max-width: 1600px;
    margin: 0 auto;
    background: var(--white-02);
    border-radius: 0;
    position: relative;
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.close-btn {
    position: fixed;
    top: 24px;
    left: 50%;
    transform: translateX(-50%);
    width: 48px;
    height: 48px;
    background: transparent;
    border: none;
    cursor: pointer;
    z-index: 100;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: none;
    text-decoration: none;
}


.close-btn svg {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: contain;
}

.review-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    height: 100%;
    flex: 1;
    position: relative;
    overflow: hidden;
}

.image-section {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    display: flex;
    align-items: stretch;
    border-radius: 0 !important;
}

.slider-click-zone {
    display: none;
}

.slider-click-zone-prev {
    left: 0;
}

.slider-click-zone-next {
    right: 0;
}

.main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    border-radius: 0 !important;
}

.image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 158px;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.00) 2.23%, #000 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 16px;
}

.thumbnails {
    display: flex;
    align-items: center;
    gap: 9px;
}

.thumbnail {
    width: 78px;
    height: 96px;
    border-radius: 8px;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s ease;
    border: 2px solid transparent;
}

.thumbnail:hover,
.thumbnail.active {
    transform: scale(1.05);
    border-color: var(--white-01);
}

.slider-dots {
    display: none;
}

.slider-dot {
    width: 6px;
    height: 6px;
    min-width: 6px;
    min-height: 6px;
    max-width: 6px;
    max-height: 6px;
    border-radius: 50%;
    background-color: #FFFFFF;
    border: none;
    outline: none;
    box-shadow: none;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
    margin: 0;
    opacity: 0.3;
    flex-shrink: 0;
}

.slider-dot.active {
    width: 18px;
    height: 6px;
    min-width: 18px;
    min-height: 6px;
    max-width: 18px;
    max-height: 6px;
    border-radius: 3px;
    background-color: #FFFFFF;
    opacity: 1;
}

.content-section {
    padding: 160px 184px 0 184px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    height: 100%;
    overflow-y: auto;
}

.review-header {
    margin-bottom: 0;
}

.city {
    font-family: 'Raleway', 'MonospaceNumbers', -apple-system, Roboto, Helvetica, sans-serif;
    color: var(--gray-01);
    font-size: 14px !important;
    font-weight: 400 !important;
    line-height: 24px !important;
    margin-bottom: 32px !important;
}

.reviewer-name {
    font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif;
    font-size: 40px !important;
    font-weight: 600 !important;
    line-height: 40px !important;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--dark-01);
    margin: 0 0 48px 0 !important;
}

.review-content {
    flex: 1;
    margin-bottom: 32px;
}

.review-text {
    font-family: 'Raleway', 'MonospaceNumbers', -apple-system, Roboto, Helvetica, sans-serif !important;
    color: var(--dark-02);
    font-size: 14px !important;
    font-weight: 400 !important;
    line-height: 24px !important;
    margin: 0 !important;
    max-width: 368px;
}

.review-video {
    margin-top: 24px;
}

.review-video iframe {
    width: 100%;
    max-width: 560px;
    height: 315px;
}

.product-info {
    margin-top: 32px;
    margin-bottom: 32px;
    padding-top: 32px;
    padding-bottom: 0;
    border-top: none;
    display: flex;
    align-items: center;
    gap: 24px;
    position: relative;
    text-decoration: none;
    color: inherit;
}

.product-info::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    max-width: 368px;
    height: 1px;
    background: var(--gray-02);
}

.product-info:hover {
    opacity: 0.8;
}

.product-image {
    flex-shrink: 0;
}

.product-image img {
    width: 96px !important;
    height: 96px !important;
    max-width: 96px !important;
    max-height: 96px !important;
    object-fit: cover;
    border-radius: 0 !important;
    display: block;
}

.product-image a {
    display: inline-block;
    transition: opacity 0.3s ease;
}

.product-image a:hover {
    opacity: 0.8;
}

.product-details {
    flex: 1;
}

.product-name {
    font-family: 'Raleway', 'MonospaceNumbers', -apple-system, Roboto, Helvetica, sans-serif !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: var(--dark-01);
    margin: 0 !important;
    line-height: 24px !important;
}

.product-price {
    font-family: 'Raleway', 'MonospaceNumbers', -apple-system, Roboto, Helvetica, sans-serif !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: var(--gray-01);
    margin: 0 !important;
    font-variant-numeric: tabular-nums;
    font-feature-settings: "tnum";
    line-height: 24px !important;
}

.divider {
    width: 100%;
    max-width: 368px;
    height: 1px;
    background: var(--gray-02);
    margin: 0 0 0 0;
}

.navigation-buttons {
    display: flex;
    align-items: stretch;
    gap: 16px;
    margin: 0 0 16px 0;
}

.nav-btn {
    flex: 1;
    max-width: 176px;
    padding: 14px 24px;
    background: var(--dark-01);
    border: none;
    color: var(--white-01);
    font-family: 'Roboto Condensed', -apple-system, Roboto, Helvetica, sans-serif !important;
    font-size: 14px !important;
    font-weight: 600;
    line-height: 20px;
    letter-spacing: 0.7px;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

/* No hover / disabled visual changes per design */

@media screen and (max-width: 1200px) {
    .review-detail-page {
        height: 100vh;
    }
    
    .review-container {
        height: 100%;
    }
    
    .content-section {
        padding: 120px 60px 40px;
    }
    
    .reviewer-name {
        font-size: 32px !important;
        line-height: 32px !important;
    }
    
    .close-btn {
        width: 60px;
        height: 60px;
    }
}

@media screen and (max-width: 960px) {
    .review-detail-page {
        height: 100vh;
    }
    
    .review-container {
        grid-template-columns: 1fr;
        height: 100%;
        justify-content: center;
    }
    
    .image-section,
    .content-section {
        max-width: 340px;
        margin: 0 auto;
    }
    
    .image-section {
        min-height: 500px;
    }
    
    .content-section {
        padding: 40px 0 32px;
    }
    
    .review-header {
        margin-bottom: 0;
    }
    
    .city {
        margin-bottom: 24px !important;
    }
    
    .reviewer-name {
        font-size: 20px !important;
        line-height: 20px !important;
        letter-spacing: 1.2px;
        margin: 24px 0 24px 0 !important;
    }
    
    .thumbnails {
        display: none;
    }
    
    .slider-dots {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
        position: absolute;
        bottom: 12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 300;
        padding: 0;
        margin: 0;
    }
    
    .thumbnail {
        width: 60px;
        height: 74px;
    }
    
    .close-btn {
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
    }
}

@media screen and (max-width: 640px) {
    .review-detail-page {
        height: auto;
        min-height: 100vh;
        /* высота плашки, чтобы контент не уезжал под неё */
        padding-bottom: 64px;
    }
    
    .review-container {
        grid-template-columns: 1fr;
        height: auto;
        justify-content: center;
    }
    
    .image-section,
    .content-section {
        width: 340px;
        max-width: 340px;
        margin: 0 auto;
    }
    
    .image-section {
        height: auto;
        min-height: 0;
        touch-action: pan-y;
    }
    
    .main-image {
        height: 360px !important;
        width: 100%;
        object-fit: cover;
        object-position: center center;
    }
    
    .content-section {
        padding: 32px 0 0;
    }
    
    .reviewer-name {
        font-size: 20px !important;
        line-height: 20px !important;
        letter-spacing: 1.2px;
        margin: 24px 0 24px 0 !important;
    }
    
    .city {
        font-size: 12px;
        line-height: 20px;
        margin-bottom: 24px !important;
    }
    
    .review-text {
        font-size: 13px;
        line-height: 22px;
    }
    
    .close-btn {
        position: static;
        width: 48px;
        height: 48px;
        margin: 16px auto 24px;
        transform: none;
        transition: none !important;
    }
    
    .close-btn-spacer {
        display: none;
        width: 48px;
        height: 48px;
        margin: 16px auto 24px;
    }
    
    .close-btn.scrolled {
        position: fixed !important;
        top: 16px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        margin: 0 !important;
        transition: none !important;
    }
    
    .close-btn.scrolled ~ .close-btn-spacer {
        display: block;
    }
    
    .image-overlay {
        position: static;
        height: auto;
        background: transparent;
        padding: 8px 0 0;
        align-items: center;
        justify-content: center;
    }
    
    .slider-click-zone {
        display: block;
        position: absolute;
        top: 0;
        bottom: 0;
        width: 50%;
        z-index: 200;
        cursor: pointer;
        background: transparent;
    }
    
    .thumbnails {
        display: none;
    }
    
    .slider-dots {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
        position: absolute;
        bottom: 12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 300;
        padding: 0;
        margin: 0;
    }
    
    .thumbnail {
        width: 48px;
        height: 60px;
    }
    
    .product-info {
        margin-top: 32px;
        margin-bottom: 24px;
        padding-top: 24px;
        padding-bottom: 0;
    }
    
    .navigation-buttons {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        margin: 0;
        padding: 16px 24px 0;
        gap: 0;
        background: #000000;
        z-index: 2000;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
    
    .nav-btn {
        font-size: 14px !important;
        padding: 14px 24px;
        max-width: none;
        width: 100%;
        background: transparent;
        color: #FFFFFF;
        border: none;
        display: inline-block;
        text-align: center;
    }
    
    .nav-btn.prev-btn {
        border-right: 1px solid rgba(255, 255, 255, 0.3);
    }
}
</style>

<script>
// Thumbnail + dots slider (desktop + mobile)
document.addEventListener('DOMContentLoaded', function() {
    var thumbnails = Array.prototype.slice.call(document.querySelectorAll('.thumbnail'));
    var dots = Array.prototype.slice.call(document.querySelectorAll('.slider-dot'));
    var mainImage = document.getElementById('main-review-image');
    
    if (!mainImage) {
        return;
    }
    
    // Build gallery images array from thumbnails (which include main image as first)
    // Main image is already displayed, so we use thumbnails data-full URLs
    var galleryImages = [];
    thumbnails.forEach(function(thumb) {
        var fullUrl = thumb.getAttribute('data-full');
        if (fullUrl) {
            galleryImages.push(fullUrl);
        }
    });
    
    // If no thumbnails but main image exists, use main image
    if (galleryImages.length === 0 && mainImage.src) {
        galleryImages.push(mainImage.src);
    }
    
    if (galleryImages.length === 0) {
        return;
    }

    var currentSlideIndex = 0;
    var imageSection = document.getElementById('image-section-slider');
    var clickZonePrev = document.getElementById('slider-click-prev');
    var clickZoneNext = document.getElementById('slider-click-next');

    function setActiveSlideByIndex(index) {
        if (!mainImage || index < 0 || index >= galleryImages.length) {
            return;
        }
        
        var fullUrl = galleryImages[index];
        if (!fullUrl) {
            return;
        }
        
        mainImage.src = fullUrl;
        
        // Update thumbnails - thumbnails correspond directly to gallery images
        thumbnails.forEach(function(t, i) {
            if (i === index) {
                t.classList.add('active');
            } else {
                t.classList.remove('active');
            }
        });
        
        // Update dots - dots correspond directly to gallery images
        dots.forEach(function(d, i) {
            if (i === index) {
                d.classList.add('active');
            } else {
                d.classList.remove('active');
            }
        });
        
        currentSlideIndex = index;
    }

    function goNext() {
        var nextIndex = (currentSlideIndex + 1) % galleryImages.length;
        setActiveSlideByIndex(nextIndex);
    }

    function goPrev() {
        var prevIndex = (currentSlideIndex - 1 + galleryImages.length) % galleryImages.length;
        setActiveSlideByIndex(prevIndex);
    }

    // Click zones for left/right navigation (mobile only)
    var isMobile = window.innerWidth <= 640;
    if (clickZonePrev && isMobile) {
        clickZonePrev.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            goPrev();
        });
    }

    if (clickZoneNext && isMobile) {
        clickZoneNext.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            goNext();
        });
    }

    // Touch swipe support (mobile only)
    if (imageSection && galleryImages.length > 1 && isMobile) {
        var touchStartX = 0;
        var touchEndX = 0;
        var minSwipeDistance = 40;

        imageSection.addEventListener('touchstart', function(e) {
            if (!e.touches || !e.touches.length) return;
            touchStartX = e.touches[0].clientX;
        }, { passive: true });

        imageSection.addEventListener('touchend', function(e) {
            if (!e.changedTouches || !e.changedTouches.length) return;
            touchEndX = e.changedTouches[0].clientX;
            var diff = touchEndX - touchStartX;
            var absDiff = Math.abs(diff);
            
            if (absDiff < minSwipeDistance) {
                return;
            }
            
            if (diff < 0) {
                // Swipe left - next
                goNext();
            } else {
                // Swipe right - prev
                goPrev();
            }
        }, { passive: true });
    }

    // Thumbnail click
    thumbnails.forEach(function(thumb, thumbIndex) {
        thumb.addEventListener('click', function() {
            setActiveSlideByIndex(thumbIndex);
        });
    });

    // Dot click
    dots.forEach(function(dot, dotIndex) {
        dot.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            setActiveSlideByIndex(dotIndex);
        });
    });

});
</script>

<script>
// Close button scroll: static initially, fixed after 16px scroll (mobile only)
(function() {
    function handleScroll() {
        var closeBtn = document.querySelector('.close-btn');
        if (!closeBtn) return;
        
        var isMobile = window.innerWidth <= 640;
        var scrollY = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        var container = document.querySelector('.content-section');
        var containerScroll = container ? container.scrollTop || 0 : 0;
        var bodyScroll = document.body.scrollTop || 0;
        var htmlScroll = document.documentElement.scrollTop || 0;
        var totalScroll = Math.max(scrollY, containerScroll, bodyScroll, htmlScroll);
        var hasScrolled = closeBtn.classList.contains('scrolled');
        var shouldAdd = isMobile && totalScroll > 16;
        
        if (shouldAdd && !hasScrolled) {
            closeBtn.classList.add('scrolled');
        } else if (!shouldAdd && hasScrolled) {
            closeBtn.classList.remove('scrolled');
        }
    }
    
    function init() {
        var closeBtn = document.querySelector('.close-btn');
        if (!closeBtn) {
            setTimeout(init, 50);
            return;
        }
        
        var container = document.querySelector('.content-section');
        window.addEventListener('scroll', handleScroll, { passive: true });
        window.addEventListener('touchmove', handleScroll, { passive: true });
        if (container) {
            container.addEventListener('scroll', handleScroll, { passive: true });
            container.addEventListener('touchmove', handleScroll, { passive: true });
        }
        document.body.addEventListener('scroll', handleScroll, { passive: true });
        document.documentElement.addEventListener('scroll', handleScroll, { passive: true });
        window.addEventListener('resize', handleScroll, { passive: true });
        handleScroll();
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
</body>
</html>
