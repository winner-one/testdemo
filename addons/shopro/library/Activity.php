<?php

namespace addons\shopro\library;

/**
 *
 */
class Activity extends Model
{



    public function __construct($platform)
    {
        $this->config = Config::getEasyWechatConfig($platform);
        switch ($platform) {
            case 'wxOfficialAccount':
                $this->app    = Factory::officialAccount($this->config);
                break;
            case 'wxMiniProgram':
                $this->app    = Factory::miniProgram($this->config);
                break;
        }

    }



}
