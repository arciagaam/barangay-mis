const index_map = document.querySelector('#map');
const create_map = document.querySelector('#create_map');
const view_map = document.querySelector('#view_map');
/*
    Etong function na to yung nag gegenerate ng map sa "MAP DIV"
*/

function generateMap({ map_id, dragging = false }) {

    // SET MAP VARIABLE OR SETTINGS (INITIALIZE NG MAP)
    var map = L.map(map_id, {
        center: [14.493569, 120.904096], // CENTER NG YAKAL 5A
        zoom: 18, 
        maxZoom: 20,
        minZoom: 17,
        scrollWheelZoom: 'center', 
        zoomControl: false,
        dragging: dragging,
    })

    // GOOGLE SATELITE (TILE NG GOOGLE MAPS) PARA IPATONG SA EXISTING MAP PARA MAG KAROON NG TEXTURE
    const googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    // SAKOP NG BARANGAY
    const polygon = L.polygon([
        [14.493824, 120.902181],
        [14.493190, 120.902519],
        [14.493392, 120.902959],
        [14.492084, 120.903752],
        [14.491824, 120.904187],
        [14.493834, 120.905694],
        [14.494888, 120.904911],
    ]).addTo(map)

    return map;
}

// CHECK KUNG NASA MAPPING HOMEPAGE OR INDEX
if (index_map) {
    const map = generateMap({ map_id: 'map' });

    const res = await fetch(BASE_PATH + '/api/mappings', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin',
    });

    if(res.ok) {
        const {data} = await res.json();

        data.forEach(mapping => {
            L.marker([mapping.latitude, mapping.longitude])
            .addTo(map)
            .bindPopup(`
                <div style="display:flex; flex-direction:column; gap:.5rem">
                    <p style="margin:0">${mapping.first_name} ${mapping.middle_name} ${mapping.first_name}</p>
                    <p style="margin:0">${mapping.house_number} ${mapping.street_name} ${mapping.others}</p>
                </div>
            `)
            ;
        })
    }

}

// CHECK KUNG NASA ADD MAPPING
if (create_map) {

    // PAG HANDLE NG CLICK NG ADD MAPPING BUTTON
    const mapModal = document.querySelector('#map_modal')
    let residentId = null;
    let mappingId = null;

    document.querySelectorAll('.mapping_btn').forEach(btn => {
        btn.addEventListener('click', () => {
            residentId = btn.dataset.resident;
            mappingId = btn.dataset.mapping;

            mapModal.classList.toggle('invisible');
            mapModal.classList.toggle('flex');
        })
    })

    const map = generateMap({ map_id: 'create_map', dragging: true });

    const popup = L.popup();

    // METHOD NI LEAFLET PARA MA HANDLE YUNG ONCLICK SA LOOB NG MAP
    map.on('click', onMapClick);

    function onMapClick(e) {

        // LALABAS POPUP PAG LICK
        popup.setLatLng(e.latlng)
            .setContent(`<div class="flex gap-2"> <p>Longitude: ${e.latlng.lng.toPrecision(8)}</p> <p>Latitude: ${e.latlng.lat.toPrecision(8)}</p> </div>`)
            .openOn(map)

        // SINE-SET YUNG LONGITUDE LATITUDE LOCATED SA LOWER PART NG MODAL
        document.querySelector('#longitude').value = e.latlng.lng.toPrecision(8);
        document.querySelector('#latitude').value = e.latlng.lat.toPrecision(8);
    }

    // SET NG MAPPING
    document.querySelector('#set_mapping').addEventListener('click', () => {
        if (document.querySelector('#longitude').value == '' || document.querySelector('#latitude').value == '') {
            document.querySelector('#error-msg').innerText = "You must select a location"
            return false;
        }

        if (mappingId == '') {
            // fetch(url, options)
            // gumamit ng fetch para makapag send ng data from client side to server side
            fetch(BASE_PATH + '/mapping/new', {
                method: 'POST', // HTTP METHOD 
                headers: {
                    'Content-Type': 'application/json', // kung ano yung expected na content to send / receive
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content // CSRF TOKEN is yung nag vavalidate na galing sa web app na to yung request (bawal gamitin sa ibat ibang sites)
                },
                credentials: 'same-origin', // pang validate if galing sa same origin
                body: JSON.stringify({
                    residentId,
                    longitude: document.querySelector('#longitude').value,
                    latitude: document.querySelector('#latitude').value
                }) // ETO YUNG PINAPASA NA DATA PAPUNTANG SERVER SIDE
            }).then((res) => res.json())
            .then(data => {
                location.reload();
            })
        } else {
            // to update instead of adding a new mapping
            fetch(BASE_PATH + '/mapping/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    residentId,
                    mappingId,
                    longitude: document.querySelector('#longitude').value,
                    latitude: document.querySelector('#latitude').value
                })
            }).then((res) => res.json())
            .then(data => {
                location.reload();
            })
        }
    })
}


let routing;

// CHECK KUNG NASA VIEW MAPPING
if (view_map) {
    const mapModal = document.querySelector('#map_modal')
    
    // LONG LAT OF BARANGAY YAKAL 5A
    const brgyLat = 14.494045
    const brgyLong = 120.904006 
    
    let marker;

    // HANDLER NG VIEW MAPPING BUTTON
    document.querySelectorAll('.mapping_btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const residentId = btn.dataset.resident;
            const mappingId = btn.dataset.mapping;
            const longitude = btn.dataset.longitude;
            const latitude = btn.dataset.latitude;

            document.querySelector('#longitude').value = longitude;
            document.querySelector('#latitude').value = latitude;

            mapModal.classList.toggle('invisible');
            mapModal.classList.toggle('flex');

            if (routing != null) {
                routing.spliceWaypoints(0, 2);
                routing.remove();
            }

            // NAG SESET NG MARKER SA MAP (LOCATION NUNG RESIDENT)
            marker = L.marker([latitude, longitude]).addTo(map);

            // ROUTING METHOD NG LEAFLET NA NAG TATAKE NG WAYPOINTS TO DETECT YUNG SHORTEST PATH FROM ONE POINT TO ANOTHER
            routing = L.Routing.control({
                waypoints: [
                    L.latLng(brgyLat, brgyLong), // BARANGAY HALL WAYPOINT
                    L.latLng(latitude, longitude) // RESIDENT WAYPOINT
                ]
            }).addTo(map);
            // marker.bindPopup(`<div class='flex flex-col gap-1 items-center justify-center'><p>Open street view</p>  <a href=http://maps.google.com/maps?q=&layer=c&cbll='${latitude}','${longitude}'&cbp=11,0,0,0,0' target="blank">Street View</a></div>`).openPopup();
        })
    })

    const map = generateMap({ map_id: 'view_map', dragging: true });
}

// CLOSE BUTTON

if (document.querySelector('#close_map')) {

    if(routing) {
        routing.spliceWaypoints(0, 2);
        routing.remove();
    }

    document.querySelector('#close_map').addEventListener('click', () => {
        const mapModal = document.querySelector('#map_modal')
        mapModal.classList.toggle('invisible');
    })
}



