jQuery(document).ready(function($) {
    'use strict';
    
    var galleryFrame;
    var galleryIds = [];
    
    // Add gallery images
    $('#add_gallery_images').on('click', function(e) {
        e.preventDefault();
        
        if (galleryFrame) {
            galleryFrame.open();
            return;
        }
        
        galleryFrame = wp.media({
            title: 'Выберите фотографии для галереи',
            button: {
                text: 'Использовать выбранные'
            },
            multiple: true,
            library: {
                type: 'image'
            }
        });
        
        galleryFrame.on('select', function() {
            var selection = galleryFrame.state().get('selection');
            var ids = [];
            
            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                ids.push(attachment.id);
                
                var imageUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                
                var item = $('<div class="gallery-item" data-id="' + attachment.id + '">' +
                    '<img src="' + imageUrl + '" />' +
                    '<button type="button" class="remove-image">×</button>' +
                    '</div>');
                
                $('#review_gallery_preview').append(item);
            });
            
            galleryIds = galleryIds.concat(ids);
            updateGalleryField();
        });
        
        galleryFrame.open();
    });
    
    // Remove image
    $(document).on('click', '.remove-image', function() {
        var item = $(this).closest('.gallery-item');
        var id = item.data('id');
        
        galleryIds = galleryIds.filter(function(galleryId) {
            return galleryId != id;
        });
        
        item.remove();
        updateGalleryField();
    });
    
    function updateGalleryField() {
        $('#review_gallery').val(galleryIds.join(','));
    }
    
    // Load existing gallery on page load
    var existingGallery = $('#review_gallery').val();
    if (existingGallery) {
        galleryIds = existingGallery.split(',').filter(function(id) {
            return id.trim() !== '';
        });
    }
    
    // Product image selector
    var productImageFrame;
    
    $('#select_product_image').on('click', function(e) {
        e.preventDefault();
        
        if (productImageFrame) {
            productImageFrame.open();
            return;
        }
        
        productImageFrame = wp.media({
            title: 'Выберите изображение товара',
            button: {
                text: 'Использовать изображение'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        productImageFrame.on('select', function() {
            var attachment = productImageFrame.state().get('selection').first().toJSON();
            $('#product_image_id').val(attachment.id);
            
            var imageUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            $('#product_image_preview').html('<img src="' + imageUrl + '" />');
            $('#remove_product_image').show();
        });
        
        productImageFrame.open();
    });
    
    // Remove product image
    $('#remove_product_image').on('click', function(e) {
        e.preventDefault();
        $('#product_image_id').val('');
        $('#product_image_preview').html('');
        $(this).hide();
    });
});

