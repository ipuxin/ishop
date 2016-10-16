# 管理员表
DROP TABLE IF EXISTS `shop_admin`;
CREATE TABLE IF NOT EXISTS `shop_admin` (
  `adminid`    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT
  COMMENT '主键ID',
  `adminuser`  VARCHAR(32)      NOT NULL DEFAULT ''
  COMMENT '管理员账号',
  `adminpass`  CHAR(32)         NOT NULL DEFAULT ''
  COMMENT '管理员密码',
  `adminemail` VARCHAR(50)      NOT NULL DEFAULT ''
  COMMENT '管理员邮箱',
  `logintime`  INT(10) UNSIGNED NOT NULL DEFAULT '0'
  COMMENT '登录时间',
  `loginip`    BIGINT           NOT NULL DEFAULT '0'
  COMMENT '登录IP',
  `createtime` INT(10) UNSIGNED NOT NULL DEFAULT '0'
  COMMENT '创建时间',
  PRIMARY KEY (`adminid`),
  UNIQUE shop_admin_adminuser_adminpass(`adminuser`, `adminpass`), /*索引1*/
  UNIQUE shop_admin_adminuser_adminemail (`adminuser`, `adminemail`/*索引2*/
  )
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `imooc_shop`.`shop_admin` (adminuser, adminpass, adminemail, logintime, loginip, createtime)
VALUES ('admin', md5('admin'), '631752525@qq.com', unix_timestamp(), '192.168.1.1', unix_timestamp());

# 用户基本信息表
DROP TABLE IF EXISTS `shop_user`;
CREATE TABLE IF NOT EXISTS `shop_user` (
  `userid`     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
  COMMENT '主键ID',
  `username`   VARCHAR(32)     NOT NULL DEFAULT '',
  `userpass`   CHAR(32)        NOT NULL DEFAULT '',
  `useremail`  VARCHAR(100)    NOT NULL DEFAULT '',
  `createtime` INT UNSIGNED    NOT NULL DEFAULT '0',
  UNIQUE shop_user_username_userpass(`username`, `userpass`),
  UNIQUE shop_user_useremail_userpass(`useremail`, `userpass`),
  PRIMARY KEY (`userid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

# 用户详细信息表
DROP TABLE IF EXISTS `shop_profile`;
CREATE TABLE IF NOT EXISTS `shop_profile` (
  `id`         BIGINT UNSIGNED      NOT NULL AUTO_INCREMENT
  COMMENT '主键ID',
  `truename`   VARCHAR(32)          NOT NULL DEFAULT ''
  COMMENT '真实姓名',
  `age`        TINYINT UNSIGNED     NOT NULL DEFAULT ''
  COMMENT '年龄',
  `sex`        ENUM ('0', '1', '2') NOT NULL DEFAULT '0'
  COMMENT '性别',
  `birthday`   DATE                 NOT NULL DEFAULT '2016-01-02'
  COMMENT '生日',
  `nickname`   VARCHAR(32)          NOT NULL DEFAULT ''
  COMMENT '昵称',
  `company`    VARCHAR(100)         NOT NULL DEFAULT ''
  COMMENT '公司',
  `userid`     BIGINT UNSIGNED      NOT NULL DEFAULT '0'
  COMMENT '用户id',
  `createtime` INT UNSIGNED         NOT NULL DEFAULT '0'
  COMMENT '创建时间',
  PRIMARY KEY ('id'),
  UNIQUE shop_profile_userid(`userid`)
)
  ENGINE InnoDB
  DEFAULT CHARSET = utf8;

# 增加字段
ALTER TABLE `shop_user`
  ADD `openid` CHAR(32) NOT NULL DEFAULT '0'
COMMENT '开放平台';

DROP TABLE IF EXISTS `shop_category`;
CREATE TABLE IF NOT EXISTS `shop_category` (
  `cateid`     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`      VARCHAR(32)     NOT NULL DEFAULT '',
  `parentid`   BIGINT UNSIGNED NOT NULL DEFAULT '0',
  `createtime` INT UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`cateid`),
  KEY shop_category_parentid(`parentid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

#  商品列表
DROP TABLE IF EXISTS `shop_product`;
CREATE TABLE IF NOT EXISTS `shop_product` (
  `productid`  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cateid`     BIGINT UNSIGNED NOT NULL DEFAULT '0',
  `title`      VARCHAR(200)    NOT NULL DEFAULT '',
  `descr`      TEXT,
  `num`        INT UNSIGNED    NOT NULL DEFAULT '0',
  `price`      DECIMAL(10, 2)  NOT NULL DEFAULT '0.00',
  `cover`      VARCHAR(200)    NOT NULL DEFAULT '',
  `pics`       TEXT,
  `issale`     ENUM ('0', '1') NOT NULL DEFAULT '0',
  `saleprice`  DECIMAL(10, 2)  NOT NULL DEFAULT '0.00',
  `ishot`      ENUM ('0', '1') NOT NULL DEFAULT '0',
  `createtime` INT UNSIGNED    NOT NULL DEFAULT '0',
  `istui`      ENUM ('0', '1') NOT NULL DEFAULT '0',
  `ison`       ENUM ('0', '1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`productid`),
  KEY shop_product_cateid(`cateid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = 'utf8';

#  购物车
DROP TABLE IF EXISTS `shop_cart`;
CREATE TABLE IF NOT EXISTS `shop_cart` (
  `cartid`     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `productid`  BIGINT UNSIGNED NOT NULL DEFAULT '0',
  `productnum` INT UNSIGNED    NOT NULL DEFAULT '0',
  `price`      DECIMAL(10, 2)  NOT NULL DEFAULT '0.00',
  `userid`     BIGINT UNSIGNED NOT NULL DEFAULT '0',
  `createtime` INT UNSIGNED    NOT NULL DEFAULT '0',
  KEY shop_cart_productid(`productid`),
  KEY shop_cart_userid(`userid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = 'utf8';

# 订单表
DROP TABLE IF EXISTS `shop_order`;
CREATE TABLE IF NOT EXISTS `shop_order` (
  `orderid`    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userid`     BIGINT UNSIGNED NOT NULL DEFAULT '0',
  `addressid`  BIGINT UNSIGNED NOT NULL DEFAULT '0'
  COMMENT '收货地址ID',
  `amount`     DECIMAL(10, 2)  NOT NULL DEFAULT '0.00'
  COMMENT '订单总价',
  `status`     INT UNSIGNED    NOT NULL DEFAULT '0'
  COMMENT '订单状态',
  `expressid`  INT UNSIGNED    NOT NULL DEFAULT '0'
  COMMENT '快递单号',
  `expressno`  VARCHAR(50)     NOT NULL DEFAULT ''
  COMMENT '快递跟踪',
  `tradeno`    VARCHAR(100)    NOT NULL DEFAULT '',
  `tradeext`   TEXT,
  `createtime` INT UNSIGNED    NOT NULL DEFAULT '0',
  `updatetime` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  COMMENT '设置为更新表时自动更新时间',
  KEY shop_order_userid(`userid`),
  KEY shop_order_addressid(`addressid`),
  KEY shop_order_expressid(`expressid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = 'utf8';

# 为表追加注释
ALTER TABLE `shop_order`
  COMMENT = '存储订单信息';

# 为字段追加注释
ALTER TABLE `shop_order`
  MODIFY `addressid` BIGINT UNSIGNED NOT NULL DEFAULT '0'
  COMMENT '收货地址ID';

# 订单详情表
DROP TABLE IF EXISTS `shop_order_detail`;
CREATE TABLE IF NOT EXISTS `shop_order_detail` (
  `detailid`   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `productid`  BIGINT UNSIGNED NOT NULL DEFAULT '0',
  `price`      DECIMAL(10, 2)  NOT NULL DEFAULT '0.00',
  `productnum` INT UNSIGNED    NOT NULL DEFAULT '0',
  `orderid`    BIGINT UNSIGNED NOT NULL DEFAULT '0'
  COMMENT '关联orderid',
  `createtime` INT UNSIGNED    NOT NULL DEFAULT '0',
  KEY shop_order_detail_productid(`productid`),
  KEY shop_order_detail_orderid(`orderid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = 'utf8';

# 收货地址
DROP TABLE IF EXISTS `shop_address`;
CREATE TABLE IF NOT EXISTS `shop_address` (
  `addressid`  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `firstname`  VARCHAR(32)     NOT NULL DEFAULT '',
  `lastname`   VARCHAR(32)     NOT NULL DEFAULT '',
  `company`    VARCHAR(100)    NOT NULL DEFAULT ''
  COMMENT '公司名',
  `address`    TEXT,
  `postcode`   CHAR(6)         NOT NULL DEFAULT ''
  COMMENT '邮编',
  `email`      VARCHAR(100)    NOT NULL DEFAULT '',
  `telephone`  CHAR(20)        NOT NULL DEFAULT '',
  `userid`     BIGINT UNSIGNED NOT NULL DEFAULT '0',
  `createtime` INT UNSIGNED    NOT NULL DEFAULT '0',
  KEY shop_address_userid(`userid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = 'utf8';

# 修改字段名
ALTER TABLE `cms_menu`
  CHANGE f a VARCHAR(20) NOT NULL DEFAULT '';