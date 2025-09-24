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



