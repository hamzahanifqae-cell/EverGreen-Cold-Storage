// Map of the cold storage units on the landing page (Leaflet + OpenStreetMap tiles).
// Loaded on demand from home.js only when the page has a [data-map] element.
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const escape = (value) => String(value).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

export function mountLocationsMap(el) {
    let units = [];
    try { units = JSON.parse(el.dataset.locations || '[]'); } catch (e) { units = []; }
    if (!units.length) return;

    const touch = window.matchMedia('(hover: none)').matches;
    const map = L.map(el, {
        scrollWheelZoom: false, // don't hijack page scrolling
        dragging: !touch, // one finger keeps scrolling the page on phones
        tap: false,
        zoomControl: true,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>',
    }).addTo(map);
    map.attributionControl.setPrefix('<a href="https://leafletjs.com" target="_blank" rel="noopener">Leaflet</a>');

    const markers = units.map((unit) => {
        const icon = L.divIcon({
            className: 'cs-map-pin',
            html: `<span><b>${escape(unit.n)}</b></span>`,
            iconSize: [38, 48],
            iconAnchor: [19, 46],
            popupAnchor: [0, -42],
        });
        const popup = `
            <div class="cs-map-popup">
                <strong>${escape(unit.title)}</strong>
                <span>${escape(unit.address)}</span>
                <span>${escape(unit.contact)}</span>
                <a href="tel:${escape(unit.tel)}" dir="ltr">${escape(unit.phone)}</a>
                <a class="cs-map-popup-dir" href="${escape(unit.directions)}" target="_blank" rel="noopener">${escape(unit.directionsLabel)} →</a>
            </div>`;
        return L.marker([unit.lat, unit.lng], { icon, title: unit.title, keyboard: true }).addTo(map).bindPopup(popup);
    });

    const bounds = L.latLngBounds(units.map((u) => [u.lat, u.lng]));
    const fit = () => map.fitBounds(bounds, { padding: [56, 56], maxZoom: 12 });
    fit();

    // "Show on map" buttons in the unit cards
    document.querySelectorAll('[data-show-on-map]').forEach((button) => {
        button.addEventListener('click', () => {
            const marker = markers[Number(button.dataset.showOnMap)];
            if (!marker) return;
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            map.flyTo(marker.getLatLng(), 14, { duration: 0.8 });
            marker.openPopup();
        });
    });

    // The map can be laid out before it is visible (reveal animation); recalc its size once shown.
    new ResizeObserver(() => map.invalidateSize()).observe(el);

    return { map, markers, fit };
}
