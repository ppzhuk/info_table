/**
 * Created by bodrik on 08.05.16.
 */
$(function() {
    var fixedValues = {
        'quarterlyPlan': '',
        'monthlyPlan': ''
    }
    $.material.init();

    $('[data-targetframe]').on('click', function() {
        $('[data-frame]').addClass('hide');
        $('[data-frame="' + $(this).data('targetframe') + '"]').removeClass('hide');
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
        if (/^\d+$/ig.test(userId)) {
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
            $('input.lockable, .lockable input, .lockable button, .lockable select').removeAttr("disabled");
            hideAlert();
        } else {
            $('input.lockable, .lockable input, .lockable button, .lockable select').attr('disabled', 'true');
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
        $('input.lockable, .lockable input, .lockable button, .lockable select').removeAttr("disabled");
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
                frame.find('[name="loginUser"]').val(data[0].loginPerson);
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
                for (i in data['members']) {
                    if (data['members'][i].personName == null) {
                        data['members'][i].personName = '';
                    }
                    buffer += '<option value="' + data['members'][i].personId + '">' + data['members'][i].personName + '</option>';
                }
                frame.find('[name="membersGroup[]"]').empty();
                frame.find('[name="membersGroup[]"]').append(buffer);
                buffer = '';
                for (i in data['otherPersons']) {
                    if (data['otherPersons'][i].personName == null) {
                        data['otherPersons'][i].personName = '';
                    }
                    buffer += '<option value="' + data['otherPersons'][i].personId + '">' + data['otherPersons'][i].personName + '</option>';
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

/*    setTimeout(function(){
        //refreshData();
        moveRow(10, 2);
    }, 2000);*/

/*    setTimeout(function(){
        moveRow(5, 8);
    }, 1000);
    /!*    setTimeout(function(){
     moveRow(6, 2);
     }, 3000);*!/
    setTimeout(function(){
        pushRows();
    }, 3000);*/


});