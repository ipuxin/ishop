<div class="container">

    <div class="col-xs-12 col-sm-4 col-md-3 sidemenu-holder">
        <!-- ================================== TOP NAVIGATION ================================== -->
        <div class="side-menu animate-dropdown">
            <div class="head"><i class="fa fa-list"></i> 所有分类</div>
            <nav class="yamm megamenu-horizontal" role="navigation">
                <ul class="nav">
                    <?php
                    foreach ($this->params['menu'] as $top):
                        ?>
                        <li class="dropdown menu-item">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $top['title']?></a>
                            <ul class="dropdown-menu mega-menu">
                                <li class="yamm-content">
                                    <!-- ================================== MEGAMENU VERTICAL ================================== -->
                                    <div class="row">
                                        <div class="col-xs-12 col-lg-4">
                                            <ul>
                                                <?php
                                                //遍历二级分类
                                                foreach ($top['children'] as $child):
                                                    ?>
                                                    <li><a href="<?php echo yii\helpers\Url::to(['product/index', 'cateid' => $child['cateid']]) ?>">
                                                            <?php echo $child['title'] ?></a></li>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </ul>
                                        </div>

                                        <div class="dropdown-banner-holder">
                                            <a href="#"><img alt="" src="assets/images/banners/banner-side.png"/></a>
                                        </div>
                                    </div>
                                    <!-- ================================== MEGAMENU VERTICAL ================================== -->
                                </li>
                            </ul>
                        </li><!-- /.menu-item -->
                        <?php
                    endforeach;
                    ?>
                    <!--<li><a href="http://themeforest.net/item/media-center-electronic-ecommerce-html-template/8178892?ref=shaikrilwan">Buy this Theme</a></li>-->
                </ul><!-- /.nav -->
            </nav><!-- /.megamenu-horizontal -->
        </div><!-- /.side-menu -->
        <!-- ================================== TOP NAVIGATION : END ================================== -->
    </div><!-- /.sidemenu-holder -->

    <div class="col-xs-12 col-sm-8 col-md-9 homebanner-holder">
        <!-- ========================================== SECTION – HERO ========================================= -->

        <div id="hero">
            <div id="owl-main" class="owl-carousel owl-inner-nav owl-ui-sm">

                <div class="item" style="background-image: url(assets/images/sliders/slider01.jpg);">
                    <div class="container-fluid">
                        <div class="caption vertical-center text-left">
                            <div class="big-text fadeInDown-1">
                                最高优惠<span class="big"><span class="sign">￥</span>400</span>
                            </div>

                            <div class="excerpt fadeInDown-2">
                                潮玩生活<br>
                                享受生活<br>
                                引领时尚
                            </div>
                            <div class="small fadeInDown-2">
                                最后 5 天限时抢购
                            </div>
                            <div class="button-holder fadeInDown-3">
                                <a href="http://www.vivo.com.cn/vivo/xplay5/" class="big le-button ">去购买</a>
                            </div>
                        </div><!-- /.caption -->
                    </div><!-- /.container-fluid -->
                </div><!-- /.item -->

                <div class="item" style="background-image: url(assets/images/sliders/slider03.jpg);">
                    <div class="container-fluid">
                        <div class="caption vertical-center text-left">
                            <div class="big-text fadeInDown-1">
                                想获得<span class="big"><span class="sign">￥</span>200</span>的优惠？
                            </div>

                            <div class="excerpt fadeInDown-2">
                                速速前来 <br>快速抢购<br>
                            </div>
                            <div class="small fadeInDown-2">
                                优惠等你拿
                            </div>
                            <div class="button-holder fadeInDown-3">
                                <a href="http://www.vivo.com.cn/vivo/xplay5/" class="big le-button ">去购买</a>
                            </div>
                        </div><!-- /.caption -->
                    </div><!-- /.container-fluid -->
                </div><!-- /.item -->

            </div><!-- /.owl-carousel -->
        </div>

        <!-- ========================================= SECTION – HERO : END ========================================= -->
    </div><!-- /.homebanner-holder -->

</div><!-- /.container -->
</div><!-- /#top-banner-and-menu -->

<!-- ========================================= HOME BANNERS ========================================= -->

<!-- ========================================= HOME BANNERS : END ========================================= -->
<div id="products-tab" class="wow fadeInUp">
    <div class="container">
        <div class="tab-holder">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#featured" data-toggle="tab">推荐商品</a></li>
                <li><a href="#new-arrivals" data-toggle="tab">最新上架</a></li>
                <li><a href="#top-sales" data-toggle="tab">最佳热卖</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!--推荐商品-->
                <div class="tab-pane active" id="featured">
                    <div class="product-grid-holder">
                        <?php
                        $i = 0;
                        foreach($this->params['tui']  as $pro):
                            if($i==4){break;}
                            $i++;
                            ?>
                        <div class="col-sm-4 col-md-3  no-margin product-item-holder hover">
                            <div class="product-item">
                                <?php if ($pro['ishot']): ?>
                                    <div class="ribbon red"><span>热卖</span></div>
                                <?php endif; ?>
                                <?php if ($pro['issale']): ?>
                                    <div class="ribbon green"><span>促销</span></div>
                                <?php endif; ?>
                                <?php if ($pro['istui']): ?>
                                    <div class="ribbon blue"><span>推荐</span></div>
                                <?php endif; ?>
                                <div class="image">
                                    <img alt="" src="assets/images/products/product-01.jpg"
                                         data-echo="<?= $pro['cover'] ?>-covermiddle"/>
                                </div>
                                <div class="body">
                                    <?php if ($pro['issale']): ?>
                                        <div
                                            class="label-discount green"><?php echo round($pro['saleprice'] / $pro['price'] * 100, 0) ?>
                                            % sale
                                        </div>
                                    <?php endif; ?>
                                    <div class="title">
                                        <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>">
                                            <?= mb_substr(htmlspecialchars_decode($pro['title']),0,20,"utf-8")  ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="prices">
                                    <?php if ($pro['issale']): ?>
                                        <div class="price-prev">￥<?= $pro['price'] ?></div>
                                        <div class="price-current pull-right">
                                            ￥<?= $pro['saleprice'] ?></div>
                                    <?php else: ?>
                                        <div class="price-prev"></div>
                                        <div class="price-current pull-right">
                                            ￥<?= $pro['price'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="<?= yii\helpers\Url::to(['cart/add', 'productid' => $pro['productid']]) ?>"
                                           class="le-button">加入购物车</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach ?>

                    </div>
                    <div class="loadmore-holder text-center">
                        <a class="btn-loadmore" href="#">
                            <i class="fa fa-plus"></i>
                            查看更多</a>
                    </div>

                </div>
                <!--最新上架-->
                <div class="tab-pane" id="new-arrivals">
                    <div class="product-grid-holder">
                        <?php
                        $i = 0;
                        foreach($this->params['new']  as $new):
                            if($i==4){break;}
                            $i++;
                            ?>
                            <div class="col-sm-4 col-md-3  no-margin product-item-holder hover">
                                <div class="product-item">
                                    <?php if ($new['ishot']): ?>
                                        <div class="ribbon red"><span>热卖</span></div>
                                    <?php endif; ?>
                                    <?php if ($new['issale']): ?>
                                        <div class="ribbon green"><span>促销</span></div>
                                    <?php endif; ?>
                                    <?php if ($new['istui']): ?>
                                        <div class="ribbon blue"><span>推荐</span></div>
                                    <?php endif; ?>
                                    <div class="image">
                                        <img alt="" src="assets/images/products/product-01.jpg"
                                             data-echo="<?= $new['cover'] ?>-covermiddle"/>
                                    </div>
                                    <div class="body">
                                        <?php if ($new['issale']): ?>
                                            <div
                                                class="label-discount green"><?php echo round($new['saleprice'] / $new['price'] * 100, 0) ?>
                                                % sale
                                            </div>
                                        <?php endif; ?>
                                        <div class="title">
                                            <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $new['productid']]) ?>">
                                                <?= mb_substr(htmlspecialchars_decode($new['title']),0,20,"utf-8")  ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="prices">
                                        <?php if ($new['issale']): ?>
                                            <div class="price-prev">￥<?= $new['price'] ?></div>
                                            <div class="price-current pull-right">
                                                ￥<?= $new['saleprice'] ?></div>
                                        <?php else: ?>
                                            <div class="price-prev"></div>
                                            <div class="price-current pull-right">
                                                ￥<?= $new['price'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="hover-area">
                                        <div class="add-cart-button">
                                            <a href="<?= yii\helpers\Url::to(['cart/add', 'productid' => $new['productid']]) ?>"
                                               class="le-button">加入购物车</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div class="loadmore-holder text-center">
                        <a class="btn-loadmore" href="#">
                            <i class="fa fa-plus"></i>
                            查看更多</a>
                    </div>
                </div>

                <!--热卖-->
                <div class="tab-pane" id="top-sales">
                    <div class="product-grid-holder">

                        <?php
                        $i = 0;
                        foreach($this->params['hot']  as $hot):
                            if($i==4){break;}
                            $i++;
                            ?>
                            <div class="col-sm-4 col-md-3  no-margin product-item-holder hover">
                                <div class="product-item">
                                    <?php if ($hot['ishot']): ?>
                                        <div class="ribbon red"><span>热卖</span></div>
                                    <?php endif; ?>
                                    <?php if ($hot['issale']): ?>
                                        <div class="ribbon green"><span>促销</span></div>
                                    <?php endif; ?>
                                    <?php if ($hot['istui']): ?>
                                        <div class="ribbon blue"><span>推荐</span></div>
                                    <?php endif; ?>
                                    <div class="image">
                                        <img alt="" src="assets/images/products/product-01.jpg"
                                             data-echo="<?= $hot['cover'] ?>-covermiddle"/>
                                    </div>
                                    <div class="body">
                                        <?php if ($hot['issale']): ?>
                                            <div
                                                class="label-discount green"><?php echo round($hot['saleprice'] / $hot['price'] * 100, 0) ?>
                                                % sale
                                            </div>
                                        <?php endif; ?>
                                        <div class="title">
                                            <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $hot['productid']]) ?>">
                                                <?= mb_substr(htmlspecialchars_decode($hot['title']),0,20,"utf-8")  ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="prices">
                                        <?php if ($hot['issale']): ?>
                                            <div class="price-prev">￥<?= $hot['price'] ?></div>
                                            <div class="price-current pull-right">
                                                ￥<?= $hot['saleprice'] ?></div>
                                        <?php else: ?>
                                            <div class="price-prev"></div>
                                            <div class="price-current pull-right">
                                                ￥<?= $hot['price'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="hover-area">
                                        <div class="add-cart-button">
                                            <a href="<?= yii\helpers\Url::to(['cart/add', 'productid' => $hot['productid']]) ?>"
                                               class="le-button">加入购物车</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    </div>
                    <div class="loadmore-holder text-center">
                        <a class="btn-loadmore" href="#">
                            <i class="fa fa-plus"></i>
                            查看更多</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ========================================= BEST SELLERS ========================================= -->

<!-- ========================================= BEST SELLERS : END ========================================= -->
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
<!-- ========================================= RECENTLY VIEWED : END ========================================= -->
<!-- ========================================= TOP BRANDS ========================================= -->
<!-- ========================================= TOP BRANDS : END ========================================= -->
