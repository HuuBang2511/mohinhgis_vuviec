<?php
/**
 * @var yii\web\View $this
 * @var string $initialDataJson
 * @var array $phuongXaList
 */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Dashboard Tổng quan';
?>

<!-- Nạp các thư viện cần thiết -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>

<!-- MỚI: Thêm thư viện Select2 và jQuery (Select2 cần jQuery để hoạt động) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<style>
    /* Tùy chỉnh thanh cuộn */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #555; }
    .litepicker { z-index: 1050 !important; }

    /* MỚI: Tùy chỉnh giao diện Select2 để khớp với Tailwind CSS */
    .select2-container--default .select2-selection--single {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        height: 42px;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        padding-left: 0.75rem;
        color: #374151;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
        right: 0.5rem;
    }
    .select2-dropdown {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }
    .select2-container .select2-search--dropdown .select2-search__field {
         border: 1px solid #e5e7eb;
         border-radius: 0.375rem;
         padding: 0.5rem;
    }
</style>

<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header và khu vực bộ lọc -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Dashboard Tổng quan</h1>
                <p class="mt-1 text-sm text-gray-500">Phân tích và giám sát tình hình khiếu nại, phản ánh.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto items-center">
                <!-- CẬP NHẬT: Bỏ class cũ, Select2 sẽ tạo giao diện riêng -->
                <?= Html::dropDownList('ma_phuongxa', null, $phuongXaList, [
                    'id' => 'phuongxa-filter',
                    'class' => 'w-full sm:w-48', // Giữ lại class chiều rộng
                    'prompt' => 'Tất cả Phường/Xã',
                    // Bỏ onchange ở đây, sẽ xử lý trong JS
                ]) ?>
                <div class="flex space-x-2 items-center">
  <input 
    type="text" 
    id="date-range-picker" 
    placeholder="Lọc theo thời gian" 
    class="p-2 border rounded-lg shadow-sm text-sm w-full sm:w-64"
  >
  <button 
    type="button" 
    id="clear-date-filter" 
    class="p-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-sm hidden"
  >
    Xóa lọc
  </button>
</div>

            </div>
        </div>

        <!-- Các chỉ số KPI -->
        <div id="kpi-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6 mb-6">
            <!-- Dữ liệu KPI sẽ được render bởi JS -->
        </div>

        <!-- Hàng chứa các biểu đồ -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
            <div class="lg:col-span-3 bg-white p-4 sm:p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Xu hướng Vụ việc Mới</h3>
                <div class="h-80"><canvas id="trendChart"></canvas></div>
            </div>
            <div class="lg:col-span-2 bg-white p-4 sm:p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Cơ cấu theo Lĩnh vực</h3>
                <div class="h-80 flex items-center justify-center"><canvas id="domainChart"></canvas></div>
            </div>
        </div>
        
        <!-- Hàng chứa các bảng -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Các Vụ việc "Nóng" Gần đây</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Vụ việc</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lĩnh vực</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phường/Xã</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cảnh báo</th>
                            </tr>
                        </thead>
                        <tbody id="hot-incidents-table" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Các Vụ việc Sắp đến hạn</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Vụ việc</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phường/Xã</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạn xử lý</th>
                            </tr>
                        </thead>
                        <tbody id="deadline-table" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let applyFilters;

    document.addEventListener('DOMContentLoaded', function () {
        let trendChart, domainChart;
        const initialData = <?= $initialDataJson ?>;

        // MỚI: Khởi tạo Select2 cho bộ lọc phường/xã
        $('#phuongxa-filter').select2();

        // MỚI: Gắn sự kiện 'change' cho Select2 để gọi hàm lọc
        $('#phuongxa-filter').on('change', function() {
            applyFilters();
        });

        const picker = new Litepicker({
    element: document.getElementById('date-range-picker'),
    singleMode: false,
    format: 'YYYY-MM-DD',
    setup: (picker) => {
        picker.on('selected', (date1, date2) => {
            document.getElementById('clear-date-filter').classList.remove('hidden'); // Hiện nút khi chọn ngày
            applyFilters();
        });
    },
});


// Nút xóa lọc ngày
document.getElementById('clear-date-filter').addEventListener('click', () => {
    picker.clearSelection(); // hủy chọn ngày trong Litepicker
    document.getElementById('date-range-picker').value = ''; // clear input text
    document.getElementById('clear-date-filter').classList.add('hidden'); // Ẩn nút
    applyFilters(); // load lại dữ liệu không có lọc
});

        applyFilters = function() {
            <?php if(Yii::$app->user->identity->phuongxa != null): ?>
            const params = new URLSearchParams({
                date_range: document.getElementById('date-range-picker').value,
                ma_phuongxa: <?= Yii::$app->user->identity->phuongxa ?>
            });
            <?php else: ?>
            const params = new URLSearchParams({
                date_range: document.getElementById('date-range-picker').value,
                ma_phuongxa: document.getElementById('phuongxa-filter').value
            });
            <?php endif; ?>

            // const params = new URLSearchParams({
            //     date_range: document.getElementById('date-range-picker').value,
            //     ma_phuongxa: document.getElementById('phuongxa-filter').value
            // });
            
            document.body.style.cursor = 'wait';
            document.getElementById('kpi-container').style.opacity = '0.5';

            fetch(`<?= Url::to(['/quanly/dashboard/filter-data']) ?>?${params.toString()}`)
                .then(res => res.json())
                .then(data => {
                    renderKpi(data.kpi);
                    updateCharts(data.charts);
                    renderHotIncidents(data.tables.hotIncidents);
                    renderDeadline(data.tables.approachingDeadline);
                })
                .finally(() => {
                    document.body.style.cursor = 'default';
                    document.getElementById('kpi-container').style.opacity = '1';
                });
        }

        // --- CÁC HÀM RENDER (Không thay đổi) ---
        function renderHotIncidents(data) {
            const tableBody = document.getElementById('hot-incidents-table');
            if (!data || data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">Không có dữ liệu</td></tr>';
                return;
            }
            tableBody.innerHTML = data.map(item => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${item.ma_vu_viec}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${item.linhVuc?.ten_linh_vuc || 'N/A'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${item.phuongXa?.ten_dvhc || 'N/A'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${item.muc_do_canh_bao === 'Đỏ' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${item.muc_do_canh_bao}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        function renderKpi(kpi) {
            document.getElementById('kpi-container').innerHTML = `
                <div class="bg-white p-4 rounded-xl shadow-md flex items-center"><div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></div><div><p class="text-3xl font-bold text-gray-800">${kpi.total}</p><p class="text-sm text-gray-500">Tổng Vụ việc</p></div></div>
                <div class="bg-white p-4 rounded-xl shadow-md flex items-center"><div class="bg-green-100 text-green-600 p-3 rounded-full mr-4"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div><p class="text-3xl font-bold text-gray-800">${kpi.resolved}</p><p class="text-sm text-gray-500">Đã giải quyết</p></div></div>
                <div class="bg-white p-4 rounded-xl shadow-md flex items-center"><div class="bg-yellow-100 text-yellow-600 p-3 rounded-full mr-4"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div><p class="text-3xl font-bold text-gray-800">${kpi.overdue}</p><p class="text-sm text-gray-500">Quá hạn</p></div></div>
                <div class="bg-white p-4 rounded-xl shadow-md flex items-center"><div class="bg-red-100 text-red-600 p-3 rounded-full mr-4"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div><div><p class="text-3xl font-bold text-gray-800">${kpi.redAlerts}</p><p class="text-sm text-gray-500">Cảnh báo Đỏ</p></div></div>`;
        }
        function renderDeadline(data) {
            const tableBody = document.getElementById('deadline-table');
            if (!data || data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-gray-500">Không có dữ liệu</td></tr>';
                return;
            }
            tableBody.innerHTML = data.map(item => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${item.ma_vu_viec}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${item.phuongXa?.ten_dvhc || 'N/A'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-medium">${new Date(item.han_xu_ly).toLocaleDateString('vi-VN')}</td>
                </tr>
            `).join('');
        }
        function updateCharts(charts) {
            const trendLabels = charts.trend.map(item => new Date(item.date).toLocaleDateString('vi-VN'));
            const trendData = charts.trend.map(item => item.count);
            if (trendChart) {
                trendChart.data.labels = trendLabels;
                trendChart.data.datasets[0].data = trendData;
                trendChart.update();
            } else {
                trendChart = new Chart(document.getElementById('trendChart').getContext('2d'), { type: 'line', data: { labels: trendLabels, datasets: [{ label: 'Số vụ việc mới', data: trendData, borderColor: 'rgba(59, 130, 246, 1)', backgroundColor: 'rgba(59, 130, 246, 0.1)', fill: true, tension: 0.4 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } } });
            }
            const domainLabels = charts.byDomain.map(item => item.ten_linh_vuc);
            const domainData = charts.byDomain.map(item => item.total);
            if (domainChart) {
                domainChart.data.labels = domainLabels;
                domainChart.data.datasets[0].data = domainData;
                domainChart.update();
            } else {
                domainChart = new Chart(document.getElementById('domainChart').getContext('2d'), { type: 'doughnut', data: { labels: domainLabels, datasets: [{ data: domainData, backgroundColor: ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#8B5CF6'], }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } });
            }
        }
        
        // Render dữ liệu ban đầu
        renderKpi(initialData.kpi);
        updateCharts(initialData.charts);
        renderHotIncidents(initialData.tables.hotIncidents);
        renderDeadline(initialData.tables.approachingDeadline);
    });
</script>
