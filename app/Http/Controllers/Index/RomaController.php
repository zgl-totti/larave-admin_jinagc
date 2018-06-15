<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/15
 * Time: 17:24
 */

namespace App\Http\Controllers\Index;


use App\Models\ChinaArea;
use App\Http\Controllers\Controller;

class RomaController extends Controller
{
    public function index()
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
        $path =  public_path('area_array555.json');
        //获取所有的数据，并变换字段名称
        $area=ChinaArea::where('region_type','>',0)
            ->select('region_id as area_id','region_name as area_name','parent_id')
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