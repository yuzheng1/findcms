<?php
namespace App\Models\Traits;

Trait AreaTrait{

    /**
     * 根据父级ID获取城市列表
     * @author zx
     * @date 2018-12-26
     * @return Model|Object
     */
    public function getAreaList(int $pid=0)
    {
        $model = self::select("id", "name AS text");
        if ($pid){
            $model = $model->where("upid",$pid);
        } else {
            $model = $model->where("level", 1);
        }
        $model = $model->get();
        return $model;
    }

    /**
     * 获取省列表
     * @author zx
     * @date 2018-12-26
     * @return array
     */
    public function getProvinceList():array
    {
        $list = $this->getAreaList();
        $res[0] = "请选择";
        foreach($list as $key => $val){
            $res[$val->id] = $val->text;
        }
        return $res;
    }

    /**
     * 获取同级城市列表
     * @author zx
     * @date 2018-12-26
     * @return array
     */
    public function getSameLevelCityList($id)
    {
        $upid = self::select("upid")
            ->where("id", $id)
            ->first();
        $list = [];
        if ($upid) $list = self::where("upid", $upid->upid)
            ->pluck("name AS text", 'id');
        return $list;
    }

    /**
     * 根据地区id获取信息
     * @author zx
     * @date 2018-12-26
     * @return Model|Object
     */
    public function getAddressData($id)
    {
        $data = self::select("id","name","level")
            ->where("id", $id)
            ->first();
        return $data;
    }
}