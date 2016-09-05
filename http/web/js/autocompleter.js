/**
 * Created by bodrik on 13.07.16.
 */

$( function() {
    $('.person-autocompleter').autocomplete({
        source: '?r=admin%2Ffind-sellers',
        minLength: 3,
        focus: function( event, ui ) {
            //console.log(ui);
            $(event.currentTarget).parentsUntil('.trow').find('[name="personId"]').val(ui.item.personId);
            $(event.currentTarget).parentsUntil('.trow').find('[name="groupId"]').val(ui.item.groupId);
        }
    });
} );