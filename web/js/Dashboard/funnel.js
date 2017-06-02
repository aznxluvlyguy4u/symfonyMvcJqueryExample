var dragging;
var highlightClass = 'bg-teal';

function drag(event) {
    event.dataTransfer.setData("elementId", event.target.id);
    event.dataTransfer.setData("oldStatusColumnId", event.target.parentElement.id);
    dragging = $(event.target);
}

function allowDrop(event) {
    event.preventDefault();
    $(event.target).closest('.status-column').addClass(highlightClass);
    dragging.css('opacity', '0');
}

function preventDrop(event) {
    $(event.target).closest('.status-column').removeClass(highlightClass);
}

function dragend(event) {
    dragging.css('opacity', '1');
}

function updateCompanyStatus(event) {
    var element = event.dataTransfer.getData("elementId");
    var oldStatusColumnId = event.dataTransfer.getData("oldStatusColumnId");
    var targetStatusColumn = $(event.target).closest('.leads-status');
    var targetStatusColumnId = targetStatusColumn.attr("id");
    var companyId = element.split('-').slice(-1)[0];
    var newStatusId = targetStatusColumnId.split('-').slice(-1)[0];
    var loadingIcon = $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    // ajax call when not dropping on itself AND only when element dropped is of the class company-box
    if (oldStatusColumnId !== targetStatusColumnId && $('#'+element).hasClass("company-box")) {
        $.ajax({
            url: '/company/update/'+companyId+'/status/'+newStatusId,
            method: 'PUT',
            beforeSend: function() {
                $('.box-header-leads').append(loadingIcon);
            },
            success: function(response) {
                event.preventDefault();
                //move element, increase new count, decrease old count, reset time diff label, set new status template
                var targetCountLabel = targetStatusColumn.find('.status-label .label');
                var oldCountLabel = $('#'+oldStatusColumnId).find('.status-label .label');
                targetCountLabel.html(parseInt(targetCountLabel.html()) + 1);
                oldCountLabel.html(parseInt(oldCountLabel.html()) - 1);
                $('#'+element).find('.label').html('1 second ago');
                $('#'+element).find('.email-icon').data('subject', response.subject);
                $('#'+element).find('.email-icon').data('body', response.body);
                targetStatusColumn.append($('#'+element));
            },
            error: function(response, status, error) {
                console.log(error);
            },
            complete: function() {
                $('.box-header-leads').find('.overlay').remove();
            }
        });
    }
    targetStatusColumn.removeClass(highlightClass);
}

function updateMembershipStatus(event) {
    var element = event.dataTransfer.getData("elementId");
    var oldStatusColumnId = event.dataTransfer.getData("oldStatusColumnId");
    var targetStatusColumn = $(event.target).closest('.memberships-status');
    var targetStatusColumnId = targetStatusColumn.attr("id");
    var membershipId = element.split('-').slice(-1)[0];
    var newStatusId = targetStatusColumnId.split('-').slice(-1)[0];
    var loadingIcon = $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    // ajax call when not dropping on itself AND only when element dropped is of the class membership-box
    if (oldStatusColumnId !== targetStatusColumnId && $('#'+element).hasClass("membership-box")) {
        $.ajax({
            url: '/membership/update/'+membershipId+'/status/'+newStatusId,
            method: 'PUT',
            beforeSend: function() {
                $('.box-header-memberships').append(loadingIcon);
            },
            success: function(response) {
                event.preventDefault();
                //move element, increase new count, decrease old count, reset time diff label, set new status template
                var targetCountLabel = targetStatusColumn.find('.status-label .label');
                var oldCountLabel = $('#'+oldStatusColumnId).find('.status-label .label');
                targetCountLabel.html(parseInt(targetCountLabel.html()) + 1);
                oldCountLabel.html(parseInt(oldCountLabel.html()) - 1);
                $('#'+element).find('.label').html('1 second ago');
                $('#'+element).find('.email-icon').data('subject', response.subject);
                $('#'+element).find('.email-icon').data('body', response.body);
                targetStatusColumn.append($('#'+element));
            },
            error: function(response, status, error) {
                console.log(error);
            },
            complete: function() {
                $('.box-header-memberships').find('.overlay').remove();
            }
        });
    }
    targetStatusColumn.removeClass(highlightClass);
}

// on document ready jquery
$(function() {
    // get ajax template data to email modal
    $('#emailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var companyId = button.data('companyid'); // Extract info from data-* attributes
        var companyName = button.data('companyname'); // Extract info from data-* attributes
        var email = button.data('email');
        var modal = $(this);
        modal.find('.modal-title').text('New message to ' + companyName);
        modal.find('#send_email_to').val(email);

        $.ajax({
            url: '/email/template/company/'+companyId,
            method: 'GET',
            success: function(response) {
                console.log(response);
                modal.find('#send_email_subject').val(response.subject);
                modal.find('#send_email_body').val(response.body);
            },
            error: function(response, status, error) {
                console.log(error);
            }
        });
    })
});