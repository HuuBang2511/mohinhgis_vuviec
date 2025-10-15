<?php

use yii\helpers\Url;

// Đăng ký các asset cần thiết.
app\widgets\maps\LeafletMapAsset::register($this);
app\widgets\maps\plugins\leafletprint\PrintMapAsset::register($this);
app\widgets\maps\plugins\markercluster\MarkerClusterAsset::register($this);
app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset::register($this);
app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset::register($this);

$this->title = 'Bản đồ GIS';
$this->params['hideHero'] = true;

// Tạo URL cơ sở cho tất cả các trang chi tiết
$vuViecDetailUrlBase = Url::to(['/quanly/vu-viec/view']);
$nocGiaDetailUrlBase = Url::to(['/quanly/noc-gia/view']);
$diemNhayCamDetailUrlBase = Url::to(['/quanly/diem-nhay-cam/view']);
$diemTrongDiemDetailUrlBase = Url::to(['/quanly/diem-trong-diem/view']);
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!-- Import Google Font & Icons -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<style>
    :root {
        --primary-color: #0d6efd;
        --light-gray: #f8fafc;
        --border-color: #e5e7eb;
        --background-color: #ffffff;
        --text-color: #1e293b;
        --text-light-color: #64748b;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --transition-speed: 0.3s;
        --font-family: 'Inter', sans-serif;
        --app-height: 100vh;
    }

    body, html {
        margin: 0; padding: 0; height: 100%; width: 100%;
        overflow: hidden; font-family: var(--font-family); color: var(--text-color);
        background-color: var(--light-gray);
    }

    #mapInfo {
        display: flex; height: var(--app-height);
    }

    #mapTong {
        flex-grow: 1; height: 100%; transition: width var(--transition-speed); position: relative;
    }

    #map {
        height: 100%; width: 100%; background-color: var(--light-gray);
    }

    /* --- Side Panel (Tabs) --- */
    #tabs {
        width: 25%; max-width: 380px; min-width: 320px;
        background: var(--background-color); border-right: 1px solid var(--border-color);
        transition: transform var(--transition-speed) ease-in-out, min-width var(--transition-speed) ease-in-out, width var(--transition-speed) ease-in-out;
        display: flex; flex-direction: column; transform: translateX(0);
        box-shadow: var(--shadow-lg);
    }

    #tabs.hidden {
        min-width: 0; width: 0; transform: translateX(0); border-right: none;
    }
    
    .tabs-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 15px; border-bottom: 1px solid var(--border-color); flex-shrink: 0;
    }
    .tabs-header img { width: auto; height: 40px; display: block; }

    .tab-buttons { display: flex; border-bottom: 1px solid var(--border-color); flex-shrink: 0; }
    .tab-button {
        flex: 1; padding: 12px; text-align: center; cursor: pointer;
        background: var(--background-color); border: none; font-weight: 500;
        color: var(--text-light-color); border-bottom: 3px solid transparent;
        transition: color 0.2s, border-color 0.2s;
    }
    .tab-button:hover { color: var(--primary-color); }
    .tab-button.active { color: var(--text-color); border-bottom: 3px solid var(--primary-color); }

    .tab-content { display: none; padding: 15px; overflow-y: auto; flex-grow: 1; -webkit-overflow-scrolling: touch; }
    .tab-content.active { display: block; }
    
    .section-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; }
    #search-box { position: relative; margin-bottom: 1rem; }
    #search-input { width: 100%; padding: 8px 12px 8px 36px; border: 1px solid var(--border-color); border-radius: 8px; box-sizing: border-box; }
    #search-box .icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-light-color); }
    #layer-control .layer-item {
        display: flex; align-items: center; padding: 10px; border-radius: 8px;
        cursor: pointer; transition: background-color 0.2s;
    }
    #layer-control .layer-item:hover { background-color: var(--light-gray); }
    #layer-control .layer-item input { margin-right: 12px; width: 16px; height: 16px; }
    #layer-control .layer-item .icon { margin-right: 8px; color: var(--text-light-color); }
    
    #feature-details { word-wrap: break-word; }
    .popup-content table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .popup-content th, .popup-content td { padding: 10px; text-align: left; border-bottom: 1px solid var(--border-color); }
    .popup-content th { font-weight: 500; width: 40%; color: var(--text-light-color); }
    .popup-content h4 { margin-top: 0; color: var(--primary-color); }

    .detail-button {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-size: 14px;
        color: white;
        background-color: var(--primary-color);
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    .detail-button:hover {
        background-color: #0b5ed7;
    }
    .detail-button .icon {
        width: 16px;
        height: 16px;
        margin-right: 6px;
    }


    .legend { background-color: var(--background-color); padding: 15px; border-radius: 8px; box-shadow: var(--shadow-lg); display: none; max-height: 40vh; overflow-y: auto; }
    .legend-item { display: flex; align-items: center; margin-bottom: 8px; font-size: 14px; }
    .legend img { width: 20px; height: 20px; margin-right: 10px; }

    #toggle-tab-btn {
        position: absolute; top: 15px; left: 15px; z-index: 1000;
        background: var(--background-color); border: 1px solid var(--border-color); border-radius: 8px;
        width: 40px; height: 40px; cursor: pointer; display: flex; align-items: center; justify-content: center;
        box-shadow: var(--shadow);
    }
    
    .leaflet-bar { border-radius: 8px !important; box-shadow: var(--shadow) !important; }

    /* Loading Overlay */
    #loading-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.7); z-index: 20000;
        display: flex; align-items: center; justify-content: center;
        transition: opacity 0.3s;
    }
    #loading-overlay.hidden { opacity: 0; pointer-events: none; }
    .spinner { width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    @media screen and (max-width: 768px) {
        #tabs {
            width: 100%; max-width: none; position: absolute; top: 0; left: 0;
            height: var(--app-height); z-index: 2000; transform: translateX(-100%);
            border-right: none;
        }
        #tabs.active { transform: translateX(0); }
        #mapTong { width: 100% !important; }
    }
</style>

<div id="mapInfo">
    <div id="tabs">
        <div class="tabs-header">
            <a href="<?= Yii::$app->homeUrl ?>" target="_blank">
                <img src="https://gis.nongdanviet.net/resources/images/logo_map_vuviec.jpg" alt="Logo">
            </a>
            <button id="back-to-map-mobile-btn"></button>
        </div>
        
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="layer">Lớp dữ liệu</button>
            <button class="tab-button" data-tab="info">Thông tin</button>
        </div>

        <div id="layer-content" class="tab-content active">
            <div class="section-title">Tìm kiếm Vụ việc</div>
             <div id="search-box">
                <i class="icon" data-lucide="search" style="width:18px; height:18px;"></i>
                <input type="text" id="search-input" placeholder="Nhập mã hoặc nội dung...">
            </div>

            <div class="section-title">Các lớp dữ liệu</div>
            <div id="layer-control"></div>
        </div>

        <div id="info-content" class="tab-content">
            <div class="section-title">Thông tin chi tiết</div>
            <div id="feature-details"><p>Nhấn vào một đối tượng trên bản đồ để xem thông tin.</p></div>
        </div>
    </div>

    <div id="mapTong">
        <div id="map"></div>
        <button id="toggle-tab-btn"></button>
        <div id="loading-overlay"><div class="spinner"></div></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const App = {
        // --- CONFIGURATION ---
        WMS_URL: 'http://103.9.77.141:8080/geoserver/mohinhgis_pa05/wms',
        GEOJSON_VUVEC_URL: 'http://103.9.77.141:8080/geoserver/mohinhgis_pa05/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=mohinhgis_pa05%3Avu_viec&maxFeatures=5000&outputFormat=application%2Fjson',
        DETAIL_URLS: {
            vuViec: '<?= $vuViecDetailUrlBase ?>',
            nocGia: '<?= $nocGiaDetailUrlBase ?>',
            diemNhayCam: '<?= $diemNhayCamDetailUrlBase ?>',
            diemTrongDiem: '<?= $diemTrongDiemDetailUrlBase ?>',
        },
        MAP_CENTER: [20.47206102639595, 106.318817631933],
        MAP_ZOOM: 14,
        
        map: null,
        leafletLayers: {},
        vuViecGeoJsonData: null,
        
        init() {
            this.UI.init();
            this.Map.init();
            this.Layers.init();
            this.Events.init();
            lucide.createIcons();
        },

        // --- MODULE QUẢN LÝ BẢN ĐỒ ---
        Map: {
            init() {
                App.map = L.map('map', { zoomControl: false }).setView(App.MAP_CENTER, App.MAP_ZOOM);
                L.control.zoom({ position: 'topright' }).addTo(App.map);

                const baseMaps = {
                    "Bản đồ Google": L.tileLayer('https://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', { maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] }).addTo(App.map),
                    "Ảnh vệ tinh": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', { maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] })
                };

                App.map.createPane('highlightPane').style.zIndex = 700;
                App.leafletLayers.highlight = L.geoJSON(null, {
                    pane: 'highlightPane',
                    style: { color: '#ff0000', weight: 5, opacity: 1, fillOpacity: 0.3, dashArray: '5, 5' }
                }).addTo(App.map);

                L.control.layers(baseMaps, null, { position: 'topright' }).addTo(App.map);
                L.control.scale({ imperial: false }).addTo(App.map);
                new L.Control.Measure({ position: 'topright', primaryLengthUnit: 'meters', primaryAreaUnit: 'sqmeters' }).addTo(App.map);
                new L.Control.Locate({ position: 'topright', strings: { title: "Hiện vị trí" } }).addTo(App.map);
                
                const legendControl = L.control({ position: 'bottomright' });
                legendControl.onAdd = () => L.DomUtil.create('div', 'legend');
                legendControl.addTo(App.map);
                App.UI.legendContainer = legendControl.getContainer();

                const legendToggle = L.control({ position: 'bottomright' });
                legendToggle.onAdd = () => {
                    const button = L.DomUtil.create('button', 'leaflet-bar leaflet-control-layers');
                    button.innerHTML = '<i data-lucide="book-open" style="width:18px; height:18px; margin: 5px;"></i>';
                    button.style.cursor = 'pointer';
                    button.title = 'Hiện chú giải';
                    button.onclick = () => App.UI.toggleLegend();
                    return button;
                };
                legendToggle.addTo(App.map);
            }
        },

        // --- MODULE QUẢN LÝ LỚP DỮ LIỆU ---
        Layers: {
            config: [
                { id: 'clusterVuviecLayer', type: 'cluster', displayName: 'Vụ việc (Cụm)', defaultVisible: false, icon: 'layout-grid' },
                { id: 'heatmapVuviecLayer', type: 'heatmap', displayName: 'Bản đồ nhiệt Vụ việc', defaultVisible: false, icon: 'flame' },
                { id: 'wmsVuviecLayer', type: 'wms', wmsName: 'mohinhgis_pa05:vu_viec', displayName: 'Vụ việc (Điểm WMS)', defaultVisible: false, zIndex: 550, icon: 'map-pin', popupFields: {'ma_vu_viec': 'Mã vụ việc', 'tom_tat_noi_dung' : 'Tóm tắt nội dung'} },
                { id: 'wmsDiemnhaycamLayer', type: 'wms', wmsName: 'mohinhgis_pa05:diem_nhay_cam', displayName: 'Điểm nhạy cảm', defaultVisible: false, zIndex: 540, icon: 'siren', popupFields: {'tenloaihinh': 'Tên loại hình', 'thongtin': 'Thông tin'} },
                { id: 'wmsDiemtrongdiemLayer', type: 'wms', wmsName: 'mohinhgis_pa05:diem_trong_diem', displayName: 'Điểm trọng điểm', defaultVisible: false, zIndex: 530, icon: 'target', popupFields: {'tenloaihinh': 'Tên loại hình', 'thongtin': 'Thông tin'} },
                { id: 'wmsNocgiaLayer', type: 'wms', wmsName: 'mohinhgis_pa05:noc_gia', displayName: 'Nóc gia', defaultVisible: false, zIndex: 520, icon: 'home', popupFields: {'so_nha': 'Số nhà', 'ten_duong' : 'Tên đường'} },
                { id: 'wmsKhuphoLayer', type: 'wms', wmsName: 'mohinhgis_pa05:kp', displayName: 'Khu phố', defaultVisible: true, zIndex: 450, icon: 'map', popupFields: {'TenKhuPho': 'Tên khu phố'} },
            ],
            
            // SỬA LỖI: Bọc trong try...finally để đảm bảo spinner luôn tắt
            async init() {
                App.UI.setLoading(true);
                try {
                    await this.fetchVuViecData();
                    this.config.forEach(cfg => this.createLayer(cfg));
                    App.UI.buildLayerControl(this.config);
                } catch (error) {
                    console.error("Lỗi nghiêm trọng khi khởi tạo lớp dữ liệu:", error);
                    App.UI.showError("Đã xảy ra lỗi khi tải dữ liệu bản đồ. Vui lòng thử lại.");
                } finally {
                    App.UI.setLoading(false);
                }
            },
            
            async fetchVuViecData() {
                try {
                    const response = await fetch(App.GEOJSON_VUVEC_URL);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    App.vuViecGeoJsonData = await response.json();
                } catch (error) {
                    console.error('Lỗi tải dữ liệu GeoJSON Vụ việc:', error);
                    // Ném lỗi ra ngoài để khối try...catch bên ngoài có thể bắt được
                    throw error;
                }
            },
            
            // SỬA LỖI: Xử lý dữ liệu an toàn hơn
            createLayer(config) {
                let layer;
                switch(config.type) {
                    case 'wms':
                        App.map.createPane(config.id).style.zIndex = config.zIndex;
                        layer = L.tileLayer.wms(App.WMS_URL, {
                            layers: config.wmsName, format: 'image/png', transparent: true, maxZoom: 22, pane: config.id
                        });
                        break;
                    case 'heatmap':
                         if (App.vuViecGeoJsonData && App.vuViecGeoJsonData.features) {
                            const heatPoints = App.vuViecGeoJsonData.features
                                .filter(f => f.geometry && f.geometry.coordinates) // Kiểm tra dữ liệu hợp lệ
                                .map(f => [f.geometry.coordinates[1], f.geometry.coordinates[0]]);
                            layer = L.heatLayer(heatPoints, { radius: 25, blur: 15, maxZoom: 18 });
                        }
                        break;
                    case 'cluster':
                         if (App.vuViecGeoJsonData && App.vuViecGeoJsonData.features) {
                            layer = L.markerClusterGroup();
                            // Lọc các đối tượng có geometry hợp lệ
                            const validFeatures = App.vuViecGeoJsonData.features.filter(f => f.geometry && f.geometry.coordinates);
                            const geoJsonLayer = L.geoJSON({type: 'FeatureCollection', features: validFeatures}, {
                                onEachFeature: (feature, marker) => {
                                    marker.on('click', () => {
                                        App.UI.displayFeatureInfo(feature, config);
                                        App.leafletLayers.highlight.clearLayers().addData(feature);
                                    });
                                }
                            });
                            layer.addLayer(geoJsonLayer);
                        }
                        break;
                }
                if (layer) {
                    App.leafletLayers[config.id] = layer;
                    if (config.defaultVisible) layer.addTo(App.map);
                }
            },

            toggle(layerId, visible) {
                const layer = App.leafletLayers[layerId];
                if (!layer) return;
                if (visible) App.map.addLayer(layer);
                else App.map.removeLayer(layer);
            },

            filterClusterLayer(searchText) {
                const clusterLayer = App.leafletLayers.clusterVuviecLayer;
                if (!clusterLayer || !App.vuViecGeoJsonData) return;
                
                clusterLayer.clearLayers();
                const filteredData = App.vuViecGeoJsonData.features.filter(feature => {
                    const props = feature.properties;
                    const content = `${props.ma_vu_viec || ''} ${props.tom_tat_noi_dung || ''}`.toLowerCase();
                    return content.includes(searchText);
                });

                const newGeoJsonLayer = L.geoJSON({ type: 'FeatureCollection', features: filteredData }, {
                    onEachFeature: (feature, marker) => {
                        marker.on('click', () => {
                            App.UI.displayFeatureInfo(feature, this.config.find(c => c.id === 'clusterVuviecLayer'));
                            App.leafletLayers.highlight.clearLayers().addData(feature);
                        });
                    }
                });
                clusterLayer.addLayer(newGeoJsonLayer);
            }
        },
        
        // --- MODULE QUẢN LÝ GIAO DIỆN ---
        UI: {
            init() {
                 this.fixMobileHeight();
                document.getElementById('toggle-tab-btn').innerHTML = `<i data-lucide="menu"></i>`;
                document.getElementById('back-to-map-mobile-btn').innerHTML = `<i data-lucide="x"></i>`;
                if (window.innerWidth <= 768) this.toggleTabPanel(false);
            },
            fixMobileHeight: () => {
                const setAppHeight = () => document.documentElement.style.setProperty('--app-height', `${window.innerHeight}px`);
                window.addEventListener('resize', setAppHeight);
                window.addEventListener('orientationchange', setAppHeight);
                setAppHeight();
            },
            buildLayerControl(layerConfig) {
                const container = document.getElementById('layer-control');
                let legendHtml = '<h4>Chú giải</h4>';
                container.innerHTML = '';
                layerConfig.forEach(config => {
                    const label = document.createElement('label');
                    label.className = 'layer-item';
                    label.innerHTML = `
                        <i class="icon" data-lucide="${config.icon || 'layer'}"></i>
                        <span>${config.displayName}</span>
                        <input type="checkbox" data-layer-id="${config.id}" ${config.defaultVisible ? 'checked' : ''} class="ml-auto">
                    `;
                    container.appendChild(label);
                    if (config.type === 'wms') {
                        const legendUrl = `${App.WMS_URL}?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=${config.wmsName}`;
                        legendHtml += `<div class="legend-item"><img src="${legendUrl}" alt="${config.displayName}"><span>${config.displayName}</span></div>`;
                    }
                });
                this.legendContainer.innerHTML = legendHtml;
                lucide.createIcons();
            },
            
            displayFeatureInfo(feature, config) {
                const props = feature.properties;
                let content = `<div class='popup-content'><h4>${config.displayName}</h4><table>`;
                
                let fields = config.popupFields || {};
                if (config.type === 'cluster') {
                    fields = {'ma_vu_viec': 'Mã vụ việc', 'tom_tat_noi_dung' : 'Tóm tắt nội dung', 'dia_chi_su_viec': 'Địa chỉ'};
                }

                for (const key in fields) {
                    if (props.hasOwnProperty(key)) {
                        content += `<tr><th>${fields[key]}</th><td>${props[key] || 'Không có'}</td></tr>`;
                    }
                }
                content += "</table>";

                let detailUrl = '';
                const featureId = feature.id; 
                const numericId = featureId ? featureId.split('.').pop() : null;

                if (numericId && !isNaN(numericId)) {
                    switch (config.id) {
                        case 'wmsVuviecLayer':
                        case 'clusterVuviecLayer':
                            detailUrl = `${App.DETAIL_URLS.vuViec}?id=${numericId}`;
                            break;
                        case 'wmsNocgiaLayer':
                            detailUrl = `${App.DETAIL_URLS.nocGia}?id=${numericId}`;
                            break;
                        case 'wmsDiemnhaycamLayer':
                            detailUrl = `${App.DETAIL_URLS.diemNhayCam}?id=${numericId}`;
                            break;
                        case 'wmsDiemtrongdiemLayer':
                            detailUrl = `${App.DETAIL_URLS.diemTrongDiem}?id=${numericId}`;
                            break;
                    }
                }

                if (detailUrl) {
                    content += `
                        <div style="margin-top: 15px; text-align: right;">
                            <a href="${detailUrl}" target="_blank" class="detail-button">
                                <i data-lucide="external-link" class="icon"></i> Xem chi tiết
                            </a>
                        </div>
                    `;
                }

                content += "</div>";
                document.getElementById('feature-details').innerHTML = content;
                lucide.createIcons();
            },
            
            setLoading(isLoading) { 
                document.getElementById('loading-overlay').classList.toggle('hidden', !isLoading);
             },
            showError(message) { 
                alert(message);
             },
            openTab(tabName) { 
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                document.getElementById(tabName + '-content').classList.add('active');
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelector(`.tab-button[data-tab='${tabName}']`).classList.add('active');
             },
            toggleTabPanel(forceShow) { 
                const tabs = document.getElementById('tabs');
                const isMobile = window.innerWidth <= 768;
                let show = (typeof forceShow === 'boolean') ? forceShow : (isMobile ? !tabs.classList.contains('active') : tabs.classList.contains('hidden'));
                
                tabs.classList.toggle(isMobile ? 'active' : 'hidden', isMobile ? show : !show);
                setTimeout(() => App.map.invalidateSize(), 300);
             },
            toggleLegend() { 
                const legend = this.legendContainer;
                legend.style.display = (legend.style.display === 'none' || legend.style.display === '') ? 'block' : 'none';
             }
        },
        
        // --- MODULE QUẢN LÝ SỰ KIỆN ---
        Events: {
            init() {
                App.map.on('click', this.onMapClick);
                document.getElementById('layer-control').addEventListener('change', e => {
                    if (e.target.matches('input[type="checkbox"]')) {
                        App.Layers.toggle(e.target.dataset.layerId, e.target.checked);
                    }
                });
                document.getElementById('search-input').addEventListener('input', e => {
                    App.Layers.filterClusterLayer(e.target.value.toLowerCase());
                });
                document.querySelector('.tab-buttons').addEventListener('click', e => {
                    if (e.target.matches('.tab-button')) App.UI.openTab(e.target.dataset.tab);
                });
                document.getElementById('toggle-tab-btn').addEventListener('click', () => App.UI.toggleTabPanel());
                document.getElementById('back-to-map-mobile-btn').addEventListener('click', () => App.UI.toggleTabPanel(false));
            },
            async onMapClick(e) {
                const point = App.map.latLngToContainerPoint(e.latlng, App.map.getZoom());
                const size = App.map.getSize();
                const bbox = App.map.getBounds().toBBoxString();

                const visibleWmsLayers = App.Layers.config
                    .filter(cfg => cfg.type === 'wms' && App.map.hasLayer(App.leafletLayers[cfg.id]))
                    .sort((a, b) => b.zIndex - a.zIndex);
                
                if (visibleWmsLayers.length === 0) return;

                document.getElementById('feature-details').innerHTML = '<p>Đang tải...</p>';
                App.leafletLayers.highlight.clearLayers();

                for (const config of visibleWmsLayers) {
                    const url = `${App.WMS_URL}?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&LAYERS=${config.wmsName}&QUERY_LAYERS=${config.wmsName}&BBOX=${bbox}&FEATURE_COUNT=1&HEIGHT=${size.y}&WIDTH=${size.x}&INFO_FORMAT=application/json&SRS=EPSG:4326&X=${Math.round(point.x)}&Y=${Math.round(point.y)}`;
                    try {
                        const response = await fetch(url);
                        const data = await response.json();
                        if (data.features && data.features.length > 0) {
                            App.UI.displayFeatureInfo(data.features[0], config);
                            App.leafletLayers.highlight.addData(data.features[0]);
                            return;
                        }
                    } catch (error) {
                        console.error(`Lỗi GetFeatureInfo lớp ${config.displayName}:`, error);
                    }
                }
                document.getElementById('feature-details').innerHTML = '<p>Không tìm thấy thông tin tại vị trí này.</p>';
            }
        }
    };

    App.init();
});
</script>

