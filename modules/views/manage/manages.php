<!-- main container -->
<div class="content">
    <div class="container-fluid">
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header">
                <h3>管理员列表</h3>
                <div class="span10 pull-right">
                    <a href="<?= \yii\helpers\Url::to(['manage/reg']); ?>" class="btn-flat success pull-right">
                        <span>&#43;</span>添加新管理员</a></div>
            </div>
            <!-- Users table -->
            <div class="row-fluid table">
                <?php
                if (Yii::$app->session->hasFlash('info')) {
                    echo Yii::$app->session->getFlash('info');
                }
                ?>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="span2" onclick="admin.del()">管理员ID</th>
                        <th class="span2">
                            <span class="line"></span>管理员账号
                        </th>
                        <th class="span2">
                            <span class="line"></span>管理员邮箱
                        </th>
                        <th class="span3">
                            <span class="line"></span>最后登录时间
                        </th>
                        <th class="span3">
                            <span class="line"></span>最后登录IP
                        </th>
                        <th class="span2">
                            <span class="line"></span>添加时间
                        </th>
                        <th class="span2">
                            <span class="line"></span>操作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- row -->
                    <?php foreach ($managers as $manager): ?>
                        <tr>
                            <td><?= $manager->adminid; ?></td>
                            <td><?= $manager->adminuser; ?></td>
                            <td><?= $manager->adminemail; ?></td>
                            <td><?= date('Y-m-d h:i:s', $manager->logintime); ?></td>
                            <td><?= long2ip($manager->loginip); ?></td>
                            <td><?= date('Y-m-d h:i:s', $manager->createtime); ?></td>
                            <td class="align-right">
                                <a id="ipuxinDel" href=" javascript:void(0);" title="<?= $manager->adminuser; ?>"
                                   data-message="删除"
                                   data-id="<?= $manager->adminid; ?>"
                                   data-url="<?= yii\helpers\Url::to(['manage/del', 'adminid' => $manager->adminid]) ?>"
                                   onclick="common.del();return false;">删除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination pull-right">
                <?= yii\widgets\LinkPager::widget(['pagination' => $pager, 'prevPageLabel' => '&#8249;', 'nextPageLabel' => '&#8250;']) ?>
                <!--                <ul>-->
                <!--                    <li><a href="">&#8249;</a></li>-->
                <!--                    <li></li>-->
                <!--                    <li></li>-->
                <!--                    <li></li>-->
                <!--                    <li></li>-->
                <!--                    <li></li>-->
                <!--                    <li></li>-->
                <!--                </ul>-->
            </div>
            <!-- end users table --></div>
    </div>
</div>
<!-- end main container -->