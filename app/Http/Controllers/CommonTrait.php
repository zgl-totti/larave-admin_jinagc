<?php
namespace app\Http\Controllers;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait CommonTrait
{
    public function wangUpload(Request $request)
    {
        if ($request->hasFile('wang-editor-image-file')) {
            $pic = $request->file('wang-editor-image-file');
            $name = md5(uniqid()).'.'.$pic->getClientOriginalExtension();
            $res = Storage::put('images/'.$name,file_get_contents($pic->getRealPath()));
            $real_path = str_replace('http://sanshiimages.oss-cn-beijing.aliyuncs.com/',config('oss.images'),Storage::url('images/'.$name));
            $result = [
                'errno' => 0,
                //'data' => [asset('storage/' . $path)],
                'data'=> [$real_path]
            ];
        } else {
            $result = ['errno' => 1];
        }
        return response()->json($result);
    }


    /**
     * 生成hash对象
     * @return Hashids
     * @author totti_zgl
     * @date 2018/5/18 9:08
     */
    private function hashObj()
    {
        $hashConfig = config('admin.hash');
        $hashObj= new Hashids($hashConfig['salt'],$hashConfig['length']);

        return $hashObj;
    }

    /**
     * 加密
     * @param $encodeMixed
     * @return string
     * @author totti_zgl
     * @date 2018/5/18 9:08
     */
    public function hashEncode($encodeMixed):string
    {
        return $this->hashObj()->encode($encodeMixed);
    }

    /**
     * 解密
     * @param $decodeStr
     * @return array
     * @author totti_zgl
     * @date 2018/5/18 9:08
     */
    public function hashDecode($decodeStr) :array
    {
        return $this->hashObj()->decode($decodeStr);
    }
}