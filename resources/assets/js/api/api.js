emojify.setConfig({img_dir : Config.cdnDomain + 'assets/images/emoji'});

emojify.run();

// 图片被点击后向通过 JavascriptBridge 向宿主应用发送图片 URL
function connectWebViewJavascriptBridge(callback) {
    if (window.WebViewJavascriptBridge) {
        callback(WebViewJavascriptBridge)
    } else {
        document.addEventListener('WebViewJavascriptBridgeReady', function () {
            callback(WebViewJavascriptBridge)
        }, false)
    }
}

connectWebViewJavascriptBridge(function (bridge) {
    bridge.init(function (message, responseCallback) {
        responseCallback(data)
    });

    var imgs = document.querySelectorAll('.markdown-content img');

    for (var i = 0; i < imgs.length; i++) {
        imgs[i].onclick = function (e) {
            var data = {"imageUrl": this.src};
            bridge.send(data, function (responseData) {

            })
        }
    }
});