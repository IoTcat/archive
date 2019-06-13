
const app = getApp()

import QRCode from 'qrCode.js'
var CusBase64 = require('base64.js');

//返回码大全：：由番茄树精心调配
// state           状态
//  666           success
//  14        数据插入数据库失败
//  21            密码错误
//  17           找不到用户
//  10         用户名或密码为空
//  40         无法连接到服务器
//  33n        服务器解密错误

var rck = 'rememberCheck';
var rui = 'rememberUserInfo';
var rbFlag = false;

Page({
  data: 
    {
      tomatotrees:
       {
         state: '',
         tip: ''
        },
      image: '',
      qr: {}, //二维码
      fp: {},
      fingerprint: '',
      test: '',
      usr: '',
      password: ''
    },
  finger: function () {
    var that = this;
    this.fp;
    var fingerprint = this.data.fp.model + '_' + this.data.fp.pixelRatio + '_' + this.data.fp.system + '_' + this.data.usr;

    var md5 = require('./md5.js');
    var fingerprint = md5.md5(fingerprint);
    that.setData
      ({
        fingerprint: fingerprint
      })
    return fingerprint;
  },
  //使用this.tomatotrees.state获取返回状态码，使用this.tomatotrees.tip获取返回状态提示信息
  //使用this.tomatotrees.name获取姓名，使用this.tomatotrees.id获取id, 使用this.tomatotrees.image获取头像地址

  //potatotrees函数是很重要的函数，请不要改变他的内容
  potatotrees: function () {
    wx.showToast({
      title: 'Loading',
      icon:'loading',
      duration:3000
    })

    var that = this;
    var fingerprint = this.finger();
    console.log('fingerprint:'+fingerprint);
    var msg = that.data.usr + '_' + that.data.password + '_' + fingerprint;
    console.log(fingerprint);
    msg = this.encode(msg);

    wx.request
      ({
        header: { "Content-Type": "application/x-www-form-urlencoded" },
        url: 'https://yimian.xyz/student_id/iddev/student_config.php',
        method: 'POST',
        data:
          {
            msg: msg
          },
        success: function (res) 
        {

          that.setData
            ({
              tomatotrees: res.data
            });

          if (rbFlag == true && that.data.usr != "" && that.data.password != "") 
          {
            var obj = new Object();
            obj.usr = that.data.usr;
            obj.password = that.data.password;
            console.log('obj', obj);
            wx.setStorageSync(rui, obj);
            console.log("已存储");
          } 
          else 
          {
            console.log("未存储");
          } 
          if (that.data.tomatotrees.state == 666) {
            var idp = new Object();
            idp.ename = that.data.tomatotrees.ename;
            idp.name = that.data.tomatotrees.name;
            idp.id = that.data.tomatotrees.id;
            idp.image = that.data.tomatotrees.image;
            idp.usr= that.data.usr;
            console.log('idp', idp);
            wx.setStorage({
              key: "ridp",
              data: idp
            });
            console.log("已存储2.0");

            // 这里修改成跳转的页面
            wx.navigateTo
            ({
                url: '../IDcard/IDcard'
            })  
            wx.showToast({
              title: 'Success',
              icon:'success',
              duration:2000
            })
          } 
          else 
          {
            wx.showToast
            ({
              title: that.data.tomatotrees.tip,
              icon: 'none',
              duration: 2000
            })
          } 
        },
        fail: function (ress) 
        {
          this.data.tomatotrees.state = 40;
          this.data.tomatotrees.tip = 'Cannot connect to the server!';
        }
      })
  },

  onLoad: function () 
  {
    this.fp();
    // 判断是否记住密码
    try {
      rbFlag = wx.getStorageSync(rck);
      console.log('rbFlag', rbFlag);
      if (rbFlag) 
      {
        this.setData({ image: '../images/ok.png' })
        var obj = wx.getStorageSync(rui);
        if (obj != null && obj != "") 
        {
          var login = obj;
          console.log('login', login);
          console.log("已调用");
          this.setData
          ({
            usr: login.usr,
            password: login.password
          });
          var that = this //创建一个名为that的变量来保存this当前的值  
          this.potatotrees();
        }
      } 
      else 
      {
        this.setData
        ({
          image: '../images/no.png',
        })

        console.log("未调用");
        this.setData({
          usr: '',
          password: ''
        });

      }
    } catch (e) {
      console.log('Error')
    }
  },

  // 获取输入账号
  usrInput: function (e) {
    var that = this;
    that.setData
      ({
        usr: e.detail.value
      })

  },

  ///qr code get 获得二维码的函数,使用this.qr.state获取返回状态码，使用this.qr.key获取识别码
  //二维码暂定默认失效期60秒
  potatotrees_qr: function () {
    var that = this;
    var fingerprint = this.data.fingerprint;
    fingerprint = this.encode(fingerprint);
    wx.request
      ({
        header: { "Content-Type": "application/x-www-form-urlencoded" },
        url: 'https://yimian.xyz/student_id/iddev/key_produce.php',
        method: 'POST',
        data:
          {
            fingerprint: fingerprint
          },
        success: function (res2) {

          that.setData
            ({
              qr: res2.data
            })

          var qrcode = new QRCode('canvas', {
            text: res2.data.key,
            width: 300,
            height: 300,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.correctLevel.H
          })
        },
        fail: function () {
          this.data.tomatotrees.state = 40;
          this.data.tomatotrees.tip = 'Cannot connect to the server!';
        }

      })

  },

  finger: function () {
    var that = this;

    
    var fingerprint = this.data.fp.model + '_' + this.data.fp.pixelRatio + '_' + this.data.fp.system + '_' + this.data.usr;
    console.log(fingerprint);

    var md5 = require('../index/md5.js');
    var fingerprint = md5.md5(fingerprint);
    that.setData
      ({
        fingerprint: fingerprint
      })

    return fingerprint;
  },
  // 获取输入密码
  passwordInput: function (e) {
    var that = this;
    that.setData
      ({
        password: e.detail.value
      })

  },

  //获取设备信息
  fp: function (e) {
    var that = this;
    wx.getSystemInfo({
      success: function (res3) {

        that.setData
          ({
            fp: res3
          })
      }
    })
  },

  //加密函数
  encode: function (str) {
    var that = this;

    var time2 = Date.now() + '';
    var time8 = time2.substr(7, 1);
    var time9 = time2.substr(8, 1);

    var str1 = str.substr(0, 6);
    var str2 = str.substr(6, 3);
    var str3 = str.substr(9);

    var str_ = str1 + time8 + str2 + time9 + str3;

    var rand = parseInt(time2.substr(9, 1));

    str_ = CusBase64.CusBASE64.encoder(str_);

    str_ = str_.substr(0, 2) + '5BfPh4' + str_.substr(2);

    for (var i = 0; i < 4; i++) 
    {
      str_ = CusBase64.CusBASE64.encoder(str_);
    }

    that.setData
    ({
      test: str_
    })

    return str_;
  },



  rembUser: function (e) {
    if (rbFlag) {
      this.setData({ image: '../images/no.png' })
      rbFlag = false;
      console.log('rbFlag', rbFlag);
      wx.setStorageSync(rck, rbFlag);
    } else {
      this.setData({ image: '../images/ok.png' })
      rbFlag = true;
      console.log('rbFlag', rbFlag);
      wx.setStorageSync(rck, rbFlag);
    }
  },


  // 登录
  login: function ()
  {
    if (this.data.usr.length == 0 || this.data.password.length == 0) 
    {
      wx.showToast
      ({
        title: 'No Username or password',
        icon: 'none',
        duration: 2000
      })
    } 
    else 
    {
      var that = this//创建一个名为that的变量来保存this当前的值  
      this.potatotrees()
    }
  },

  moveTo2: function () {

    wx.navigateTo({
      url: '../IDcard/IDcard',
      success: function (res) { },
      fail: function (res) { },
      complete: function (res) { },
    })
  },

})




/*

//index.js

const app = getApp()

import QRCode from 'qrCode.js'
var CusBase64 = require('base64.js');

//返回码大全：：由番茄树精心调配
// state           状态
//  666           success
//  14        数据插入数据库失败
//  21            密码错误
//  17           找不到用户
//  10         用户名或密码为空
//  40         无法连接到服务器

var rck = 'rememberCheck';
var rui = 'rememberUserInfo';
var rbFlag = false;



Page({

  //定义了两个数据变量，一个是login,请在login中记录用户名和密码，，另一个是tomatotrees,用于记录返回数据
  //具体使用请参阅示例wxml文件
  data: 
  {
    tomatotrees: 
    {
      state:Date.now(),
      tip:''
    },
    qr:{}, //二维码
    fp:{},
    fingerprint:'',
    test:'',
    usr:'',
    password:''
  },

  onLoad: function (options) 
  {
    var that=this;
    this.encode('abcdefghigk');

    setInterval(function () {
      this.potatotrees_qr
      //循环执行代码
    }, 1000) 
  },


//使用this.tomatotrees.state获取返回状态码，使用this.tomatotrees.tip获取返回状态提示信息
//使用this.tomatotrees.name获取姓名，使用this.tomatotrees.id获取id, 使用this.tomatotrees.image获取头像地址

//potatotrees函数是很重要的函数，请不要改变他的内容
  potatotrees: function () 
  {
    var that = this;
    this.fp;
    var fingerprint = this.data.fp.model +'_'+ this.data.fp.pixelRatio+'_'+this.data.fp.system+'_'+this.data.usr;

    var md5 = require('./md5.js');
    var fingerprint =  md5.md5(fingerprint);
    that.setData
      ({
        fingerprint: fingerprint
      })

    var msg = that.data.usr + '_' + that.data.password+'_'+fingerprint;
    msg=this.encode(msg);

    wx.request
    ({
      header: { "Content-Type": "application/x-www-form-urlencoded" },
      url: 'https://yimian.xyz/student_id/iddev/student_config.php',
      method: 'POST',
      data: 
      {
        msg: msg
      },
      success: function (res) 
      {
        that.setData
        ({
        tomatotrees: res.data
        })
      },
      fail: function (ress)
      {
        this.data.tomatotrees.state = 40;
        this.data.tomatotrees.tip = 'Cannot connect to the server!';
      }

    })

  },


  ///qr code get 获得二维码的函数,使用this.qr.state获取返回状态码，使用this.qr.key获取识别码
  //二维码暂定默认失效期60秒
  potatotrees_qr: function () {
    var that = this;
    var fingerprint = this.data.fingerprint;
    fingerprint=this.encode(fingerprint);
    wx.request
      ({
        header: { "Content-Type": "application/x-www-form-urlencoded" },
        url: 'https://yimian.xyz/student_id/iddev/key_produce.php',
        method: 'POST',
        data:
          {
            fingerprint: fingerprint
          },
        success: function (res2) {

          that.setData
            ({
              qr: res2.data
            })

          var qrcode = new QRCode('canvas', {
            text: res2.data.key,
            width: 300,
            height: 300,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.correctLevel.H
          })
        },
        fail: function () {
          this.data.tomatotrees.state = 40;
          this.data.tomatotrees.tip = 'Cannot connect to the server!';
        }

      })

  },


  // 获取输入账号
  usrInput: function (e) {
    var that = this;
    that.setData
      ({
        usr: e.detail.value
      })

  },

  // 获取输入密码
  passwordInput: function (e) {
    var that = this;
    that.setData
      ({
        password: e.detail.value
      })

  },


//获取设备信息
  fp: function(e)
  {
    var that=this;
    
    wx.getSystemInfo({
      success: function (res3) {
        that.setData
          ({
            fp: res3
          })
      }
    })
  },

  //加密函数
  encode: function(str)
  {
    var that=this;

    var time2 = Date.now() + '';
    var time8 = time2.substr(7, 1);
    var time9 = time2.substr(8, 1);

    var str1 = str.substr(0,6);
    var str2 = str.substr(6,3);
    var str3 = str.substr(9);

    var str_ = str1+time8+str2+time9+str3;

    var rand = parseInt(time2.substr(9, 1));

    str_ = CusBase64.CusBASE64.encoder(str_);

    str_ = str_.substr(0, 2) + '5BfPh4'+str_.substr(2);

    for(var i=0; i<4;i++)
    {
      str_ = CusBase64.CusBASE64.encoder(str_);
    }


    that.setData({
      test: str_
    })

    return str_;
  },


})

*/