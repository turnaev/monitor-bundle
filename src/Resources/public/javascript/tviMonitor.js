/*! Copyright (c) 2018 Vladimir Turnaev turnaev@gmail.com
 * Licensed under the MIT License (LICENSE.txt)
 */

function v(o) {
    console.log(o)
}
(function (factory) {
    if ( typeof define === 'function' && define.amd ) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS style for Browserify
        module.exports = factory;
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {

    "use strict";

    const DEFAULT_TIMEOUT = 3;
    const STATUS_TIMEOUT = 3;

    const STATUS_CODE_SUCCESS = 0;
    const STATUS_CODE_WARNING = 100;
    const STATUS_CODE_SKIP = 200;
    const STATUS_CODE_UNKNOWN = 300;
    const STATUS_CODE_FAILURE = 1000;
    const STATUS_UNKNOW = 'UNKNOW';

    var icons = {
        [STATUS_CODE_SUCCESS]: 'fas fa-xs fa-check-circle',
        [STATUS_CODE_WARNING]: 'fas fa-xs fa-exclamation-circle',
        [STATUS_CODE_SKIP]: 'fas fa-xs fa-ban',
        [STATUS_CODE_UNKNOWN]: 'far fa-xs fa-question-circle',
        [STATUS_CODE_FAILURE]: 'fas fa-xs fa-exclamation-triangle',
        [STATUS_UNKNOW]: 'far fa-xs fa-question-circlee'
    };

    var classes = {
        [STATUS_CODE_SUCCESS]: 'status-success',
        [STATUS_CODE_WARNING]: 'status-warning',
        [STATUS_CODE_SKIP]: 'status-skip',
        [STATUS_CODE_UNKNOWN]: 'status-unknown',
        [STATUS_CODE_FAILURE]: 'status-failure',
        [STATUS_UNKNOW]: 'status-unknown'
    };

    var methods  = {

        icon: function(statusCode) {
            return icons[statusCode] || icons[STATUS_UNKNOW]
        },

        class: function(statusCode) {
            return classes[statusCode] || classes[STATUS_UNKNOW]
        }
    };

    $.fn.tviMonitor = function () {

        var $checkResult = $(this);
        var $checks = $('.check', $checkResult);

        $checks.each(function() {

            var $check = $(this);

            var url = $check.data('url');
            var heardBeat = $check.data('heard-beat');

            var $status = $('.status', $check);
            var $statusName = $('.status-name', $status);
            var $statusCode = $('.status-code i', $status);

            var $message = $('.message', $check);
            var $data = $('.data', $check);

            var $refresh = $('.controll .refresh', $check);
            var $refreshStatus = $('.controll .refresh i', $check);

            var $refreshLock = $('.controll .refresh .lock', $check);
            var $refreshTime = $('.controll .refresh .time', $check);

            $refresh.attr('disabled', false);
            $refreshLock.attr('disabled', false);

            function setStatus(statusCode) {

                $refreshStatus.removeClass('fa-spin');

                $statusCode.removeAttr('style').hide().fadeIn();

                var statusIcon = methods.icon(statusCode);
                $statusCode.removeAttr('class').addClass(statusIcon);

                var statusClass = methods.class(statusCode);
                $check.removeAttr('class').addClass('check ' + statusClass)
            }

            function setData(data) {

                $message.text(data.message || '');
                $statusName.text(data.statusName || '');

                if(data.data == undefined) {
                    $data.text('');
                } else {
                    var data = data.data;

                    if (typeof data == 'object') {
                        data = JSON.stringify(data)
                    }
                    $data.text(data);
                }
            }

            $refreshTime.on('blur', function() {
                $refresh.trigger('click');

                if(!$refreshTime.val()) {
                    $refreshTime.val(DEFAULT_TIMEOUT)
                }
            });

            $refreshLock.on('change', function() {
                var isChecked = $refreshLock.prop('checked');
                
                if(isChecked) {
                    $refreshTime.attr('disabled', false);
                    $refreshTime.attr('hidden', false);

                    if(!$refreshTime.val()) {
                        $refreshTime.val(DEFAULT_TIMEOUT)
                    }
                } else {
                    $refreshTime.attr('hidden', true);
                    $refreshTime.attr('disabled', true);
                }
            });

            var timer = null;

            function refreshByTimer() {
                if(timer) {clearTimeout(timer);}

                if(heardBeat > 0) {
                    timer = setTimeout(function() {$refresh.trigger('click');}, 1000 * heardBeat);
                } else if($refreshLock.prop('checked') > 0) {
                    timer = setTimeout(function() {$refresh.trigger('click');}, 1000 * $refreshTime.val());
                }
            }

            $refresh.on('click', function(e) {
                var $target = $(e.target);

                if($target.is(':checkbox') || $target.is('.time')) {
                    if(!($target.is(':checkbox') && !$target.prop('checked'))) {
                        return;
                    }
                }

                $refreshStatus.addClass('fa-spin');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        setData(data);
                        setStatus(data.statusCode);
                        refreshByTimer();
                    },
                    error: function() {
                        setData({});
                        setStatus(STATUS_UNKNOW);
                        refreshByTimer();

                        console.log("error while loading ui checks: " +  url);
                    }
                });
            });

            $refresh.trigger('click')
        });

        // var $allControll = $('.head .controll', $checkResult);
        // var $allRefresh = $('.refresh', $allControll);
        //
        // $allRefresh.on('click', function(e) {
        //
        //     var isLock = $(e.target).is(':checkbox');
        //
        //     if(isLock) {
        //
        //         var isChecked = $(e.target).prop('checked');
        //
        //         $checks.each(function(e) {
        //
        //             var $check = $(this);
        //             var $lock = $('.refresh :checkbox:not(:disabled)', $check);
        //
        //             if($lock.size()) {
        //
        //                 $lock.prop('checked', isChecked).trigger('change');
        //
        //                 if(isChecked) {
        //                     var $refresh = $('.refresh', $check);
        //                     $refresh.click();
        //                 }
        //             }
        //         });
        //
        //     } else {
        //
        //         $checks.each(function(e) {
        //             var $check = $(this);
        //             var $lock = $('.refresh :checkbox:not(:disabled)', $check);
        //
        //             if($lock.length) {
        //                 var $refresh = $('.refresh', $check);
        //                 $refresh.click();
        //             }
        //         })
        //     }
        // });

        return this
    };
}));