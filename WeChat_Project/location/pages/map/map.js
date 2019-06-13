//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
      latitude: 23.232711,
      longitude: 121.475754,
      scale: '0',
    markers: [{
      iconPath: "/img/Marker.svg",
      id: 0,
      latitude: 23.099994,
      longitude: 121.324520,
      width: 50,
      height: 50,
      callout: { content: "Xintiandi \n No. 230 Madang Road\n Luwan District\n Shanghai", fontSize: 15, color: "#ff0000", padding: 10 }
    }]
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  
  onLoad: function () {
    var that = this;

    wx.getLocation({
      //type: 'wgs84',
      type: 'gcj02',
      success: function (res) {
        that.data.latitude = res.latitude
        that.data.markers[0].latitude = res.latitude;
        that.data.markers[0].longitude = res.longitude;
        that.data.longitude = res.longitude;
        console.log(res.latitude);
        console.log(res.longitude);
        console.log(that.data.latitude);
        console.log(that.data.markers[0].latitude);

      that.onLoad();
        //var speed = res.speed
        //var accuracy = res.accuracy
      }
    })

    /*
    const endpoint = 'https://yimian.xyz';

    wx.request({
      url: endpoint,
      header: { 'content-type': 'application/json' },
      success: function (res) {
        console.log('success!' + res.statusCode);
        console.log(res)
      },
      fail: function (res) {

      },
      complete: function (res) {

      }
    })

    */
  },

})
