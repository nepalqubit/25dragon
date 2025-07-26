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
}

interface InteractiveMapProps {
  trips: Trip[]
}

// Nepal regions with approximate coordinates
const nepalRegions = {
  everest: {
    name: 'Everest Region',
    center: [27.9881, 86.9250] as [number, number],
    bounds: [
      [27.7, 86.5],
      [28.3, 87.3],
      [28.1, 87.1],
      [27.9, 86.7]
    ] as [number, number][],
    color: '#ef4444',
    trips: ['1'] as string[]
  },
  annapurna: {
    name: 'Annapurna Region',
    center: [28.5967, 83.8200] as [number, number],
    bounds: [
      [28.2, 83.5],
      [28.9, 84.1],
      [28.7, 84.3],
      [28.4, 83.7]
    ] as [number, number][],
    color: '#10b981',
    trips: ['2'] as string[]
  },
  langtang: {
    name: 'Langtang Region',
    center: [28.2096, 85.5200] as [number, number],
    bounds: [
      [28.0, 85.2],
      [28.4, 85.8],
      [28.3, 85.6],
      [28.1, 85.4]
    ] as [number, number][],
    color: '#3b82f6',
    trips: ['3'] as string[]
  },
  manaslu: {
    name: 'Manaslu Region',
    center: [28.5500, 84.5600] as [number, number],
    bounds: [
      [28.3, 84.3],
      [28.7, 84.8],
      [28.6, 84.6],
      [28.4, 84.4]
    ] as [number, number][],
    color: '#f59e0b',
    trips: [] as string[]
  }
}

// Sample trekking routes
const sampleRoutes = {
  '1': [ // Everest Base Camp
    [27.7172, 86.7138], // Lukla
    [27.8056, 86.7139], // Namche Bazaar
    [27.8369, 86.7647], // Tengboche
    [27.8758, 86.8289], // Dingboche
    [27.8881, 86.8531], // Lobuche
    [27.9006, 86.8528]  // EBC
  ] as [number, number][],
  '2': [ // Annapurna Circuit
    [28.2096, 83.9856], // Besisahar
    [28.3500, 84.1200], // Manang
    [28.4500, 84.0800], // Thorong Phedi
    [28.4800, 84.0200], // Muktinath
    [28.3700, 83.9400]  // Pokhara
  ] as [number, number][],
  '3': [ // Langtang Valley
    [28.1000, 85.3200], // Syabrubesi
    [28.2000, 85.4500], // Langtang Village
    [28.2096, 85.5200], // Kyanjin Gompa
  ] as [number, number][]
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
  const [selectedRegion, setSelectedRegion] = useState<string | null>(null)
  const [mapReady, setMapReady] = useState(false)
  const mapRef = useRef<L.Map | null>(null)

  useEffect(() => {
    setMapReady(true)
  }, [])

  const handleRegionClick = (regionKey: string) => {
    setSelectedRegion(regionKey === selectedRegion ? null : regionKey)
  }

  if (!mapReady) {
    return (
      <div className="h-full w-full bg-gray-200 animate-pulse flex items-center justify-center">
        <div className="text-gray-500">Loading map...</div>
      </div>
    )
  }

  return (
    <div className="h-full w-full relative">
      <MapContainer
        center={[28.3949, 84.1240]} // Center of Nepal
        zoom={7}
        style={{ height: '100%', width: '100%' }}
        ref={mapRef}
      >
        <TileLayer
          attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        />
         
        {/* Region Polygons */}
        {Object.entries(nepalRegions).map(([key, region]) => (
          <Polygon
            key={key}
            positions={region.bounds}
            pathOptions={{
              color: region.color,
              fillColor: region.color,
              fillOpacity: selectedRegion === key ? 0.4 : 0.2,
              weight: selectedRegion === key ? 3 : 2,
              opacity: 0.8
            }}
            eventHandlers={{
              click: () => handleRegionClick(key)
            }}
          >
            <Popup>
              <div className="p-2">
                <h3 className="font-bold text-lg mb-2">{region.name}</h3>
                <p className="text-sm text-gray-600 mb-2">
                  {region.trips.length} trek(s) available
                </p>
                <button className="bg-primary-600 text-white px-3 py-1 rounded text-sm hover:bg-primary-700 transition-colors">
                  View Treks
                </button>
              </div>
            </Popup>
          </Polygon>
        ))}
         
        {/* Trekking Routes */}
        {selectedRegion && selectedRegion in sampleRoutes && (
          <Polyline
            positions={sampleRoutes[selectedRegion as keyof typeof sampleRoutes]}
            pathOptions={{
              color: '#dc2626',
              weight: 4,
              opacity: 0.8,
              dashArray: '10, 5'
            }}
          />
        )}

        {/* Route Markers */}
        {selectedRegion && selectedRegion in sampleRoutes && (
          sampleRoutes[selectedRegion as keyof typeof sampleRoutes].map((position, index) => (
            <Marker
              key={`${selectedRegion}-${index}`}
              position={position}
              icon={createCustomIcon(nepalRegions[selectedRegion as keyof typeof nepalRegions].color)}
            >
              <Popup>
                <div className="p-2">
                  <p className="text-sm font-medium">Route Point {index + 1}</p>
                  <p className="text-xs text-gray-600">
                    Lat: {position[0].toFixed(4)}, Lng: {position[1].toFixed(4)}
                  </p>
                </div>
              </Popup>
            </Marker>
          ))
        )}

        {/* Trip Markers */}
        {trips.map((trip) => {
          const region = Object.values(nepalRegions).find(r => 
            r.trips.includes(trip.id)
          )
          if (!region) return null

          return (
            <Marker
              key={trip.id}
              position={region.center}
              icon={createCustomIcon('#059669')}
            >
              <Popup>
                <div className="p-3 max-w-xs">
                  <h4 className="font-bold text-base mb-2">{trip.title}</h4>
                  <div className="space-y-1 text-sm">
                    <p><span className="font-medium">Duration:</span> {trip.duration} days</p>
                    <p><span className="font-medium">Difficulty:</span> {trip.difficulty}</p>
                    <p><span className="font-medium">Price:</span> ${trip.price}</p>
                    <p><span className="font-medium">Rating:</span> {trip.rating}/5 ({trip.reviews} reviews)</p>
                  </div>
                  <button className="mt-3 w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition-colors">
                    View Details
                  </button>
                </div>
              </Popup>
            </Marker>
          )
        })}
      </MapContainer>

      {/* Legend */}
      <div className="absolute top-4 right-4 bg-white p-4 rounded-lg shadow-lg z-[1000]">
        <h4 className="font-bold text-sm mb-3">Regions</h4>
        <div className="space-y-2">
          {Object.entries(nepalRegions).map(([key, region]) => (
            <div
              key={key}
              className="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-1 rounded"
              onClick={() => handleRegionClick(key)}
            >
              <div
                className="w-4 h-4 rounded"
                style={{ backgroundColor: region.color }}
              ></div>
              <span className="text-xs">{region.name}</span>
            </div>
          ))}
        </div>
        {selectedRegion && (
          <div className="mt-3 pt-3 border-t">
            <p className="text-xs text-gray-600">Click region to toggle route</p>
          </div>
        )}
      </div>
    </div>
  )
}