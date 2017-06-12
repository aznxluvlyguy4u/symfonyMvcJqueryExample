var $contractDocCollectionHolder;

// setup an "add a ContractDoc" link
var $addContractDocLink = $('<td colspan="3" align="right"><a href="#" class="add_contractDoc_link"><i class="fa fa-plus" aria-hidden="true"></i> Add a Contract Document</a></td>');
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
        var newFormCreateRow = $('<tr class="contractDoc"><td><i class="fa fa-upload" aria-hidden="true"></i></td></tr>');
        var newFormCreate = newFormCreateRow.append($('<td></td>').append(prototypeCreate.replace(/__name__/g, index))).append($('<td class="contractDoc-delete"></td>'));
        // increase the index with one for the next item
        $contractDocCollectionHolder.data('index', index + 1);
        // Display the form in the page in an li, before the "Add a contractDoc" link li
        $newLinkLi.before(newFormCreate);
        // add a delete link to the new form
        addContractDocFormDeleteLink(newFormCreate.find('.contractDoc-delete'));
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