@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.css" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />

    <link rel="stylesheet" type="text/css"
        href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">

    <script src="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js"></script>

    <style>
        #map {
            height: 400px;
        }
    </style>
@endpush

<div>
    <x-slot name="title">Lokasi Guru</x-slot>

    <x-slot name="pagePretitle">Lokasi Guru</x-slot>

    <x-slot name="pageTitle">Tambahkan Lokasi Guru</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('on-duty.grade-assignment.index')" />
    </x-slot>

    <x-alert />

    @unless (session('alert'))
        @if (!$this->gradeAssignmentLocation['latitude'] && !$this->gradeAssignmentLocation['longitude'])
            <div class="alert alert-warning alert-dismissible bg-white" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <h1 class="text-warning las la-exclamation-triangle"></h1>
                    </div>

                    <div>
                        <h4 class="alert-title">Lokasi Belum Ditentukan</h4>
                        <div class="text-muted">Tentukan dan atur lokasi terlebih dahulu, dengan menekan lokasi saat ini dan
                            simpan lokasi</div>
                    </div>
                </div>

                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @else
            <div class="alert alert-success alert-dismissible bg-white" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <h1 class="text-success las la-check"></h1>
                    </div>

                    <div>
                        <h4 class="alert-title">Lokasi Telah Di Tentukan</h4>
                        <div class="text-muted">Abaikan jika tidak ingin mengubah lokasi.</div>
                    </div>
                </div>

                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif
    @endunless

    <form class="card" wire:submit.prevent="save">
        <div class="border-bottom">
            <div class="d-flex justify-content-between p-3 flex-wrap">
                <div>
                    <h3 class="mb-0">Lokasi Guru, Saat Memberikan Tugas Kelas</h3>
                    <p class="mt-0 mb-0">Geser marker lokasi atau cari lokasi untuk menentukan titik lokasi piket.</p>
                </div>
                <div class="text-end mt-lg-0 mt-3">
                    <button wire:click="resetLocation" type="button" class="btn btn-blue">Lokasi Saat Ini<span
                            class="ms-1 fs-1 las la-map-marker"></span></button>
                </div>
            </div>
        </div>

        <div class="card-body" wire:ignore>
            <div class="row">
                <div class="col-12 rounded-3" id="map"></div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="longitude" name="longitude" label="Longitude" type="text" disabled />
                </div>
                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="latitude" name="latitude" label="Latitude" type="text" disabled />
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="btn-list justify-content-end">
                <button wire:click='resetLocation' type="button" class="btn">Reset Lokasi</button>

                <x-datatable.button.save target="save" name="Simpan Lokasi" class="btn-green" />
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
            integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

        <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>

        <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>

        <script src="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js"></script>

        <script>
            document.addEventListener('livewire:init', () => {
                let mapLayer, mapMarker, searchControl, resultSearch;

                const ZOOM = 13;
                const MIN_ZOOM = 7;
                const MAX_ZOOM = 15;
                const IS_FULL_SCREEN = true;

                const OSM = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                });

                const getLocation = () => {
                    return new Promise((resolve, reject) => {
                        if (!navigator.geolocation) {
                            reject(new Error("Geolocation tidak didukung oleh browser ini."));
                            return;
                        }

                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const latitude = @this.latitude ?? position.coords.latitude;
                                const longitude = @this.longitude ?? position.coords.longitude;

                                Livewire.dispatch('changeLocation', {
                                    latitude,
                                    longitude
                                });

                                resolve({
                                    latitude,
                                    longitude
                                });
                            },
                            (error) => {
                                console.error("Gagal mendapatkan lokasi:", error);
                                reject(error);
                            }
                        );
                    });
                };

                const changeMarker = (lat, lng) => {
                    const customMarker = L.icon({
                        iconUrl: @this.mapMarker,
                        iconSize: [90, 70],
                        popupAnchor: [-3, -38]
                    });

                    if (mapMarker) {
                        mapMarker.setLatLng([lat, lng]);
                    } else {
                        mapMarker = L.marker([lat, lng], {
                            draggable: true,
                            icon: customMarker,
                        }).addTo(mapLayer);

                        mapMarker.on('dragend', (e) => {
                            const {
                                lat,
                                lng
                            } = e.target.getLatLng();
                            Livewire.dispatch('changeLocation', {
                                latitude: lat,
                                longitude: lng
                            });
                        });
                    }
                };

                const initMap = (lat, lng) => {
                    mapLayer = L.map('map', {
                        center: [lat, lng],
                        zoom: ZOOM,
                        layers: [OSM],
                        minZoom: MIN_ZOOM,
                        maxZoom: MAX_ZOOM,
                        fullscreenControl: IS_FULL_SCREEN,
                    });

                    changeMarker(lat, lng);

                    mapLayer.on('click', (e) => {
                        const {
                            lat,
                            lng
                        } = e.latlng;
                        Livewire.dispatch('changeLocation', {
                            latitude: lat,
                            longitude: lng
                        });
                        changeMarker(lat, lng);
                    });

                    searchControl = new L.esri.Controls.Geosearch().addTo(mapLayer);
                    resultSearch = new L.LayerGroup().addTo(mapLayer);

                    searchControl.on('results', (data) => {
                        if (data.results.length > 0) {
                            const {
                                lat,
                                lng
                            } = data.results[0].latlng;
                            Livewire.dispatch('changeLocation', {
                                latitude: lat,
                                longitude: lng
                            });
                            changeMarker(lat, lng);
                        }
                    });
                };

                Livewire.on('resetLocation', () => {
                    getLocation()
                        .then(({
                            latitude,
                            longitude
                        }) => {
                            Livewire.dispatch('changeLocation', {
                                latitude,
                                longitude
                            });

                            document.querySelector('input[name="latitude"]').value = latitude;
                            document.querySelector('input[name="longitude"]').value = longitude;

                            if (mapMarker) {
                                mapMarker.setLatLng([latitude, longitude]);
                                mapLayer.setView([latitude, longitude], ZOOM);
                            } else {
                                changeMarker(latitude, longitude);
                            }
                        })
                        .catch((error) => console.error("Gagal mengatur ulang lokasi:", error));
                });

                getLocation()
                    .then(({
                        latitude,
                        longitude
                    }) => initMap(latitude, longitude))
                    .catch((error) => console.error("Gagal memuat peta:", error));
            });
        </script>
    @endpush
</div>
