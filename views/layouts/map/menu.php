<?php
use yii\helpers\Url;


//$menu = Yii::$app->params['adminSidebar'];
$menu = \app\views\layouts\map\menulist::$adminSidebar;

$roleUser = (new \yii\db\Query())
->select(['group_id'])
->from('auth_assignment')
->where(['user_id' => Yii::$app->user->getId()])
->one();

if(Yii::$app->user->identity->is_admin){
    $menu['tintuc'] = [
        'name' => 'Tin tức',
        'icon' => 'fa fa-newspaper',
        'items' => [
            [
                'name' => 'Quản lý tin tức',
                'icon' => 'fa fa-list',
                'url' => '/quanly/tin-tuc'
            ],
            [
                'name' => 'Quản lý video',
                'icon' => 'fa fa-list',
                'url' => '/quanly/video'
            ],
            [
                'name' => 'Quản lý hình ảnh',
                'icon' => 'fa fa-list',
                'url' => '/quanly/hinh-anh'
            ],
        ],

    ];

    $menu['quantri'] = [
        'name' => 'Quản trị hệ thống',
        'icon' => 'fa fa-cogs',
        'items' => [
            [
                'name' => 'Quản lý người dùng',
                'icon' => 'fa-users',
                'url' => '/auth/user'
            ],
            [
                'name' => 'Quản lý nhóm quyền',
                'icon' => 'fa-th-list',
                'url' => '/user/auth-group'
            ],
            [
                'name' => 'Quản lý quyền truy cập',
                'icon' => 'fa-th-list',
                'url' => '/user/auth-role'
            ],
            [
                'name' => 'Quản lý hành động',
                'icon' => 'fa-th-list',
                'url' => '/user/auth-action'
            ],
            [
                'name' => 'Lịch sử hoạt động',
                'icon' => 'fa-th-list',
                'url' => '/quanly/activity'
            ],
        ],

    ];
}
?>

<div class="bg-white border-primary" id="menu-horizontal">
    <div class="content py-1">
        <div class="d-lg-none">
            <button type="button" class="btn w-100 btn-primary d-flex justify-content-between align-items-center"
                data-toggle="class-toggle" data-target="#main-navigation" data-class="d-none">
                Menu
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <div id="main-navigation" class="d-none d-lg-block">
            <ul class="nav-main nav-main-horizontal nav-main-hover nav-main-">
                <li class="nav-main-item">
                    <a class="nav-main-link fs-6" href="<?= Yii::$app->urlManager->createUrl('quanly')?>">
                        <i class="nav-main-link-icon fa fa-home"></i>
                        <span class="nav-main-link-name">Trang chủ</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu fs-6" data-toggle="submenu" aria-haspopup="true"
                        aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa-solid fa-chart-simple"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link fs-6" target="blank"
                            href="<?= Yii::$app->urlManager->createUrl('quanly/default/dashboard')?>">
                                <i class="nav-main-link-icon fa-solid fa-chart-simple"></i>
                                <span class="nav-main-link-name">Dashboard tổng quan</span>
                            </a>
                        </li>
                        <?php if((!Yii::$app->user->identity->is_admin) && ($roleUser['group_id'] == 3 || $roleUser['group_id'] == 5 )) : ?>
                        <li class="nav-main-item">
                            <a class="nav-main-link fs-6" target="blank"
                            href="<?= Yii::$app->urlManager->createUrl('quanly/default/dashboard-phuong')?>">
                                <i class="nav-main-link-icon fa-solid fa-chart-simple"></i>
                                <span class="nav-main-link-name">Dashboard theo phường</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php foreach ($menu as $item): ?>
                <?php if (!isset($item['items'])): ?>
                <li class="nav-main-item">
                    <a class="nav-main-link fs-6" href="<?= Yii::$app->urlManager->createUrl($item['url'])?>">
                        <i class="nav-main-link-icon <?= $item['icon']?>"></i>
                        <span class="nav-main-link-name"><?= $item['name']?></span>
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu fs-6" data-toggle="submenu" aria-haspopup="true"
                        aria-expanded="false" href="#">
                        <i class="nav-main-link-icon <?= isset($item['icon']) ? $item['icon'] : ''?>"></i>
                        <span class="nav-main-link-name"><?= isset($item['name']) ? $item['name'] : ''?></span>
                    </a>
                    <ul class="nav-main-submenu">
                        <?php foreach ($item['items'] as $child): ?>
                        <?php if($child['name'] == 'divider'):?>
                        <div role="separator" class="dropdown-divider"></div>
                        <?php else:?>
                        <li class="nav-main-item">
                            <a class="nav-main-link fs-6 my-1"
                                href="<?= Yii::$app->urlManager->createUrl($child['url'])?>">
                                <i class="nav-main-link-icon <?= $child['icon']?>"></i>
                                <span class="nav-main-link-name"><?= $child['name']?></span>
                            </a>
                        </li>
                        <?php endif;?>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
</div>