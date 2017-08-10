var adminNs =
    {
        initDraggableEntityRows: function()
        {
            var dragSourceElement = null; // the object being drug
            var startPosition = null; // the index of the row element (0 through whatever)
            var endPosition = null; // the index of the row element being dropped on (0 through whatever)
            var parent; // the parent element of the dragged item
            var entityId; // the id (key) of the entity
            var oldColor = null;
            var feedbackColor = '#abcdef';
            function handleDragStart(e)
            {
                dragSourceElement = this;
                entityId = $(this).attr('rel');
                dragSourceElement.style.opacity = '0.8';
                parent = dragSourceElement.parentNode;
                startPosition = Array.prototype.indexOf.call(parent.children, dragSourceElement);
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            }
            function handleDragOver(e)
            {

                if (e.preventDefault) {
                    e.preventDefault(); // Necessary. Allows us to drop.
                }
                e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
                return false;
            }
            function handleDragEnter(e)
            {
                if(e.target !== dragSourceElement) {
                    oldColor = $(e.target).parent().css('background-color');
                    $(e.target).parent().css('background-color', feedbackColor);
                }
                this.classList.add('over');
            }
            function handleDragLeave(e)
            {
                if(e.target !== dragSourceElement) {
                    $(e.target).parent().css('background-color', oldColor);
                    oldColor = $(e.target).parent().css('background-color');
                }
                this.classList.remove('over');  // this / e.target is previous target element.
            }
            function handleDrop(e)
            {
                if (e.stopPropagation) {
                    e.stopPropagation(); // stops the browser from redirecting.
                }
                // Don't do anything if dropping the same column we're dragging.
                if (dragSourceElement != this) {
                    endPosition = Array.prototype.indexOf.call(parent.children, this);
                    console.log("end: "+endPosition);
                    // Set the source column's HTML to the HTML of the column we dropped on.
                    dragSourceElement.innerHTML = this.innerHTML;
                    this.innerHTML = e.dataTransfer.getData('text/html');
                    // do the ajax call to update the database
                    $.ajax({
                        url: 'sort/'+entityId+'/'+endPosition,
                    })
                    .done(function(res) {
                        $("table.sortable tbody").replaceWith($(res).find("table.sortable tbody"));
                    })
                    .fail(function(err) {
                        alert("An error occurred while sorting. Please refresh the page and try again." + err)
                    })
                    .always(function() {
                        adminNs.initDraggableEntityRows();
                    });
                }
                return false;
            }
            function handleDragEnd(e)
            {
                this.style.opacity = '1';  // this / e.target is the source node.
                [].forEach.call(rows, function (row) {
                    row.classList.remove('over');
                });
            }
            var rows = document.querySelectorAll('table.sortable > tbody tr');
            [].forEach.call(rows, function(row) {
                row.addEventListener('dragstart', handleDragStart, false);
                row.addEventListener('dragenter', handleDragEnter, false);
                row.addEventListener('dragover', handleDragOver, false);
                row.addEventListener('dragleave', handleDragLeave, false);
                row.addEventListener('drop', handleDrop, false);
                row.addEventListener('dragend', handleDragEnd, false);
            });
        },
        /**
         * Primary Admin initialization method.
         * @returns {boolean}
         */
        init: function() {

            this.initDraggableEntityRows();
            return true;
        }
    };
$(function() {
    adminNs.init();
});
