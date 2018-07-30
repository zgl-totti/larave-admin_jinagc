<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23
 * Time: 15:30
 */

namespace App\Http\Controllers\Index;


use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $except = [
        'category/index',
    ];


    public function index(Request $request)
    {
        if($request->isMethod('post')) {
            $token = $request->input('token');
            if (empty($token)) {
                $res = [
                    'status' => 0,
                ];

                return response()->json($res);
            }

            if ($token != 'totti') {
                $res = [
                    'status' => 0,
                ];

                return response()->json($res);
            }

            $list = Category::all()->toArray();
            $category = $this->make_tree($list);

            $res = [
                'status' => 1,
                'data' => $category
            ];

            return response()->json($res);
        }else{
            $res = [
                'status' => 0,
            ];

            return response()->json($res);
        }
    }

    //生成无限极分类树
    protected function make_tree($arr)
    {
        $refer = array();
        $tree = array();
        foreach ($arr as $k => $v) {
            $refer[$v['id']] = &$arr[$k];  //创建主键的数组引用
        }

        foreach ($arr as $k => $v) {
            $parent_id = $v['parent_id'];   //获取当前分类的父级id
            if ($parent_id == 0) {
                $tree[] = &$arr[$k];    //顶级栏目
            } else {
                if (isset($refer[$parent_id])) {
                    $refer[$parent_id]['data'][] = &$arr[$k];    //如果存在父级栏目，则添加进父级栏目的子栏目数组中
                }
            }
        }

        return $tree;
    }
}