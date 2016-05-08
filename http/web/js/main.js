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
});