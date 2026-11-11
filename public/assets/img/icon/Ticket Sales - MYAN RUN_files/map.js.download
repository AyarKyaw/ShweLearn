/**
 * Initialize Mapbox Maps
 * Uses window.mapboxToken provided by Laravel Blade
 */

function setupMapboxMap(containerId) {
    const mapElement = document.getElementById(containerId);
    
    // Only run if the element exists on the current page
    if (mapElement) {
        // Use the token from the global window object (set in Blade)
        if (!window.mapboxToken) {
            console.error("Mapbox token is missing. Please check your .env and Blade setup.");
            return;
        }

        mapboxgl.accessToken = window.mapboxToken;

        // Default coordinates
        const coordinates = [-0.108968, 51.492933];

        const map = new mapboxgl.Map({
            container: containerId,
            style: 'mapbox://styles/mapbox/light-v11',
            center: coordinates,
            zoom: 14,
            cooperativeGestures: true
        });

        // Create the GeoJSON data structure
        const geojson = {
            type: 'FeatureCollection',
            features: [
                {
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: coordinates
                    }
                }
            ]
        };

        // Add markers to the map
        for (const feature of geojson.features) {
            const el = document.createElement('div');
            el.className = 'marker';

            new mapboxgl.Marker(el)
                .setLngLat(feature.geometry.coordinates)
                .addTo(map);
        }
    }
}

// Initialize for both possible IDs
setupMapboxMap('map');
setupMapboxMap('map1');