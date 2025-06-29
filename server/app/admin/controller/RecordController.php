<?php
// +----------------------------------------------------------------------
// | 贵州猿创科技 [致力于通过产品和服务，帮助创业者高效化开拓市场]
// +----------------------------------------------------------------------
// | Copyright(c)2019~2024 https://xhadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author:贵州猿创科技<416716328@qq.com>|<Tel:18786709420>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\BaseController;
use app\common\providers\UploadProviders;
use app\model\UseAppRecord;
use support\Request;

class RecordController extends BaseController
{
    /**
     * 获取应用创建记录
     * @author:下次一定
     * @email:1950781041@qq.com
     * @Date:2025-05-30
     */
    public function getList(Request $request)
    {

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $data = $request->get();
        $where = [];

        if (isset($data['nickname']) && $data['nickname']) {
            $where[] = ['u.nickname', 'like', '%' . $data['nickname'] . '%'];
        }
        if (isset($data['status']) && $data['status'] !== '') {
            $where[] = ['s.status', '=', $data['status']];
        }
        if (isset($data['userId']) && $data['userId']) {
            $where[] = ['s.uid', '=', $data['userId']];
        }

        if (isset($data['title']) && $data['title']) {
            $where[] = ['s.title', 'like', '%' . $data['title'] . '%'];
        }

        $list = UseAppRecord::alias('s')
            ->join('user u', 's.uid = u.id', 'left')
            ->field(' s.*, u.nickname, u.avatar')
            ->order('s.id desc')
            ->where($where)
            ->paginate([
                'list_rows' => $limit,
                'page' => $page
            ])->each(function ($model) {
                if (!empty($model->avatar)) {
                    if (strpos($model->avatar, 'mmopen') === false) {
                        $model->avatar = UploadProviders::url($model->avatar);
                    }
                }
                return $model;
            });
        return $this->successData($list);
    }


    /**
     * 删除
     * @author:下次一定
     * @email:1950781041@qq.com
     * @Date:2025-06-09
     */
    public function delete(Request $request)
    {
        $data = $request->post();
        $scene = UseAppRecord::where('id', $data['id'])
            ->find();
        if (!$scene) {
            return $this->error('场景不存在');
        }
        if ($scene->delete()) {
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
}