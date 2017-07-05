define({ "api": [
  {
    "type": "GET",
    "url": "/index/home",
    "title": "01.首页(ok)",
    "description": "<p>@apiName  home</p>",
    "group": "Index",
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
            "type": "Array",
            "optional": false,
            "field": "banners",
            "description": "<p>轮播图.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.id",
            "description": "<p>id.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.seqNo",
            "description": "<p>序号.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "banners.link",
            "description": "<p>跳转链接.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "banners.img",
            "description": "<p>图片.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": true,
            "field": "banners.title",
            "description": "<p>标题.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "unreadMsg",
            "description": "<p>未读消息.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "unreadMsg.io",
            "description": "<p>询价单未读数量.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "unreadMsg.po",
            "description": "<p>采购单未读数量.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "unreadMsg.msg",
            "description": "<p>推送消息未读数量.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "unreadMsg.ask",
            "description": "<p>咨询消息未读数量.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "name": "GetIndexHome",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.ruitukeji.com/index/home"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/apiCode",
    "title": "00.返回码说明(ok)",
    "description": "<p>技术支持：<a href=\"http://www.ruitukeji.com\" target=\"_blank\">睿途科技</a></p>",
    "name": "apiCode",
    "group": "Index",
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.ruitukeji.com/apiCode"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/appConfig",
    "title": "01.应用配置参数(OK)",
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
        "url": "http://wztx.drv.api.ruitukeji.com/appConfig"
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
        "url": "http://wztx.drv.api.ruitukeji.com/lastApk"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/index/sendCaptcha",
    "title": "02.发送验证码(ok)",
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
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Index.php",
    "groupTitle": "Index",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.ruitukeji.com/index/sendCaptcha"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/message",
    "title": "01.我的消息-列表(ok)",
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
            "field": "type",
            "defaultValue": "private",
            "description": "<p>消息类型. all=全部  system=系统消息 private=私人消息</p>"
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
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/Message.php",
    "groupTitle": "Message",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.ruitukeji.com/message"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/message/:id",
    "title": "02.我的消息-详情(ok)",
    "name": "read",
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
        "url": "http://wztx.drv.api.ruitukeji.com/message/:id"
      }
    ]
  },
  {
    "type": "GET",
    "url": "/user/info",
    "title": "04.获取用户信息(ok)",
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
            "field": "bindMobile",
            "description": "<p>绑定手机号.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bindEmail",
            "description": "<p>绑定邮箱.</p>"
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
            "field": "nickName",
            "description": "<p>昵称.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>供应商编号.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>供应商名称.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "typeCode",
            "description": "<p>主分类编码.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "typeName",
            "description": "<p>主分类名称.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "taxCode",
            "description": "<p>税号.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "foundDate",
            "description": "<p>成立日期.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "taxRate",
            "description": "<p>税率.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "mobile",
            "description": "<p>电话.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>手机.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>邮箱.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "fax",
            "description": "<p>传真.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "ctcName",
            "description": "<p>联系人.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>地址.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payWay",
            "description": "<p>付款方式.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "comName",
            "description": "<p>企业名称.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "purchCode",
            "description": "<p>采购员工号.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "purchName",
            "description": "<p>采购员工姓名.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "purchType",
            "description": "<p>供应商采购属性.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "checkType",
            "description": "<p>检验类型.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "checkRate",
            "description": "<p>抽检比例.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "arvRate",
            "description": "<p>到货率.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "passRate",
            "description": "<p>合格率.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "creditLevel",
            "description": "<p>信用等级.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.ruitukeji.com/user/info"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/login",
    "title": "02.用户登录(ok)",
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
        "url": "http://wztx.drv.api.ruitukeji.com/User/login"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/User/resetPwd",
    "title": "03.重置密码(toto)",
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
            "field": "password",
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
        "url": "http://wztx.drv.api.ruitukeji.com/User/resetPwd"
      }
    ]
  },
  {
    "type": "PUT",
    "url": "/user/updateInfo",
    "title": "06.更新用户信息(ok)",
    "name": "updateInfo",
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
            "optional": true,
            "field": "sex",
            "description": "<p>性别 1=男 2=女 0=未知.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "avatar",
            "description": "<p>头像.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "nickName",
            "description": "<p>昵称.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "payWay",
            "description": "<p>付款方式.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/api/controller/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://wztx.drv.api.ruitukeji.com/user/updateInfo"
      }
    ]
  },
  {
    "type": "POST",
    "url": "/user/uploadAvatar",
    "title": "05.上传并修改头像(ok)",
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
        "url": "http://wztx.drv.api.ruitukeji.com/user/uploadAvatar"
      }
    ]
  }
] });
