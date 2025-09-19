<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class BootstrapAsset extends AssetBundle
{

    public $css = [
        "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css",
    ];
    public $js = [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',
    ];

    public $jsOptions = ['position' => View::POS_HEAD];
}
