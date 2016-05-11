/**
 * Created by bodrik on 08.05.16.
 */
$(function() {
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
    });

    $('[data-targetframe="frame1"]').click(function(){
        refreshFormEditGroup($('#select-groupId').find('option:selected').val());
    });

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
});