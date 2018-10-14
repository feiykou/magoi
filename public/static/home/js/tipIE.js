$(function(){
    function getBroswerVersion() {
        var Sys = {};
        var ua = navigator.userAgent.toLowerCase();
        if (ua) {
            window.ActiveXObject ? Sys.version = "ie_"
                + ua.match(/msie ([\d]+)/)[1]
                : document.getBoxObjectFor ? Sys.version = "firefox_"
                + ua.match(/firefox\/([\d.]+)/)[1]
                : window.MessageEvent && !document.getBoxObjectFor ? Sys.version = "chrome"
                    : window.opera ? Sys.version = "opera_"
                        + ua.match(/opera.([\d.]+)/)[1]
                        : window.openDatabase ? Sys.version = ua
                            .match(/version\/([\d.]+)/)[1]
                            : 0;
        }
        return Sys;
    }

    function shakeCharacter(id) {
        var boxDom = document.getElementById(id);
        if (!boxDom)
            return;
        var text = boxDom.innerText || boxDom.textContent;
        var html = "";

        for (var i = 0; i < text.length; i++) {
            html += "<span>" + text.charAt(i) + "</span>";
        }
        boxDom.innerHTML = html;
        var arr = [];
        var spanDoms = boxDom.children;
        for (var i = 0; i < spanDoms.length; i++) {
            spanDoms[i].style.left = spanDoms[i].offsetLeft + "px";
            spanDoms[i].style.top = (spanDoms[i].offsetTop) + "px";
            arr.push({
                left : spanDoms[i].offsetLeft,
                top : spanDoms[i].offsetTop
            });
        }
        for (var i = 0; i < spanDoms.length; i++) {
            spanDoms[i].style.position = "absolute";
            spanDoms[i].style.left = "-120px";
        }

        function right() {
            var index = 1;
            var speed = 0;
            var timer = null;
            timer = setInterval(function() {
                if (index == (arr.length + 1)) {
                    clearInterval(timer);
                    index = 0;
                    left();
                    // down();
                } else {
                    speed = (arr[arr.length - index].left - spanDoms[arr.length
                        - index].offsetLeft) * 0.752;
                    speed = speed > 0 ? Math.ceil(speed) : Math.floor(speed);
                    spanDoms[arr.length - index].style.left = spanDoms[arr.length
                        - index].offsetLeft
                        + speed + "px";
                    if (speed == 0) {
                        index++;
                    }
                }
            }, 24);
        }

        function left() {
            var index = 0;
            var speed = 10;
            var timer = null;
            timer = setInterval(function() {
                if (index == arr.length) {
                    clearInterval(timer);
                    index = 1;
                    right();
                } else {
                    var icur = (spanDoms[index].offsetLeft - speed);
                    speed *= 1.015;
                    spanDoms[index].style.left = icur + "px";
                    if (icur <= 0) {
                        spanDoms[index].style.left = "-100px";
                        index++;
                    }
                }
            }, 30);
        }

        function down() {
            var index = 0;
            var speed = 10;
            var timer = null;
            timer = setInterval(function() {
                if (index == arr.length) {
                    clearInterval(timer);
                    index = 1;
                } else {
                    var icur = spanDoms[index].offsetTop - speed;
                    speed += 1;
                    spanDoms[index].style.top = icur + "px";
                    if (icur <= -1000) {
                        index++;
                    }
                }
            }, 30);
        }

        right();
    }

    //浏览器的拦截
    var v = getBroswerVersion().version;
    console.log(v);
    if(v=="ie_6" || v=="ie_7" || v=="ie_8"){
        $("body").append("<div id='tipnobox' style='background:#1F53A0;padding:20% 0px;text-align:center;font-size:36px;line-height:46px;font-weight:700;color:#fff;position:fixed;width:100%;height:100%;top:0;left:0;z-index:9999'>很是遗憾，作者关闭了IE6,7,8,为推动互联的发展做贡献....请下载ie9+浏览器,推荐使用google和火狐浏览器.</div>");
        shakeCharacter("tipnobox");
        return;
    };


});