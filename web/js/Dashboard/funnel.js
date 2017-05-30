function drag(event) {
    event.dataTransfer.setData("elementId", event.target.id);
    event.dataTransfer.setData("oldStatusColumnId", event.target.parentElement.id);
}

function allowDrop(event) {
    event.preventDefault();
}

function updateCompanyStatus(event) {
    var element = event.dataTransfer.getData("elementId");
    var oldStatusColumnId = event.dataTransfer.getData("oldStatusColumnId");
    var targetStatusColumnId = $(event.target).closest('.leads-status').attr("id");
    var companyId = element.split('-').slice(-1)[0];
    var newStatusId = $(event.target).closest('.leads-status').attr('id').split('-').slice(-1)[0];
    var loadingIcon = $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    // ajax call when not dropping on itself
    if (oldStatusColumnId !== targetStatusColumnId) {
        $.ajax({
            url: '/company/update/'+companyId+'/status/'+newStatusId,
            method: 'PUT',
            beforeSend: function() {
                $('.box-header-leads').append(loadingIcon);
            },
            success: function(response) {
                event.preventDefault();
                //move element, increase new count, decrease old count, reset time diff label
                var targetCountLabel = $(event.target).closest('.leads-status').find('.status-label .label');
                var oldCountLabel = $('#'+oldStatusColumnId).find('.status-label .label');
                targetCountLabel.html(parseInt(targetCountLabel.html()) + 1);
                oldCountLabel.html(parseInt(oldCountLabel.html()) - 1);
                $('#'+element).find('.label').html('1 second ago');
                $(event.target).closest('.leads-status').append($('#'+element));
            },
            error: function(response, status, error) {
                console.log(error);
            },
            complete: function() {
                $('.box-header-leads').find('.overlay').remove();
            }
        });
    }
}

function updateMembershipStatus(event) {
    var element = event.dataTransfer.getData("elementId");
    var oldStatusColumnId = event.dataTransfer.getData("oldStatusColumnId");
    var targetStatusColumnId = $(event.target).closest('.memberships-status').attr("id");
    var membershipId = element.split('-').slice(-1)[0];
    var newStatusId = $(event.target).closest('.memberships-status').attr('id').split('-').slice(-1)[0];
    var loadingIcon = $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    // ajax call when not dropping on itself
    if (oldStatusColumnId !== targetStatusColumnId) {
        $.ajax({
            url: '/membership/update/'+membershipId+'/status/'+newStatusId,
            method: 'PUT',
            beforeSend: function() {
                $('.box-header-memberships').append(loadingIcon);
            },
            success: function(response) {
                event.preventDefault();
                //move element, increase new count, decrease old count, reset time diff label
                var targetCountLabel = $(event.target).closest('.memberships-status').find('.status-label .label');
                var oldCountLabel = $('#'+oldStatusColumnId).find('.status-label .label');
                targetCountLabel.html(parseInt(targetCountLabel.html()) + 1);
                oldCountLabel.html(parseInt(oldCountLabel.html()) - 1);
                $('#'+element).find('.label').html('1 second ago');
                $(event.target).closest('.memberships-status').append($('#'+element));
            },
            error: function(response, status, error) {
                console.log(error);
            },
            complete: function() {
                $('.box-header-memberships').find('.overlay').remove();
            }
        });
    }
}

// on document ready jquery
$(function() {
});