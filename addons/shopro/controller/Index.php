<?php

namespace addons\shopro\controller;

use addons\shopro\model\Config;
use think\Db;
use think\Config as FaConfig;
use fast\Random;

class Index extends Base
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function index()
    {

    }

    //初始化商城数据
    public function init()
    {
        $params = $this->request->get();
        $platform = $this->request->header('platform');

        $config = new \addons\shopro\model\Config;

        $platformConfig = json_decode($config->where(['name' => $platform])->value('value'), true);
        $paymentConfig = $config->where('group', 'payment')->select();
        $payment = [];
        foreach ($paymentConfig as $key => $config) {
            $val = json_decode($config->value, true);
            if ($val && in_array($platform, $val['platform'])) {
                $payment[] = $config->name;
            }
        }
        $data['payment'] = $payment;        // 平台支持的支付方式

        $shopro = json_decode(Config::where(['name' => 'shopro'])->value('value'), true);
        $data['info'] = $shopro;
        $data['info']['logo'] = cdnurl($data['info']['logo'], true);
        $autologin = false;
        if (isset($platformConfig['auto_login']) && $platformConfig['auto_login'] == 1) {
            $autologin = true;
        }
        $data['wechat'] = [
            'appid' => isset($platformConfig['app_id']) ? $platformConfig['app_id'] : '',
            'autologin' => $autologin,
        ];
        $share = json_decode(Config::where(['name' => 'share'])->value('value'), true);

        $data['share'] = [
            'title' => $share['title'],
            'image' => isset($share['image']) ? cdnurl($share['image'], true) : '',
            'goods_poster_bg' => isset($share['goods_poster_bg']) ? cdnurl($share['goods_poster_bg'], true) : '',
            'user_poster_bg' => isset($share['user_poster_bg']) ? cdnurl($share['user_poster_bg'], true) : '',
            'groupon_poster_bg' => isset($share['groupon_poster_bg']) ? cdnurl($share['groupon_poster_bg'], true) : '',
        ];
        $data['addons'] = array_keys(get_addon_list());
        $this->success('初始化数据', $data);

    }

    //商城模板数据
    public function template()
    {
        $get = $this->request->get();
        $platform = $this->request->header('platform');
        if (isset($get['shop_id'])) {
            $template = \addons\shopro\model\Decorate::getCurrentPlatformDecorate('preview', $get['shop_id']);
        } else {
            $template = \addons\shopro\model\Decorate::getCurrentPlatformDecorate($platform);
        }
        $this->success('模板数据', $template);
    }

    //自定义页面
    public function custom()
    {
        $get = $this->request->get();
        $decorate = \addons\shopro\model\Decorate::get($get['id']);
        if (!$decorate) {
            $this->error('未找到自定义页面');
        }
        $decorate->template = \addons\shopro\model\Decorate::getCustomDecorate($get['id']);
        $this->success('自定义模板数据', $decorate);
    }

    //富文本详情
    public function richtext()
    {
        $id = $this->request->get('id');
        $data = \addons\shopro\model\Richtext::get(['id' => $id]);
        $this->success($data->title, $data);
    }

    //同步前端所有页面链接
    public function asyncLink()
    {
        $post = $this->request->post();
        $newLink = $post['data'];
        $existLink = Db::name('shopro_link')->select();
        $newLinkPath = array_column($newLink, 'path');
        $existLinkPath = array_flip(array_column($existLink, 'path'));
        $insertData = [];
        $count = 1;
        foreach ($newLinkPath as $key => $item) {
            if (!isset($existLinkPath[$item]) && isset($newLink[$key]['meta']['async']) && $newLink[$key]['meta']['async']) {
                $insertData[] = [
                    'name' => isset($newLink[$key]['meta']['title']) ? $newLink[$key]['meta']['title'] : '新链接' . $count,
                    'path' => $item,
                    'group' => isset($newLink[$key]['meta']['group']) ? $newLink[$key]['meta']['group'] : '其它',
                    'createtime' => time(),
                    'updatetime' => time()
                ];
                $count++;
            }
        }
        if ($insertData !== []) {
            Db::name('shopro_link')->insertAll($insertData);
        }
    }

    //前端获取轮播图平均色
    //1.0.7以后此接口作废
    public function asyncBannerBgColor()
    {
        $params = $this->request->post();
        $platform = $this->request->header('platform');
        $data['template'] = \addons\shopro\model\Decorate::asyncBannerBgColor($platform, $params);
    }

    //店铺装修 截图商城首页
    public function asyncDecorateScreenShot()
    {
        $params = $this->request->post();
        \addons\shopro\model\Decorate::asyncDecorateScreenShot($params);
    }

    /**
     * 上传文件
     * @ApiMethod (POST)
     * @param File $file 文件流
     */
    public function upload()
    {
        $file = $this->request->file('file');
        if (empty($file)) {
            $this->error(__('No file upload or server upload limit exceeded'));
        }

        //判断是否已经存在附件
        $sha1 = $file->hash();

        $upload = FaConfig::get('upload');

        preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
        $type = strtolower($matches[2]);
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
        $fileInfo = $file->getInfo();
        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';

        $mimetypeArr = explode(',', strtolower($upload['mimetype']));
        $typeArr = explode('/', $fileInfo['type']);

        //禁止上传PHP和HTML文件
        if (in_array($fileInfo['type'], ['text/x-php', 'text/html']) || in_array($suffix, ['php', 'html', 'htm'])) {
            $this->error(__('Uploaded file format is limited'));
        }
        //验证文件后缀
        if ($upload['mimetype'] !== '*' &&
            (
                !in_array($suffix, $mimetypeArr)
                || (stripos($typeArr[0] . '/', $upload['mimetype']) !== false && (!in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)))
            )
        ) {
            $this->error(__('Uploaded file format is limited'));
        }
        //验证是否为图片文件
        $imagewidth = $imageheight = 0;
        if (in_array($fileInfo['type'], ['image/gif', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/png', 'image/webp']) || in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'webp'])) {
            $imgInfo = getimagesize($fileInfo['tmp_name']);
            if (!$imgInfo || !isset($imgInfo[0]) || !isset($imgInfo[1])) {
                $this->error(__('Uploaded file is not a valid image'));
            }
            $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
            $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
        }
        $replaceArr = [
            '{year}' => date("Y"),
            '{mon}' => date("m"),
            '{day}' => date("d"),
            '{hour}' => date("H"),
            '{min}' => date("i"),
            '{sec}' => date("s"),
            '{random}' => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
            '{suffix}' => $suffix,
            '{.suffix}' => $suffix ? '.' . $suffix : '',
            '{filemd5}' => md5_file($fileInfo['tmp_name']),
        ];
        $savekey = $upload['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);
        //
        $splInfo = $file->validate(['size' => $size])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
        if ($splInfo) {
            $params = array(
                'admin_id' => 0,
                'user_id' => (int)$this->auth->id,
                'filesize' => $fileInfo['size'],
                'imagewidth' => $imagewidth,
                'imageheight' => $imageheight,
                'imagetype' => $suffix,
                'imageframes' => 0,
                'mimetype' => $fileInfo['type'],
                'url' => $uploadDir . $splInfo->getSaveName(),
                'uploadtime' => time(),
                'storage' => 'local',
                'sha1' => $sha1,
            );
            $attachment = new \app\common\model\Attachment;
            $attachment->data(array_filter($params));
            $attachment->save();
            \think\Hook::listen("upload_after", $attachment);
            $this->success(__('Upload successful'), [
                'url' => $uploadDir . $splInfo->getSaveName(),
                'full_url' => request()->domain() . $uploadDir . $splInfo->getSaveName()
            ]);
        } else {
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }



    /**
     * 上传 base64 图片
     * @ApiMethod (POST)
     * @param base64 内容
     */
    public function uploadBase64()
    {
        $content = $this->request->post('content', '');
        
        if (empty($content) || !preg_match('/^(data:\s*image\/(\w+);base64,)/', $content, $matches)) {
            $this->error(__('No file upload or server upload limit exceeded'));
        }
        
        $upload = FaConfig::get('upload');

        $suffix = $matches[2]; //图片后缀

        $mimetypeArr = explode(',', strtolower($upload['mimetype']));

        // 判断是否允许的后缀
        if ($upload['mimetype'] !== '*' && !in_array($suffix, $mimetypeArr)) {
            $this->error(__('Uploaded file format is limited'));
        }

        $replaceArr = [
            '{year}' => date("Y"),
            '{mon}' => date("m"),
            '{day}' => date("d"),
            '{hour}' => date("H"),
            '{min}' => date("i"),
            '{sec}' => date("s"),
            '{random}' => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => "店铺装修截图",
            '{suffix}' => $suffix,
            '{.suffix}' => $suffix ? '.' . $suffix : '',
            '{filemd5}' => md5($content),
        ];
        $savekey = $upload['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);
        $fullDir = ROOT_PATH . '/public' . $uploadDir;

        $uploadPath = $uploadDir . $fileName;
        $fullPath = $fullDir . $fileName;

        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        $imageContent = base64_decode(str_replace($matches[1], '', $content));
        if (file_put_contents($fullPath, $imageContent)) {
            $imgInfo = getimagesize($fullPath);

            $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : 0;
            $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : 0;

            $params = array(
                'admin_id' => 0,
                'user_id' => (int) $this->auth->id,
                'filesize' => strlen($content),
                'imagewidth' => $imagewidth,
                'imageheight' => $imageheight,
                'imagetype' => $suffix,
                'imageframes' => 0,
                'mimetype' => isset($imgInfo['mime']) ? $imgInfo['mime'] : 'image/' . $suffix,
                'url' => $uploadPath,
                'uploadtime' => time(),
                'storage' => 'local',
                'sha1' => hash_file('sha1', $fullPath),
            );
            $attachment = new \app\common\model\Attachment;
            $attachment->data(array_filter($params));
            $attachment->save();

            \think\Hook::listen("upload_after", $attachment);
            $this->success(__('Upload successful'), [
                'url' => $uploadPath,
                'full_url' => request()->domain() . $uploadPath
            ]);
        } else {
            $this->error("文件上传失败");
        }
    }

    public function debugLog()
    {
        $params = $this->request->post();
        \think\Log::write($params, 'notice');
    }

}
