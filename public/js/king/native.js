/**
 * native模块，common子模块
 * 和客户端通讯相关模块
 * @module native
 */
/*eslint-disable*/
// import "./adapter"

var App = window.App || {},
    defaultCallback = function () {};

var Native = {
    NUser: {
        getUserInfo: defaultCallback,
        getUserLoginInfo: defaultCallback,
    },
    NUI:{
        loading:defaultCallback,
        loadFinish:defaultCallback,
        tip:defaultCallback,
        setRightBtn:defaultCallback
    }
}

var __handle = {
    NUI: {
        loading: function (options) {
            options.loadingText = options.text;
            return true;
        },
        tip: function (options) {
            options.text = options.text || '';
            options.style = options.style || 'info';
            // 本地开发浏览器环境
            if (App.IS_LOCAL) {
                console.info('[Tip]'+ options.text);
                return false;
            }
            return true;
        }
    }
}

for (var _module in Native) {
    if (Native.hasOwnProperty(_module)) {
        for (var method in Native[_module]) {
            if (Native[_module].hasOwnProperty(method)) {
                Native[_module][method] = (function (module, method) {
                    return function (options) {
                        var result = true;
                        options = options || {};
                        if (__handle[module] && __handle[module][method] && Object.prototype.toString.call(__handle[module][method]) == '[object Function]') {
                            result = __handle[module][method].call(this, options);
                        }
                        if (result) {
                            App.call(module + '.' + method, options);
                        }
                    }
                })(_module, method);
            }
        }
    }
}
console.log(Native)
// module.exports = Native;
