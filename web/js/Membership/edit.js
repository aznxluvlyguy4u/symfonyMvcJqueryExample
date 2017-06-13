var $contractDocCollectionHolder;
var $sepaFormCollectionHolder;

// setup an "add a Document" link
var $addContractDocLink = $('<td colspan="3" align="center"><a href="#" class="add_contractDoc_link"><i class="fa fa-plus" aria-hidden="true"></i> Add a Contract Document</a></td>');
var $newContractDocLinkLi = $('<tr class="contractDoc"></tr>').append($addContractDocLink);

var $addSepaFormLink = $('<td colspan="3" align="center"><a href="#" class="add_sepaForm_link"><i class="fa fa-plus" aria-hidden="true"></i> Add a SEPA Form</a></td>');
var $newSepaFormLinkLi = $('<tr class="sepaForm"></tr>').append($addSepaFormLink);

$(function() {
    /*
     * ContractDoc Block
     */
    // Get the ul that holds the collection of ContractDocs
    $contractDocCollectionHolder = $('.appbundle_membership_contractDocs tbody');

    // add a delete link to all of the existing  form li elements
    $contractDocCollectionHolder.find('.contractDoc-delete').each(function() {
        addDocumentFormDeleteLink($(this));
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
        var newFormCreateRow = $('<tr class="contractDoc"><td width="15%" ><i class="fa fa-upload" aria-hidden="true"></i></td></tr>');
        var newFormCreate = newFormCreateRow.append($('<td width="70%" ></td>').append(prototypeCreate.replace(/__name__/g, index))).append($('<td width="15%" class="contractDoc-delete"></td>'));
        // increase the index with one for the next item
        $contractDocCollectionHolder.data('index', index + 1);
        // Display the form in the page in an li, before the "Add a contractDoc" link li
        $newLinkLi.before(newFormCreate);
        // add a delete link to the new form
        addDocumentFormDeleteLink(newFormCreate.find('.contractDoc-delete'));
    }
    
    /*
     * SepaForm Block
     */
    $sepaFormCollectionHolder = $('.appbundle_membership_sepaForms tbody');
    $sepaFormCollectionHolder.find('.sepaForm-delete').each(function() {
        addDocumentFormDeleteLink($(this));
    });

    $sepaFormCollectionHolder.append($newSepaFormLinkLi);
    $sepaFormCollectionHolder.data('index', $sepaFormCollectionHolder.find('.sepaForm').length);
    $addSepaFormLink.children('a').on('click', function(e) {
        e.preventDefault();
        addSepaFormForm($sepaFormCollectionHolder, $newSepaFormLinkLi);
    });
    
    function addSepaFormForm($sepaFormCollectionHolder, $newLinkLi) {
        var prototypeCreate = $sepaFormCollectionHolder.data('prototype-create');
        var index = $sepaFormCollectionHolder.data('index');
        var newFormCreateRow = $('<tr class="sepaForm"><td width="15%"><i class="fa fa-upload" aria-hidden="true"></i></td></tr>');
        var newFormCreate = newFormCreateRow.append($('<td width="70%" ></td>').append(prototypeCreate.replace(/__name__/g, index))).append($('<td width="15%" class="sepaForm-delete"></td>'));
        $sepaFormCollectionHolder.data('index', index + 1);
        $newLinkLi.before(newFormCreate);
        addDocumentFormDeleteLink(newFormCreate.find('.sepaForm-delete'));
    }

    

    function addDocumentFormDeleteLink($newFormUl) {
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