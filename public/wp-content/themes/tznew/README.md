# TZnew WordPress Theme

A full-featured, production-ready WordPress theme for Techzen Corporation.

## Requirements

- **WordPress**: 6.6 or higher
- **PHP**: 8.2 or higher
- **ACF Pro**: Required for custom fields functionality
- **Elementor**: Optional (theme includes Elementor integration)
- **WooCommerce**: Optional (theme includes WooCommerce integration)

## Features

### Custom Post Types
- **Trekking**: Complete trekking package management
- **Tours**: Tour package management
- **FAQ**: Frequently asked questions
- **Blog**: Custom blog posts with ACF integration

### Custom Taxonomies
- **Region**: For trekking and tours (Annapurna, Everest, Langtang, etc.)
- **Difficulty**: For trekking (Easy, Moderate, Challenging, Strenuous)
- **Tour Type**: For tours
- **ACF Tag**: For blog posts

### Integrations
- **ACF Pro**: Extensive custom fields for all post types
- **Elementor**: Custom widgets and theme support
- **WooCommerce**: E-commerce functionality with custom product linking
- **REST API**: Full API support for all custom post types and fields
- **Tailwind CSS**: Modern utility-first CSS framework

### PHP 8.2+ Compatibility
This theme has been fully updated for PHP 8.2+ compatibility including:
- Proper null coalescing operator usage
- Array access validation
- Secure $_GET/$_POST handling
- Modern WordPress coding standards

### Security Features
- Sanitized input handling
- Escaped output
- Nonce verification for forms
- Secure file access checks

## Installation

1. Upload the theme to `/wp-content/themes/tznew/`
2. Activate the theme in WordPress admin
3. Install and activate required plugins (ACF Pro)
4. Import ACF field groups from `/acf-json/` directory
5. Configure theme options in Customizer

## Development

The theme uses modern development practices:
- Tailwind CSS for styling
- Modular PHP architecture
- REST API endpoints
- Custom post types and taxonomies
- Advanced Custom Fields integration

## Support

For support and documentation, visit [Techzen Corporation](https://techzeninc.com)

## License

GNU General Public License v2 or later