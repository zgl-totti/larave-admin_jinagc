<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);

Encore\Admin\Grid\Column::extend('shipments',App\Admin\Extensions\Shipments::class);

Encore\Admin\Form::extend('editor', App\Admin\Extensions\Form\WangEditor::class);

Encore\Admin\Facades\Admin::js('/vendor/echarts/echarts.js');

Encore\Admin\Grid\Column::extend('color',function ($value){
    if(empty($value)){return '';}
    return "<button class='btn btn-info' style='background-color: $value;width: 35px;height: 20px;'></button>";
});
