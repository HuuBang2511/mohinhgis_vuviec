<?php

/**
 * @var yii\web\View $this
 * @var string $allVuViecJson
 * @var array $linhVucList
 * @var array $phuongXaList
 * @var array $kpiData
 * @var string $chartDataJson
 */

use yii\helpers\Url;

$this->title = 'Bảng điều khiển Giám sát Điểm nóng Xã hội';
?>

<!-- Nạp các thư viện CSS cần thiết -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
<script src="https://cdn.tailwindcss.com"></script>

<style>
    /* Tùy chỉnh thanh cuộn cho đẹp hơn */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #555; }
    .leaflet-popup-content-wrapper { border-radius: 8px; }
    .litepicker { z-index: 1000 !important; }
    /* Tùy chỉnh cho chú giải (Legend) */
    .legend {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255,255,255,0.8);
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        border-radius: 5px;
        line-height: 18px;
        color: #555;
    }
    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.9;
        border: 1px solid #999;
    }

    /* Styles cho sidebar mobile */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 40;
    }

    .sidebar-mobile {
        position: fixed;
        top: 0;
        left: -100%;
        width: 320px;
        height: 100%;
        background: white;
        transition: left 0.3s ease-in-out;
        z-index: 50;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-mobile.show {
        left: 0;
    }

    .sidebar-overlay.show {
        display: block;
    }

    /* Nút toggle sidebar */
    .sidebar-toggle {
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1000;
        background: white;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        display: none;
    }

    /* Responsive styles */
    @media (max-width: 1023px) {
        .sidebar-desktop {
            display: none;
        }
        
        .sidebar-toggle {
            display: block;
        }
        
        .main-content {
            width: 100% !important;
        }
    }

    @media (min-width: 1024px) {
        .sidebar-mobile {
            display: none;
        }
        
        .sidebar-overlay {
            display: none !important;
        }
    }
</style>

<div class="flex flex-col lg:flex-row h-screen bg-gray-100 font-sans">
    <!-- Nút toggle sidebar cho mobile -->
    <button id="sidebar-toggle" class="sidebar-toggle">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <!-- Overlay cho mobile -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- ===== SIDEBAR DESKTOP ===== -->
    <aside class="sidebar-desktop w-full lg:w-96 bg-white shadow-lg flex flex-col h-full">
        <!-- Header Sidebar -->
        <div class="p-4 border-b">
            <h1 class="text-xl font-bold text-gray-800">Bảng điều khiển Giám sát</h1>
            <p class="text-sm text-gray-500">TP. Hồ Chí Minh</p>
        </div>

        <!-- Vùng cuộn của Sidebar -->
        <div class="flex-1 overflow-y-auto p-4 space-y-6">
            <!-- Các chỉ số KPI -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Tổng quan</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600"><?= $kpiData['total'] ?></p>
                        <p class="text-xs text-blue-500">Tổng vụ việc</p>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-red-600"><?= $kpiData['overdue'] ?></p>
                        <p class="text-xs text-red-500">Quá hạn</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-green-600"><?= $kpiData['newToday'] ?></p>
                        <p class="text-xs text-green-500">Mới hôm nay</p>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Bộ lọc thông minh</h3>
                <div class="space-y-3">
                    <input type="text" id="date-range-picker" placeholder="Lọc theo khoảng thời gian" class="w-full p-2 border rounded-lg text-sm">
                    <select id="filter-linhvuc" class="w-full p-2 border rounded-lg text-sm">
                        <option value="">Tất cả lĩnh vực</option>
                        <?php foreach ($linhVucList as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= $item['ten_linh_vuc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="filter-phuongxa" class="w-full p-2 border rounded-lg text-sm">
                        <option value="">Tất cả phường/xã</option>
                         <?php foreach ($phuongXaList as $item): ?>
                            <option value="<?= $item['ma_dvhc'] ?>"><?= $item['ten_dvhc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="reset-filters" class="w-full bg-gray-600 text-white p-2 rounded-lg text-sm hover:bg-gray-700 transition">Xóa bộ lọc</button>
                </div>
            </div>

            <!-- Biểu đồ -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Thống kê</h3>
                <div class="bg-gray-50 p-3 rounded-lg space-y-4">
                    <div>
                        <p class="text-sm font-medium text-center mb-1">Top 5 phường/xã có nhiều vụ việc</p>
                        <canvas id="topWardsChart"></canvas>
                    </div>
                    <div>
                         <p class="text-sm font-medium text-center mb-1">Cơ cấu theo lĩnh vực</p>
                        <canvas id="domainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===== SIDEBAR MOBILE ===== -->
    <aside id="sidebar-mobile" class="sidebar-mobile">
        <!-- Header Sidebar với nút đóng -->
        <div class="p-4 border-b flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Bảng điều khiển Giám sát</h1>
                <p class="text-sm text-gray-500">TP. Hồ Chí Minh</p>
            </div>
            <button id="sidebar-close" class="text-gray-500 hover:text-gray-700">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Vùng cuộn của Sidebar Mobile -->
        <div class="flex-1 overflow-y-auto p-4 space-y-6">
            <!-- Các chỉ số KPI -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Tổng quan</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600"><?= $kpiData['total'] ?></p>
                        <p class="text-xs text-blue-500">Tổng vụ việc</p>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-red-600"><?= $kpiData['overdue'] ?></p>
                        <p class="text-xs text-red-500">Quá hạn</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-green-600"><?= $kpiData['newToday'] ?></p>
                        <p class="text-xs text-green-500">Mới hôm nay</p>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc Mobile -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Bộ lọc thông minh</h3>
                <div class="space-y-3">
                    <input type="text" id="date-range-picker-mobile" placeholder="Lọc theo khoảng thời gian" class="w-full p-2 border rounded-lg text-sm">
                    <select id="filter-linhvuc-mobile" class="w-full p-2 border rounded-lg text-sm">
                        <option value="">Tất cả lĩnh vực</option>
                        <?php foreach ($linhVucList as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= $item['ten_linh_vuc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="filter-phuongxa-mobile" class="w-full p-2 border rounded-lg text-sm">
                        <option value="">Tất cả phường/xã</option>
                         <?php foreach ($phuongXaList as $item): ?>
                            <option value="<?= $item['ma_dvhc'] ?>"><?= $item['ten_dvhc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="reset-filters-mobile" class="w-full bg-gray-600 text-white p-2 rounded-lg text-sm hover:bg-gray-700 transition">Xóa bộ lọc</button>
                </div>
            </div>

            <!-- Biểu đồ Mobile -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Thống kê</h3>
                <div class="bg-gray-50 p-3 rounded-lg space-y-4">
                    <div>
                        <p class="text-sm font-medium text-center mb-1">Top 5 phường/xã có nhiều vụ việc</p>
                        <canvas id="topWardsChartMobile"></canvas>
                    </div>
                    <div>
                         <p class="text-sm font-medium text-center mb-1">Cơ cấu theo lĩnh vực</p>
                        <canvas id="domainChartMobile"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===== MAP ===== -->
    <main class="main-content flex-1 h-screen lg:h-full">
        <div id="map" class="w-full h-full"></div>
    </main>
</div>

<!-- Nạp các thư viện JS cần thiết -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
    // Khởi tạo các biến toàn cục
    let map;
    let heatmapLayer, markerLayer, phuongXaLayer, kpLayer;
    let kpRiskData = {};
    const allVuViecData = <?= $allVuViecJson ?>;
    const chartData = <?= $chartDataJson ?>;
    const hcmcCoords = [10.7769, 106.7009];
    let pickerDesktop, pickerMobile; // Biến cho date picker

    // Hàm khởi tạo sidebar mobile
    function initMobileSidebar() {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarMobile = document.getElementById('sidebar-mobile');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarClose = document.getElementById('sidebar-close');

        // Mở sidebar
        sidebarToggle.addEventListener('click', () => {
            sidebarMobile.classList.add('show');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        });

        // Đóng sidebar
        function closeSidebar() {
            sidebarMobile.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Đồng bộ các bộ lọc giữa desktop và mobile
        syncFilters();
    }

    // Hàm đồng bộ bộ lọc giữa desktop và mobile
    function syncFilters() {
        const desktopFilters = {
            linhvuc: document.getElementById('filter-linhvuc'),
            phuongxa: document.getElementById('filter-phuongxa'),
            resetBtn: document.getElementById('reset-filters')
        };

        const mobileFilters = {
            linhvuc: document.getElementById('filter-linhvuc-mobile'),
            phuongxa: document.getElementById('filter-phuongxa-mobile'),
            resetBtn: document.getElementById('reset-filters-mobile')
        };

        // Đồng bộ từ desktop sang mobile
        desktopFilters.linhvuc.addEventListener('change', (e) => {
            mobileFilters.linhvuc.value = e.target.value;
            applyFilters();
        });

        desktopFilters.phuongxa.addEventListener('change', (e) => {
            mobileFilters.phuongxa.value = e.target.value;
            applyFilters();
        });

        // Đồng bộ từ mobile sang desktop
        mobileFilters.linhvuc.addEventListener('change', (e) => {
            desktopFilters.linhvuc.value = e.target.value;
            applyFilters();
        });

        mobileFilters.phuongxa.addEventListener('change', (e) => {
            desktopFilters.phuongxa.value = e.target.value;
            applyFilters();
        });

        // Reset filters
        desktopFilters.resetBtn.addEventListener('click', () => {
            desktopFilters.linhvuc.value = '';
            desktopFilters.phuongxa.value = '';
            mobileFilters.linhvuc.value = '';
            mobileFilters.phuongxa.value = '';
            if (pickerDesktop) pickerDesktop.clearSelection();
            if (pickerMobile) pickerMobile.clearSelection();
            applyFilters();
        });

        mobileFilters.resetBtn.addEventListener('click', () => {
            desktopFilters.linhvuc.value = '';
            desktopFilters.phuongxa.value = '';
            mobileFilters.linhvuc.value = '';
            mobileFilters.phuongxa.value = '';
            if (pickerDesktop) pickerDesktop.clearSelection();
            if (pickerMobile) pickerMobile.clearSelection();
            applyFilters();
        });
    }

    // Hàm khởi tạo bản đồ
    function initMap() {
        map = L.map('map').setView(hcmcCoords, 11);

        L.tileLayer('https://thuduc-maps.hcmgis.vn/thuducserver/gwc/service/wmts?layer=thuduc:thuduc_maps&style=&tilematrixset=EPSG:900913&Service=WMTS&Request=GetTile&Version=1.0.0&Format=image/png&TileMatrix=EPSG:900913:{z}&TileCol={x}&TileRow={y}', {
                    maxZoom: 25,
                    minZoom: 10,
                    attribution: 'HCMGIS'
                }).addTo(map);

        map.createPane('boundaryPane');
        map.getPane('boundaryPane').style.zIndex = 450;
        map.createPane('heatmapPane');
        map.getPane('heatmapPane').style.zIndex = 350;
        map.getPane('heatmapPane').style.pointerEvents = 'none';

        kpLayer = L.geoJSON(null, {
            pane: 'boundaryPane',
            style: styleKp
        });

        heatmapLayer = L.heatLayer([], { radius: 25, blur: 15, maxZoom: 12, gradient: {0.4: 'blue', 0.65: 'lime', 1: 'red'}, pane: 'heatmapPane' });
        markerLayer = L.layerGroup();
        phuongXaLayer = L.geoJSON(null, { style: { weight: 2, color: '#3388ff', opacity: 0.8, fillOpacity: 0.1 }, pane: 'boundaryPane' });
        
        const overlayLayers = {
            "Điểm vụ việc": markerLayer,
            "Bản đồ nhiệt": heatmapLayer,
            "Phân tích điểm nóng theo Khu phố": kpLayer,
            "Ranh giới Phường/Xã": phuongXaLayer,
        };
        L.control.layers(null, overlayLayers, { collapsed: false, position: 'topright' }).addTo(map);

        heatmapLayer.addTo(map);
        markerLayer.addTo(map);
        phuongXaLayer.addTo(map);

        updateMapData(allVuViecData);
        drawBoundaries();
        addLegend();
        
        handleZoomLayers();
        map.on('zoomend', handleZoomLayers);
    }

    // Hàm xử lý hiển thị lớp theo mức zoom
    function handleZoomLayers() {
        if (map.getZoom() >= 14) {
            if (!map.hasLayer(kpLayer)) map.addLayer(kpLayer);
        } else {
            if (map.hasLayer(kpLayer)) map.removeLayer(kpLayer);
        }
    }

    // Hàm cập nhật dữ liệu trên bản đồ
    function updateMapData(data) {
        heatmapLayer.setLatLngs([]);
        markerLayer.clearLayers();
        if (data.length === 0) return;
        const heatPoints = [];
        data.forEach(vv => {
            if (!vv.geojson) return;
            const coords = JSON.parse(vv.geojson).coordinates;
            const latLng = [coords[1], coords[0]];
            const riskScore = vv.diem_rui_ro || 1;
            heatPoints.push([...latLng, riskScore / 100]);
            const marker = createRiskMarker(latLng, vv.muc_do_canh_bao);
            marker.bindPopup(createPopupContent(vv));
            markerLayer.addLayer(marker);
        });
        heatmapLayer.setLatLngs(heatPoints);
    }

    // Hàm tạo marker với màu sắc theo mức độ cảnh báo
    function createRiskMarker(latLng, level) {
        let color;
        switch (level) {
            case 'Đỏ': color = '#EF4444'; break;
            case 'Vàng': color = '#F59E0B'; break;
            default: color = '#3B82F6'; break;
        }
        return L.circleMarker(latLng, {
            radius: 8, fillColor: color, color: '#FFFFFF',
            weight: 2, opacity: 1, fillOpacity: 0.8,
            pane: 'markerPane' 
        });
    }

    // Hàm tạo nội dung cho popup
    function createPopupContent(vv) {
        const noiDung = vv.tom_tat_noi_dung || 'Không có mô tả.';
        
        let historyHtml = '<p class="text-gray-500 text-sm">Chưa có lịch sử xử lý.</p>';
        if (vv.lichSuXuLies && vv.lichSuXuLies.length > 0) {
            historyHtml = vv.lichSuXuLies.map(item => `
                <li class="mb-3 ms-4">
                    <div class="absolute w-3 h-3 bg-gray-300 rounded-full mt-1.5 -start-1.5 border border-white"></div>
                    <time class="mb-1 text-xs font-normal leading-none text-gray-500">${new Date(item.ngay_thuc_hien).toLocaleString('vi-VN')}</time>
                    <p class="font-semibold text-gray-800">${item.trangThai?.ten_trang_thai || 'N/A'}</p>
                    <p class="text-sm text-gray-600">${item.ghi_chu_xu_ly || ''}</p>
                    <p class="text-xs text-gray-500 italic">Thực hiện: ${item.canBoThucHien?.ho_ten || 'N/A'}</p>
                </li>
            `).join('');
            historyHtml = `<ol class="relative border-s border-gray-200">${historyHtml}</ol>`;
        }

        const container = L.DomUtil.create('div', 'w-full max-w-sm md:max-w-md');
        container.innerHTML = `
            <div class="p-2">
                <p class="font-bold text-lg mb-2 pb-2 border-b">${vv.ma_vu_viec}</p>
                
                <div class="mb-3">
                    <h4 class="font-semibold text-gray-700 text-sm mb-1">Nội dung phản ánh</h4>
                    <p class="text-sm text-gray-600 bg-gray-50 p-2 rounded-md">${noiDung}</p>
                </div>

                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm mb-3">
                    <div>
                        <p class="font-semibold text-gray-700">Người phản ánh:</p>
                        <p class="text-gray-600">${vv.nguoiDan?.ho_ten || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Số điện thoại:</p>
                        <p class="text-gray-600">${vv.nguoiDan?.so_dien_thoai || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Cán bộ tiếp nhận:</p>
                        <p class="text-gray-600">${vv.canBoTiepNhan?.ho_ten || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Trạng thái hiện tại:</p>
                        <p class="text-gray-600 font-bold">${vv.trangThaiHienTai?.ten_trang_thai || 'N/A'}</p>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-700 text-sm mb-2">Lịch sử xử lý</h4>
                    <div class="max-h-40 overflow-y-auto pr-2">
                        ${historyHtml}
                    </div>
                </div>
            </div>
            <div class="p-2 border-t mt-2">
                 <button class="w-full bg-blue-600 text-white p-2 rounded-lg text-sm hover:bg-blue-700 transition" onclick="alert('Chức năng Xem chi tiết cho vụ việc ${vv.id}')">
                    Mở Hồ sơ Vụ việc
                </button>
            </div>
        `;
        return container;
    }
    
    // Hàm style cho lớp Khu phố dựa trên điểm rủi ro
    function styleKp(feature) {
        const riskInfo = kpRiskData[feature.properties.id] || { color: '#bdc3c7', score: 0 };
        return {
            fillColor: riskInfo.color,
            weight: 1,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.7
        };
    }

    // Hàm vẽ tất cả các lớp ranh giới
    function drawBoundaries() {
        Promise.all([
            fetch('<?= Url::to(['/quanly/map/get-kp-geojson']) ?>').then(res => res.json()),
            fetch('<?= Url::to(['/quanly/map/get-kp-risk-data']) ?>').then(res => res.json())
        ]).then(([kpGeoJson, initialKpRiskData]) => {
            kpRiskData = initialKpRiskData;
            kpLayer.addData(kpGeoJson);
            kpLayer.eachLayer(layer => {
                layer.on({
                    mouseover: e => e.target.setStyle({ weight: 3, color: '#34495e' }),
                    mouseout: e => kpLayer.resetStyle(e.target),
                    click: e => {
                        const props = e.target.feature.properties;
                        const riskInfo = kpRiskData[props.id] || { score: 'N/A', level: 'Chưa có dữ liệu' };
                        const sdtBiThuHtml = props.phuongxa_sdt_bithu ? `<a href="tel:${props.phuongxa_sdt_bithu}" class="text-blue-600 hover:underline">${props.phuongxa_sdt_bithu}</a>` : 'N/A';
                        const sdtChuTichHtml = props.phuongxa_sdt_ctubnd ? `<a href="tel:${props.phuongxa_sdt_ctubnd}" class="text-blue-600 hover:underline">${props.phuongxa_sdt_ctubnd}</a>` : 'N/A';
                        const content = `
                            <div class="w-80">
                                <div class="font-bold text-lg mb-2 pb-2 border-b">${props.ten_kp || 'N/A'}</div>
                                <div class="text-sm space-y-1">
                                    <p><strong>Mức độ rủi ro:</strong> ${riskInfo.level}</p>
                                    <p><strong>Tổng điểm:</strong> ${riskInfo.score}</p>
                                </div>
                                <div class="mt-3 pt-2 border-t">
                                    <p class="font-semibold text-base mb-1">Thông tin Phường/Xã</p>
                                    <div class="text-sm space-y-1">
                                        <p><strong>Tên ĐVHC:</strong> ${props.phuongxa_ten_dvhc || 'N/A'}</p>
                                        <p><strong>Tỉnh/Thành cũ:</strong> ${props.phuongxa_tinhthanh_cu || 'N/A'}</p>
                                        <p><strong>Bí thư:</strong> ${props.phuongxa_bi_thu || 'N/A'}</p>
                                        <p><strong>SĐT Bí thư:</strong> ${sdtBiThuHtml}</p>
                                        <p><strong>Chủ tịch:</strong> ${props.phuongxa_ho_ten_ct || 'N/A'}</p>
                                        <p><strong>SĐT Chủ tịch:</strong> ${sdtChuTichHtml}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        L.popup({ maxWidth: 400 }).setLatLng(e.latlng).setContent(content).openOn(map);
                    }
                });
            });
            updateKpColors(kpRiskData);
        });

        fetch('<?= Url::to(['/quanly/map/get-phuongxa-geojson']) ?>')
            .then(response => response.json())
            .then(data => {
                phuongXaLayer.addData(data);
                phuongXaLayer.eachLayer(layer => {
                    layer.on({
                        mouseover: e => e.target.setStyle({ weight: 3, color: '#e67e22' }),
                        mouseout: e => phuongXaLayer.resetStyle(e.target),
                        click: e => {
                            const props = e.target.feature.properties;
                            const content = `
                                <div class="w-72">
                                    <div class="font-bold text-lg mb-2 pb-2 border-b">${props.ten_dvhc || 'N/A'}</div>
                                    <div class="text-sm space-y-1">
                                        <p><strong>Tỉnh/Thành:</strong> ${props.tinh_thanh || 'N/A'}</p>
                                        <p><strong>Quận/Huyện cũ:</strong> ${props.quanhuyen_cu || 'N/A'}</p>
                                        <p><strong>Tỉnh/Thành cũ:</strong> ${props.tinhthanh_cu || 'N/A'}</p>
                                        <p><strong>Sắp xếp từ:</strong> ${props.sapxeptu || 'N/A'}</p>
                                        <p><strong>Dân số:</strong> ${props.dan_so ? Number(props.dan_so).toLocaleString('vi-VN') : 'N/A'}</p>
                                        <p><strong>Diện tích (km²):</strong> ${props.dien_tich ? Number(props.dien_tich).toFixed(2) : 'N/A'}</p>
                                        <p><strong>Tổng số ĐVHC:</strong> ${props.tsdvhc_cap || 'N/A'}</p>
                                        <p><strong>Số xã:</strong> ${props.so_xa || 'N/A'}</p>
                                        <p><strong>Số phường:</strong> ${props.so_phuong || 'N/A'}</p>
                                    </div>
                                </div>
                            `;
                            L.popup({ maxWidth: 400 }).setLatLng(e.latlng).setContent(content).openOn(map);
                        }
                    });
                });
            });
    }

    // Hàm cập nhật màu sắc cho lớp Khu phố
    function updateKpColors(newRiskData) {
        kpRiskData = newRiskData;
        if (kpLayer && map.hasLayer(kpLayer)) {
            kpLayer.eachLayer(layer => {
                kpLayer.resetStyle(layer);
            });
        }
    }

    // Hàm thêm chú giải cho bản đồ
    function addLegend() {
        const legend = L.control({position: 'bottomright'});
        legend.onAdd = function (map) {
            const div = L.DomUtil.create('div', 'info legend');
            const grades = [
                { level: 'Điểm nóng', color: '#e74c3c' },
                { level: 'Nguy cơ cao', color: '#e67e22' },
                { level: 'Cần chú ý', color: '#f1c40f' },
                { level: 'Bình thường', color: '#2ecc71' },
                { level: 'Chưa có dữ liệu', color: '#bdc3c7' }
            ];
            let labels = ['<strong>Điểm nóng theo Khu phố</strong>'];
            grades.forEach(grade => {
                labels.push(`<i style="background:${grade.color}"></i> ${grade.level}`);
            });
            div.innerHTML = labels.join('<br>');
            return div;
        };
        legend.addTo(map);
    }

    // Hàm khởi tạo các bộ lọc
    function initFilters() {
        // Desktop date picker
        pickerDesktop = new Litepicker({
            element: document.getElementById('date-range-picker'),
            singleMode: false,
            format: 'YYYY-MM-DD',
            setup: (picker) => { 
                picker.on('selected', () => {
                    // Đồng bộ với mobile picker
                    document.getElementById('date-range-picker-mobile').value = document.getElementById('date-range-picker').value;
                    applyFilters();
                });
            },
        });

        // Mobile date picker
        pickerMobile = new Litepicker({
            element: document.getElementById('date-range-picker-mobile'),
            singleMode: false,
            format: 'YYYY-MM-DD',
            setup: (picker) => { 
                picker.on('selected', () => {
                    // Đồng bộ với desktop picker
                    document.getElementById('date-range-picker').value = document.getElementById('date-range-picker-mobile').value;
                    applyFilters();
                });
            },
        });
    }

    // Hàm áp dụng bộ lọc cho tất cả các lớp
    function applyFilters() {
        const linhVucValue = document.getElementById('filter-linhvuc').value || document.getElementById('filter-linhvuc-mobile').value;
        const phuongXaValue = document.getElementById('filter-phuongxa').value || document.getElementById('filter-phuongxa-mobile').value;
        const dateRangeValue = document.getElementById('date-range-picker').value || document.getElementById('date-range-picker-mobile').value;

        const params = new URLSearchParams({
            linh_vuc_id: linhVucValue,
            ma_dvhc: phuongXaValue,
            date_range: dateRangeValue
        });

        document.getElementById('map').style.opacity = '0.5';

        Promise.all([
            fetch(`<?= Url::to(['/quanly/map/filter-data']) ?>?${params.toString()}`).then(res => res.json()),
            fetch(`<?= Url::to(['/quanly/map/get-kp-risk-data']) ?>?${params.toString()}`).then(res => res.json())
        ]).then(([vuViecData, newKpRiskData]) => {
            updateMapData(vuViecData);
            updateKpColors(newKpRiskData);
        }).finally(() => {
            document.getElementById('map').style.opacity = '1';
        });
    }

    // Hàm khởi tạo biểu đồ
    function initCharts() {
        // Desktop charts
        const topWardsCtx = document.getElementById('topWardsChart').getContext('2d');
        new Chart(topWardsCtx, {
            type: 'bar',
            data: {
                labels: chartData.topWards.map(item => item.ten_dvhc),
                datasets: [{
                    label: 'Số vụ việc',
                    data: chartData.topWards.map(item => item.total),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
        });

        const domainCtx = document.getElementById('domainChart').getContext('2d');
        new Chart(domainCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.byDomain.map(item => item.ten_linh_vuc),
                datasets: [{
                    data: chartData.byDomain.map(item => item.total),
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#8B5CF6'],
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } } }
        });

        // Mobile charts
        const topWardsCtxMobile = document.getElementById('topWardsChartMobile').getContext('2d');
        new Chart(topWardsCtxMobile, {
            type: 'bar',
            data: {
                labels: chartData.topWards.map(item => item.ten_dvhc),
                datasets: [{
                    label: 'Số vụ việc',
                    data: chartData.topWards.map(item => item.total),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
        });

        const domainCtxMobile = document.getElementById('domainChartMobile').getContext('2d');
        new Chart(domainCtxMobile, {
            type: 'doughnut',
            data: {
                labels: chartData.byDomain.map(item => item.ten_linh_vuc),
                datasets: [{
                    data: chartData.byDomain.map(item => item.total),
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#8B5CF6'],
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } } }
        });
    }

    // Chạy các hàm khởi tạo khi DOM đã sẵn sàng
    document.addEventListener('DOMContentLoaded', () => {
        initMap();
        initFilters();
        initCharts();
        initMobileSidebar();
    });
</script>
