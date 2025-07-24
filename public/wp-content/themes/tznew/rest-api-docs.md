# TZnew Theme REST API Documentation

This document provides information on how to use the REST API to access custom post types and fields in the TZnew WordPress theme.

## Overview

The TZnew theme has been configured to expose all custom post types, taxonomies, and ACF fields to the WordPress REST API. This allows you to build headless applications or integrate with external systems using the data from your WordPress site.

## Available Endpoints

### Custom Post Types

- **Trekking**: `/wp-json/wp/v2/trekking`
- **Tours**: `/wp-json/wp/v2/tours`
- **FAQ**: `/wp-json/wp/v2/faq`
- **Blog**: `/wp-json/wp/v2/blog`

### Custom Taxonomies

- **Region**: `/wp-json/wp/v2/region`
- **Difficulty**: `/wp-json/wp/v2/difficulty`
- **Tour Type**: `/wp-json/wp/v2/tour_type`
- **Blog Tags**: `/wp-json/wp/v2/acf_tag`

## Accessing Custom Fields

All ACF fields are available in the REST API response under the `acf` property. For example, to get the ACF fields for a trekking post:

```
GET /wp-json/wp/v2/trekking/123
```

The response will include an `acf` object containing all the custom fields:

```json
{
  "id": 123,
  "title": { "rendered": "Everest Base Camp Trek" },
  "acf": {
    "difficulty": "Moderate",
    "duration": "14 days",
    "overview": "<p>Experience the adventure of a lifetime...</p>",
    "highlights": [
      "View of Mount Everest",
      "Sherpa culture"
    ],
    "includes": "<p>All accommodations...</p>",
    "excludes": "<p>International flights...</p>"
  },
  "featured_image": {
    "medium": {
      "url": "https://example.com/wp-content/uploads/2023/01/everest-300x200.jpg",
      "width": 300,
      "height": 200
    },
    "full": {
      "url": "https://example.com/wp-content/uploads/2023/01/everest.jpg",
      "width": 1200,
      "height": 800
    },
    "alt": "Everest Base Camp"
  }
}
```

## Filtering and Pagination

You can filter and paginate results using standard WordPress REST API parameters:

### Pagination

```
GET /wp-json/wp/v2/trekking?page=1&per_page=10
```

### Filtering by Taxonomy

```
GET /wp-json/wp/v2/trekking?region=5
```

### Searching

```
GET /wp-json/wp/v2/trekking?search=everest
```

### Ordering

```
GET /wp-json/wp/v2/trekking?order=asc&orderby=title
```

## Authentication

By default, the REST API endpoints are publicly accessible for read operations. For write operations or accessing private data, you'll need to authenticate your requests.

The WordPress REST API supports several authentication methods:

1. **Cookie Authentication**: For logged-in users browsing the site
2. **Basic Authentication**: Requires a plugin for HTTP Basic Authentication
3. **OAuth 1.0a**: More secure method requiring a plugin
4. **Application Passwords**: Built into WordPress 5.6+

For headless applications, Application Passwords is recommended.

## Using with Headless Applications

This REST API configuration makes it easy to build headless applications with frameworks like React, Vue, or Angular. Here's a simple example using JavaScript fetch API:

```javascript
// Fetch all trekking posts
fetch('https://yoursite.com/wp-json/wp/v2/trekking?_embed')
  .then(response => response.json())
  .then(posts => {
    console.log(posts);
    // Process the posts data
    posts.forEach(post => {
      console.log(post.title.rendered);
      console.log(post.acf.overview);
      console.log(post.featured_image.medium.url);
    });
  });
```

## Using with AI Content Generation

The REST API can be used to feed data to AI systems for content generation. Here's how you might structure a request to an AI system:

```javascript
async function generateAIContent() {
  // Fetch data from WordPress
  const response = await fetch('https://yoursite.com/wp-json/wp/v2/trekking/123');
  const post = await response.json();
  
  // Prepare data for AI
  const promptData = {
    title: post.title.rendered,
    overview: post.acf.overview,
    highlights: post.acf.highlights,
    difficulty: post.acf.difficulty,
    duration: post.acf.duration
  };
  
  // Send to AI service (example)
  const aiResponse = await fetch('https://ai-service.com/generate', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      prompt: `Generate a social media post about this trek: ${JSON.stringify(promptData)}`,
      max_tokens: 150
    })
  });
  
  const aiContent = await aiResponse.json();
  return aiContent.generated_text;
}
```

## Troubleshooting

### Common Issues

1. **404 Not Found**: Ensure the post type or taxonomy is properly registered with `show_in_rest` set to `true`.

2. **ACF Fields Not Showing**: Make sure the REST API integration is properly set up and the fields exist for the post.

3. **CORS Issues**: If accessing from a different domain, you may need to add CORS headers using a plugin or custom code.

### Debugging

To see all available routes:

```
GET /wp-json/wp/v2
```

To check if a specific post type is available in the REST API:

```
GET /wp-json/wp/v2/types
```

## Need Help?

If you encounter any issues or need further assistance, please contact the theme developer.