/*eslint-disable*/
window.$$ = {
    extend: function (n, o) {
        for (var i in o)
            o.hasOwnProperty(i) && (n[i] = o[i])
    }
};

var App = window.App = window.$$.platformAdapter = {},
    slice = Array.prototype.slice;

// 常量定义
var ua = navigator.userAgent.toUpperCase();
// 当前环境是否为Android平台
App.IS_ANDROID = ua.indexOf('ANDROID') != -1;
// 当前环境是否为IOS平台
App.IS_IOS = ua.indexOf('IPHONE OS') != -1;
// 当前环境是否为WP平台
App.IS_WP = ua.indexOf('WINDOWS') != -1 && ua.indexOf('PHONE') != -1;
// 当前环境是否为本地开发浏览器环境
App.IS_LOCAL = (location.hostname == 'localhost') || (ua.match(/MicroMessenger/i) == 'micromessenger');

var toString = Object.prototype.toString;

var count = 0,
    noop = function () {};

function errHandler(error) {
    switch (error.code) {
        case '001':
            App.call('tip', '网络异常，请更换网络环境并重试');
            break;
        case '002':
            App.call('tip', '没有连接网络');
            break;
        case '003':
            App.call('tip', '网络超时');
            break;
        case '004':
            App.call('tip', '服务器发生异常');
            break;
        default:
            App.call('tip', '未知错误');
            break;
    }
}

/**
 * utils工具集
 */
var Utils = App.utils = {
    _incrementId: 0,
    isPlainObj: function (obj) {
        return typeof (obj) == "object" && Object.prototype.toString.call(obj).toLowerCase() == "[object object]" && !obj.length;
    },
    isFn: function (fn) {
        return typeof fn === "function";
    },
    uniqId: function (prefix) {
        return prefix + this._incrementId++
    },
    noop: function () {},
    isStr: function (v) {
        return typeof v == "string";
    },
    extend: function (target, source) {
        for (var p in source) {
            if (source.hasOwnProperty(p)) {
                target[p] = source[p];
            }
        }

        return target;
    },
    detect: (function () {
        var ua = navigator.userAgent;
        return {
            isIOS: /ip(hone|ad|od)/i.test(ua),
            isAndroid: /Android\s+(\d+(\.\d+)*);/i.test(ua)
        }
    })()
};

// 调用一个Native方法
$$.extend(App, {

    // 回调方法集
    _handlers: {},
    /**
     * Replace all function with callback id in json structure
     * @fixme 应用层api方法存储起来的callback未能有良好的回收机制
     * @param {Array} args
     * @returns {Array} args all function are replaced
     * @private
     * @example
     * //一个更复杂的参数例子，会将其中的所有js function替换成callback id
        var callback_args = [
            function(){return "arr level 1"},
            "string",
            {
                name: "Shinvey",
                getName: function(){return this.name},
                arr: [1,2, function(){return "obj level 2"}]
            },
            1,
            1.23,
            true,
            [
                1,
                2,
                function(){return "arr level 2"},
                {
                    name: "Jary",
                    getName: function(){return this.name},
                    arr: [1,2, function(){return "obj level 3"}]
                }
            ],
            null,
            undefined
        ];
        callback_args = this._fnReplace(callback_args);
        console.dir(JSON.stringify(callback_args));
        //=> ["_cb0","string",{"name":"Shinvey","getName":"_cb1","arr":[1,2,"_cb2"]},1,1.23,true,[1,2,"_cb3",{"name":"Jary","getName":"_cb4","arr":[1,2,"_cb5"]}],null,null]
     */
    _fnReplace: function (args) {
        var _this = this,
            util = Utils,
            fnReplace = _this._fnReplace,
            testArrOrObj = function (el) {
                return Array.isArray(el) || util.isPlainObj(el);
            },
            iterator = function (el, idx, arr) {
                if (util.isFn(el)) {
                    arr[idx] = _this._addCallback(el);

                } else if (testArrOrObj(el)) {
                    return fnReplace.call(_this, el);
                }
            };

        if (testArrOrObj(args) == false) {
            return args;
        }

        for (var key in args) {
            if (args.hasOwnProperty(key)) iterator(args[key], key, args);
        }

        return args;
    },
    /**
     * 增加callback
     * @param {Function} arg
     * @returns {String} callback 回调函数id
     * @private
     */
    _addCallback: function (arg) {
        var callback,
            handlers;

        callback = Utils.uniqId("callback_");
        handlers = App._handlers;
        handlers[callback] = this.handlersErrorCallback(arg);

        return callback;
    },
    /**
     * 包装callback增加error处理逻辑
     * @param {Function} arg
     * @returns {Function}
     */
    handlersErrorCallback: function (arg) {
        return function (error) {
            if (!error) {
                console.log(arguments);
                arg.apply(window, slice.call(arguments, 1));
            } else {
                // TODO: 统一处理入口
                errHandler(error);
            }
        };
    },
    /**
     * 调用Native方法
     * @param method
     */
    call: function (method) {
        var self = this;
        if (App.IS_WP) {
            return;
        }

        var methodArr = method.split('.');
        if (methodArr.length !== 2) {
            return;
        }

        var pluginName = methodArr[0];
        var methodName = methodArr[1];

        var args = slice.call(arguments, 1),
            arg = null,
            callback = null,
            hasCallbackFlag = false,
            handlers = {};
        var handlersErrorCallback = this.handlersErrorCallback;

        // 参数内容处理。请注意，arg参数（字符串）安卓和IOS需要单独处理。iOS不需要加双引号，Android需要加。
        for (var i = 0, len = args.length; i < len; i++) {
            arg = args[i];
            arg = arg || '';
            // 函数型参数转换
            if (toString.call(arg) == '[object Function]') {
                callback = method + '_' + count++;
                handlers = App._handlers;
                //platformAdapter._handlers[callback] = arg;
                handlers[callback] = handlersErrorCallback(arg);
                arg = callback;
            } else if (toString.call(arg) == '[object Object]') {
                arg = JSON.stringify(self._fnReplace(arg));
            } else if (toString.call(arg) == '[object Array]') {
                arg = JSON.stringify(arg);
            }

            if (App.IS_ANDROID) {
                arg = arg.toString().replace(/\|/g, '||');
                arg = '"' + arg + '"';
            }
            args[i] = arg;
        }

        if (App.IS_ANDROID) {
            var getParameter = function (param) {
                var reg = new RegExp('[&,?]' + param + '=([^\\&]*)', 'i');
                var value = reg.exec(location.search);
                return value ? value[1] : '';
            };
            //if (methodList[method]) {
            //    method = methodList[method] + '.' + method;
            //}
            prompt('call://' + method + '(' + args.join('|') + ')');

        } else {
            //var businessClass = window[methodList[method]];
            var businessClass = window[pluginName];
            try {
                if (businessClass && businessClass[methodName]) {
                    businessClass[methodName].apply(businessClass, args);
                } else {
                    window.YDIOS[methodName].apply(window.YDIOS, args);
                }
            } catch (e) {
                // error
                console.error(method, "调用失败", e);
            }
        }
    },
    //method:callback_0({'age': '52555'})
    //"a({'age': '555'})"
    callback: function (method) {
        var args = slice.call(arguments, 1),
            handlers = App._handlers,
            callback = null;
        callback = handlers[method];
        if (callback && toString.call(callback) == '[object Function]') {
            callback.apply(window, args);
        }
    }
});

window.__callback = $$.platformAdapter.callback;
