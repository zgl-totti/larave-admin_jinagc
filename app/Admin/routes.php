<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resources([
        'china/province' => China\ProvinceController::class,
        'china/city' => China\CityController::class,
        'china/district' => China\DistrictController::class,
        'brand'=>BrandController::class,
        'category'=>CategoryController::class,
        'goods'=>GoodsController::class,
        'goods_type'=>GoodsTypeController::class,
        'order'=>OrderController::class,
        'source'=>SourceController::class,
        'express'=>ExpressController::class,
        'pay'=>PayController::class,
        'comment'=>CommentController::class,
        'user'=>UserController::class,
        'users_level'=>UsersLevelController::class,
        'users_integral'=>UsersIntegralController::class,
        'integral_goods'=>IntegralGoodsController::class,
        'integral_order'=>IntegralOrderController::class,
        'article_cate'=>ArticleCateController::class,
        'article_content'=>ArticleContentController::class,
        'chart_week'=>ChartWeekController::class,
        'chart_month'=>ChartMonthController::class,
        'mall'=>MallController::class,
        'advertise'=>AdvertiseController::class,
        'advertise_position'=>AdvertisePositionController::class,
        'seckill'=>SeckillController::class,
        'seckill_order'=>SeckillOrderController::class,
        'bonus'=>BonusController::class,
        'bonus_users'=>BonusUsersController::class,
        'promotions'=>PromotionsController::class,
        'hot_words'=>HotWordsController::class,
        'navigation'=>NavigationController::class,
        'notification'=>NotificationController::class,
        'notification_type'=>NotificationTypeController::class,
        'inquiry_cate'=>InquiryCateController::class,
        'inquiry_departments'=>InquiryDepartmentsController::class,
        'inquiry_expert'=>InquiryExpertController::class,
        'inquiry_ask'=>InquiryAskController::class,
        'inquiry_appointment'=>InquiryAppointmentController::class,
    ]);

    $router->post('order/shipments','OrderController@shipments');
    $router->get('city','OrderController@city');
    $router->get('town','OrderController@town');
    $router->get('users/{type}','UserController@newly');
    $router->get('orders/{type}','OrderController@newly');
    $router->get('order-detail/{id}','OrderController@orderDetail')->where('id','[0-9]+');
    $router->get('sale/{type}','OrderController@sale');
    $router->get('after-sales/{id}','OrderController@afterSales')->where('id','[0-9]+');
    $router->post('after-sales-service','OrderController@afterSalesService');
    $router->get('integral-order-detail/{id}','IntegralOrderController@orderDetail')->where('id','[0-9]+');
    $router->get('type','IntegralGoodsController@type');
    $router->get('comments/{id}','CommentController@examine')->where('id','[0-9]+');
    $router->post('reply','CommentController@reply');
    $router->get('resource','NotificationController@resource');
    $router->get('ask/{id}','InquiryAskController@ask')->where('id','[0-9]+');
    $router->get('expert','InquiryAppointmentController@expert');

});
