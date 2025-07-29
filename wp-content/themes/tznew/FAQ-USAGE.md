# FAQ System Usage Guide

## Overview
The FAQ system has been completely redesigned to be simple and flexible. Each FAQ is now a single question-answer pair that can be assigned to specific tours, treks, or marked as general.

## Creating FAQs

### 1. Add New FAQ
- Go to WordPress Admin → FAQs → Add New
- Enter a descriptive title for the FAQ
- Fill in the required fields:
  - **FAQ Question**: The question text
  - **FAQ Answer**: The answer (supports rich text/HTML)
  - **FAQ Category**: Choose from general, trekking, tours, or booking
  - **Applicable To**: Select where this FAQ should appear (General, Trekking, Tours)
  - **Display Order**: Number to control the order (lower numbers appear first)

### 2. FAQ Fields Explained

#### FAQ Question
- Simple text field for the question
- Keep it clear and concise
- Example: "What is included in the trek package?"

#### FAQ Answer
- Rich text editor (WYSIWYG)
- Supports formatting, lists, links, etc.
- Can include HTML for advanced formatting

#### FAQ Category
- **general**: General questions about the company/services
- **trekking**: Specific to trekking activities
- **tours**: Specific to tour packages
- **booking**: Related to booking process

#### Applicable To
- **General**: Will appear on all post types when "Show General FAQs" is enabled
- **Trekking**: Will appear on trekking posts when general FAQs are enabled
- **Tours**: Will appear on tour posts when general FAQs are enabled

#### Display Order
- Numeric value to control FAQ order
- Lower numbers appear first (1, 2, 3...)
- FAQs with the same order will be sorted alphabetically

## Assigning FAQs to Tours/Treks

### Method 1: Direct Selection
1. Edit a Tour or Trekking post
2. Scroll to "FAQ Selection" section
3. Use "Selected FAQs" field to choose specific FAQs
4. Selected FAQs will appear in the order you select them

### Method 2: General FAQs
1. Edit a Tour or Trekking post
2. In "FAQ Selection" section, enable "Show General FAQs"
3. This will automatically display:
   - All FAQs marked as "General"
   - All FAQs marked for the current post type (Tours/Trekking)
4. General FAQs appear after any specifically selected FAQs
5. General FAQs are ordered by the "Display Order" field

### Combining Both Methods
- You can use both methods together
- Selected FAQs appear first
- General FAQs appear after selected ones
- Duplicate FAQs are automatically filtered out

## Display Locations

FAQs will automatically appear on:
- Single trekking post pages (after main content)
- Single tour post pages (after main content)
- FAQ archive page (shows all FAQs)
- Individual FAQ post pages

## Styling and Appearance

### FAQ Section Features
- Responsive design with Tailwind CSS
- Collapsible questions (click to expand/collapse)
- Category tags for better organization
- Smooth animations and hover effects
- Mobile-friendly accordion interface

### Customization
- Styles are defined in `/template-parts/faq-section.php`
- Uses Tailwind CSS classes for easy customization
- JavaScript handles the toggle functionality
- Icons use Font Awesome (dashicons as fallback)

## Best Practices

### Content Guidelines
1. **Questions**: Keep them specific and user-focused
2. **Answers**: Be comprehensive but concise
3. **Categories**: Use consistently across all FAQs
4. **Order**: Group related questions with similar order numbers

### Organization Tips
1. Create general FAQs for common questions
2. Use specific FAQs for unique tour/trek details
3. Keep FAQ titles descriptive for admin management
4. Review and update FAQs regularly

### Performance Considerations
1. Limit the number of FAQs per page (recommended: 10-15)
2. Use the order field to prioritize most important questions
3. Consider creating separate FAQ pages for extensive content

## Troubleshooting

### FAQs Not Appearing
1. Check if FAQ post is published
2. Verify "Applicable To" field is set correctly
3. Ensure "Show General FAQs" is enabled if using general FAQs
4. Check if specific FAQs are selected in the post

### Display Issues
1. Clear any caching plugins
2. Check if theme files are properly uploaded
3. Verify ACF fields are properly configured
4. Check browser console for JavaScript errors

### Styling Problems
1. Ensure Tailwind CSS is loaded
2. Check for CSS conflicts with other plugins
3. Verify Font Awesome icons are loading
4. Test on different devices and browsers

## Technical Details

### Files Modified/Created
- `/inc/acf-integration.php` - ACF field definitions
- `/template-parts/faq-section.php` - FAQ display template
- `/template-parts/content-faq.php` - Individual FAQ content
- `/single-trekking.php` - Added FAQ section
- `/single-tours.php` - Added FAQ section
- `/functions.php` - Added helper function

### Database Structure
- Post Type: `faq`
- Meta Fields: `faq_question`, `faq_answer`, `faq_category`, `faq_applicable_to`, `faq_order`
- Tour/Trek Meta: `selected_faqs`, `show_general_faqs`

### Helper Functions
- `tznew_display_faqs()` - Main function to display FAQ section
- `tznew_get_field_safe()` - Safe ACF field retrieval
- Uses existing theme functions for consistency