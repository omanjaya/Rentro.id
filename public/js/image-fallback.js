/**
 * Image fallback handler for broken images
 * Replaces broken images with beautiful category-appropriate fallbacks
 */

// Default fallback images by category
const defaultImages = {
    laptop: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=300&fit=crop',
    camera: 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=300&fit=crop',
    audio: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=300&fit=crop',
    gaming: 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=500&h=300&fit=crop',
    mobile: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=300&fit=crop',
    default: 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=500&h=300&fit=crop'
};

// Function to handle image errors
function handleImageError(img) {
    // Try to determine category from context
    let category = 'default';
    
    // Check alt text for category hints
    const altText = img.alt.toLowerCase();
    if (altText.includes('laptop') || altText.includes('computer')) category = 'laptop';
    else if (altText.includes('camera') || altText.includes('photo')) category = 'camera';
    else if (altText.includes('audio') || altText.includes('sound') || altText.includes('speaker')) category = 'audio';
    else if (altText.includes('gaming') || altText.includes('console') || altText.includes('game')) category = 'gaming';
    else if (altText.includes('phone') || altText.includes('mobile') || altText.includes('tablet')) category = 'mobile';
    
    // Check parent elements for category data
    const parentWithCategory = img.closest('[data-category]');
    if (parentWithCategory) {
        const dataCategory = parentWithCategory.dataset.category.toLowerCase();
        if (defaultImages[dataCategory]) category = dataCategory;
    }
    
    // Set the fallback image
    img.src = defaultImages[category];
    img.classList.add('fallback-image');
}

// Add global event listener for image errors
document.addEventListener('DOMContentLoaded', function() {
    // Handle existing images that might already be broken
    document.querySelectorAll('img').forEach(img => {
        if (img.complete && img.naturalHeight === 0) {
            handleImageError(img);
        }
    });
    
    // Handle future image errors
    document.addEventListener('error', function(e) {
        if (e.target.tagName === 'IMG') {
            handleImageError(e.target);
        }
    }, true);
});