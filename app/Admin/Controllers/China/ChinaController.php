<?php

namespace App\Admin\Controllers\China;

use App\Http\Controllers\Controller;
use App\Models\ChinaArea;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChinaController extends Controller
{
    public function cascading(Request $request)
    {
        return Admin::content(function (Content $content) use ($request) {

            $content->header('Cascading select');

            $form = new Form($request->all());
            $form->method('GET');
            $form->action('/demo/china/cascading-select');

            $form->select('province')->options(

                ChinaArea::province()->pluck('name', 'id')

            )->load('city', '/demo/api/china/city');

            $form->select('city')->options(function ($id) {

                return ChinaArea::options($id);

            })->load('district', '/demo/api/china/district');

            $form->select('district')->options(function ($id) {

                return ChinaArea::options($id);

            });
            $content->row(new Box('Form', $form));

            if ($request->has('province')) {
                $content->row(new Box('Query', new Table(['key', 'value'], $request->only(['province', 'city', 'district']))));
            }
        });
    }

    public function city(Request $request)
    {
        $provinceId = $request->get('q');

        return ChinaArea::city()->where('parent_id', $provinceId)->get(['id', DB::raw('name as text')]);
    }

    public function district(Request $request)
    {
        $cityId = $request->get('q');

        return ChinaArea::district()->where('parent_id', $cityId)->get(['id', DB::raw('name as text')]);
    }

    public function area()
    {
        // $area=ChinaArea::where('type',1)->get()->toArray();
        // foreach ($area as $k1=>$v1){
        //     $area[$k1]['data']=ChinaArea::where('parent_id',$v1['id'])->get()->toArray();
        //     if(!empty($area[$k1]['data'])){
        //         foreach ($area[$k1]['data'] as $k2=>$v2){
        //             $area[$k1]['data'][$k2]['data']=ChinaArea::where('parent_id',$v2['id'])->get()->toArray();
        //         }
        //     }
        // }
        // $china_area=json_encode($area);
        // file_put_contents('F:\phpStudy\WWW\signup\public\mobile\assets\js\area_array.json',$china_area);
        //获取路径
        $path =  public_path('mobile/assets/js/area_array.json');
        //获取所有的数据，并变换字段名称
        $area=ChinaArea::where('type','>',0)
                ->select('id as area_id','name as area_name','parent_id')
                ->get()->toArray();
        $tree = $this->make_tree($area);
        file_put_contents($path,json_encode($tree));

    }
        //生成无限极分类树
        public function make_tree($arr)
        {
            $refer = array();
            $tree = array();
            foreach($arr as $k => $v){
                $refer[$v['area_id']] = & $arr[$k];  //创建主键的数组引用
            }
            
            foreach($arr as $k => $v){
                $parent_id = $v['parent_id'];   //获取当前分类的父级id
                if($parent_id == 1){
                    $tree[] = & $arr[$k];	//顶级栏目
                }else{
                    if(isset($refer[$parent_id])){
                        $refer[$parent_id]['data'][] = & $arr[$k];	//如果存在父级栏目，则添加进父级栏目的子栏目数组中
                    }
                }
            }
            
            return $tree;
        }
}
