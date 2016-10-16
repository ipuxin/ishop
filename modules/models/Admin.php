<?php
namespace app\modules\models;

use yii\db\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

class Admin extends ActiveRecord
{
    //记住我, 专用模拟变量
    public $rememberMe = true;
    //邮箱找回秘密,重置密码
    public $repass;

    public static function tableName()
    {
        return '{{%admin}}';
    }

    public function rules()
    {

        return [
            ['adminuser', 'required', 'message' => '管理员账号不能为空',
                'on' => ['login', 'seekpass', 'changepass', 'adminadd', 'changeemail']],
            ['adminemail', 'required', 'message' => '邮箱不能为空',
                'on' => ['seekpass', 'adminadd', 'changeemail']],
            ['adminuser', 'unique', 'message' => '管理员账号不能重复',
                'on' => ['adminadd']],
            ['adminpass', 'required', 'message' => '管理员密码不能为空',
                'on' => ['login', 'changepass', 'adminadd', 'changeemail']],
            ['rememberMe', 'boolean', 'on' => ['login']],
            //Yii没有提供验证密码的方法,validatePass为自定义方法
            ['adminpass', 'validatePass', 'on' => ['login', 'changeemail']],
            ['adminemail', 'email', 'message' => '邮箱格式不正确',
                'on' => ['seekpass', 'adminadd', 'changeemail']],
            ['adminemail', 'unique', 'message' => '电子邮箱已被注册',
                'on' => ['adminadd', 'changeemail']],
            ['adminemail', 'validateEmail', 'on' => ['seekpass']],
            ['repass', 'required', 'message' => '确认密码不能为空',
                'on' => ['changepass', 'adminadd']],
            ['repass', 'compare', 'compareAttribute' => 'adminpass', 'message' => '两次密码不一致',
                'on' => ['changepass', 'adminadd']],
        ];
    }

    //更改标签为中文
    public function attributeLabels()
    {
        return [
            'adminuser' => '管理员账号',
            'adminemail' => '管理员邮箱',
            'adminpass' => '管理员密码',
            'repass' => '确认密码'
        ];
    }

    //执行密码验证
    public function validatePass()
    {
        //为了减小数据库压力,先保证前面没有错误,再查询数据库
        if (!$this->hasErrors()) {
            $data = self::find()
                ->where('adminuser =:user and adminpass =:pass',
                    [':user' => $this->adminuser, ':pass' => md5($this->adminpass)])
                ->one();
            if (is_null($data)) {
                $this->addError('adminpass', '用户名或者密码错误');
            }
        }
    }

    public function login($data)
    {
        //设定场景为登录
        $this->scenario = 'login';

        //如果载入成功而且验证无误,开始登录
        if ($this->load($data) && $this->validate()) {
            //更新登录时间和登录ip
            $this->updateAll(['logintime' => time(), 'loginip' => ip2long(Yii::$app->request->userIP)],
                'adminuser=:user', [':user' => $this->adminuser]);

            //获取数据库中该用户的信息
            $admin = self::find()
                ->where('adminuser=:user', [':user' => $this->adminuser])->one();

            //转化为数组
            $arrAdmin = ArrayHelper::toArray($admin, [
                'app\models\Post' => [
                    'adminid',
                    'adminuser',
                    'adminemail',
                    'logintime',
                    'loginip',
                    'createtime'=>function($admin) {
                        return date('Y-m-d H:i:s', $admin->createtime);
                    }
                ]
            ]);

            //拼合上登录记录,注意字段名的覆盖哦!
            $admin = array_merge($arrAdmin, ['isLogin' => 1, 'adminuser' => $this->adminuser]);

            //把拼合好的用户所有信息存入session
            $lifetime = $this->rememberMe ? 24 * 3600 : 0;
            $session = Yii::$app->session;
            session_set_cookie_params($lifetime);
            $session['admin'] = $admin;

//            var_dump($session['admin']['isLogin']);
//            var_dump($session['admin']['adminid']);
//            var_dump($session['admin']['adminuser']);
//            var_dump(date('Y-m-d H:i:s', $session['admin']['createtime']));
//            exit;//string(13) "ipuxin@qq.com"
            return (bool)$session['admin']['isLogin'];
        }
        return false;
    }

    //找回密码验证,验证管理员所填邮箱是否是该管理员注册的
    public function validateEmail()
    {
        if (!$this->hasErrors()) {
            $data = self::find()
                ->where('adminuser=:user and adminemail=:email',
                    [':user' => $this->adminuser, ':email' => $this->adminemail])
                ->one();
            if (is_null($data)) {
                $this->addError('adminemail', '管理员账号邮箱不匹配');
            }
        }
    }

    //发送邮件找回密码
    public function seekPass($data)
    {
        //设定场景为找回密码
        $this->scenario = 'seekpass';

        if ($this->load($data) && $this->validate()) {
//          Yii::$app->mailer->compose('contact/html', ['contactForm' => $form]);
            $time = time();
            $token = $this->createToken($data['Admin']['adminuser'], $time);
            $mailer = Yii::$app->mailer->compose('seekpass', ['adminuser' => $data['Admin']['adminuser'], 'time' => $time, 'token' => $token]);
            $mailer->setFrom('ilingsu@163.com');
            $mailer->setTo($data['Admin']['adminemail']);
            $mailer->setSubject('壹朴心商城-找回密码');
            if ($mailer->send()) {
                return true;
            }
        }
        return false;
    }

    public function createToken($adminuser, $time)
    {
        return md5(md5($adminuser) . base64_decode(Yii::$app->request->userIP) . md5($time));
    }

    public function changePass($data)
    {
        //设置校验场景为修改密码
        $this->scenario = 'changepass';

        if ($this->load($data) && $this->validate()) {
            return (bool)$this->updateAll(['adminpass' => md5($this->adminpass)],
                'adminuser=:user', [':user' => $this->adminuser]);
        }
        return false;
    }

    //添加管理员
    public function reg($data)
    {
        //定义验证场景
        $this->scenario = 'adminadd';

        if ($this->load($data) && $this->validate()) {
            $this->adminpass = md5($this->adminpass);
            $this->createtime = time();
            $this->logintime = time();
            if ($this->save(false)) {
                return true;
            }
            return false;
        }
        return false;
    }

    //修改管理员邮箱
    public function changeEmail($data)
    {
        $this->scenario = 'changeemail';
        if ($this->load($data) && $this->validate()) {
            return (bool)$this->updateAll(['adminemail' => $this->adminemail],
                'adminuser=:user', [':user' => $this->adminuser]);
        }
        return false;
    }
}