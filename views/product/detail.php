<?php
use yii\bootstrap\ActiveForm;

//存储用户浏览信息的cookies
$proId = (int)$product['productid'];
//读取cookie,判断是否是数组
$cookies = \Yii::$app->request->cookies;
$dataId = $cookies->getValue('browse');
//如果是数组,并且当前id 不在其中, 才存储
if (is_array($dataId)) {

    if (!in_array($proId, $dataId)) {
        $dataId[] = $proId;
    }

} else if ($dataId != $proId) {
    $dataId[] = $proId;
}
//开始存储
$cookies = Yii::$app->response->cookies;
$cookies->add(new \yii\web\Cookie([
    'name' => 'browse',
    'value' => $dataId,
    'expire' => time() + 36000
]));
?>
<!-- ============================================================= HEADER : END ============================================================= -->
<div id="single-product">
    <div class="container">

        <div class="no-margin col-xs-12 col-sm-6 col-md-5 gallery-holder">
            <div class="product-item-holder size-big single-product-gallery small-gallery">

                <div id="owl-single-product">
                    <div class="single-product-gallery-item" id="slide1">
                        <a data-rel="prettyphoto" href="<?php echo $product['cover'] ?>-coverbig">
                            <img class="img-responsive" alt="" src="<?php echo $product['cover'] ?>-coverbig"/>
                        </a>
                    </div><!-- /.single-product-gallery-item -->
                    <?php $i = 2 ?>
                    <?php foreach ((array)json_decode($product['pics'], true) as $k => $pic): ?>
                        <div class="single-product-gallery-item" id="slide<?php echo $i ?>">
                            <a data-rel="prettyphoto" href="<?php echo $pic ?>-coverbig">
                                <img class="img-responsive" alt="" src="<?php echo $pic ?>-coverbig"/>
                            </a>
                        </div><!-- /.single-product-gallery-item -->
                        <?php $i++ ?>
                    <?php endforeach; ?>
                </div><!-- /.single-product-slider -->

                <div class="single-product-gallery-thumbs gallery-thumbs">

                    <div id="owl-single-product-thumbnails">
                        <?php $i = 2 ?>
                        <?php foreach ((array)json_decode($product['pics'], true) as $k => $pic): ?>
                            <a class="horizontal-thumb" data-target="#owl-single-product"
                               data-slide="<?php echo $i - 1 ?>" href="#slide<?php echo $i ?>">
                                <img width="67" alt="" src="<?php echo $pic ?>-piclistsmall"/>
                            </a>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div><!-- /#owl-single-product-thumbnails -->

                    <div class="nav-holder left hidden-xs">
                        <a class="prev-btn slider-prev" data-target="#owl-single-product-thumbnails" href="#prev"></a>
                    </div><!-- /.nav-holder -->

                    <div class="nav-holder right hidden-xs">
                        <a class="next-btn slider-next" data-target="#owl-single-product-thumbnails" href="#next"></a>
                    </div><!-- /.nav-holder -->

                </div><!-- /.gallery-thumbs -->

            </div><!-- /.single-product-gallery -->
        </div><!-- /.gallery-holder -->
        <div class="no-margin col-xs-12 col-sm-7 body-holder">
            <div class="body">
                <!--<div class="star-holder inline"><div class="star" data-score="4"></div></div>-->
                <div style="margin-top:30px"></div>
                <div class="title"><a href="#"><?php echo $product['title'] ?></a></div>
                <div class="availability" style="font-size:15px;margin:0;line-height:30px"><label>库存:</label><span
                        class="available">  <?php echo $product['num'] ?></span></div>
                <!--<div class="excerpt">
        <p><?php echo $product['descr'] ?></p>
        </div>-->

                <div class="prices">
                    <?php if ($product['issale']): ?>
                        <div class="price-current">￥<?php echo $product['saleprice'] ?></div>
                        <div class="price-prev">￥<?php echo $product['price'] ?></div>
                    <?php else: ?>
                        <div class="price-current">￥<?php echo $product['price'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="qnt-holder">
                    <?php $form = ActiveForm::begin([
                        'action' => yii\helpers\Url::to(['cart/add']),
                    ]) ?>
                    <div class="le-quantity">
                        <a class="minus" href="#reduce"></a>
                        <input name="productnum" readonly="readonly" type="text" value="1"/>
                        <a class="plus" href="#add"></a>
                    </div>
                    <input type="hidden" name="price"
                           value="<?= $product['issale'] == '1' ? $product['saleprice'] : $product['price'] ?>">
                    <input type="hidden" name="productid" value="<?= $product['productid'] ?>">
                    <input type='submit' id="addto-cart" class="le-button huge" value="加入购物车">
                    <?php ActiveForm::end(); ?>
                </div><!-- /.qnt-holder -->
            </div><!-- /.body -->

        </div><!-- /.body-holder -->
    </div><!-- /.container -->
</div><!-- /.single-product -->

<!-- ========================================= SINGLE PRODUCT TAB ========================================= -->
<section id="single-product-tab">
    <div class="container">
        <div class="tab-holder">

            <ul class="nav nav-tabs simple">
                <li class="active"><a href="#description" data-toggle="tab">商品详情</a></li>
            </ul><!-- /.nav-tabs -->

            <div class="tab-content">
                <div class="tab-pane active" id="description">
                    <p><?php echo $product['descr'] ?></p>
                </div><!-- /.tab-pane #description -->

            </div><!-- /.tab-content -->

        </div><!-- /.tab-holder -->
    </div><!-- /.container -->
</section><!-- /#single-product-tab -->
<!-- ========================================= SINGLE PRODUCT TAB : END ========================================= -->
<!-- ========================================= RECENTLY VIEWED ========================================= -->
<section id="recently-reviewd" class="wow fadeInUp">
    <div class="container">
        <div class="carousel-holder hover">

            <div class="title-nav">
                <h2 class="h1">最近浏览</h2>
                <div class="nav-holder">
                    <a href="#prev" data-target="#owl-recently-viewed"
                       class="slider-prev btn-prev fa fa-angle-left"></a>
                    <a href="#next" data-target="#owl-recently-viewed"
                       class="slider-next btn-next fa fa-angle-right"></a>
                </div>
            </div><!-- /.title-nav -->

            <div id="owl-recently-viewed" class="owl-carousel product-grid-holder">
                <?php
                foreach($this->params['new'] as $pro):
                    ?>
                    <div class="no-margin carousel-item product-item-holder size-small hover">
                        <div class="product-item">
                            <div class="ribbon blue"><span>new!</span></div>
                            <div class="image">
                                <img alt="" src="assets/images/blank.gif"
                                     data-echo="<?= $pro['cover'] ?>-picsmall"/>
                            </div>
                            <div class="body">
                                <div class="title">
                                    <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']])?>"><?=$pro['title']?></a>
                                </div>
                                <!--                            <div class="brand">zeiss</div>-->
                            </div>
                            <div class="prices">
                                <div class="price-prev pull-left">￥<?=$pro['price']?></div>
                                <div class="price-current text-right">￥<?=$pro['saleprice']?></div>
                            </div>
                            <div class="hover-area">
                                <div class="add-cart-button">
                                    <a href="<?= yii\helpers\Url::to(['cart/add', 'productid' => $pro['productid']]) ?>" class="le-button">加入购物车</a>
                                </div>
                                <!--                            <div class="wish-compare">-->
                                <!--                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>-->
                                <!--                                <a class="btn-add-to-compare" href="#">Compare</a>-->
                                <!--                            </div>-->
                            </div>
                        </div><!-- /.product-item -->
                    </div><!-- /.product-item-holder -->
                <?php endforeach;?>

            </div><!-- /#recently-carousel -->

        </div><!-- /.carousel-holder -->
    </div><!-- /.container -->
</section><!-- /#recently-reviewd -->
<!-- ========================================= RECENTLY VIEWED : END ========================================= -->        <!-- ============================================================= FOOTER ============================================================= -->

