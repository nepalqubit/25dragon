'use client'

import { useEffect, useRef, useState } from 'react'
import { MapContainer, TileLayer, Marker, Popup, Polygon, Polyline } from 'react-leaflet'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

// Fix for default markers in react-leaflet
delete (L.Icon.Default.prototype as any)._getIconUrl
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
  iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
})

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
  permalink: string
  post_type: string
}

interface Region {
  id: number
  name: string
  slug: string
  description: string
  coordinates?: {
    latitude: number
    longitude: number
  }
  polygon_coordinates?: [number, number][]
  polygon_color: string
  show_on_map: boolean
  assigned_trekking: Trip[]
  assigned_tours: Trip[]
}

interface InteractiveMapProps {
  trips: Trip[]
}

// Default fallback regions (will be replaced by dynamic data)
const defaultRegions: Region[] = [
  {
    id: 1,
    name: 'Everest Region',
    slug: 'everest',
    description: 'Home to the world\'s highest peak',
    coordinates: { latitude: 27.9881, longitude: 86.9250 },
    polygon_coordinates: [
      [27.7, 86.5],
      [28.3, 87.3],
      [28.1, 87.1],
      [27.9, 86.7]
    ],
    polygon_color: '#ef4444',
    show_on_map: true,
    assigned_trekking: [],
    assigned_tours: []
  }
]

// Trip icon colors
const TRIP_COLORS = {
  trekking: '#059669',
  tours: '#3b82f6'
}

// Custom icons
const createCustomIcon = (color: string) => {
  return L.divIcon({
    html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
    className: 'custom-marker',
    iconSize: [20, 20],
    iconAnchor: [10, 10]
  })
}

export default function InteractiveMap({ trips }: InteractiveMapProps) {
  const [selectedRegion, setSelectedRegion] = useState<number | null>(null)
  const [mapReady, setMapReady] = useState(false)
  const [regions, setRegions] = useState<Region[]>(defaultRegions)
  const [loading, setLoading] = useState(true)
  const mapRef = useRef<L.Map | null>(null)

  useEffect(() => {
    loadRegions()
  }, [])

  const loadRegions = async () => {
    try {
      const response = await fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'tznew_get_regions_for_map',
          nonce: (window as any).tznew_ajax?.nonce || ''
        })
      })
      
      const data = await response.json()
      if (data.success && data.data) {
        setRegions(data.data)
      }
    } catch (error) {
      console.error('Failed to load regions:', error)
    } finally {
      setLoading(false)
      setMapReady(true)
    }
  }

  const handleRegionClick = (regionId: number) => {
    setSelectedRegion(regionId === selectedRegion ? null : regionId)
  }

  if (!mapReady || loading) {
    return (
      <div className="h-full w-full bg-gray-200 animate-pulse flex items-center justify-center">
        <div className="text-gray-500">Loading map...</div>
      </div>
    )
  }

  const selectedRegionData = regions.find(r => r.id === selectedRegion)

  return (
    <div className="h-full w-full relative">
      <MapContainer
        center={[27.7172, 85.3240]} // Center of Kathmandu
        zoom={7}
        style={{ height: '100%', width: '100%' }}
        ref={mapRef}
      >
        <TileLayer
          attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        />
         
        {/* Region Polygons */}
        {regions.filter(region => region.show_on_map && region.polygon_coordinates).map((region) => (
          <Polygon
            key={region.id}
            positions={region.polygon_coordinates!}
            pathOptions={{
              color: region.polygon_color,
              fillColor: region.polygon_color,
              fillOpacity: selectedRegion === region.id ? 0.4 : 0.2,
              weight: selectedRegion === region.id ? 3 : 2,
              opacity: 0.8
            }}
            eventHandlers={{
              click: () => handleRegionClick(region.id)
            }}
          >
            <Popup>
              <div className="p-2">
                <h3 className="font-bold text-lg mb-2">{region.name}</h3>
                <p className="text-sm text-gray-600 mb-2">
                  {region.description}
                </p>
                <p className="text-sm text-gray-600 mb-2">
                  {region.assigned_trekking.length + region.assigned_tours.length} trip(s) available
                </p>
                <button className="bg-primary-600 text-white px-3 py-1 rounded text-sm hover:bg-primary-700 transition-colors">
                  View Trips
                </button>
              </div>
            </Popup>
          </Polygon>
        ))}
        {/* Trip Markers for Selected Region */}
        {selectedRegionData && (
          [...selectedRegionData.assigned_trekking, ...selectedRegionData.assigned_tours].map((trip) => {
            const center = selectedRegionData.coordinates
            if (!center) return null

            return (
               <Marker
                 key={`${trip.post_type}-${trip.id}`}
                 position={[center.latitude, center.longitude]}
                 icon={createCustomIcon(TRIP_COLORS[trip.post_type as keyof typeof TRIP_COLORS] || '#059669')}
               >
                <Popup>
                  <div className="p-3 max-w-xs">
                    <h4 className="font-bold text-base mb-2">{trip.title}</h4>
                    <div className="space-y-1 text-sm">
                      <p><span className="font-medium">Type:</span> {trip.post_type === 'trekking' ? 'Trekking' : 'Tour'}</p>
                      <p><span className="font-medium">Duration:</span> {trip.duration} days</p>
                      <p><span className="font-medium">Difficulty:</span> {trip.difficulty}</p>
                      <p><span className="font-medium">Price:</span> ${trip.price}</p>
                      <p><span className="font-medium">Rating:</span> {trip.rating}/5 ({trip.reviews} reviews)</p>
                    </div>
                    <a 
                      href={trip.permalink}
                      className="mt-3 w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition-colors inline-block text-center"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      View Details
                    </a>
                  </div>
                </Popup>
              </Marker>
            )
          })
        )}

        {/* All Trip Markers when no region is selected */}
        {!selectedRegion && regions.map(region => 
          [...region.assigned_trekking, ...region.assigned_tours].map((trip) => {
            const center = region.coordinates
            if (!center || !region.show_on_map) return null

            return (
               <Marker
                 key={`${trip.post_type}-${trip.id}`}
                 position={[center.latitude, center.longitude]}
                 icon={createCustomIcon(TRIP_COLORS[trip.post_type as keyof typeof TRIP_COLORS] || '#059669')}
               >
                <Popup>
                  <div className="p-3 max-w-xs">
                    <h4 className="font-bold text-base mb-2">{trip.title}</h4>
                    <div className="space-y-1 text-sm">
                      <p><span className="font-medium">Region:</span> {region.name}</p>
                      <p><span className="font-medium">Type:</span> {trip.post_type === 'trekking' ? 'Trekking' : 'Tour'}</p>
                      <p><span className="font-medium">Duration:</span> {trip.duration} days</p>
                      <p><span className="font-medium">Difficulty:</span> {trip.difficulty}</p>
                      <p><span className="font-medium">Price:</span> ${trip.price}</p>
                      <p><span className="font-medium">Rating:</span> {trip.rating}/5 ({trip.reviews} reviews)</p>
                    </div>
                    <a 
                      href={trip.permalink}
                      className="mt-3 w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition-colors inline-block text-center"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      View Details
                    </a>
                  </div>
                </Popup>
              </Marker>
            )
          })
        ).flat()}
      </MapContainer>

      {/* Legend */}
      <div className="absolute top-4 right-4 bg-white p-4 rounded-lg shadow-lg z-[1000]">
        <h4 className="font-bold text-sm mb-3">Regions</h4>
        <div className="space-y-2">
          {regions.filter(region => region.show_on_map).map((region) => (
            <div
              key={region.id}
              className="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-1 rounded"
              onClick={() => handleRegionClick(region.id)}
            >
              <div
                className="w-4 h-4 rounded"
                style={{ backgroundColor: region.polygon_color }}
              ></div>
              <span className="text-xs">{region.name}</span>
            </div>
          ))}
        </div>
        {selectedRegion && (
          <div className="mt-3 pt-3 border-t">
            <p className="text-xs text-gray-600">Click region to view trips</p>
          </div>
        )}
      </div>
    </div>
  )
}