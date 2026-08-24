// js/vendor/u2f-api-polyfill.js

AJAX.registerOnload("u2f.js", function () {
    var $inputReg = $("#u2f_registration_response");
    if ($inputReg.length > 0) {
        var $formReg = $inputReg.parents("form");
        $formReg.find("input[type=submit]").hide();
        setTimeout(function () {
            var request = JSON.parse($inputReg.attr("data-request"));

            if (!(window.u2f && typeof window.u2f.register === "function")) {
                return;
            }

            u2f.register(
                request.appId,
                [request],
                JSON.parse($inputReg.attr("data-signatures")),
                function (data) {
                    if (data.errorCode && data.errorCode !== 0) {
                        switch (data.errorCode) {
                            case 5:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FTimeout,
                                    false,
                                    "error",
                                );
                                break;
                            case 4:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FErrorRegister,
                                    false,
                                    "error",
                                );
                                break;
                            case 3:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FInvalidClient,
                                    false,
                                    "error",
                                );
                                break;
                            case 2:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FBadRequest,
                                    false,
                                    "error",
                                );
                                break;
                            default:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FUnknown,
                                    false,
                                    "error",
                                );
                                break;
                        }
                        return;
                    }
                    $inputReg.val(JSON.stringify(data));
                    $formReg.trigger("submit");
                },
            );
        }, 1000);
    }
    var $inputAuth = $("#u2f_authentication_response");
    if ($inputAuth.length > 0) {
        var $formAuth = $inputAuth.parents("form");
        $formAuth.find("input[type=submit]").hide();
        setTimeout(function () {
            var request = JSON.parse($inputAuth.attr("data-request"));

            if (!(window.u2f && typeof window.u2f.sign === "function")) {
                return;
            }

            u2f.sign(
                request[0].appId,
                request[0].challenge,
                request,
                function (data) {
                    if (data.errorCode && data.errorCode !== 0) {
                        switch (data.errorCode) {
                            case 5:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FTimeout,
                                    false,
                                    "error",
                                );
                                break;
                            case 4:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FErrorAuthenticate,
                                    false,
                                    "error",
                                );
                                break;
                            case 3:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FInvalidClient,
                                    false,
                                    "error",
                                );
                                break;
                            case 2:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FBadRequest,
                                    false,
                                    "error",
                                );
                                break;
                            default:
                                Functions.ajaxShowMessage(
                                    Messages.strU2FUnknown,
                                    false,
                                    "error",
                                );
                                break;
                        }
                        return;
                    }
                    $inputAuth.val(JSON.stringify(data));
                    $formAuth.trigger("submit");
                },
            );
        }, 1000);
    }
});
