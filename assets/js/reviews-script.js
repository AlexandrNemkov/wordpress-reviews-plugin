jQuery(document).ready(function($) {
    'use strict';
    
    // Store total reviews count - ONLY from reviewsAjax, never touch .reviews-count element
    var totalReviewsCount = (typeof reviewsAjax !== 'undefined' && reviewsAjax.totalReviews) ? reviewsAjax.totalReviews : 0;
    
    // Distribute gallery items into columns
    function distributeGallery() {
        // #region agent log
        fetch('http://127.0.0.1:7243/ingest/fa1a99b8-4679-45f8-9443-3ce5e5a33b9d',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'reviews-script.js:8',message:'distributeGallery entry',data:{innerWidth:window.innerWidth,userAgent:navigator.userAgent},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
        // #endregion
        var $gallery = $('#reviews-gallery');
        var $items = $gallery.find('.review-gallery-item');
        
        if ($items.length === 0) return;
        
        // Clear existing columns
        $gallery.empty();
        
        // Determine number of columns based on screen size
        var width = window.innerWidth;
        var columns = 8;
        if (width <= 1400 && width > 1200) {
            columns = 6;
        } else if (width <= 1200 && width > 960) {
            columns = 4;
        } else if (width <= 960 && width > 640) {
            columns = 3;
        } else if (width <= 640) {
            columns = 4; // Mobile: 4 columns (must match CSS @media (max-width: 640px))
        }
        
        // #region agent log
        fetch('http://127.0.0.1:7243/ingest/fa1a99b8-4679-45f8-9443-3ce5e5a33b9d',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'reviews-script.js:32',message:'columns determined',data:{innerWidth:window.innerWidth,innerHeight:window.innerHeight,columns:columns,itemsCount:$items.length,breakpoint960:window.innerWidth <= 960,breakpoint640:window.innerWidth <= 640},timestamp:Date.now(),sessionId:'debug-session',runId:'run2',hypothesisId:'A'})}).catch(()=>{});
        // #endregion
        
        // Create columns
        var $columns = [];
        for (var i = 0; i < columns; i++) {
            var $column = $('<div class="gallery-column"></div>');
            $columns.push($column);
            $gallery.append($column);
        }
        
        // #region agent log
        fetch('http://127.0.0.1:7243/ingest/fa1a99b8-4679-45f8-9443-3ce5e5a33b9d',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'reviews-script.js:35',message:'columns created',data:{columnsCreated:$columns.length,itemsCount:$items.length},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'B'})}).catch(()=>{});
        // #endregion
        
        // Distribute items
        $items.each(function(index) {
            var columnIndex = index % columns;
            $columns[columnIndex].append($(this));
        });
        
        // #region agent log
        var computedStyle = window.getComputedStyle($gallery[0]);
        var mediaQuery640 = window.matchMedia('(max-width: 640px)').matches;
        var mediaQuery960 = window.matchMedia('(max-width: 960px)').matches;
        var actualGridColumns = computedStyle.gridTemplateColumns.split(' ').length;
        var visibleColumns = $columns.filter(function() {
            return $(this).css('display') !== 'none' && $(this).is(':visible');
        }).length;
        var columnElements = $gallery.find('.gallery-column');
        var visibleColumnElements = columnElements.filter(function() {
            return $(this).css('display') !== 'none' && $(this).is(':visible');
        }).length;
        fetch('http://127.0.0.1:7243/ingest/fa1a99b8-4679-45f8-9443-3ce5e5a33b9d',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'reviews-script.js:64',message:'distributeGallery exit',data:{innerWidth:window.innerWidth,columns:columns,gridTemplateColumns:computedStyle.gridTemplateColumns,actualColumns:$columns.length,actualGridColumns:actualGridColumns,visibleColumns:visibleColumns,columnElementsCount:columnElements.length,visibleColumnElements:visibleColumnElements,mediaQuery640:mediaQuery640,mediaQuery960:mediaQuery960},timestamp:Date.now(),sessionId:'debug-session',runId:'run2',hypothesisId:'B'})}).catch(()=>{});
        // #endregion
    }
    
    // Initial distribution
    // #region agent log
    fetch('http://127.0.0.1:7243/ingest/fa1a99b8-4679-45f8-9443-3ce5e5a33b9d',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'reviews-script.js:95',message:'initial distributeGallery call',data:{innerWidth:window.innerWidth,readyState:document.readyState},timestamp:Date.now(),sessionId:'debug-session',runId:'run2',hypothesisId:'C'})}).catch(()=>{});
    // #endregion
    distributeGallery();
    
    // Redistribute on window resize (only if columns count changed)
    var resizeTimer;
    var lastColumns = getColumnsForWidth(window.innerWidth);
    var lastInnerWidth = window.innerWidth;
    
    function getColumnsForWidth(width) {
        if (width <= 1400 && width > 1200) {
            return 6;
        } else if (width <= 1200 && width > 960) {
            return 4;
        } else if (width <= 960 && width > 640) {
            return 3;
        } else if (width <= 640) {
            return 4; // Mobile: 4 columns
        }
        return 8;
    }
    
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            var newColumns = getColumnsForWidth(window.innerWidth);
            var widthDiff = Math.abs(lastInnerWidth - window.innerWidth);
            // #region agent log
            fetch('http://127.0.0.1:7243/ingest/fa1a99b8-4679-45f8-9443-3ce5e5a33b9d',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'reviews-script.js:100',message:'resize event triggered',data:{innerWidth:window.innerWidth,newColumns:newColumns,lastColumns:lastColumns,lastInnerWidth:lastInnerWidth,widthDiff:widthDiff,willRedistribute:(lastColumns !== newColumns || widthDiff > 100)},timestamp:Date.now(),sessionId:'debug-session',runId:'run2',hypothesisId:'D'})}).catch(()=>{});
            // #endregion
            // Only redistribute if columns count changed or significant width change (>100px)
            if (lastColumns !== newColumns || widthDiff > 100) {
                lastColumns = newColumns;
                lastInnerWidth = window.innerWidth;
                distributeGallery();
            }
        }, 250);
    });
    
    // Track scroll events to see if they trigger resize
    var scrollEventCount = 0;
    $(window).on('scroll', function() {
        scrollEventCount++;
        if (scrollEventCount % 20 === 0) { // Log every 20th scroll event
            // #region agent log
            fetch('http://127.0.0.1:7243/ingest/fa1a99b8-4679-45f8-9443-3ce5e5a33b9d',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'reviews-script.js:115',message:'scroll event',data:{innerWidth:window.innerWidth,scrollY:window.scrollY,scrollEventCount:scrollEventCount},timestamp:Date.now(),sessionId:'debug-session',runId:'run2',hypothesisId:'D'})}).catch(()=>{});
            // #endregion
        }
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
                    
                    // Determine number of columns (use same logic as distributeGallery)
                    var width = window.innerWidth;
                    var columns = 8;
                    if (width <= 1400 && width > 1200) {
                        columns = 6;
                    } else if (width <= 1200 && width > 960) {
                        columns = 4;
                    } else if (width <= 960 && width > 640) {
                        columns = 3;
                    } else if (width <= 640) {
                        columns = 4; // Mobile: 4 columns (must match CSS @media (max-width: 640px))
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
                    var count = response.data.count || 0;
                    
                    // Check if no results found
                    if (count === 0) {
                        // Hide gallery and load more button
                        $('#reviews-gallery').empty();
                        $('#load-more-reviews').hide();
                        
                        // Show empty state message
                        $('#reviews-empty-state').show();
                        
                        // Update mobile filter count to show 0
                        if (typeof window.updateFilterCount === 'function' && totalReviewsCount > 0) {
                            window.updateFilterCount(0, totalReviewsCount);
                        }
                    } else {
                        // Hide empty state message
                        $('#reviews-empty-state').hide();
                        
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
                }
            },
            error: function() {
                alert('Ошибка фильтрации');
            }
        });
    }
    
    // Make applyFilters globally available
    window.applyFilters = applyFilters;
    
    // Reset filters button handler
    $('#reviews-reset-filters-btn').on('click', function() {
        // Reset all filter options to first (default) option (both desktop and mobile)
        $('.filter-dropdown .filter-option').removeClass('active');
        $('.filter-dropdown .filter-option:first-child').addClass('active');
        
        // Update filter text to default (both desktop and mobile)
        $('.filter-value').each(function() {
            var $dropdown = $(this).find('.filter-dropdown');
            var $firstOption = $dropdown.find('.filter-option:first-child');
            var $filterText = $(this).find('.filter-text');
            if ($firstOption.length && $filterText.length) {
                var text = $firstOption.find('span').length ? $firstOption.find('span').text() : $firstOption.text().trim();
                $filterText.text(text);
            }
        });
        
        // Reset mobile bottom sheet options if any
        $('.mobile-filter-bottom-sheet-option').removeClass('active');
        $('.mobile-filter-bottom-sheet-option:first-child').addClass('active');
        
        // Close all dropdowns
        $('.filter-value').removeClass('active');
        
        // Close mobile bottom sheet if open
        var $bottomSheet = $('#mobile-filter-bottom-sheet');
        if ($bottomSheet && $bottomSheet.hasClass('active')) {
            $bottomSheet.removeClass('active');
            $('body').css('overflow', '');
        }
        
        // Apply filters (this will show all reviews again)
        applyFilters();
    });
    
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

