var $contractDocCollectionHolder;

// setup an "add a ContractDoc" link
var $addContractDocLink = $('<td colspan="3"><a href="#" class="add_contractDoc_link">Add a Contract Document</a></td>');
var $newContractDocLinkLi = $('<tr class="contractDoc"></tr>').append($addContractDocLink);

$(function() {
    /*
     * ContractDoc Block
     */
    // Get the ul that holds the collection of ContractDocs
    $contractDocCollectionHolder = $('.appbundle_membership_contractDocs tbody');

    // add a delete link to all of the existing  form li elements
    $contractDocCollectionHolder.find('.contractDoc-delete').each(function() {
        addContractDocFormDeleteLink($(this));
    });

    // add the "add a contractDoc" anchor and li to the contractDocs ul
    $contractDocCollectionHolder.append($newContractDocLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $contractDocCollectionHolder.data('index', $contractDocCollectionHolder.find('.contractDoc').length);

    $addContractDocLink.children('a').on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new contractDoc form (see next code block)
        addContractDocForm($contractDocCollectionHolder, $newContractDocLinkLi);
    });

    function addContractDocForm($contractDocCollectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototypeCreate = $contractDocCollectionHolder.data('prototype-create');
        // get the new index
        var index = $contractDocCollectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newFormCreateRow = $('<tr><td><i class="fa fa-upload" aria-hidden="true"></i></td><td class="upload-icon"></td><td class="contractDoc-delete"></td></tr>');
        console.log(newFormCreateRow);
        var newFormCreate = newFormCreateRow.find('.upload-icon').append(prototypeCreate.replace(/__name__/g, index));
//        console.log(newFormCreate);
        // increase the index with one for the next item
        $contractDocCollectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a contractDoc" link li
//        var $newFormUl = $('<tr class="contractDoc"></tr>').append($('<th></th>')).append(newFormCreate).append('<th class="contractDoc-delete"></th>');
        var $newFormUl = $('<tr class="contractDoc"></tr>').append(newFormCreate);
//        console.log($newFormUl);
        $newLinkLi.before($newFormUl);

        // add a delete link to the new form
        addContractDocFormDeleteLink($newFormUl.find('.contractDoc-delete'));
    }

    function addContractDocFormDeleteLink($newFormUl) {
        var $removeFormA = $('<button type="button" class="btn btn-box-tool"><i class="fa fa-times"></i></button>');
        $newFormUl.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $newFormUl.parent().remove();
        });
    }
});