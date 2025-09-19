<?php

use yii\helpers\Url;


//$sidebar = Yii::$app->controller->module->params['adminSidebar'];



?>

<style>

/* #page-container.page-header-fixed.sidebar-o #page-header, #page-container.page-header-glass.sidebar-o #page-header {
        padding-left: 250px;
}*/


/* nav#sidebar {
    width: 20%;
} */

.content{
    padding: 0;
    width: 100% !important;
}  

.content-side{
    padding: 0;
}

</style>

<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="bg-header-dark">
        <div class="content-header bg-white-10">
            <!-- Logo -->
            <a class="font-w600 text-white tracking-wide" href="/">
                <?php if (isset(Yii::$app->params['logo'])) : ?>
                    <img src="<?= Yii::$app->homeUrl ?>/resources/images/logo_hpn.png" alt="logo" width="50%" class="logo-default">
                <?php else : ?>
                    <span class="">
                        <?= isset(Yii::$app->params['siteName']) ? Yii::$app->params['siteName'] : 'siteName' ?>
                    </span>
                <?php endif ?>


            </a>
            <!-- END Logo -->

            <!-- Options -->
            <div>


                <!-- Close Sidebar, Visible only on mobile screens -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="d-lg-none text-white ml-2" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                    <i class="fa fa-times-circle"></i>
                </a>
                <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <div id="tabs">
                
                
                <div class="tab-buttons">
                    <button class="tab-button active" onclick="openTab('layer')">Lớp dữ liệu</button>
                    <button class="tab-button" onclick="openTab('info')">Thông tin chi tiết</button>
                </div>
                <div id="layer-content" class="tab-content active">
                    <h5>Hiển thị lớp dữ liệu</h5>
                    <div id="layer-control">
                        <label><input type="checkbox" onchange="toggleLayer('wmsLoogerLayer')"> Data Logger</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsDonghoKhLayer')"> Đồng hồ khách hàng</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsDonghoTongLayer')"> Hầm hồ tổng</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsHamLayer')"> Hầm</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsOngCaiLayer')"> Ống cái</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsOngCaiDHLayer')"> Ống cái đồng hồ</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsOngNganhLayer')"> Ống ngành</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsOngTruyenDanLayer')"> Hầm</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsTrambomLayer')"> Trạm bơm</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsTramCuuHoaLayer')"> Trạm cứu hỏa</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsVanPhanPhoiLayer')"> Van phân phối</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('wmsSucoLayer')"> Sự cố điểm bể</label><br>
                        <label><input type="checkbox" onchange="toggleLayer('wmsDMA')"> DMA</label><br>
                        <label><input type="checkbox" checked onchange="toggleLayer('highlightLayer')"> Highlight</label><br>
                    </div>
                </div>
                <div id="info-content" class="tab-content">
                    <h5>Thông tin chi tiết</h5>
                    <div id="feature-info" style="height: calc(100vh - 60px); overflow-y: auto;">
                        <div id="feature-details">Chọn một đối tượng trên bản đồ để xem thông tin</div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- END Side Navigation -->
    </div>
</nav>
<!-- END Sidebar -->