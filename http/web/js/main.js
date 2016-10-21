/**
 * Created by bodrik on 08.05.16.
 */

$(function() {
    var fixedValues = {
        'quarterlyPlan': '',
        'monthlyPlan': '',
        'userId': 0
    }
    $.material.init();

    $('a.lockable').click(function(e) {
        if ($(this).attr('disabled')) {
            e.preventDefault();
            return false;
        }
    });

    $(".month-picker").datepicker({
        changeMonth: true,
        changeYear: true,
        changeDay: false,
        showButtonPanel: true,
        dateFormat: 'yy-mm-01',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('yy-mm-01', new Date(year, month, 1)));
        }
    });

    $(".month-picker").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "left top",
            at: "left bottom",
            of: $(this)
        });
    });
    //$(".datepicker").datepicker("option", "dateFormat", 'yy-mm-dd');

    //$('.price-control').number(false, 2, '.', ' ');

    $('[data-targetframe]').on('click', function() {
        if ($(this).attr('disabled')) {
            return false;
        }
        $('[data-frame]').addClass('hide');
        $('[data-frame="' + $(this).data('targetframe') + '"]').removeClass('hide');
        $("body, html").animate({
            scrollTop: 0
        }, 800);
    });

    $('[data-select1], [data-select2]').on('dblclick', function() {
        var target;
        var source = $(this);
        if ($(this).data('select1') ) {
            target = $('[data-select2="' + $(this).data('select1') + '"]');
        } else {
            target = $('[data-select1="' + $(this).data('select2') + '"]');
        }
        console.log(target);
        target.append(source.find('option:selected'));
        source.find('option:selected').remove();
    });

    $('form').submit(function() {
        $(this).find('[data-select1] option').each(function(index, obj) {
            $(obj).prop('selected', true);
        });
        if ($(this).find('[name="password"]').size()) {
            if ($(this).find('[name="password"]').val() != $(this).find('[name="repeatPassword"]').val()) {
                alert('Не удалось сохранить данные пользователя. Введённые пароли не совпадают.');
                return false;
            }
        }
    });

    $('[data-targetframe="frame1"]').click(function(){
        refreshFormEditGroup($('#select-groupId').find('option:selected').val());
    });

    $('[data-targetframe="editUser"]').click(function(){
        refreshFormEditUser($('#select-personId').find('option:selected').val());
    });

    $('[name="membersGroup[]"]').click(function() {
        var userId = Number($(this).val());
        fixedValues['userId'] = userId;
        if (/^\d+$/ig.test(userId)) {
            var frame = $('[data-frame="frame1"]');
            frame.find('[name="monthlyPlan"]').prop("disabled", false);
            frame.find('[name="quarterlyPlan"]').prop("disabled", false);
            $.ajax({
                url: "?r=admin%2Fget-seller-plan-json",
                method: "POST",
                data: {
                    _csrf: _csrf,
                    personId: userId,
                    groupId: $('input[name="groupId"]').val()
                },
                dataType: "json",
                success: function (data) {
                    var frame = $('[data-frame="frame1"]');
                    frame.find('[name="monthlyPlan"]').val('');
                    frame.find('[name="quarterlyPlan"]').val('');
                    if ($.isEmptyObject(data)) {
                        return true;
                    }
                    // Заполняем форму
                    fixedValues['userId'] = data[0].idPerson;
                    fixedValues['monthlyPlan'] = data[0].monthly;
                    fixedValues['quarterlyPlan'] = data[0].quarterly;
                    frame.find('[name="monthlyPlan"]').val(fixedValues['monthlyPlan']);
                    frame.find('[name="quarterlyPlan"]').val(fixedValues['quarterlyPlan']);
/*                    frame.find('[name="nameUser"]').val(data[0].fioPerson);
                    frame.find('select[name="accessType"] option').each(function () {
                        if ($(this).val() == data[0].accessType) {
                            $(this).prop('selected', true);
                        }
                    });*/
                }
            });
        }
    });

    $('[name="monthlyPlan"]').on('change, keyup', function() {
        verifyPlan(fixedValues['monthlyPlan'], this);
    });

    $('[name="quarterlyPlan"]').on('change, keyup', function() {
        verifyPlan(fixedValues['quarterlyPlan'], this);
    });

    $('body').delegate('#btn-save-plan', 'click', function() {
        var frame = $('[data-frame="frame1"]');
        var monthly = frame.find('[name="monthlyPlan"]').val();
        var quarterly = frame.find('[name="quarterlyPlan"]').val();
        savePlan(fixedValues['userId'], monthly, quarterly);
    });

    $('body').delegate('#btn-revert-plan', 'click', function() {
        revertPlan(fixedValues['userId'], $('input[name="groupId"]').val());
    });

    function verifyPlan(strComparePlan, obj)
    {
        if (strComparePlan == $(obj).val()) {
            $('input.lockable, .lockable input, .lockable button, .lockable select, a.lockable').removeAttr("disabled");
            hideAlert();
        } else {
            $('input.lockable, .lockable input, .lockable button, .lockable select, a.lockable').attr('disabled', 'true');
            var content = 'Для того, чтобы сохранить изменения планов и перейти к редактированию группы, нажмите кнопку: ' +
                '<input type="button" id="btn-save-plan" class="btn btn-link btn-sm" value="Сохранить"/>' +
                '<br/>Для того, чтобы вернуть исходные значения планов и перейти к редактированию группы, нажмите кнопку: ' +
                '<input type="button" id="btn-revert-plan" class="btn btn-link btn-sm" value="Отмена"/>';
            showAlert('Сохранение изменений планов', content, 'alert-info');
        }
    }

    function revertPlan()
    {
        var frame = $('[data-frame="frame1"]');
        frame.find('[name="monthlyPlan"]').val(fixedValues['monthlyPlan']);
        frame.find('[name="quarterlyPlan"]').val(fixedValues['quarterlyPlan']);
        $('input.lockable, .lockable input, .lockable button, .lockable select, a.lockable').removeAttr("disabled");
        hideAlert();
    }

    function savePlan(userId, monthly, quarterly)
    {
        $.ajax({
            url: "?r=admin%2Fsave-plan-json",
            method: "POST",
            data: {
                _csrf: _csrf,
                personId: userId,
                groupId: $('input[name="groupId"]').val(),
                monthlyValue: monthly,
                quarterlyValue: quarterly
            },
            dataType: "json",
            success: function (data) {
                if (!data.code) {
                    showAlert('Статус действия', 'Обновление планов произошло успешно', 'alert-success');
                } else {
                    showAlert('Статус действия', 'Упс... что-то пошло не так', 'alert-danger');
                }
                setTimeout(function() {
                    hideAlert();
                }, 3000);
            }
        });
    }

    function showAlert(title, content, style)
    {
        $('#main-alert').removeClass('alert-info');
        $('#main-alert').removeClass('alert-success');
        $('#main-alert').removeClass('alert-danger');
        style = style || 'alert-info';
        $('#main-alert').addClass(style);
        $('#alert-title').html(title);
        $('#alert-content').html(content);
        $('#main-alert').addClass('show');
    }

    function hideAlert()
    {
        $('#main-alert').removeClass('show');
    }

    function refreshFormEditUser(userId)
    {
        $.ajax({
            url: "?r=admin%2Fget-user-json",
            method: "POST",
            data: {
                _csrf: _csrf,
                userId: userId
            },
            dataType: "json",
            success: function (data) {
                // Заполняем форму
                var frame = $('[data-frame="editUser"]');
                frame.find('[name="userId"]').val(data[0].idPerson);
                frame.find('[name="nameUser"]').val(data[0].fioPerson);
                frame.find('select[name="accessType"] option').each(function(){
                    if ($(this).val() == data[0].accessType) {
                        $(this).prop('selected', true);
                    }
                });
            }
        });
    }

    function refreshFormEditGroup(groupId)
    {
        $.ajax({
            url: "?r=admin%2Fget-group-json",
            method: "POST",
            data: {
                _csrf : _csrf,
                groupId : groupId
            },
            dataType: "json",
            success: function(data) {
                console.log(data);
                var groupTypes = {
                    'seller': 'Продавцы',
                    'KAM': 'КАМ'
                }
                // Заполняем форму
                var frame = $('[data-frame="frame1"]');
                frame.find('[name="groupId"]').val($('#select-groupId').val());
                frame.find('[name="typeGroup"]').html(groupTypes[data[0].groupType]);
                frame.find('[name="nameGroup"]').val(data[0].groupName);
                var buffer = '';

                var counter = 0;
                for (i in data['members']) {
                    if (data['members'][i].personName == null) {
                        data['members'][i].personName = '';
                    }
                    counter++;
                    buffer += '<option value="' + data['members'][i].personId + '">' + counter + '. ' + data['members'][i].personName + '</option>';
                }
                frame.find('[name="membersGroup[]"]').empty();
                frame.find('[name="membersGroup[]"]').append(buffer);
                buffer = '';
                counter = 0;
                for (i in data['otherPersons']) {
                    counter++;
                    if (data['otherPersons'][i].personName == null) {
                        data['otherPersons'][i].personName = '';
                    }
                    buffer += '<option value="' + data['otherPersons'][i].personId + '">' + counter + '. ' + data['otherPersons'][i].personName + '</option>';
                }
                frame.find('[name="otherPersons[]"]').empty();
                frame.find('[name="otherPersons[]"]').append(buffer);
            }
        })
    }

    function sleep(ms) {
        ms += new Date().getTime();
        while (new Date() < ms){}
    }

    $('.btn-submit').click(function() {
        var btn = $(this);
        var form = btn.parents('form');
        if (btn.attr('name') == 'remove_user') {
            if (!confirm('При удалении пользователя, он будет исключён из группы и информация о его продажах будет утеряна. Вы дейстивтельно хотите продолжить?')) {
                return false;
            }
        }
        if (btn.attr('name') == 'remove_group') {
            if (!confirm('При удалении группы, все сотрудники будут исключены из неё. Вы дейстивтельно хотите продолжить?')) {
                return false;
            }
        }
        form.attr('action', form.attr('action') + (form.attr('action').length ? '&' : '?') + 'action=' + btn.attr('name'));
        form.trigger('submit');
    });

    //*******

    if (window.page && page == 'tablo') {
        setInterval(function() {
            var now = moment();
            moment.lang('ru');
            $('.timehere').html(now.format('dddd, MMMM DD YYYY, h:mm:ss'));
        }, 1000);

        function rebuildTable(data) {
            $savedData = [];
            $('[data-position]').each(function(){
                $(this).css('transition', 'top 1s cubic-bezier(0, 0, 1, 1), background 1s linear');
                $savedData[$(this).data('position')] = {
                    'position' : $(this).find('div.place').html(),
                    'background' : $(this).css('background'),
                    'font-size' : $(this).css('font-size'),
                    'box-shadow' : $(this).css('box-shadow')
                }
            });
            for (i in data) {
                var row = $('#row' + data[i].personId);
                var newpos = Number(i) + 1;
                row.find('.sells-value').html(data[i].sellsValue);
                row.find('.sells-month1-value').html(data[i].monthValue1);
                row.find('.sells-month2-value').html(data[i].monthValue2);
                row.find('.sells-month3-value').html(data[i].monthValue3);
                row.find('.sells-month4-value').html(data[i].monthValue3);
                row.find('.sells-year-value').html(data[i].yearValue);
                row.find('.sells-plan-quarterly').html(data[i].quarterly);
                row.find('.sells-plan-monthly').html(data[i].monthly);
                row.css('top', offset + 40 * newpos + 'px');
                row.css('background', $savedData[newpos]['background']);
                row.css('font-size', $savedData[newpos]['font-size']);
                row.css('box-shadow', $savedData[newpos]['box-shadow']);
                row.css('z-index', 100 - newpos);
                row.find('div.place').html($savedData[newpos]['position']);
                row.data('position', newpos);
            }
        }

        function moveRow(id, position) {
            var curRow = $('#row' + id);
            $('[data-position]').each(function(){
                //console.log($(this).css('top'));
                if ($(this).data('position') == position) {
                    //if ($(this).css('top') == 40 * position + 'px') {
                    //var buffTop = $(this).css('top');
                    $(this).css('top', offset + 40 * curRow.data('position') + 'px');
                    curRow.css('top', offset + 40 * position + 'px');
                    var buffPlace = $(this).find('div.place').html();
                    $(this).find('div.place').html(curRow.find('div.place').html());
                    curRow.find('div.place').html(buffPlace);
                    var buffPosition = $(this).data('position');
                    $(this).data('position', curRow.data('position'));
                    curRow.data('position', buffPosition);
                    var buffBackground = $(this).css('background');
                    $(this).css('background', curRow.css('background'));
                    curRow.css('background', buffBackground);
                    var buffZindex = $(this).css('z-index');
                    $(this).css('z-index', curRow.css('z-index'));
                    curRow.css('z-index', buffZindex);
                    return false;
                }
            });
        }

        function pullRows() {
            $('[data-position]').each(function() {
                var curRow = $(this);
                if (curRow.data('position') > 3) {
                    curRow.css('opacity', 0);
                    curRow.css('top', offset + 40 * curRow.data('position') + 'px');
                    setTimeout(function () {
                        curRow.css('transition', 'opacity 1s linear');
                        curRow.css('opacity', 1);
                    }, 10);
                }
            });
            setTimeout(function(){
                refreshData();
            }, 10000);
        }

        function pushRows() {
            var counter = 0;
            $('[data-position]').each(function(){
                console.log($(this).css('top'));
                if ($(this).data('position') > 3) {
                    $(this).css('transition', 'top 2s linear');
                    if ($(this).css('top') > (offset + 120) + 'px') {
                        counter++;
                        $(this).css('top', '-=40');
                    }
                }
            });
            if (counter) {
                setTimeout(function(){
                    pushRows();
                }, 2000);
            } else {
                setTimeout(function(){
                    pullRows();
                }, 10);
            }
            //curRow.css('top', 60 + 40 * position);
        }

        function refreshData() {
            $.ajax({
                url: "?r=admin%2Fget-sells-json",
                method: "POST",
                data: {
                    _csrf : _csrf,
                    groupId : groupId,
                    period : period
                },
                dataType: "json",
                success: function(data) {
                    // Обновляем данные
                    rebuildTable(data);
                    var winHeight = $(window).height();
                    var lastRowTop = $('[data-position="' + $('[data-position]').size() + '"]').offset().top;
                    var lastRowTHeight = $('[data-position="' + $('[data-position]').size() + '"]').height();
                    //console.log(lastRowTHeight);
                    if (lastRowTop + lastRowTHeight > winHeight) {
                        setTimeout(function(){
                            pushRows();
                        }, 10000);
                    } else {
                        setTimeout(function(){
                            refreshData();
                        }, 10000);
                    }
                }
            })
        }

        refreshData();
    }

    $('[data-presubmit]').submit(function(){
       if (!confirm($(this).data('presubmit'))) {
           return false;
       }
    });

    if (window.page && page == 'charts') {
        $(function () {
            $('[name="unionMode"]').change(function() {
                var self = $(this);
                if (self.find('option:selected').val() == 1) {
                    $('#chart-seller-filter').fadeIn();
                } else {
                    $('#chart-seller-filter').fadeOut();
                }
            });
            $('[name="unionMode"]').trigger('change');

            Highcharts.setOptions({
                lang: {
                    loading: 'Загрузка...',
                    months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                    weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                    shortMonths: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'],
                    exportButtonTitle: "Экспорт",
                    printButtonTitle: "Печать",
                    rangeSelectorFrom: "С",
                    rangeSelectorTo: "По",
                    rangeSelectorZoom: "Период",
                    downloadPNG: 'Скачать PNG',
                    downloadJPEG: 'Скачать JPEG',
                    downloadPDF: 'Скачать PDF',
                    downloadSVG: 'Скачать SVG',
                    printChart: 'Напечатать график'
                }
            });

            var groupId = window.groupId ? window.groupId : 0;
            var startDate = window.startDate ? window.startDate : '';
            var endDate = window.endDate ? window.endDate : '';

            console.log($('[name="chart-filters"]').serialize());

            $.getJSON('?' + $('[name="chart-filters"]').serialize().replace('r=admin%2Fcharts', 'r=admin%2Fget-charts-json'), function (response) {
                console.log(response.series);
                $('#chart').highcharts({
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: response.title
                    },
                    subtitle: {
                        text: response.subtitle
                    },
                    xAxis: {
                        type: 'datetime',
                        tickInterval: 24 * 3600 * 1000 * 30,
                        dateTimeLabelFormats: {
                            day: '%d %b %Y'    //ex- 01 Jan 2016
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Доход (руб)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f} руб.</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: response.series
                });
            });
        });
    }

});