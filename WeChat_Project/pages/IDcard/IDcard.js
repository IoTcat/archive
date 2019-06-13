



//index.js

const app = getApp()

import QRCode from '../index/qrCode.js'
var CusBase64 = require('../index/base64.js');

var shu;
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
      edata: {},
      qr: {}, //二维码
      fp: {},
      fingerprint: '',
      test: '',
      usr:'',
      password: ''
    },

  onLoad: function (options) {
    var that = this;
    this.fp();
    wx.getStorage({
      key: 'ridp',
      success: function (res) {
        console.log(res.data);
        var edata = res.data;
        that.setData
          ({
            edata : res.data
          })
        console.log('edata',edata);
        
        
      },
    })
    this.encode('abcdefghigk');
    console.log(options);

      this.potatotrees_qr();


    setTimeout(function () {
      that.potatotrees_qr();
      //要延时执行的代码
    }, 500) //延迟时间 这里是1秒
    setTimeout(function () {
      that.potatotrees_qr();
      //要延时执行的代码
    }, 50000) //延迟时间 这里是1秒



  },

  finger: function () {
    
    var that = this;
    wx.getStorage({
      key: 'ridp',
      success: function (res) {
        console.log(res.data);
        var edata = res.data;
        var usr = '';

        that.setData
          ({
            edata: res.data
          })
        console.log('edata', edata);
        usr = that.data.edata.usr;
        console.log('usr', usr);
        shu = res.data.usr;
        console.log('usr??????', shu);
      

    var fingerprint = that.data.fp.model + '_' + that.data.fp.pixelRatio + '_' + that.data.fp.system + '_' +shu;
    console.log(fingerprint);
    var md5 = require('../index/md5.js');
    var fingerprint = md5.md5(fingerprint);
        console.log(fingerprint);
    that.setData
      ({
        fingerprint: fingerprint
      })
    return fingerprint;
      },
    })

  },

   
  //使用this.tomatotrees.state获取返回状态码，使用this.tomatotrees.tip获取返回状态提示信息
  //使用this.tomatotrees.name获取姓名，使用this.tomatotrees.id获取id, 使用this.tomatotrees.image获取头像地址



  ///qr code get 获得二维码的函数,使用this.qr.state获取返回状态码，使用this.qr.key获取识别码
  //二维码暂定默认失效期60秒
  potatotrees_qr: function () {
    var that = this;
    this.finger();
    var fingerprint = this.data.fingerprint;
    console.log(fingerprint);
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
          console.log(res2.data.key);
          if (res2.data.key == 233) {
            setTimeout(function () {
              that.potatotrees_qr();
              //要延时执行的代码
            }, 500) //延迟时间 这里是1秒
            }

          var qrcode = new QRCode('canvas', {
            text: res2.data.key,
            width: 180,
            height: 180,
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





  //获取设备信息
  fp: function (e) 
  {
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

    for (var i = 0; i < 4; i++) {
      str_ = CusBase64.CusBASE64.encoder(str_);
    }


    that.setData({
      test: str_
    })

    return str_;
  },

//返回登录页清楚缓存
  moveTo1: function () {

    wx.navigateBack({
      delta: 1,
    })
   wx.clearStorageSync()
   wx.clearStorage()
   var edata = ''

  },

})


