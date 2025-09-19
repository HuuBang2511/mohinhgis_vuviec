<?php

/**
 * Description of Module
 *
 * @author admin
 */

namespace app\modules\auth;

use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->layout = '@app/modules/layouts/main';

        $this->modules = [
            'cms' => [
                'class' => 'app\modules\auth\cms\Module'
            ],
            'api' => [
                'class' => 'app\modules\auth\api\Module'
            ],
        ];
    }
}
