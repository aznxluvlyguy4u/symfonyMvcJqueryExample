function drag(event) {
    event.dataTransfer.setData("elementId", event.target.id);
}

function allowDrop(event) {
    //dropping is allowed
    if ($(event.target).hasClass("leads-status")) {
        event.preventDefault();
        event.dataTransfer.dropEffect = "all";
    }
    //dropping is NOT allowed
    else {
        event.dataTransfer.dropEffect = "none";
    }
}

function drop(event) {
    var data = event.dataTransfer.getData("elementId");
    var companyId = data.split('-').slice(-1)[0];
    var statusId = event.target.getAttribute("id").split('-').slice(-1)[0];
    $.ajax({
        url: '/company/update/'+companyId+'/status/'+statusId,
        method: 'PUT',
        dataType:"json",
        data: {
            companyId: companyId,
            statusId: statusId,
        },
        beforeSend: function() {
            $('body').css('cursor', 'wait');
        },
        success: function(response) {
            event.preventDefault();
            $('#'+data).find('.label').html('0 second ago');
            event.target.appendChild(document.getElementById(data));
        },
        error: function(response, status, error) {
            console.log(error);
        },
        complete: function() {
            $('body').css('cursor', 'default');
        }
    });
}

// on document ready jquery
$(function() {
});