function confirmationDialog(event, element, message)
{
    var confirmation = confirm(message);

    if(!confirmation)
        event.preventDefault();
}
