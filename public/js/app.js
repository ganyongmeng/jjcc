/* page loading being */
var menuV = new Vue({
    el: '.el-aside',
    data:{
        unique: true,
    },
    methods: {
        openPage: function(pageName){
            window.localStorage.removeItem('searchForm');
            window.location.href = pageName;
        }
    }
});
var navbarApp = new Vue({
    el: '.navbar',
    data:{
        upPwdFormVisible: false,
        upPwdForm: {old_passwd:'',passwd1:'',passwd2:''},
        validate_rules: {
            old_passwd: [{required:true,message:'必须输入', trigger: 'blur'},{min:6,max:20,message:'密码必须是6-20位', trigger: 'blur'}],
            passwd1: [{required:true,message:'必须输入', trigger: 'blur'},{min:6,max:20,message:'密码必须是6-20位', trigger: 'blur'}],
            passwd2: [{ validator: function(rule, value, callback){
                if (value == ''){
                    callback(new Error('必须输入'));
                }else if (value !== navbarApp.upPwdForm.passwd1) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            }}],
        }
    },
    methods: {
        handleCommand: function(cmd){
            switch(parseInt(cmd)){
                case 1: 
                    if (this.$refs['upPwdForm']!==undefined){
                        this.$refs['upPwdForm'].resetFields();
                    }
                    this.upPwdFormVisible=true;
                    this.upPwdForm = {old_passwd:'',passwd1:'',passwd2:''};
                    break;
                case 2: this.logout(); break;
            }
        },
        updatePwd: function(){
            var self = this;
            this.$refs['upPwdForm'].validate(function(valid){
                if(valid){
                    httpPost('/user/passwd/change',self.upPwdForm,function(data){
                        if (data.code === 200){
                            self.upPwdFormVisible = false;
                            self.$message.success('修改密码成功');
                        }else{
                            self.$message.error(data.msg);
                        }
                    });
                }
            });
        },
        logout: function(){
            httpGet('/admin/logout',{},function(data){
                if (data.code===200){
                  window.location.href = '/admin/login';
                }
            });
        }
    }
});
/* page loading end */

/* config data */
var cities = ['广州','佛山','上海','南宁','东莞','北京','深圳','成都','重庆','厦门','武汉','杭州','天津','合肥'];
var category = ['足球','网球','篮球','羽毛球'];
var payTypes = ['支付宝','与场馆老板结算(老会员)','对公转账','银行卡','优惠券支付','会员卡支付','微信(APP)',
    '微信公众号支付','微信小程序','现金支付','与外部公司结算','与场馆老板结算(散客)','与总部内部核算','现场支付','一卡通','礼品卡']

function httpPost(url,data,callback){
    $.ajax({
        url: url,
        data: data,
        type: 'POST',
        headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        cache: false,
        dataType: 'json',
        success: function(data){
            if (data.code==302){
                menuV.$message({
                    message: data.msg,
                    type: 'error',
                    onClose: function(){
                        window.location.href = '/admin/login';
                    }
                });
            }else{
                callback(data);
            }
        },
        error: function(data){
            menuV.$message({
                message: '登录失效，请重新登陆',
                type: 'error',
                onClose: function(){
                    window.location.href = '/admin/login';
                }
            });
        }
    });
}

function httpGet(url,data,callback){
    $.ajax({
        url: url,
        data: data,
        type: 'GET',
        cache: false,
        headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: 'json',
        success: function(data){
            if (data.code==302){
                menuV.$message({
                    message: data.msg,
                    type: 'error',
                    onClose: function(){
                        window.location.href = '/admin/login';
                    }
                });
            }else{
                callback(data);
            }
        },
        error: function(data){
            menuV.$message({
                message: '登录失效，请重新登陆',
                type: 'error',
                onClose: function(){
                    window.location.href = '/admin/login';
                }
            });
        }
    });
}
function httpDwonload2(url, param) {
    var link = url+'?_=1';
    for (var key in param) {
        if (param[key]==null || param[key]==''){
            continue;
        }
        if (typeof(param[key])=='object'){
            for(var k in param[key]){
                link += '&'+key+'['+k+']='+ param[key][k];
            }
        }else{
             link += '&'+key+'='+ param[key];
        }
    }
    window.location.href = link;
}
function httpDwonload(url, param,  fileName, cb, errcb) {
    var xhr = new XMLHttpRequest();
    var form = new FormData();

    for (var key in param) {
        if (typeof(param[key])=='object'){
            for(var k in param[key]){
                form.append(key+'['+k+']', param[key][k]);
            }
        }else{
            form.append(key, param[key]);
        }
    }

    xhr.open('POST', url, true);
    xhr.responseType = "arraybuffer";
    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    xhr.onreadystatechange = function(e) {
        if (xhr.readyState == 4) {
            if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
                var blob = new Blob([this.response], {type: 'application/vnd.ms-excel'});
                fileName += '.csv';

                if (window.navigator.msSaveOrOpenBlob) {
                    navigator.msSaveBlob(blob, fileName);
                } else {
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = fileName;
                    link.click();
                    window.URL.revokeObjectURL(link.href);
                }

                typeof cb === 'function' && cb();
            } else {
                typeof errcb === 'function' && errcb();
            }

        }
    }
    xhr.send(form);
}

function getUrlParam(name) {  
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象  
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数  
    if (r != null) return decodeURIComponent(r[2]); return null; //返回参数值  
}

function isEmpty(str){
    if ( str==undefined || str==null || str==''){
        return true;
    }else{
        return false;
    }
}

function dateToString(date){
    if (typeof(date)=='object' && date!=null){
         var month=date.getMonth()+1;
         month =(month<10 ? "0"+month:month); 
         var day = date.getDate();
         day =(day<10 ? "0"+day:day); 
        return date.getFullYear()+'-'+month+'-'+day;
    }else{
        return date;
    }
}

/*
 * 信息提示弹窗。originMsg 可以传数组。
 */
function alertMessages(self, type, title, originMsg) {
    const h = self.$createElement;
    if (jQuery.isArray(originMsg)) {
        var msg = [];
        for (var i=0; i<originMsg.length; i++){
            msg.push(h('p',null,originMsg[i]));
        }
    }else {
        var msg = originMsg;
    }
    self.$alert(h('p', null, msg), title, { type:type, confirmButtonText: '确定'});
}
