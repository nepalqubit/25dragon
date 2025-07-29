# InteractiveMap Component

A React component for displaying an interactive map of Nepal's trekking regions using Leaflet.

## Features

- Interactive map with Nepal's major trekking regions (Everest, Annapurna, Langtang, Manaslu)
- Clickable region polygons with different colors
- Trekking route visualization with polylines
- Custom markers for route points and trip locations
- Popup information for regions and trips
- Legend with region toggle functionality
- Responsive design with loading state

## Dependencies

- React 18+
- react-leaflet 4.2+
- leaflet 1.9+
- TypeScript (for development)

## Installation

1. Install dependencies:
```bash
npm install
```

2. Build the component:
```bash
npm run build
```

## Usage

```tsx
import InteractiveMap from './components/InteractiveMap';

const trips = [
  {
    id: '1',
    title: 'Everest Base Camp Trek',
    region: 'everest',
    duration: 14,
    difficulty: 'Challenging',
    price: 2500,
    image: '/images/ebc.jpg',
    rating: 4.8,
    reviews: 156,
    highlights: ['Base Camp', 'Kala Patthar'],
    bestSeason: ['March-May', 'September-November']
  }
  // ... more trips
];

<InteractiveMap trips={trips} />
```

## Props

### InteractiveMapProps

- `trips`: Array of Trip objects to display on the map

### Trip Interface

```tsx
interface Trip {
  id: string
  title: string
  region: string
  duration: number
  difficulty: 'Easy' | 'Moderate' | 'Challenging' | 'Extreme'
  price: number
  image: string
  rating: number
  reviews: number
  highlights: string[]
  bestSeason: string[]
}
```

## Regions

The component includes predefined regions:

- **Everest Region** (Red) - Includes EBC trek route
- **Annapurna Region** (Green) - Includes Annapurna Circuit route
- **Langtang Region** (Blue) - Includes Langtang Valley route
- **Manaslu Region** (Orange) - No predefined routes

## Customization

### Adding New Regions

Modify the `nepalRegions` object to add new regions:

```tsx
const nepalRegions = {
  newRegion: {
    name: 'New Region',
    center: [latitude, longitude],
    bounds: [[lat1, lng1], [lat2, lng2], ...],
    color: '#hexcolor',
    trips: ['tripId1', 'tripId2']
  }
}
```

### Adding New Routes

Add routes to the `sampleRoutes` object:

```tsx
const sampleRoutes = {
  'tripId': [
    [lat1, lng1], // Start point
    [lat2, lng2], // Waypoint
    [lat3, lng3]  // End point
  ]
}
```

## Styling

The component uses Tailwind CSS classes. Ensure your project includes Tailwind CSS or replace with custom CSS classes.

## Browser Support

- Modern browsers with ES6+ support
- Mobile responsive
- Touch-friendly interactions