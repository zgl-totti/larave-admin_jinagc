<?php

namespace App\Admin\Extensions;

use App\Models\NewsContent;
use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class Share extends AbstractDisplayer
{
    public function display()
    {
        $key=$this->getKey();
        Admin::script($this->script($key));

        return <<<EOT
<div id="share_{$key}" data-sites="weibo,qq,qzone,wechat"></div>
EOT;

    }

    protected function script($key){
        $info=NewsContent::find($key);
        $title='新闻分享';
        $type=1;
        $url=url('share/'.$info->school_id.'/'.$info->user_id.'/'.$info->id.'/'.$type);
        return <<<SCRIPT
        
socialShare('#share_{$key}', {url:'{$url}',title:'{$title}',sites: ['qzone', 'qq', 'weibo','wechat']});

SCRIPT;

    }
}
