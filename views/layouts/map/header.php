<?php
    Yii::$app->cache->flush();

    //dd(Yii::$app->params);
?>
<header id="page-header" class="bg-primary">
    <div class="content-header">
        <div class="d-flex align-items-center h-100 pb-2">
            <a class="text-center h-100" href="<?= Yii::$app->homeUrl ?>">
                <img src="https://nongdanviet.net/resources/images/logo_tf.jpg" width="100%"
                     alt="logo" class="h-100">
            </a>
        </div>
        <div>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-light" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-user d-sm-none"></i>
                    <span class="d-none d-sm-inline-block"><?= (!Yii::$app->user->isGuest) ? Yii::$app->user->identity->username : 'Admin' ?></span>
                    <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">

                    <div class="p-2">
                        <a class="dropdown-item"
                           href="<?= Yii::$app->urlManager->createUrl(['user/auth-user/profile']) ?>">
                            <i class="far fa-user mr-1"></i> Thông tin tài khoản
                        </a>
                        <a class="dropdown-item"
                           href="<?= Yii::$app->urlManager->createUrl(['user/auth-user/change-pass']) ?>">
                            <i class="fa fa-recycle mr-1"></i> Đổi mật khẩu
                        </a>
                        <a class="dropdown-item"
                           href="<?= Yii::$app->urlManager->createUrl(['user/auth/logout']) ?>">
                            <i class="far fa-fw fa-arrow-alt-circle-left mr-1"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="page-header-search" class="overlay-header bg-header-dark">
        <div class="content-header">
            <form class="w-100" action="be_pages_generic_search.html" method="POST">
                <div class="input-group">
                    <button type="button" class="btn btn-primary" data-toggle="layout" data-action="header_search_off">
                        <i class="fa fa-fw fa-times-circle"></i>
                    </button>
                    <input type="text" class="form-control" placeholder="Search your websites.."
                           id="page-header-search-input" name="page-header-search-input">
                </div>
            </form>
        </div>
    </div>
    <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-2x fa-spinner fa-spin text-white"></i>
            </div>
        </div>
    </div>
</header>

