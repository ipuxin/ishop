<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <!--    不带导航的header-->
    <!DOCTYPE html>
    <html lang="zh-cn">
    <head>
        <meta property="qc:admins" content="6757752303070610501663757164506000"/>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="keywords" content="MediaCenter, Template, eCommerce">
        <meta name="robots" content="all">

        <title>壹朴心商城</title>
        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">

        <!-- Customizable CSS -->
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="assets/css/red.css">
        <link rel="stylesheet" href="assets/css/owl.carousel.css">
        <link rel="stylesheet" href="assets/css/owl.transitions.css">
        <link rel="stylesheet" href="assets/css/animate.min.css">


        <!-- Icons/Glyphs -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">

        <!-- Favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- HTML5 elements and media queries Support for IE8 : HTML5 shim and Respond.js -->
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->

    </head>
<body>

<div class="wrapper">
    <!-- ============================================================= TOP NAVIGATION ============================================================= -->
    <nav class="top-bar animate-dropdown">
        <div class="container">
            <div class="col-xs-12 col-sm-6 no-margin">
                <ul>
                    <li><a href="<?= yii\helpers\Url::to(['index/index']) ?>">首页</a></li>
                    <?php if (\Yii::$app->session['isLogin'] == 1): ?>
                        <li><a href="<?php echo yii\helpers\Url::to(['cart/index']) ?>">我的购物车</a></li>
                        <li><a href="<?php echo yii\helpers\Url::to(['order/index']) ?>">我的订单</a></li>
                    <?php endif; ?>
                </ul>
                </ul>
            </div><!-- /.col -->

            <div class="col-xs-12 col-sm-6 no-margin">
                <ul class="right">
                    <?php if (\Yii::$app->session['isLogin'] == 1): ?>
                        您好 , 欢迎回来 <?= \Yii::$app->session['loginname']; ?>
                        <a href="<?= yii\helpers\Url::to(['member/logout']); ?>">安全退出</a>
                    <?php else: ?>
                        <li><a href="<?= yii\helpers\Url::to(['member/auth']) ?>">注册</a></li>
                        <li><a href="<?= yii\helpers\Url::to(['member/auth']) ?>">登录</a></li>
                    <?php endif; ?>
                </ul>
            </div><!-- /.col -->
        </div><!-- /.container -->
    </nav><!-- /.top-bar -->
    <!-- ============================================================= TOP NAVIGATION : END ============================================================= -->
    <!-- ============================================================= HEADER ============================================================= -->
    <header>
        <div class="container no-padding">

            <div class="col-xs-12 col-sm-12 col-md-3 logo-holder">
                <!-- ============================================================= LOGO ============================================================= -->
                <div class="logo">
                    <a href="<?= yii\helpers\Url::to(['index/index']) ?>">
                        <img alt="logo" src="assets/images/logo.PNG" width="233" height="54"/>
                    </a>
                </div><!-- /.logo -->
                <!-- ============================================================= LOGO : END ============================================================= -->
            </div><!-- /.logo-holder -->

            <div class="col-xs-12 col-sm-12 col-md-6 top-search-holder no-margin">
                <div class="contact-row">
                    <div class="phone inline">
                        <i class="fa fa-phone"></i> 18039208926
                    </div>
                    <div class="contact inline">
                        <i class="fa fa-envelope"></i> ipuxin@<span class="le-color">qq.com</span>
                    </div>
                </div><!-- /.contact-row -->
                <!-- ============================================================= SEARCH AREA ============================================================= -->
                <div class="search-area">

                    <?php
                    $form = ActiveForm::begin([
                        'action'=>yii\helpers\Url::to(['index/search']),
                    ]);
                    ?>
                        <div class="control-group">
                            <?php echo $form->field($search, 'title')->textInput(['class' => 'search-field', 'placeholder'=>"搜索商品"]); ?>
                            <ul class="categories-filter animate-dropdown">
                                <li class="dropdown">

                                    <a class="dropdown-toggle" data-toggle="dropdown" href="category-grid.html">所有分类</a>

                                    <ul class="dropdown-menu" role="menu">
                                        <?php foreach ($this->params['menu'] as $top):?>
                                            <li role="presentation">
                                                <a role="menuitem" tabindex="-1" href="category-grid.html"><?= $top['title'] ?></a>
                                            </li>
                                        <?php endforeach?>

                                    </ul>
                                </li>
                            </ul>

                            <a style="padding:15px 15px 13px 12px" class="search-button" href="#"></a>

                        </div>
                    <?php ActiveForm::end(); ?>
                </div><!-- /.search-area -->
                <!-- ============================================================= SEARCH AREA : END ============================================================= -->
            </div><!-- /.top-search-holder -->

            <div class="col-xs-12 col-sm-12 col-md-3 top-cart-row no-margin">
                <div class="top-cart-row-container">

                    <!-- ============================================================= SHOPPING CART DROPDOWN ============================================================= -->
                    <div class="top-cart-holder dropdown animate-dropdown">

                        <div class="basket">

                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <div class="basket-item-count">
                                    <span class="count"><?php echo count($this->params['cart']['products']) ?></span>
                                    <img src="/assets/images/icon-cart.png" alt=""/>
                                </div>

                                <div class="total-price-basket">
                                    <span class="lbl">您的购物车:</span>
                                    <span class="total-price">
                    <span class="sign">￥</span><span class="value"><?php echo $this->params['cart']['total'] ?></span>
                    </span>
                                </div>
                            </a>

                            <ul class="dropdown-menu">
                                <?php
                                foreach ($this->params['cart']['products'] as $product):
                                    ?>
                                    <li>
                                        <div class="basket-item">
                                            <div class="row">
                                                <div class="col-xs-4 col-sm-4 no-margin text-center">
                                                    <div class="thumb">
                                                        <img alt="" src="<?php echo $product['cover'] ?>-picsmall"/>
                                                    </div>
                                                </div>
                                                <div class="col-xs-8 col-sm-8 no-margin">
                                                    <div class="title"><?php echo $product['title'] ?></div>
                                                    <div class="price">￥ <?php echo $product['price'] ?></div>
                                                </div>
                                            </div>
                                            <a class="close-btn"
                                               href="<?= yii\helpers\Url::to(['cart/del', 'cartid' => $product['cartid']]) ?>"></a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                                <li class="checkout">
                                    <div class="basket-item">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <a href="<?php echo yii\helpers\Url::to(['cart/index']) ?>"
                                                   class="le-button inverse">查看购物车</a>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <a href="<?php echo yii\helpers\Url::to(['cart/index']) ?>"
                                                   class="le-button">去往收银台</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div><!-- /.basket -->
                    </div><!-- /.top-cart-holder -->
                </div><!-- /.top-cart-row-container -->
                <!-- ============================================================= SHOPPING CART DROPDOWN : END ============================================================= -->
            </div><!-- /.top-cart-row -->

        </div><!-- /.container -->
    </header>
    <!-- ============================================================= HEADER : END ============================================================= -->
    <div id="top-banner-and-menu">
        <?= $content ?>
        <!-- ============================================================= FOOTER ============================================================= -->
        <footer id="footer" class="color-bg">

            <div class="container">
                <div class="row no-margin widgets-row">
                    <div class="col-xs-12  col-sm-4 no-margin-left">
                        <!-- ============================================================= FEATURED PRODUCTS ============================================================= -->
                        <div class="widget">
                            <h2>最新商品</h2>
                            <div class="body">
                                <ul>
                                    <li>
                                        <?php
                                        $i = 0;
                                        foreach ($this->params['new'] as $pro):
                                            if ($i == 3) break;
                                            $i++; ?>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-9 no-margin">
                                                    <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"><?= $pro['title'] ?></a>
                                                    <div class="price">
                                                        <div class="price-prev">￥<?= $pro['price'] ?></div>
                                                        <div class="price-current">￥<?= $pro['saleprice'] ?></div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-3 no-margin">
                                                    <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"
                                                       class="thumb-holder">
                                                        <img alt="" src="assets/images/blank.gif"
                                                             data-echo="<?= $pro['cover'] ?>-picsmall"/>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </li>
                                </ul>
                            </div><!-- /.body -->
                        </div> <!-- /.widget -->
                        <!-- ============================================================= FEATURED PRODUCTS : END ============================================================= -->
                    </div><!-- /.col -->

                    <div class="col-xs-12 col-sm-4 ">
                        <!-- ============================================================= ON SALE PRODUCTS ============================================================= -->
                        <div class="widget">
                            <h2>促销商品</h2>
                            <div class="body">
                                <ul>
                                    <li>
                                        <?php $i = 0;
                                        foreach ($this->params['hot'] as $pro):
                                            if ($i == 3) break;
                                            $i++; ?>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-9 no-margin">
                                                    <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"><?= $pro['title'] ?></a>
                                                    <div class="price">
                                                        <div class="price-prev">￥<?= $pro['price'] ?></div>
                                                        <div class="price-current">￥<?= $pro['saleprice'] ?></div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-3 no-margin">
                                                    <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"
                                                       class="thumb-holder">
                                                        <img alt="" src="assets/images/blank.gif"
                                                             data-echo="<?= $pro['cover'] ?>-picsmall"/>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </li>
                                </ul>
                            </div><!-- /.body -->
                        </div> <!-- /.widget -->
                        <!-- ============================================================= ON SALE PRODUCTS : END ============================================================= -->
                    </div><!-- /.col -->

                    <div class="col-xs-12 col-sm-4 ">
                        <!-- ============================================================= TOP RATED PRODUCTS ============================================================= -->
                        <div class="widget">
                            <h2>最热商品</h2>
                            <div class="body">
                                <ul>
                                    <li>
                                        <?php $i = 0;
                                        foreach ($this->params['tui'] as $pro):
                                            if ($i == 3) break;
                                            $i++; ?>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-9 no-margin">
                                                    <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"><?= $pro['title'] ?></a>
                                                    <div class="price">
                                                        <div class="price-prev">￥<?= $pro['price'] ?></div>
                                                        <div class="price-current">￥<?= $pro['saleprice'] ?></div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-3 no-margin">
                                                    <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>"
                                                       class="thumb-holder">
                                                        <img alt="" src="assets/images/blank.gif"
                                                             data-echo="<?= $pro['cover'] ?>-picsmall"/>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </li>
                                </ul>
                            </div><!-- /.body -->
                        </div><!-- /.widget -->
                        <!-- ============================================================= TOP RATED PRODUCTS : END ============================================================= -->
                    </div><!-- /.col -->

                </div><!-- /.widgets-row-->
            </div><!-- /.container -->

            <div class="sub-form-row">
                <!--<div class="container">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 no-padding">
                        <form role="form">
                            <input placeholder="Subscribe to our newsletter">
                            <button class="le-button">Subscribe</button>
                        </form>
                    </div>
                </div>--><!-- /.container -->
            </div><!-- /.sub-form-row -->

            <div class="link-list-row">
                <div class="container no-padding">
                    <div class="col-xs-12 col-md-4 ">
                        <!-- ============================================================= CONTACT INFO ============================================================= -->
                        <div class="contact-info">
                            <div class="footer-logo">
                                <a href="<?= yii\helpers\Url::to(['index/index']) ?>"><img alt="logo"
                                                                                           src="assets/images/logo.PNG"
                                                                                           width="233" height="54"/></a>
                            </div><!-- /.footer-logo -->

                            <p class="regular-bold"> 请通过电话，电子邮件随时联系我们</p>

                            <p>
                                河南省 郑州市 金水区 <br>中州大道农业路交叉口苏荷中心 616
                                <br>壹朴心 (QQ:631752525)
                            </p>

                        </div>
                        <!-- ============================================================= CONTACT INFO : END ============================================================= -->
                    </div>

                    <div class="col-xs-12 col-md-8 no-margin">
                        <!-- ============================================================= LINKS FOOTER ============================================================= -->
                        <div class="link-widget">
                            <div class="widget">
                                <h3>热销商品</h3>
                                <ul>
                                    <?php
                                    $i = 0;
                                    foreach ($this->params['hot'] as $pro):
                                        if ($i == 5) break;
                                        $i++;
                                        ?>
                                        <li>
                                            <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>">
                                                <?= $pro['title'] ?></a></li>
                                    <?php endforeach ?>
                                </ul>
                            </div><!-- /.widget -->
                        </div><!-- /.link-widget -->

                        <div class="link-widget">
                            <div class="widget">
                                <h3>最新商品</h3>
                                <ul>
                                    <?php
                                    $i = 0;
                                    foreach ($this->params['new'] as $pro):
                                        if ($i == 5) break;
                                        $i++;
                                        ?>
                                        <li>
                                            <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro['productid']]) ?>">
                                                <?= $pro['title'] ?></a></li>
                                    <?php endforeach ?>
                                </ul>
                            </div><!-- /.widget -->
                        </div><!-- /.link-widget -->

                        <div class="link-widget">
                            <div class="widget">
                                <h3>最近浏览(layout1)</h3>

                                <ul>
                                    <!--从cookie中取出浏览记录-->
                                    <?php
                                    $i = 0;
                                    if (!empty($this->params['browse'])) {
                                        foreach ($this->params['browse'] as $pro):
                                            if ($i == 5) break;
                                            $i++; ?>
                                            <li>
                                                <a href="<?= yii\helpers\Url::to(['product/detail', 'productid' => $pro->productid]) ?>">
                                                    <?= $pro->title ?></a></li>
                                        <?php endforeach;
                                    } ?>
                                </ul>
                            </div><!-- /.widget -->
                        </div><!-- /.link-widget -->
                        <!-- ============================================================= LINKS FOOTER : END ============================================================= -->
                    </div>
                </div><!-- /.container -->
            </div><!-- /.link-list-row -->

            <div class="copyright-bar">
                <div class="container">
                    <div class="col-xs-12 col-sm-6 no-margin">
                        <div class="copyright">
                            &copy; <a href="/">Ipuxin.com</a> - all rights reserved
                        </div><!-- /.copyright -->
                    </div>
                    <div class="col-xs-12 col-sm-6 no-margin">
                        <div class="payment-methods ">
                            <ul>
                                <li><img alt="" src="assets/images/payments/payment-visa.png"></li>
                                <li><img alt="" src="assets/images/payments/payment-master.png"></li>
                                <li><img alt="" src="assets/images/payments/payment-paypal.png"></li>
                                <li><img alt="" src="assets/images/payments/payment-skrill.png"></li>
                            </ul>
                        </div><!-- /.payment-methods -->
                    </div>
                </div><!-- /.container -->
            </div><!-- /.copyright-bar -->

        </footer><!-- /#footer -->
        <!-- ============================================================= FOOTER : END ============================================================= -->
    </div><!-- /.wrapper -->

    <!-- JavaScripts placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery-1.10.2.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/gmap3.min.js"></script>
    <script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/css_browser_selector.min.js"></script>
    <script src="assets/js/echo.min.js"></script>
    <script src="assets/js/jquery.easing-1.3.min.js"></script>
    <script src="assets/js/bootstrap-slider.min.js"></script>
    <script src="assets/js/jquery.raty.min.js"></script>
    <script src="assets/js/jquery.prettyPhoto.min.js"></script>
    <script src="assets/js/jquery.customSelect.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script>

        $("#createlink").click(function () {
            $(".billing-address").slideDown();
        });

        //减少商品数量
        $(".minus").click(function () {
            var cartid = $("input[name=productnum]").attr('id');
            var num = parseInt($("input[name=productnum]").val()) - 1;
            //获取购物车总价
            var total = parseFloat($(".value .pull-right span").html());
            //获取商品购物车中的价格
            var price = parseFloat($(".price span").html());
            //调用方法改变数据库的数量
            changeNum(cartid, num);
            //更新单个商品总价,和订单总价
            $(".value .pull-right span").html(total - price);
            $(".value .pull-right .ordertotal span").html(total - price);
        });

        //增加商品数量
        $(".plus").click(function () {
            var cartid = $("input[name=productnum]").attr('id');
            var num = parseInt($("input[name=productnum]").val()) + 1;
            //获取购物车总价
            var total = parseFloat($(".value.pull-right span").html());
            var price = parseFloat($(".price span").html());
            changeNum(cartid, num);
            $(".value.pull-right span").html(total + price);
            $(".value.pull-right.ordertotal span").html(total + price);
        });

        //利用Ajax传递数据给model层,来更新数据库
        function changeNum(cartid, num) {
            $.get('<?= yii\helpers\Url::to(['cart/mod']) ?>', {
                'productnum': num,
                'cartid': cartid
            }, function (data) {
            });
        }

        var total = parseFloat($("#total span").html());
        $(".le-radio.express").click(function () {
            var ototal = parseFloat($(this).attr('data')) + total;
            $("#ototal span").html(ototal);
        });

        $("input.address").click(function () {
            var addressid = $(this).val();
            $("input[name=addressid]").val(addressid);
        });
    </script>

    <script src="js/dialog/layer.js"></script>
    <script src="js/dialog.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>

