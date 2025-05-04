@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">

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

<div class="container-fluid">
    <x-mobile.title-page title="Sunting Penugasan Kelas" subtitle="Menyunting Tugas Kelas." />

    <x-mobile.alert />

    <div class="row">
        <form class="col-12" wire:submit="edit">
            <div class="card">
                <div class="card-body px-3">
                    <div class="card border">
                        <div class="card-header">
                            <h4>Lokasi Anda</h4>
                            <button wire:click="resetLocation" type="button" class="btn btn-dark btn-sm mt-1"><i
                                    class="iconoir-map-pin"></i></button>
                        </div>

                        <div class="card-body" wire:ignore>
                            <div class="row">
                                <div class="col-12 rounded-3" id="map"></div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <x-form.input wire:model="latitude" name="latitude" type="text" label="Longitude"
                                        placeholder="0" disabled />
                                </div>

                                <div class="col-6">
                                    <x-form.input wire:model="longitude" name="longitude" type="text"
                                        label="Longitude" placeholder="0" disabled />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border">
                        <div class="card-header">
                            <h4>Waktu</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <x-form.input wire:model="waktuMulai" name="waktuMulai" type="datetime-local"
                                        label="Waktu Mulai Tugas" />

                                    <x-form.input wire:model="waktuSelesai" name="waktuSelesai" type="datetime-local"
                                        label="Waktu Selesai Tugas" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border">
                        <div class="card-header">
                            <h4>Input Data Penugasan</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <x-form.textarea wire:model="alasanGuru" name="alasanGuru"
                                        label="Alasan Anda Tidak Masuk" />

                                    <x-form.select wire:model="mataPelajaran" name="mataPelajaran"
                                        label="Mata Pelajaran">
                                        <option value="">- pilih mapel -</option>
                                        @foreach ($this->subjects as $subject)
                                            <option wire:key="row-{{ $subject->id }}" value="{{ $subject->id }}">
                                                {{ $subject->name ?? '-' }}</option>
                                        @endforeach
                                    </x-form.select>

                                    <div wire:ignore>
                                        <x-form.textarea id="keteranganTugas" wire:model="keteranganTugas"
                                            name="keteranganTugas" label="Deskirpsi / Keterangan Tugas"
                                            placeholder="Masukkan keterangan tugas jika ada." required />
                                    </div>

                                    <x-form.input wire:model.lazy="fileTugas" name="fileTugas"
                                        label="File Tugas (jika ada)" type="file" />
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="col-12">
                                <button wire:click="edit" type="submit" class="btn btn-primary-dark w-100">Sunting
                                    Tugas</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
    <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
    <script src="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/suneditor@latest/src/lang/ko.js"></script>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editor = SUNEDITOR.create(document.getElementById('keteranganTugas'), {
                height: 200,
                defaultStyle: "font-family: Times New Roman; font-size: 12px;",
                font: ['Times New Roman', 'Arial', 'Verdana', 'Tahoma', 'Courier New'],
                buttonList: [
                    ['font', 'fontSize', 'formatBlock', 'list', 'align', 'horizontalRule', 'lineHeight',
                        'undo', 'redo', 'bold', 'italic', 'underline'
                    ],
                ],
                lang: SUNEDITOR_LANG['en']
            });

            editor.onChange = function(contents) {
                @this.set('keteranganTugas', contents);
            };

            Livewire.on('changeKeteranganTugas', function(content) {
                editor.setContents(content[0]);
            });

            Livewire.on('clearDataKeteranganTugas', function(content) {
                editor.setContents(content[0]);
            })
        });
    </script>
@endpush
