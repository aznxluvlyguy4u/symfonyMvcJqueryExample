/**
 * This can also be used for other confirmations such as click events, form submissions etc :)
 *
 */

var ConfirmEnums = {
    CLEAR_COMMENTS: 'Do you really want to clear the comments history?'
};

function confirmationDialog(event, element, message)
{
    var confirmation = confirm(message);

    if(!confirmation)
        event.preventDefault();
}
