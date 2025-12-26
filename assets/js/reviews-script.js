jQuery(document).ready(function($) {
    'use strict';
    
    // Store total reviews count - ONLY from reviewsAjax, never touch .reviews-count element
    var totalReviewsCount = (typeof reviewsAjax !== 'undefined' && reviewsAjax.totalReviews) ? reviewsAjax.totalReviews : 0;
    
    // Distribute gallery items into columns
    function distributeGallery() {
        var $gallery = $('#reviews-gallery');
        var $items = $gallery.find('.review-gallery-item');
        
        if ($items.length === 0) return;
        
        // Clear existing columns
        $gallery.empty();
        
        // Determine number of columns based on screen size
        var columns = 8;
        if (window.innerWidth <= 1400 && window.innerWidth > 1200) {
            columns = 6;
        } else if (window.innerWidth <= 1200 && window.innerWidth > 960) {
            columns = 4;
        } else if (window.innerWidth <= 960 && window.innerWidth > 640) {
            columns = 3;
        } else if (window.innerWidth <= 640) {
            columns = 2;
        }
        
        // Create columns
        var $columns = [];
        for (var i = 0; i < columns; i++) {
            var $column = $('<div class="gallery-column"></div>');
            $columns.push($column);
            $gallery.append($column);
        }
        
        // Distribute items
        $items.each(function(index) {
            var columnIndex = index % columns;
            $columns[columnIndex].append($(this));
        });
    }
    
    // Initial distribution
    distributeGallery();
    
    // Redistribute on window resize
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(distributeGallery, 250);
    });
    
    // Initialize filter options - mark first option as active
    $('.filter-dropdown .filter-option:first-child').addClass('active');
    
    // Filter dropdowns
    $('.filter-value').on('click', function(e) {
        e.stopPropagation();
        $(this).toggleClass('active');
    });
    
    $(document).on('click', function() {
        $('.filter-value').removeClass('active');
    });
    
    $('.filter-option').on('click', function(e) {
        e.stopPropagation();
        var value = $(this).data('value');
        var $span = $(this).find('span');
        var text = $span.length ? $span.text() : $(this).text().trim();
        var filterGroup = $(this).closest('.filter-group');
        var filterValue = filterGroup.find('.filter-value');
        var filterText = filterGroup.find('.filter-text');
        var filterDropdown = filterGroup.find('.filter-dropdown');
        
        // Update active state
        filterDropdown.find('.filter-option').removeClass('active');
        $(this).addClass('active');
        
        filterText.text(text);
        filterValue.removeClass('active');
        
        // Trigger filter
        applyFilters();
    });
    
    // Load more reviews
    var currentPage = 1;
    var isLoading = false;
    
    $('#load-more-reviews').on('click', function() {
        if (isLoading) return;
        
        isLoading = true;
        currentPage++;
        
        var $button = $(this);
        $button.prop('disabled', true).text('Загрузка...');
        
        var filters = getCurrentFilters();
        
        $.ajax({
            url: reviewsAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_reviews',
                nonce: reviewsAjax.nonce,
                page: currentPage,
                posts_per_page: 24,
                filters: filters
            },
            success: function(response) {
                if (response.success) {
                    // Create temporary container to parse HTML
                    var $temp = $('<div>').html(response.data.html);
                    var $newItems = $temp.find('.review-gallery-item');
                    
                    // Get current items count
                    var currentItemsCount = $('#reviews-gallery .review-gallery-item').length;
                    
                    // Determine number of columns
                    var columns = 8;
                    if (window.innerWidth <= 1400 && window.innerWidth > 1200) {
                        columns = 6;
                    } else if (window.innerWidth <= 1200 && window.innerWidth > 960) {
                        columns = 4;
                    } else if (window.innerWidth <= 960 && window.innerWidth > 640) {
                        columns = 3;
                    } else if (window.innerWidth <= 640) {
                        columns = 2;
                    }
                    
                    // Ensure we have enough columns
                    var $gallery = $('#reviews-gallery');
                    var $columns = $gallery.find('.gallery-column');
                    if ($columns.length < columns) {
                        for (var i = $columns.length; i < columns; i++) {
                            $gallery.append($('<div class="gallery-column"></div>'));
                        }
                        $columns = $gallery.find('.gallery-column');
                    }
                    
                    // Distribute new items
                    $newItems.each(function(index) {
                        var columnIndex = (currentItemsCount + index) % columns;
                        $columns.eq(columnIndex).append($(this));
                    });
                    
                    if (!response.data.has_more) {
                        $button.hide();
                    } else {
                        $button.prop('disabled', false).text('Показать еще');
                    }
                }
                isLoading = false;
            },
            error: function() {
                isLoading = false;
                $button.prop('disabled', false).text('Показать еще');
                alert('Ошибка загрузки отзывов');
            }
        });
    });
    
    // Apply filters
    function applyFilters() {
        var filters = getCurrentFilters();
        
        $.ajax({
            url: reviewsAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_reviews',
                nonce: reviewsAjax.nonce,
                page: 1,
                posts_per_page: 24,
                filters: filters
            },
            success: function(response) {
                if (response.success) {
                    // Create temporary container to parse HTML
                    var $temp = $('<div>').html(response.data.html);
                    var $items = $temp.find('.review-gallery-item');
                    
                    // Clear and rebuild gallery
                    $('#reviews-gallery').empty();
                    $items.each(function() {
                        $('#reviews-gallery').append($(this));
                    });
                    
                    // Distribute into columns
                    distributeGallery();
                    
                    // Update mobile filter count (filtered / total)
                    if (typeof window.updateFilterCount === 'function' && totalReviewsCount > 0) {
                        window.updateFilterCount(response.data.count, totalReviewsCount);
                    }
                    
                    currentPage = 1;
                    if (response.data.has_more) {
                        $('#load-more-reviews').show().prop('disabled', false).text('Показать еще');
                    } else {
                        $('#load-more-reviews').hide();
                    }
                }
            },
            error: function() {
                alert('Ошибка фильтрации');
            }
        });
    }
    
    // Make applyFilters globally available
    window.applyFilters = applyFilters;
    
    function getCurrentFilters() {
        var filters = {};
        
        // Get city filter
        var $cityActive = $('#filter-city .filter-option.active');
        if ($cityActive.length && $cityActive.data('value')) {
            filters.city = $cityActive.data('value');
        }
        
        // Get product filter
        var $productActive = $('#filter-product .filter-option.active');
        if ($productActive.length && $productActive.data('value')) {
            filters.product = $productActive.data('value');
        }
        
        // Get year filter
        var $yearActive = $('#filter-year .filter-option.active');
        if ($yearActive.length && $yearActive.data('value')) {
            filters.year = $yearActive.data('value');
        }
        
        // Get format filter
        var $formatActive = $('#filter-format .filter-option.active');
        if ($formatActive.length && $formatActive.data('value') === 'video') {
            filters.has_video = 'true';
        }
        
        return filters;
    }
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }
    
    // Single review page - thumbnail switching + slider dots (desktop + mobile)
    function setActiveSlideByIndex(index) {
        var $thumb = $('.thumbnail[data-index=\"' + index + '\"]');
        if ($thumb.length) {
            var fullImageUrl = $thumb.data('full');
            if (fullImageUrl) {
                $('#main-review-image').attr('src', fullImageUrl);
                $('.thumbnail').removeClass('active');
                $thumb.addClass('active');
                $('.slider-dot').removeClass('active');
                $('.slider-dot[data-index=\"' + index + '\"]').addClass('active');
            }
        }
    }

    $('.thumbnail').on('click', function() {
        var index = $(this).data('index');
        if (typeof index !== 'undefined') {
            setActiveSlideByIndex(index);
        }
    });

    $('.slider-dot').on('click', function() {
        var index = $(this).data('index');
        if (typeof index !== 'undefined') {
            setActiveSlideByIndex(index);
        }
    });
});

