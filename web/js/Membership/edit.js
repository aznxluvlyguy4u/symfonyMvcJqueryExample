var $contractDocCollectionHolder;

// setup an "add a ContractDoc" link
var $addContractDocLink = $('<li><a href="#" class="add_contractDoc_link">Add a Contract Document</a></li>');
var $newContractDocLinkLi = $('<div class="contractDoc"></div>').append($addContractDocLink);

$(function() {
    /*
     * ContractDoc Block
     */
    // Get the ul that holds the collection of ContractDocs
    $contractDocCollectionHolder = $('.appbundle_membership_contractDocs');

    // add a delete link to all of the existing  form li elements
    $contractDocCollectionHolder.find('.contractDoc').each(function() {
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
        var newFormCreate = $('<div></div>').append(prototypeCreate.replace(/__name__/g, index));
        // increase the index with one for the next item
        $contractDocCollectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a contractDoc" link li
        var $newFormUl = $('<div class="contractDoc"></div>').append(newFormCreate);
        $newLinkLi.before($newFormUl);

        // add a delete link to the new form
        addContractDocFormDeleteLink($newFormUl);
    }

    function addContractDocFormDeleteLink($newFormUl) {
        var $removeFormA = $('<a href="#">delete this contract document</a>');
        $newFormUl.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $newFormUl.remove();
        });
    }
});