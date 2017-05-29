function drag(event) {
    event.dataTransfer.setData("elementId", event.target.id);
    event.dataTransfer.setData("oldStatusId", event.target.parentElement.id);
}

function allowDrop(event) {
    // //dropping is allowed
    // if ($(event.target).hasClass("leads-status")) {
    //     event.preventDefault();
    //     event.dataTransfer.dropEffect = "all";
    // }
    // //dropping is NOT allowed
    // else {
    //     event.dataTransfer.dropEffect = "none";
    // }
    event.preventDefault();
}

function updateCompanyStatus(event) {
    var element = event.dataTransfer.getData("elementId");
    var oldStatusId = event.dataTransfer.getData("oldStatusId");
    var companyId = element.split('-').slice(-1)[0];
    var statusId = $(event.target).closest('.leads-status').attr('id').split('-').slice(-1)[0];
    var loadingIcon = $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    $.ajax({
        url: '/company/update/'+companyId+'/status/'+statusId,
        method: 'PUT',
        dataType:"json",
        data: {
            companyId: companyId,
            statusId: statusId,
        },
        beforeSend: function() {
            $('.box-header-leads').append(loadingIcon);
        },
        success: function(response) {
            event.preventDefault();
            //move element, increase new count, decrease oldcount, reset time diff label
            var targetCountLabel = $(event.target).find('.status-label .label');
            var oldCountLabel = $('#'+oldStatusId).find('.status-label .label');
            targetCountLabel.html(parseInt(targetCountLabel.html()) + 1);
            oldCountLabel.html(parseInt(oldCountLabel.html()) - 1);
            $('#'+element).find('.label').html('0 second ago');
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

function updateMembershipStatus(event) {
    var element = event.dataTransfer.getData("elementId");
    var oldStatusId = event.dataTransfer.getData("oldStatusId");
    var companyId = element.split('-').slice(-1)[0];
    var statusId = event.target.getAttribute("id").split('-').slice(-1)[0];
    var loadingIcon = $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    $.ajax({
        url: '/company/update/'+companyId+'/status/'+statusId,
        method: 'PUT',
        dataType:"json",
        data: {
            companyId: companyId,
            statusId: statusId,
        },
        beforeSend: function() {
            $('.box-header-leads').append(loadingIcon);
        },
        success: function(response) {
            event.preventDefault();
            //move element, increase new count, decrease oldcount, reset time diff label
            var targetCountLabel = $(event.target).find('.status-label .label');
            var oldCountLabel = $('#'+oldStatusId).find('.status-label .label');
            targetCountLabel.html(parseInt(targetCountLabel.html()) + 1);
            oldCountLabel.html(parseInt(oldCountLabel.html()) - 1);
            $('#'+element).find('.label').html('0 second ago');
            event.target.appendChild(document.getElementById(element));
        },
        error: function(response, status, error) {
            console.log(error);
        },
        complete: function() {
            $('.box-header-leads').find('.overlay').remove();
        }
    });
}

// on document ready jquery
$(function() {
});