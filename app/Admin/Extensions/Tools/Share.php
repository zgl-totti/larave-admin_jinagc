<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/12
 * Time: 11:20
 */

namespace App\Admin\Extensions\Tools;


use App\Models\NewsContent;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Encore\Admin\Form\Field;

class Share
{
    protected $info;
    //选择器
    protected $selector;
    //分享的url
    protected $url;
    //分享的title
    protected $title;
    //分享的类型
    protected $shareType;
    //分享的目标Id
    protected $shareId;
    public function __construct(array $info)
    {
        $this->info = $info;
        $this->selector = 'share_'.$info['shareType'].'_'.$info['shareId'];
        $this->url    = $info['url'];
        $this->title  = $info['title'];
        
    }

    protected function script()
    {
        return <<<SCRIPT
socialShare('#{$this->selector}', {url:'{$this->url}',title:'{$this->title}',sites: ['qzone', 'qq', 'weibo','wechat']});

SCRIPT;
    }

    public function render()
    {
        Admin::script($this->script());

        return <<<EOT

<div id="{$this->selector}" data-sites="weibo,qq,qzone,wechat"></div>
EOT;
    }

    public function __toString()
    {
        return $this->render();
    }
}