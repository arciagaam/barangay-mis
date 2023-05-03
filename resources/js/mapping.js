const index_map = document.querySelector('#map');
const create_map = document.querySelector('#create_map')
const view_map = document.querySelector('#view_map')
function generateMap({map_id, dragging=false}) {
    var map = L.map(map_id, {
        center : [14.493569, 120.904096],
        zoom:18,
        maxZoom:20,
        minZoom:17,
        scrollWheelZoom:'center',
        zoomControl:false,
        dragging:dragging,
    })

    const googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    }).addTo(map);
    
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

if(index_map) {
    const map = generateMap({map_id:'map'});
}

if(create_map) {
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

    const map = generateMap({map_id:'create_map', dragging:true});

    const popup = L.popup();
    function onMapClick(e) {
        popup.setLatLng(e.latlng)
        .setContent(`<div class="flex gap-2"> <p>Longitude: ${e.latlng.lng.toPrecision(8)}</p> <p>Latitude: ${e.latlng.lat.toPrecision(8)}</p> </div>`)
        .openOn(map)

        document.querySelector('#longitude').value = e.latlng.lng.toPrecision(8);
        document.querySelector('#latitude').value = e.latlng.lat.toPrecision(8);
    }

    map.on('click', onMapClick);
    
    document.querySelector('#set_mapping').addEventListener('click', () => {
        if(document.querySelector('#longitude').value == '' || document.querySelector('#latitude').value == '') {
            document.querySelector('#error-msg').innerText = "You must select a location"
            return false;
        }
        if(mappingId == ''){
            fetch(BASE_PATH + '/mapping/new', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    residentId,
                    longitude: document.querySelector('#longitude').value,
                    latitude: document.querySelector('#latitude').value
                })
            }).then((res) => res.json())
            .then(data => {
                location.reload();
            })
        }else{
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

if(view_map) {
    const mapModal = document.querySelector('#map_modal')
    const brgyLat = 14.494045
    const brgyLong = 120.904006

    let marker;
    let routing;

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

            console.log('wa')


            marker = L.marker([latitude, longitude]).addTo(map);

            routing = L.Routing.control({
                waypoints: [
                    L.latLng(brgyLat, brgyLong),
                    L.latLng(latitude, longitude)
                ]
            }).addTo(map);
            // marker.bindPopup(`<div class='flex flex-col gap-1 items-center justify-center'><p>Open street view</p>  <a href=http://maps.google.com/maps?q=&layer=c&cbll='${latitude}','${longitude}'&cbp=11,0,0,0,0' target="blank">Street View</a></div>`).openPopup();
        })
    })


    const map = generateMap({map_id:'view_map', dragging:true});

    
    document.querySelector('#close_map').addEventListener('click', () => {
        mapModal.classList.toggle('invisible');
        mapModal.classList.toggle('flex');

        map.removeControl(routing)

    })
}


