define({ "api": [
  {
    "type": "GET",
    "url": "/car/getAllCarStyle",
    "title": "获取车辆车长信息以及车型done",
    "name": "getAllCarStyle",
    "group": "Car",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "length",
            "description": "<p>车长数组</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "type",
            "description": "<p>车型数组</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "length-type.name",
            "description": "<p>名称</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "length-type.type",
            "description": "<p>1=车型，2=车长</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "length-type.status",
            "description": "<p>0=正常，1=删除</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "length-type.over_metres_price",
            "description": "<p>超出起步公里费</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "length-type.weight_price",
            "description": "<p>计重费</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "length-type.init_kilometres",
            "description": "<p>起步公里数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "length-type.init_price",
            "description": "<p>车长-起步价</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Car.php",
    "groupTitle": "Car",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/car/getAllCarStyle"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/car/getOneCarStyle",
    "title": "获取单个车辆信息",
    "name": "getOneCarStyle",
    "group": "Car",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card_number",
            "description": "<p>车牌号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "length",
            "description": "<p>车型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>车长</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Car.php",
    "groupTitle": "Car",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/car/getOneCarStyle"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/comment/commentInfo",
    "title": "获取评论内容done",
    "name": "commentInfo",
    "group": "Comment",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>订单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>订单ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sp_id",
            "description": "<p>评论人ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sp_name",
            "description": "<p>评价人的姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "dr_id",
            "description": "<p>司机ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dr_name",
            "description": "<p>司机姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "post_time",
            "description": "<p>提交时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "limit_ship",
            "description": "<p>发货时效几星</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "attitude",
            "description": "<p>服务态度几星</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "satisfaction",
            "description": "<p>满意度 几星</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>评论文字</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>0=正常显示，1=不显示给司机</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Comment.php",
    "groupTitle": "Comment",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/comment/commentInfo"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/file/uploadImg",
    "title": "上传图片done",
    "name": "uploadImg",
    "group": "File",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Image",
            "optional": false,
            "field": "file",
            "description": "<p>上传的文件 最大5M 支持'jpg', 'gif', 'png', 'jpeg'</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>下载链接(绝对路径)</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/File.php",
    "groupTitle": "File",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/file/uploadImg"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/goods/detail",
    "title": "货源详情done",
    "name": "detail",
    "group": "Goods",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>货源ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "goods_name",
            "description": "<p>货品名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "weight",
            "description": "<p>重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_city",
            "description": "<p>起始地</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_city",
            "description": "<p>目的地</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_receive_name",
            "description": "<p>收货人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_phone",
            "description": "<p>收货人电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_address_name",
            "description": "<p>收货人地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_address_detail",
            "description": "<p>收货人地址详情</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_send_name",
            "description": "<p>寄件人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_phone",
            "description": "<p>寄件人电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_address_name",
            "description": "<p>寄件人地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_address_datail",
            "description": "<p>寄件人地址详情</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "usecar_time",
            "description": "<p>用车时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "send_time",
            "description": "<p>发货时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "arr_time",
            "description": "<p>到达时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "real_name",
            "description": "<p>车主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "policy_code",
            "description": "<p>保单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "is_pay",
            "description": "<p>是否支付1为已支付 0为未支付</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "is_receipt",
            "description": "<p>货物回单1-是-默认，2-否</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "system_price",
            "description": "<p>系统出价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "mind_price",
            "description": "<p>货主出价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "final_price",
            "description": "<p>总运费</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Goods.php",
    "groupTitle": "Goods",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/goods/detail"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/goods/enableQuoteList",
    "title": "司机接单（可以报价）列表（有路线展示司机附近的货源信息，没有展示附近货源信息）done",
    "name": "enableQuoteList",
    "group": "Goods",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "line_id",
            "description": "<p>路线ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>报价ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_city",
            "description": "<p>起始地</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_city",
            "description": "<p>目的地</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "goods_name",
            "description": "<p>货品名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "weight",
            "description": "<p>货品重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "system_price",
            "description": "<p>系统出价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sp_price",
            "description": "<p>货主出价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "usecar_time",
            "description": "<p>用车时间</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Goods.php",
    "groupTitle": "Goods",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/goods/enableQuoteList"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/goods/goodsList",
    "title": "货源列表（根据设定路线展示）done",
    "name": "goodsList",
    "group": "Goods",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "org_city",
            "description": "<p>出发地</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "dest_city",
            "description": "<p>目的地</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "car_style_length_id",
            "description": "<p>车长ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "car_style_type_id",
            "description": "<p>车型ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.id",
            "description": "<p>订单ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.org_city",
            "description": "<p>出发地</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.dest_city",
            "description": "<p>目的地</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.mind_price",
            "description": "<p>心理价格</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.system_price",
            "description": "<p>系统价格</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.goods_name",
            "description": "<p>货物名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.weight",
            "description": "<p>总重量（吨）</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Goods.php",
    "groupTitle": "Goods",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/goods/goodsList"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/index/getAdvertisement",
    "title": "首页轮播图done",
    "description": "<p>@apiName  getAdvertisement</p>",
    "group": "Index",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>轮播图.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "list.id",
            "description": "<p>id.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "list.position",
            "description": "<p>序号.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.url",
            "description": "<p>跳转链接.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.src",
            "description": "<p>图片.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "unreadMsg",
            "description": "<p>未读消息.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "GetIndexGetadvertisement",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/index/getAdvertisement"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/index/getArticle",
    "title": "获取文章内容done",
    "description": "<p>@apiName  getArticle</p>",
    "group": "Index",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>文章标识(司机端：关于我们-driver_about,提现服务说明-driver_withdrawa_description,推荐奖励说明-driver_recommend_reward,用户注册协议-driver_registration_protocol)</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>文章标题.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>文章内容.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>文章标识.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "GetIndexGetarticle",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/index/getArticle"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/apiCode",
    "title": "返回码说明(ok)",
    "description": "<p>技术支持：<a href=\"http://www.ruitukeji.com\" target=\"_blank\">睿途科技</a></p>",
    "name": "apiCode",
    "group": "Index",
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/apiCode"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/appConfig",
    "title": "应用配置参数done",
    "name": "appConfig",
    "group": "Index",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payWays",
            "description": "<p>付款方式 一维数组</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "xxx",
            "description": "<p>其他参数</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/appConfig"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/lastApk",
    "title": "获取最新apk下载地址(ok)",
    "name": "lastApk",
    "group": "Index",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>下载链接.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "versionNum",
            "description": "<p>真实版本号.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "version",
            "description": "<p>显示版本号.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/lastApk"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/index/sendCaptcha",
    "title": "发送验证码done",
    "name": "sendCaptcha",
    "group": "Index",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "opt",
            "description": "<p>验证码类型 reg=注册 restpwd=找回密码 login=登陆 bind=绑定手机号.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "codeId",
            "description": "<p>此为客户端系统当前时间截 除去前两位后经MD5 加密后字符串.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "validationId",
            "description": "<p>codeIdvalidationId(此为手机号除去第一位后字符串+（codeId再次除去前三位） 生成字符串后经MD5加密后字符串) 后端接收到此三个字符串后      也同样生成validationId 与接收到的validationId进行对比 如果一致则发送短信验证码，否则不发送。同时建议对 codeId 进行唯一性检验   另外，错误时不要返回错误内容，只返回errCode，此设计仅限获取短信验证码</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/index/sendCaptcha"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/message/detail",
    "title": "我的消息-详情done",
    "name": "detail",
    "group": "Message",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>消息ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>标题.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>内容.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "isRead",
            "description": "<p>是否阅读</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "pushTime",
            "description": "<p>推送时间.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Message.php",
    "groupTitle": "Message",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/message/detail"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/message/getUnRead",
    "title": "未读消息数量done",
    "name": "getUnRead",
    "group": "Message",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": true,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>列表.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.name",
            "description": "<p>名称.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "list.unread",
            "description": "<p>未读数量.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.icon_url",
            "description": "<p>图标链接.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.push_type",
            "description": "<p>推送类型.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.msg",
            "description": "<p>列表文案.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Message.php",
    "groupTitle": "Message",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/message/getUnRead"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/message",
    "title": "我的消息-列表done",
    "name": "index",
    "group": "Message",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "push_type",
            "defaultValue": "private",
            "description": "<p>消息类型. system=系统消息 private=私人消息</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "pageSize",
            "defaultValue": "20",
            "description": "<p>每页数据量.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>列表.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "list.id",
            "description": "<p>消息ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.type",
            "description": "<p>类型.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.title",
            "description": "<p>标题.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.summary",
            "description": "<p>摘要.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "list.isRead",
            "description": "<p>是否阅读</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.pushTime",
            "description": "<p>推送时间.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页数据量.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "dataTotal",
            "description": "<p>数据总数.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageTotal",
            "description": "<p>总页码数.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "unreadnum",
            "description": "<p>未读消息.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Message.php",
    "groupTitle": "Message",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/message"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/order/detail",
    "title": "订单详情done",
    "name": "detail",
    "group": "Order",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "order_id",
            "description": "<p>订单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>init 初始状态（未分发订单前）quote报价中（分发订单后）quoted已报价-未配送（装货中）distribute配送中（在配送-未拍照）发货中 photo 拍照完毕（订单已完成）pay_failed（支付失败）/pay_success（支付成功）comment（已评论）</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order_code",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "goods_name",
            "description": "<p>货品名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "weight",
            "description": "<p>重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_city",
            "description": "<p>起始地</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_city",
            "description": "<p>目的地</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_receive_name",
            "description": "<p>收货人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_phone",
            "description": "<p>收货人电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_address_name",
            "description": "<p>收货人地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "dest_address_detail",
            "description": "<p>收货人地址详情</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_send_name",
            "description": "<p>寄件人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_phone",
            "description": "<p>寄件人电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_address_name",
            "description": "<p>寄件人地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "org_address_datail",
            "description": "<p>寄件人地址详情</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "usecar_time",
            "description": "<p>用车时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "send_time",
            "description": "<p>发货时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "arr_time",
            "description": "<p>到达时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "real_name",
            "description": "<p>车主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "policy_code",
            "description": "<p>保单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "is_pay",
            "description": "<p>是否支付1为已支付 0为未支付</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "is_receipt",
            "description": "<p>货物回单1-是-默认，2-否</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "system_price",
            "description": "<p>系统出价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "mind_price",
            "description": "<p>货主出价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "final_price",
            "description": "<p>总运费</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Order.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/order/detail"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/order/listInfo",
    "title": "订单列表done",
    "name": "listInfo",
    "group": "Order",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>订单状态（all全部状态，quote报价中，quoted已报价，待发货 distribute配送中（在配送-未拍照）发货中 photo 拍照完毕（订单已完成）） success 已完成的，包含未评论</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "pageSize",
            "defaultValue": "20",
            "description": "<p>每页数据量.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>订单列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.order_id",
            "description": "<p>订单ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.org_address_name",
            "description": "<p>出发地名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.dest_address_name",
            "description": "<p>目的地名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.weight",
            "description": "<p>货物重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.goods_name",
            "description": "<p>货物名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.car_style_length",
            "description": "<p>车长</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.car_style_type",
            "description": "<p>车型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.final_price",
            "description": "<p>最终报价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.system_price",
            "description": "<p>系统价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.mind_price",
            "description": "<p>心理价位</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.usecar_time",
            "description": "<p>用车时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.status",
            "description": "<p>init             初始状态（未分发订单前）quote报价中（分发订单后）quoted已报价-未配送（装货中）distribute配送中（在配送-未拍照）发货中 photo 拍照完毕（订单已完成）pay_failed（支付失败）/pay_success（支付成功）comment（已评论）</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页数据量.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "dataTotal",
            "description": "<p>数据总数.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageTotal",
            "description": "<p>总页码数.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Order.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/order/listInfo"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/order/shipping",
    "title": "司机进行发货动作done",
    "name": "shipping",
    "group": "Order",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>order_id</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "org_address_maps",
            "description": "<p>出发地地址的坐标 如116.480881,39.989410</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Order.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/order/shipping"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/order/uploadCerPic",
    "title": "上传到货凭证done",
    "name": "uploadCerPic",
    "group": "Order",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "order_id",
            "description": "<p>order_id</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "img_url",
            "description": "<p>图片链接，多个用 | 分隔</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "dest_address_maps",
            "description": "<p>目的地址的坐标 如116.480881,39.989410</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order_id",
            "description": "<p>order_id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Order.php",
    "groupTitle": "Order",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/order/uploadCerPic"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/pay",
    "title": "我的钱包",
    "name": "index",
    "group": "Pay",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "balance",
            "description": "<p>账户余额</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "pre_month_total_order",
            "description": "<p>上月累计单数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "pre_month_total_money",
            "description": "<p>上月累计金额</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "cur_month_total_order",
            "description": "<p>本月累计单数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "month_total_money",
            "description": "<p>本月累计金额</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "year_total_money",
            "description": "<p>年累计金额</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "uninvoicing_singular_total_order",
            "description": "<p>累计未结账单数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "uninvoicing_singular_total_money",
            "description": "<p>累计未结金额数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "withdrawal_money",
            "description": "<p>可提现金额和余额一致</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bonus",
            "description": "<p>我的推荐奖励</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Pay.php",
    "groupTitle": "Pay",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/pay"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/pay/recharge",
    "title": "充值",
    "name": "recharge",
    "group": "Pay",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "real_amount",
            "description": "<p>充值金额</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "pay_way",
            "description": "<p>支付方式 1=支付宝，2=微信</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "pay_status",
            "description": "<p>支付状态 0=未支付，1=支付成功，2=支付失败</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "balance",
            "description": "<p>充值之前的金额</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "pay_info",
            "description": "<p>支付返回信息</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Pay.php",
    "groupTitle": "Pay",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/pay/recharge"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/pay/showCashRecord",
    "title": "提现记录done",
    "name": "showCashRecord",
    "group": "Pay",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "pageSize",
            "defaultValue": "20",
            "description": "<p>每页数据量.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>提现记录</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.withdrawal_amount",
            "description": "<p>提现金额</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.bank_name",
            "description": "<p>银行名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.payment_account",
            "description": "<p>收款账号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.create_at",
            "description": "<p>提交提现时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.status",
            "description": "<p>提现状态init=未处理，agree=后台同意，refuse=已拒绝，pay_success=银行返回成功，pay_fail=银行返回失败</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页数据量.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "dataTotal",
            "description": "<p>数据总数.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageTotal",
            "description": "<p>总页码数.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Pay.php",
    "groupTitle": "Pay",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/pay/showCashRecord"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/pay/showPayRecord",
    "title": "查看账单done",
    "name": "showPayRecord",
    "group": "Pay",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "pageSize",
            "defaultValue": "20",
            "description": "<p>每页数据量.</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "is_pay",
            "description": "<p>是否支付1为已支付 0为未支付</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>账单列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.order_id",
            "description": "<p>订单ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.real_name",
            "description": "<p>货主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.company_name",
            "description": "<p>货主公司名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.customer_type",
            "description": "<p>货主类型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.org_city",
            "description": "<p>发货地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.dest_city",
            "description": "<p>收货地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.final_price",
            "description": "<p>运价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.pay_time",
            "description": "<p>订单完成时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.usecar_time",
            "description": "<p>用车时间</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "list.is_pay",
            "description": "<p>是否支付1为已支付 0为未支付</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.status",
            "description": "<p>photo 拍照完毕（订单已完成） sucess(完成后的所有状态)pay_failed（支付失败）/pay_success（支付成功）comment（已评论）</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页数据量.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "dataTotal",
            "description": "<p>数据总数.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageTotal",
            "description": "<p>总页码数.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Pay.php",
    "groupTitle": "Pay",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/pay/showPayRecord"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/pay/withDraw",
    "title": "提现done",
    "name": "withDraw",
    "group": "Pay",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "withdrawal_amount",
            "description": "<p>提现金额</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "bank_name",
            "description": "<p>银行名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "payment_account",
            "description": "<p>收款账号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "account_name",
            "description": "<p>开户名</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Pay.php",
    "groupTitle": "Pay",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/pay/withDraw"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/quote/saveQuote",
    "title": "提交货源报价done",
    "name": "saveQuote",
    "group": "Quote",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "goods_id",
            "description": "<p>货源ID。</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "dr_price",
            "description": "<p>司机出价</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "is_receive",
            "description": "<p>是否立即下单 0表示不立即下单 1表示立即下单</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Quote.php",
    "groupTitle": "Quote",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/quote/saveQuote"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/recommend/showMyRecommList",
    "title": "显示我的推荐列表done",
    "name": "showMyRecommList",
    "group": "Recommend",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "pageSize",
            "defaultValue": "20",
            "description": "<p>每页数据量.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.avatar",
            "description": "<p>被推荐人头像</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.name",
            "description": "<p>被推荐人名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.bonus",
            "description": "<p>奖励金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页数据量.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "dataTotal",
            "description": "<p>数据总数.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "pageTotal",
            "description": "<p>总页码数.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Recommend.php",
    "groupTitle": "Recommend",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/recommend/showMyRecommList"
      }
    ]
  },
  {
    "type": "get",
    "url": "/test/test",
    "title": "测试",
    "name": "test",
    "group": "Test",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Users unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "firstname",
            "description": "<p>Firstname of the User.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "lastname",
            "description": "<p>Lastname of the User.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Car.php",
    "groupTitle": "Test",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/test/test"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/carAuth",
    "title": "车辆认证done",
    "name": "carAuth",
    "group": "User",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "car_type",
            "description": "<p>车型.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "car_style_type_id",
            "description": "<p>车型ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "car_length",
            "description": "<p>车长.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "car_style_length_id",
            "description": "<p>车长ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "weight",
            "description": "<p>载重.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "volume",
            "description": "<p>可载体积.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "card_number",
            "description": "<p>车牌号码.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "policy_pic",
            "description": "<p>保险单照片.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "policy_startline",
            "description": "<p>保单开始时间.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "policy_deadline",
            "description": "<p>保单截止时间.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "license_startline",
            "description": "<p>行驶证开始时间.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "license_deadline",
            "description": "<p>行驶证截止时间.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "operation_startline",
            "description": "<p>营运证开始日期.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "operation_deadline",
            "description": "<p>营运证截止日期.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "driving_startline",
            "description": "<p>驾驶证开始日期.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "driving_deadline",
            "description": "<p>驾驶证截止日期.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "index_pic",
            "description": "<p>车头和车牌号照片.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "vehicle_license_pic",
            "description": "<p>行驶证照片.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "driving_licence_pic",
            "description": "<p>驾驶证照片.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "operation_pic",
            "description": "<p>营运证照片.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/User/carAuth"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/changeWork",
    "title": "改变工作状态done",
    "name": "changeWork",
    "group": "User",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "online",
            "description": "<p>上班状态 0=上班，1=不上班</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/User/changeWork"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/driverAuth",
    "title": "司机认证done",
    "name": "driverAuth",
    "group": "User",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "logistics_type",
            "description": "<p>物流类型 1：同城物流 2：长途物流</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "real_name",
            "description": "<p>真实姓名.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "sex",
            "description": "<p>性别 1=男 2=女 0=未知.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "identity",
            "description": "<p>身份证号.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "hold_pic",
            "description": "<p>手持身份证照.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "front_pic",
            "description": "<p>身份证正面照.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "back_pic",
            "description": "<p>身份证反面照.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/User/driverAuth"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/user/getAuthInfo",
    "title": "获取认证信息done",
    "name": "getAuthInfo",
    "group": "User",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "real_name",
            "description": "<p>真实姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>手机号码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "identity",
            "description": "<p>身份证号</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sex",
            "description": "<p>性别 1=男 2=女 0=未知</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>常驻地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card_number",
            "description": "<p>车牌号码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "car_type",
            "description": "<p>车型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "car_length",
            "description": "<p>车长</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "weight",
            "description": "<p>载重</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "volume",
            "description": "<p>可载体积</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "policy_pic",
            "description": "<p>保险单照片</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "index_pic",
            "description": "<p>车头和车牌号照片</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "vehicle_license_pic",
            "description": "<p>行驶证照片</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "driving_licence_pic",
            "description": "<p>驾驶证照片</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "operation_pic",
            "description": "<p>营运证照片</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "policy_startline",
            "description": "<p>保单开始时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "policy_deadline",
            "description": "<p>保单截止时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "license_startline",
            "description": "<p>行驶证开始时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "license_deadline",
            "description": "<p>行驶证截止时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "operation_startline",
            "description": "<p>营运证开始日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "operation_deadline",
            "description": "<p>营运证截止日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "driving_startline",
            "description": "<p>驾驶证开始日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "driving_deadline",
            "description": "<p>驾驶证截止日期</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "logistics_type",
            "description": "<p>物流类型 1：同城物流 2：长途物流</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "hold_pic",
            "description": "<p>手持身份证照.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "front_pic",
            "description": "<p>身份证正面照.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "back_pic",
            "description": "<p>身份证反面照.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "car_style_type_id",
            "description": "<p>车型ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "car_style_length_id",
            "description": "<p>车长ID.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/user/getAuthInfo"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/user/info",
    "title": "获取用户信息done",
    "name": "info",
    "group": "User",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>id.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>绑定手机号.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sex",
            "description": "<p>性别 1=男 2=女 0=未知.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "avatar",
            "description": "<p>头像.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "real_name",
            "description": "<p>昵称.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "auth_status",
            "description": "<p>认证状态（init=未认证， check=认证中，pass=认证通过，refuse=认证失败，delete=后台删除）</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/user/info"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/User/isWork",
    "title": "获取工作状态done",
    "name": "isWork",
    "group": "User",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "online",
            "description": "<p>上班状态 0=上班，1=不上班</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/User/isWork"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/login",
    "title": "用户登录done",
    "name": "login",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "account",
            "description": "<p>账号/手机号/邮箱.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>加密的密码. 加密方式：MD5(&quot;RUITU&quot;+明文密码+&quot;KEJI&quot;).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "wxOpenid",
            "description": "<p>微信openid.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pushToken",
            "description": "<p>消息推送token.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<p>接口调用凭证.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "refreshToken",
            "description": "<p>刷新凭证.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "expireTime",
            "description": "<p>有效期.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "userId",
            "description": "<p>用户id.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/User/login"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/reg",
    "title": "用户注册done",
    "name": "reg",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "user_name",
            "description": "<p>手机号/用户名.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>加密的密码. 加密方式：MD5(&quot;RUITU&quot;+明文密码+&quot;KEJI&quot;)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "captcha",
            "description": "<p>验证码.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "recomm_code",
            "description": "<p>推荐码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pushToken",
            "description": "<p>推送使用的Token</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "userId",
            "description": "<p>用户id.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<p>接口调用凭证.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/User/reg"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/forget",
    "title": "重置密码done",
    "name": "resetPwd",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "account",
            "description": "<p>账号/手机号/邮箱.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "new_password",
            "description": "<p>加密的密码. 加密方式：MD5(&quot;RUITU&quot;+明文密码+&quot;KEJI&quot;).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "captcha",
            "description": "<p>验证码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/User/forget"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/updatePwd",
    "title": "修改密码done",
    "name": "updatePwd",
    "group": "User",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "old_password",
            "description": "<p>加密的密码. 加密方式：MD5(&quot;RUITU&quot;+明文密码+&quot;KEJI&quot;).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "new_password",
            "description": "<p>加密的密码. 加密方式：MD5(&quot;RUITU&quot;+明文密码+&quot;KEJI&quot;).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "repeat_password",
            "description": "<p>重复密码.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/User/updatePwd"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/user/uploadAvatar",
    "title": "上传并修改头像done",
    "name": "uploadAvatar",
    "group": "User",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Image",
            "optional": false,
            "field": "file",
            "description": "<p>上传的文件 最大5M 支持'jpg', 'gif', 'png', 'jpeg'</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "retType",
            "defaultValue": "json",
            "description": "<p>返回数据格式 默认=json  jsonp</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>下载链接(绝对路径)</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/user/uploadAvatar"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/linelist/delline",
    "title": "删除路线信息done",
    "name": "delline",
    "group": "linelist",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "drline_id",
            "description": "<p>路线ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Linelist.php",
    "groupTitle": "linelist",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/linelist/delline"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/linelist/setline",
    "title": "设置路线done",
    "name": "setline",
    "group": "linelist",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "authorization-token",
            "description": "<p>token.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "org_city",
            "description": "<p>路线的起始地省市区</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "dest_city",
            "description": "<p>路线的到达地省市区</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "drline_id",
            "description": "<p>路线ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Linelist.php",
    "groupTitle": "linelist",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/linelist/setline"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/linelist/showline",
    "title": "获取路线信息done",
    "name": "showline",
    "group": "linelist",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "list",
            "description": "<p>路线列表</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "list.drline_id",
            "description": "<p>路线ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.org_city",
            "description": "<p>路线的起始地省市区</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "list.dest_city",
            "description": "<p>路线的到达地省市区</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Linelist.php",
    "groupTitle": "linelist",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.zenmechi.cc/linelist/showline"
      }
    ]
  }
] });
