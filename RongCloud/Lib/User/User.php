<?php

/**
 * 用户模块
 * User=> hejinyu
 * Date=> 2018/7/23
 * Time=> 11=>41
 */

namespace RongCloud\Lib\User;

use RongCloud\Lib\User\Tag\Tag;
use RongCloud\Lib\User\Block\Block;
use RongCloud\Lib\User\Blacklist\Blacklist;
use RongCloud\Lib\User\Whitelist\Whitelist;
use RongCloud\Lib\User\MuteGroups\MuteGroups;
use RongCloud\Lib\User\Onlinestatus\Onlinestatus;
use RongCloud\Lib\User\MuteChatrooms\MuteChatrooms;
use RongCloud\Lib\User\Chat\Ban;
use RongCloud\Lib\User\Remark\Remark;
use RongCloud\Lib\User\BlockPushPeriod\BlockPushPeriod;
use RongCloud\Lib\User\Profile\Profile;
use RongCloud\Lib\Utils;
use RongCloud\Lib\Request;

class User
{
    /**
     * 用户模块路径
     *
     * @var string
     */
    private $jsonPath = 'Lib/User/';

    /**
     * 请求配置文件
     *
     * @var string
     */
    private $conf = "";

    /**
     * 校验配置文件
     *
     * @var string
     */
    private $verify = "";

    /**
     * User constructor.
     */
    function __construct()
    {
        //初始化请求配置和校验文件路径
        $this->conf = Utils::getJson($this->jsonPath . 'api.json');
        $this->verify = Utils::getJson($this->jsonPath . 'verify.json');
    }

    /**
     * @param $User array 用户注册
     * @param
     * $User = [
     * 'id'=> 'ujadk90ha',//用户id
     * 'name'=> 'Maritn',//用户名称
     * 'portrait'=> 'http://7xogjk.com1.z0.glb.clouddn.com/IuDkFprSQ1493563384017406982' //用户头像
     * ];
     * @return array
     */
    public function register(array $User = [])
    {
        $conf = $this->conf['register'];
        $error = (new Utils())->check(
            [
                'api' => $conf,
                'model' => 'user',
                'data' => $User,
                'verify' => $this->verify['user']
            ]
        );
        if ($error) {
            return $error;
        }
        $User = (new Utils())->rename($User, [
            'id' => 'userId',
            'portrait' => 'portraitUri'
        ]);
        $result = (new Request())->Request($conf['url'], $User);
        $bodyParameter = (new Request())->getQueryFields($User);
        $result = (new Utils())->responseError($result, $conf['response']['fail'], $bodyParameter);
        return $result;
    }

    /**
     * Token 失效
     * 
     * @param $User array 用户信息
     * @param
     * $User = [
     * 'id'=> ['ujadk90ha1'],   //需要设置 Token 失效的用户 ID，支持设置多个最多不超过 20 个。
     * 'time'=> 1623123911000  //过期时间戳精确到毫秒，该时间戳前用户获取的 Token 全部失效，使用时间戳之前的 Token 已经在连接中的用户不会立即失效，断开后无法进行连接。
     * ];
     * @return array
     */
    public function expire(array $User = [])
    {
        $conf = $this->conf['expire'];
        $error = (new Utils())->check(
            [
                'api' => $conf,
                'model' => 'user',
                'data' => $User,
                'verify' => $this->verify['expire']
            ]
        );
        if ($error) {
            return $error;
        }
        $User = (new Utils())->rename($User, [
            'id' => 'userId'
        ]);
        $result = (new Request())->Request($conf['url'], $User);
        $bodyParameter = (new Request())->getQueryFields($User);
        $result = (new Utils())->responseError($result, $conf['response']['fail'], $bodyParameter);
        return $result;
    }

    /**
     * @param $User array 用户信息更新
     * @param
     * $User = [
     * 'id'=> 'ujadk90ha',//用户id
     * 'name'=> 'Maritn',//用户名称
     * 'portrait'=> 'http://7xogjk.com1.z0.glb.clouddn.com/IuDkFprSQ1493563384017406982' //用户头像
     * ];
     * @return array
     */
    public function update(array $User = [])
    {
        $conf = $this->conf['update'];
        $error = (new Utils())->check(
            [
                'api' => $conf,
                'model' => 'user',
                'data' => $User,
                'verify' => $this->verify['user']
            ]
        );
        if ($error) {
            return $error;
        }
        $User = (new Utils())->rename($User, [
            'id' => 'userId',
            'portrait' => 'portraitUri'
        ]);
        $result = (new Request())->Request($conf['url'], $User);
        $result = (new Utils())->responseError($result, $conf['response']['fail']);
        return $result;
    }

    /**
     * @param $User array 查询用户信息
     * @param
     * $User = [
     * 'id'=> 'ujadk90ha',//用户id
     * ];
     * @return array
     */
    public function get(array $User = [])
    {
        $conf = $this->conf['get'];
        $error = (new Utils())->check(
            [
                'api' => $conf,
                'model' => 'user',
                'data' => $User,
                'verify' => $this->verify['user']
            ]
        );
        if ($error) {
            return $error;
        }
        $User = (new Utils())->rename($User, [
            'id' => 'userId',
        ]);
        $result = (new Request())->Request($conf['url'], $User);
        $result = (new Utils())->responseError($result, $conf['response']['fail']);
        if ($result['code'] == 200) {
            $result = (new Utils())->rename($result, [
                'userName' => 'name',
                'userPortrait' => 'portrait',
            ]);
        }
        return $result;
    }

    /**
     * @param $User array 用户注销
     * @param
     * $User = [
     * 'id'=> 'ujadk90ha',//用户id
     * ];
     * @return array
     */
    public function abandon(array $User = [])
    {
        $conf = $this->conf['cancel_set'];
        $error = (new Utils())->check(
            [
                'api' => $conf,
                'model' => 'user',
                'data' => $User,
                'verify' => $this->verify['user']
            ]
        );
        if ($error) {
            return $error;
        }
        $User = (new Utils())->rename($User, [
            'id' => 'userId',
        ]);
        $result = (new Request())->Request($conf['url'], $User);
        $result = (new Utils())->responseError($result, $conf['response']['fail']);
        return $result;
    }

    /**
     * @param $User array 查询已注销用户
     * @param
     * @return array
     */
    public function abandonQuery(array $params = ["page"=>1,"size"=>50])
    {
        $conf = $this->conf['cancel_query'];

        $User = (new Utils())->rename($params, [
            'page' => 'pageNo',
            'size' => 'pageSize'
        ]);

        $result = (new Request())->Request($conf['url'], $User);
        $result = (new Utils())->responseError($result, $conf['response']['fail']);
        return $result;
    }

    /**
     * @param $User array 注销用户激活
     * @param
     * $User = [
     * 'id'=> 'ujadk90ha',//用户id
     * ];
     * @return array
     */
    public function activate(array $User = [])
    {
        $conf = $this->conf['active'];
        $error = (new Utils())->check(
            [
                'api' => $conf,
                'model' => 'user',
                'data' => $User,
                'verify' => $this->verify['user']
            ]
        );
        if ($error) {
            return $error;
        }
        $User = (new Utils())->rename($User, [
            'id' => 'userId',
        ]);
        $result = (new Request())->Request($conf['url'], $User);
        $result = (new Utils())->responseError($result, $conf['response']['fail']);
        return $result;
    }

    /**
     * 重新激活用户 ID
     * @param $User array 用户id
     * @param
     * $User = [
     * 'id'=> 'ujadk90ha',//用户id
     * ];
     * @return array
     */
    public function reactivate(array $User = [])
    {
        $conf = $this->conf['reactivate'];
        $error = (new Utils())->check(
            [
                'api' => $conf,
                'model' => 'user',
                'data' => $User,
                'verify' => $this->verify['user']
            ]
        );
        if ($error) {
            return $error;
        }
        $User = (new Utils())->rename($User, [
            'id' => 'userId',
        ]);
        $result = (new Request())->Request($conf['url'], $User);
        $result = (new Utils())->responseError($result, $conf['response']['fail']);
        return $result;
    }

    /**
     * @param $User array 查询用户所在群组
     * @param
     * $User = [
     * 'id'=> 'ujadk90ha',//用户id
     * ];
     * @return array
     */
    public function getGroups(array $User = [])
    {
        $conf = $this->conf['getGroups'];
        $error = (new Utils())->check(
            [
                'api' => $conf,
                'model' => 'user',
                'data' => $User,
                'verify' => $this->verify['user']
            ]
        );
        if ($error) {
            return $error;
        }
        $User = (new Utils())->rename($User, [
            'id' => 'userId',
        ]);
        $result = (new Request())->Request($conf['url'], $User);
        $result = (new Utils())->responseError($result, $conf['response']['fail']);
        return $result;
    }

    /**
     * 创建封禁对象
     *
     * @return Block
     */
    public function Block()
    {
        return new Block();
    }

    /**
     * 创建黑名单对象
     *
     * @return Blacklist
     */
    public function Blacklist()
    {
        return new Blacklist();
    }

    /**
     * 创建用户在线状态对象
     *
     * @return Onlinestatus
     */
    public function Onlinestatus()
    {
        return new Onlinestatus();
    }

    /**
     * 全局群组禁言
     *
     * @return MuteGroups
     */
    public function MuteGroups()
    {
        return new MuteGroups();
    }

    /**
     * 全局聊天室禁言
     *
     * @return MuteChatrooms
     */
    public function MuteChatrooms()
    {
        return new MuteChatrooms();
    }

    /**
     * 用户标签
     *
     * @return Tag
     */
    public function Tag()
    {
        return new Tag();
    }

    /**
     * 创建白名单对象
     *
     * @return Whitelist
     */
    public function Whitelist()
    {
        return new Whitelist();
    }

    /**
     * 用户单聊禁言
     *
     * @return Ban
     */
    public function Ban()
    {
        return new Ban();
    }

    /**
     * 用户推送备注
     *
     * @return Remark
     */
    public function Remark()
    {
        return new Remark();
    }

    /**
     * 用户免打扰时间段
     *
     * @return Remark
     */
    public function BlockPushPeriod()
    {
        return new BlockPushPeriod();
    }

    /**
     * 用户信息托管
     *
     * @return Profile
     */
    public function Profile()
    {
        return new Profile();
    }
    
}
