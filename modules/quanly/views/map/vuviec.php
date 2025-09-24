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
?>
<!-- 
    QUAN TRỌNG: Thẻ Meta Viewport để đảm bảo hiển thị đúng trên thiết bị di động.
    Nếu bạn có một file layout chính (vd: main.php), hãy đảm bảo thẻ này có trong <head>.
-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!-- Import Google Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-color: #007bff;
        --light-gray: #f1f5f9;
        --border-color: #e2e8f0;
        --background-color: #fff;
        --text-color: #334155;
        --text-light-color: #64748b;
        --shadow-color: rgba(0, 0, 0, 0.1);
        --transition-speed: 0.3s;
        --font-family: 'Inter', sans-serif;
        /* Biến chiều cao động, sẽ được set bằng JS */
        --app-height: 100vh;
    }

    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        overflow: hidden;
        font-family: var(--font-family);
        color: var(--text-color);
    }

    #mapInfo {
        display: flex;
        /* Sử dụng biến --app-height thay vì 100vh */
        height: var(--app-height);
    }

    #mapTong {
        flex-grow: 1;
        height: 100%;
        transition: width var(--transition-speed);
        position: relative;
    }

    #map {
        height: 100%;
        width: 100%;
        background-color: var(--light-gray);
    }

    /* --- Side Panel (Tabs) --- */
    #tabs {
        width: 25%;
        max-width: 350px;
        min-width: 300px;
        background: var(--background-color);
        border-right: 1px solid var(--border-color);
        transition: transform var(--transition-speed) ease-in-out, min-width var(--transition-speed) ease-in-out, width var(--transition-speed) ease-in-out;
        display: flex;
        flex-direction: column;
        transform: translateX(0);
        box-shadow: 2px 0 10px var(--shadow-color);
    }

    #tabs.hidden {
        min-width: 0;
        width: 0;
        transform: translateX(0); /* Desktop uses width/min-width to hide */
        border-right: none;
    }
    
    .tabs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid var(--border-color);
        flex-shrink: 0; /* Ngăn header bị co lại */
    }

    .tabs-header a {
        flex-grow: 1;
        margin-right: 15px;
    }

    .tabs-header img {
        width: 100%;
        height: auto;
        display: block;
    }

    .tab-buttons {
        display: flex;
        border-bottom: 1px solid var(--border-color);
        flex-shrink: 0;
    }

    .tab-button {
        flex: 1;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        background: var(--background-color);
        border: none;
        font-weight: 500;
        color: var(--text-light-color);
        border-bottom: 3px solid transparent;
        transition: color 0.2s, border-color 0.2s;
    }

    .tab-button:hover {
        color: var(--primary-color);
    }

    .tab-button.active {
        color: var(--text-color);
        border-bottom: 3px solid var(--primary-color);
    }

    .tab-content {
        display: none;
        padding: 15px;
        overflow-y: auto;
        flex-grow: 1;
        /* Cải thiện trải nghiệm cuộn trên iOS */
        -webkit-overflow-scrolling: touch;
    }

    .tab-content.active {
        display: block;
    }

    #layer-control .layer-item {
        display: flex;
        align-items: center;
        margin: 12px 0;
        cursor: pointer;
    }
    #layer-control .layer-item input {
        margin-right: 10px;
        width: 16px;
        height: 16px;
    }
    
    #feature-details {
        word-wrap: break-word;
    }

    .popup-content table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .popup-content th, .popup-content td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    .popup-content th {
        font-weight: 500;
        width: 40%;
        color: var(--text-light-color);
    }
    .popup-content h4 {
        margin-top: 0;
        color: var(--primary-color);
    }

    .legend {
        background-color: var(--background-color);
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 10px var(--shadow-color);
        display: none;
        max-height: 40vh;
        overflow-y: auto;
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .legend img {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    #back-to-map-mobile-btn {
        display: none;
    }

    @media screen and (max-width: 768px) {
        #tabs {
            width: 100%;
            max-width: none;
            position: absolute;
            top: 0;
            left: 0;
            height: var(--app-height);
            z-index: 2000;
            transform: translateX(-100%);
            border-right: none;
        }
        
        #tabs.active {
            transform: translateX(0);
        }

        #mapTong {
            width: 100% !important;
        }
        
        #back-to-map-mobile-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 5px;
        }

        .leaflet-bottom.leaflet-right, .leaflet-bottom.leaflet-left {
            margin-bottom: 40px;
        }
    }

    #toggle-tab-btn {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 1000;
        background: var(--background-color);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        width: 40px;
        height: 40px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px var(--shadow-color);
    }
    
    .leaflet-bar {
        border-radius: 8px !important;
        box-shadow: 0 2px 5px var(--shadow-color) !important;
    }
    .leaflet-bar a, .leaflet-bar button {
        border-radius: 8px !important;
    }
</style>

<div id="mapInfo">
    <!-- Loại bỏ class 'hidden' ban đầu để JS kiểm soát hoàn toàn -->
    <div id="tabs">
        <div class="tabs-header">
            <a href="<?= Yii::$app->homeUrl ?>" target="_blank">
                <img src="https://gis.nongdanviet.net/resources/images/logo_map.jpg" alt="Logo">
            </a>
            <button id="back-to-map-mobile-btn"></button>
        </div>
        
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="layer">Lớp dữ liệu</button>
            <button class="tab-button" data-tab="info">Thông tin</button>
        </div>

        <div id="layer-content" class="tab-content active">
            <h5>Hiển thị lớp dữ liệu</h5>
            <div id="layer-control"></div>
        </div>

        <div id="info-content" class="tab-content">
            <h5>Thông tin chi tiết</h5>
            <div id="feature-details">
                <p>Nhấn vào một đối tượng trên bản đồ để xem thông tin.</p>
            </div>
        </div>
    </div>

    <div id="mapTong">
        <div id="map"></div>
        <button id="toggle-tab-btn"></button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- KHẮC PHỤC LỖI 100VH TRÊN MOBILE ---
    const setAppHeight = () => {
        const doc = document.documentElement;
        doc.style.setProperty('--app-height', `${window.innerHeight}px`);
    };
    window.addEventListener('resize', setAppHeight);
    window.addEventListener('orientationchange', setAppHeight);
    setAppHeight(); // Set initial height

    // --- ICONS ---
    const ICONS = {
        menu: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
        close: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>'
    };

    // --- CONFIGURATION ---
    const WMS_URL = 'https://nongdanviet.net/geoserver/mohinhgis_pa05/wms';
    const MAP_CENTER = [20.473381288809428, 106.31907196809175];
    const MAP_ZOOM = 11;
    const layerConfig = [
        { id: 'wmsDiemnhaycamLayer', wmsName: 'mohinhgis_pa05:diem_nhay_cam', displayName: 'Điểm nhạy cảm', defaultVisible: false, zIndex: 550, popupFields: {'tenloaihinh': 'Tên loại hình', 'thongtin': 'Thông tin', 'ghichu': 'Ghi chú'} },
        { id: 'wmsDiemtrongdiemLayer', wmsName: '	mohinhgis_pa05:diem_trong_diem', displayName: 'Điểm trọng điểm', defaultVisible: false, zIndex: 550, popupFields: {'tenloaihinh': 'Tên loại hình', 'thongtin': 'Thông tin', 'ghichu': 'Ghi chú'} },
        { id: 'wmsKhuphoLayer', wmsName: '	mohinhgis_pa05:kp', displayName: 'Khu phố', defaultVisible: false, zIndex: 550, popupFields: {'TenKhuPho': 'Tên khu phố'} },
        { id: 'wmsNocgiaLayer', wmsName: '	mohinhgis_pa05:noc_gia', displayName: 'Nóc gia', defaultVisible: false, zIndex: 550, popupFields: {'so_nha': 'Số nhà'} },
    ];

    // --- MAP INITIALIZATION ---
    const map = L.map('map', { zoomControl: false }).setView(MAP_CENTER, MAP_ZOOM);
    const leafletLayers = {};
    L.control.zoom({ position: 'topright' }).addTo(map);

    const baseMaps = {
        "Bản đồ Google": L.tileLayer('https://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', { maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] }).addTo(map),
        "Ảnh vệ tinh": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', { maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] })
    };

    map.createPane('highlightPane').style.zIndex = 700;
    const highlightLayer = L.geoJSON(null, {
        pane: 'highlightPane',
        style: { color: '#ff0000', weight: 5, opacity: 1, fillOpacity: 0.3, dashArray: '5, 5' }
    }).addTo(map);

    // --- DYNAMIC UI GENERATION ---
    function initializeDynamicUI() {
        const layerControlContainer = document.getElementById('layer-control');
        let legendHtml = '<h4>Chú giải</h4>';

        layerConfig.forEach(config => {
            const label = document.createElement('label');
            label.className = 'layer-item';
            label.innerHTML = `<input type="checkbox" data-layer-id="${config.id}" ${config.defaultVisible ? 'checked' : ''}> <span>${config.displayName}</span>`;
            layerControlContainer.appendChild(label);
            
            const legendUrl = `${WMS_URL}?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=${config.wmsName}`;
            legendHtml += `<div class="legend-item"><img src="${legendUrl}" alt="${config.displayName}"><span>${config.displayName}</span></div>`;
        });
        
        legendControl.getContainer().innerHTML = legendHtml;
        document.getElementById('toggle-tab-btn').innerHTML = ICONS.menu;
        document.getElementById('back-to-map-mobile-btn').innerHTML = ICONS.close;
    }

    // --- LAYER MANAGEMENT ---
    function createWmsLayer(config) {
        map.createPane(config.id).style.zIndex = config.zIndex;
        return L.tileLayer.wms(WMS_URL, {
            layers: config.wmsName,
            format: 'image/png',
            transparent: true,
            maxZoom: 22,
            pane: config.id
        });
    }
    
    layerConfig.forEach(config => {
        leafletLayers[config.id] = createWmsLayer(config);
        if (config.defaultVisible) {
            leafletLayers[config.id].addTo(map);
        }
    });

    // --- MAP CONTROLS ---
    L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);
    L.control.scale({ imperial: false }).addTo(map);
    new L.Control.Measure({ position: 'topright', primaryLengthUnit: 'meters', primaryAreaUnit: 'sqmeters' }).addTo(map);
    new L.Control.Locate({ position: 'topright', strings: { title: "Hiện vị trí" } }).addTo(map);

    const legendControl = L.control({ position: 'bottomright' });
    legendControl.onAdd = () => L.DomUtil.create('div', 'legend');
    legendControl.addTo(map);
    
    const legendToggle = L.control({ position: 'bottomright' });
    legendToggle.onAdd = () => {
        const button = L.DomUtil.create('button', 'leaflet-bar');
        button.innerHTML = 'Chú giải';
        button.style.cursor = 'pointer';
        button.onclick = () => {
            const legendDiv = legendControl.getContainer();
            legendDiv.style.display = (legendDiv.style.display === 'none' || legendDiv.style.display === '') ? 'block' : 'none';
        };
        return button;
    };
    legendToggle.addTo(map);

    // --- EVENT HANDLERS ---
    map.on('click', async function(e) {
        const latlng = e.latlng;
        const point = map.latLngToContainerPoint(latlng, map.getZoom());
        const size = map.getSize();
        const bbox = map.getBounds().toBBoxString();

        const visibleLayers = layerConfig
            .filter(cfg => map.hasLayer(leafletLayers[cfg.id]))
            .sort((a, b) => b.zIndex - a.zIndex);

        if (visibleLayers.length === 0) return;

        document.getElementById('feature-details').innerHTML = '<p>Đang tải...</p>';
        highlightLayer.clearLayers();

        for (const config of visibleLayers) {
            const url = `${WMS_URL}?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&LAYERS=${config.wmsName}&QUERY_LAYERS=${config.wmsName}&BBOX=${bbox}&FEATURE_COUNT=1&HEIGHT=${size.y}&WIDTH=${size.x}&INFO_FORMAT=application/json&SRS=EPSG:4326&X=${Math.floor(point.x)}&Y=${Math.floor(point.y)}`;
            
            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.features && data.features.length > 0) {
                    displayFeatureInfo(data.features[0], config);
                    if (window.innerWidth <= 768) {
                        openTab('info');
                        toggleTabPanel(true);
                    }
                    return;
                }
            } catch (error) {
                console.error(`Lỗi khi lấy thông tin từ lớp ${config.displayName}:`, error);
            }
        }
        
        document.getElementById('feature-details').innerHTML = '<p>Không tìm thấy thông tin tại vị trí này.</p>';
    });
    
    function displayFeatureInfo(feature, config) {
        const properties = feature.properties;
        let content = `<div class='popup-content'><h4>${config.displayName}</h4><table>`;

        for (const key in config.popupFields) {
            if (properties.hasOwnProperty(key)) {
                content += `<tr><th>${config.popupFields[key]}</th><td>${properties[key] || 'Không có'}</td></tr>`;
            }
        }
        content += "</table></div>";
        document.getElementById('feature-details').innerHTML = content;

        highlightLayer.clearLayers();
        highlightLayer.addData(feature);
    }
    
    document.getElementById('layer-control').addEventListener('change', function(e) {
        if (e.target.matches('input[type="checkbox"]')) {
            const layerId = e.target.dataset.layerId;
            const layer = leafletLayers[layerId];
            if (e.target.checked) {
                map.addLayer(layer);
            } else {
                map.removeLayer(layer);
            }
        }
    });

    // --- UI FUNCTIONS ---
    document.querySelector('.tab-buttons').addEventListener('click', function(e) {
        if (e.target.classList.contains('tab-button')) {
            openTab(e.target.dataset.tab);
        }
    });

    function openTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.getElementById(tabName + '-content').classList.add('active');
        
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.tab-button[data-tab='${tabName}']`).classList.add('active');
    }

    /**
     * [SỬA LỖI] Hàm này được viết lại để xử lý trạng thái mobile/desktop một cách rõ ràng,
     * tránh xung đột class khi tải trang trực tiếp ở chế độ mobile.
     */
    window.toggleTabPanel = function(forceShow) {
        const tabs = document.getElementById('tabs');
        const isMobile = window.innerWidth <= 768;
        let show;

        if (typeof forceShow === 'boolean') {
            show = forceShow;
        } else {
            // Xác định trạng thái hiện tại để toggle
            show = isMobile ? !tabs.classList.contains('active') : tabs.classList.contains('hidden');
        }

        if (isMobile) {
            // Trên mobile, chỉ dùng class 'active'
            tabs.classList.remove('hidden'); // Dọn dẹp class của desktop
            tabs.classList.toggle('active', show);
        } else {
            // Trên desktop, chỉ dùng class 'hidden'
            tabs.classList.remove('active'); // Dọn dẹp class của mobile
            tabs.classList.toggle('hidden', !show);
        }
        
        // Cập nhật kích thước bản đồ sau khi animation hoàn tất
        setTimeout(() => map.invalidateSize(), 300);
    }

    document.getElementById('toggle-tab-btn').addEventListener('click', () => toggleTabPanel());
    document.getElementById('back-to-map-mobile-btn').addEventListener('click', () => toggleTabPanel(false));
    
    initializeDynamicUI();

    // Set initial state based on screen size
    if (window.innerWidth > 768) {
        toggleTabPanel(true); // Luôn hiện panel trên desktop khi tải
    } else {
        toggleTabPanel(false); // Luôn ẩn panel trên mobile khi tải
    }
});
</script>
