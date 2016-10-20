<?php
//用户表
namespace app\models;

use app\common\helps\Tools;
use yii\db\ActiveRecord;
use Yii;

class User extends ActiveRecord
{
    public $repass;
    public $loginname;
    public $rememberMe = true;

    public static function tableName()
    {
        return "{{%user}}";
    }

    //设置关联表
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['userid' => 'userid']);
    }

    public function rules()
    {
        return [
            ['loginname', 'required', 'message' => '登录用户名不能为空', 'on' => ['login']],
            ['openid', 'required', 'message' => 'openid不能为空', 'on' => ['qqreg']],
            ['username', 'required', 'message' => '用户名不能为空', 'on' => ['reg', 'regbymail', 'qqreg','update']],
            ['username', 'unique', 'message' => '用户已经被注册', 'on' => ['reg', 'regbymail', 'qqreg','update']],
            ['openid', 'unique', 'message' => 'openid已经被注册', 'on' => ['reg', 'regbymail', 'qqreg']],
            ['useremail', 'required', 'message' => '电子邮件不能为空', 'on' => ['reg', 'regbymail','update']],
            ['useremail', 'email', 'message' => '电子邮件格式不正确', 'on' => ['reg', 'regbymail','update']],
            ['useremail', 'unique', 'message' => '电子邮件已被注册', 'on' => ['reg', 'regbymail','update']],
            ['userpass', 'required', 'message' => '用户密码不能为空', 'on' => ['reg', 'login', 'regbymail', 'qqreg','update']],
            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['reg', 'qqreg']],
            ['repass', 'compare', 'compareAttribute' => 'userpass', 'message' => '两次密码输入不一致', 'on' => ['reg', 'qqreg']],
            ['userpass', 'validatePass', 'on' => ['login']],
            [['createtime'], 'safe', 'message' => '性别不能为空', 'on' => ['update']],

        ];
    }

    //用户密码验证
    public function validatePass()
    {
        //如果前置验证没有错误则执行数据库验证
        if (!$this->hasErrors()) {
            $loginname = '';
        }
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'userpass' => '用户密码',
            'repass' => '确认密码',
            'useremail' => '电子邮箱',
            'loginname' => '用户名/电子邮箱',
            'createtime'=>'登录时间'
        ];
    }

    //后台添加用户
    public function reg($data, $scenario = 'reg')
    {

        //验证输入是否合法,设置验证场景
        $this->scenario = $scenario;
        //如果正确加载 而且 前置验证输入信息合法
        if ($this->load($data) && $this->validate()) {

            //为添加准备数据
            $this->createtime = time();
            $this->userpass = md5($this->userpass);
            //主表保存
            if (!$this->save(false)) {
                return Tools::show(0,'主表保存失败','');
//                return true;
            }
            //获取保存成功的用户id
            $userid = $this->getPrimaryKey();
            //附表保存
            $profile = new Profile;
//            var_dump($data);
//            var_dump($data['Profile']['truename']);
//            exit;
            $profile->truename = $data['Profile']['truename'];
            $profile->userid = $userid;
            $profile->createtime = time();
            if ($profile->save(false)) {

                return true;
            }
            return false;
        }

        return false;
    }

    //发送邮件注册用户
    public function regByMail($data)
    {
        //创建用户名,创建密码
        $data['User']['username'] = 'ipuxin_' . uniqid();
        $data['User']['userpass'] = uniqid();
        //设置验证场景
        $this->scenario = 'regbymail';
        //如果加载和前置验证成功
        if ($this->load($data) && $this->validate()) {
            $mailer = Yii::$app->mailer->compose('createuser', ['userpass' => $data['User']['userpass'], 'username' => $data['User']['username']]);
            $mailer->setFrom('ilingsu@163.com');
            $mailer->setTo($data['User']['useremail']);
            $mailer->setSubject('壹朴心商城-新建用户');
            if ($mailer->send() && $this->reg($data, 'regbymail')) {
                return true;
            }
        }
        return false;
    }

    //登录判断主题
    public function login($data)
    {

        /**
         * 设置验证场景,
         * 验证成功保存用户信息到session,
         */
        $this->scenario = 'login';
        if ($this->load($data) && $this->validate()) {
            $lifetime = $this->rememberMe ? 24 * 3600 : 0;
            $session = Yii::$app->session;
            session_set_cookie_params($lifetime);
            $session['loginname'] = $this->loginname;
            $session['isLogin'] = 1;

            return (bool)$session['isLogin'];
        }
        return false;
    }
}
