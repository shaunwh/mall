$jq(document).ready(function() {
    init();
    loginClickEvent();
    logoutClickEvent();
    $jq(document).ajaxComplete(function() {
        loginClickEvent();
        logoutClickEvent();
    });
});

/* Event when click login link */
function loginClickEvent() {
    $jq('.a-login-link').click(function() {
        $jq('#ajax-login-block').show();
        $jq('.ajax-load-img').show();
        $jq.ajax({
            url: $jq('#base-url').val() + "ajaxlogin/login/index/",
            type: "POST",
            data : { param : '' },
            dataType: "json",
            success: function(response) {
                $jq('.ajax-load-img').hide();
                $jq('.ajax-body-login').show(400);
                $jq('.ajax-body-login .page-title h1').html(response.title);
                $jq('.ajax-body-login .ajax-content').html(response.body);
            }
        });
    });
}

/* Event when click logout link */
function logoutClickEvent() {
    $jq('.a-logout-link').click(function() {
        $jq('#ajax-logout-block').show();
        $jq('.ajax-load-img').show();
        $jq.ajax({
            url: $jq('#base-url').val() + "ajaxlogin/login/logout/",
            type: "POST",
            data : {param : ''},
            dataType: "json",
            success: function(response) {
                if(response.error == false) {
                    closeLogoutForm();
                    window.location = window.location.href;
                } else {
                    $jq('.ajax-body-logout .page-title h1').html("Log out account");
                    $jq('.ajax-body-logout .ajax-content h1').hide();
                    $jq('.ajax-body-logout .ajax-content p.error-msg').html(response.message);
                }
            }
        });
    });
}


function ajaxLogIn() {
    $jq('.error-msg').hide();
    var check = $jq('#use-redirect').val();
    var dataForm = new VarienForm('ajax-login-form', true);
    if(dataForm.validator && dataForm.validator.validate()) {
        $jq('.ajax-img').show();
        $jq.ajax({
            url         : $jq('#base-url').val() + "ajaxlogin/login/loginPost/",
            type        : "POST",
            data        : {
                email   : $jq('#email-address').val(),
                pass    : $jq('#password').val()
            },
            dataType    : "json",
            success     : function(response) {
                if(response.error == false) {
                    if(check == "1") {
                        closeLoginForm();
                        window.location = $jq('#base-url').val() + "customer/account";
                    } else {
                        closeLoginForm();
                        window.location = window.location.href;
                    }
                } else {
                    $jq('.ajax-img').hide();
                    $jq('.error-msg').html(response.message).show(300);
                }
            }
        });
    }
}

function showRegisterForm() {
    $jq('.ajax-img-rg').show();
    $jq.ajax({
        url         : $jq('#base-url').val() + "ajaxlogin/register/index/",
        type        : "POST",
        dataType    : "json",
        success     : function(response) {
            $jq('.ajax-login-di').remove();
            $jq('.ajax-body-login .ajax-content').html(response.body);
        }
    });
}

function backToLogin() {
    $jq('.ajax-img').show();
    $jq.ajax({
        url: $jq('#base-url').val() + "ajaxlogin/login/index",
        type: "POST",
        dataType: "json",
        success: function(response) {
            $jq('.account-create').remove();
            $jq('.ajax-body-login .ajax-content').html(response.body);
        }
    });
}

function submitRegister() {
    var dataForm = new VarienForm('form-validate', true);
    if(dataForm.validator && dataForm.validator.validate()) {
        dataForm.submit();
    }
}


function init() {
    $jq('#ajax-login-block').hide();
    $jq('#ajax-logout-block').hide();
    $jq('.ajax-body-login').hide();
    $jq('.ajax-load-img').hide();
    $jq('.ajax-body-logout').hide();

}

function closeLogoutForm() {
    $jq('#ajax-logout-block').hide();
    $jq('.ajax-body-logout').hide();
    $jq('.ajax-load-img').hide();
}

function closeLoginForm() {
    $jq('#ajax-login-block').hide();
    $jq('.ajax-body-login').hide();
}