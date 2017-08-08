var $contractDocCollectionHolder;
var $sepaFormCollectionHolder;
var $keysFormCollectionHolder;
var $kvkExtractCollectionHolder;
var $depositReceiptCollectionHolder;

// setup an "add a Document" link
var $addContractDocLink = $('<td colspan="3" align="center"><a href="#" class="add_contractDoc_link"><i class="fa fa-plus" aria-hidden="true"></i> Add a Contract Document</a></td>');
var $newContractDocLinkLi = $('<tr class="contractDoc"></tr>').append($addContractDocLink);

var $addSepaFormLink = $('<td colspan="3" align="center"><a href="#" class="add_sepaForm_link"><i class="fa fa-plus" aria-hidden="true"></i> Add a SEPA Form</a></td>');
var $newSepaFormLinkLi = $('<tr class="sepaForm"></tr>').append($addSepaFormLink);

var $addKeysFormLink = $('<td colspan="3" align="center"><a href="#" class="add_keysForm_link"><i class="fa fa-plus" aria-hidden="true"></i> Add a Keys Form</a></td>');
var $newKeysFormLinkLi = $('<tr class="keysForm"></tr>').append($addKeysFormLink);

var $addKvkExtractLink = $('<td colspan="3" align="center"><a href="#" class="add_kvkExtract_link"><i class="fa fa-plus" aria-hidden="true"></i> Add a KVK Extract</a></td>');
var $newKvkExtractLinkLi = $('<tr class="kvkExtract"></tr>').append($addKvkExtractLink);

var $addDepositReceiptLink = $('<td colspan="3" align="center"><a href="#" class="add_depositReceipt_link"><i class="fa fa-plus" aria-hidden="true"></i> Add a Deposit Receipt</a></td>');
var $newDepositReceiptLinkLi = $('<tr class="depositReceipt"></tr>').append($addDepositReceiptLink);

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

    /*
     * KeysForm Block
     */
    $keysFormCollectionHolder = $('.appbundle_membership_keysForms tbody');
    $keysFormCollectionHolder.find('.keysForm-delete').each(function() {
        addDocumentFormDeleteLink($(this));
    });

    $keysFormCollectionHolder.append($newKeysFormLinkLi);
    $keysFormCollectionHolder.data('index', $keysFormCollectionHolder.find('.keysForm').length);
    $addKeysFormLink.children('a').on('click', function(e) {
        e.preventDefault();
        addKeysFormForm($keysFormCollectionHolder, $newKeysFormLinkLi);
    });

    function addKeysFormForm($keysFormCollectionHolder, $newLinkLi) {
        var prototypeCreate = $keysFormCollectionHolder.data('prototype-create');
        var index = $keysFormCollectionHolder.data('index');
        var newFormCreateRow = $('<tr class="keysForm"><td width="15%"><i class="fa fa-upload" aria-hidden="true"></i></td></tr>');
        var newFormCreate = newFormCreateRow.append($('<td width="70%" ></td>').append(prototypeCreate.replace(/__name__/g, index))).append($('<td width="15%" class="keysForm-delete"></td>'));
        $keysFormCollectionHolder.data('index', index + 1);
        $newLinkLi.before(newFormCreate);
        addDocumentFormDeleteLink(newFormCreate.find('.keysForm-delete'));
    }

    /*
     * KvkExtract Block
     */
    $kvkExtractCollectionHolder = $('.appbundle_membership_kvkExtracts tbody');
    $kvkExtractCollectionHolder.find('.kvkExtract-delete').each(function() {
        addDocumentFormDeleteLink($(this));
    });

    $kvkExtractCollectionHolder.append($newKvkExtractLinkLi);
    $kvkExtractCollectionHolder.data('index', $kvkExtractCollectionHolder.find('.kvkExtract').length);
    $addKvkExtractLink.children('a').on('click', function(e) {
        e.preventDefault();
        addKvkExtractForm($kvkExtractCollectionHolder, $newKvkExtractLinkLi);
    });

    function addKvkExtractForm($kvkExtractCollectionHolder, $newLinkLi) {
        var prototypeCreate = $kvkExtractCollectionHolder.data('prototype-create');
        var index = $kvkExtractCollectionHolder.data('index');
        var newFormCreateRow = $('<tr class="kvkExtract"><td width="15%"><i class="fa fa-upload" aria-hidden="true"></i></td></tr>');
        var newFormCreate = newFormCreateRow.append($('<td width="70%" ></td>').append(prototypeCreate.replace(/__name__/g, index))).append($('<td width="15%" class="kvkExtract-delete"></td>'));
        $kvkExtractCollectionHolder.data('index', index + 1);
        $newLinkLi.before(newFormCreate);
        addDocumentFormDeleteLink(newFormCreate.find('.kvkExtract-delete'));
    }

    /*
     * Deposit Receipt Block
     */
    $depositReceiptCollectionHolder = $('.appbundle_membership_depositReceipts tbody');
    $depositReceiptCollectionHolder.find('.depositReceipt-delete').each(function() {
        addDocumentFormDeleteLink($(this));
    });

    $depositReceiptCollectionHolder.append($newDepositReceiptLinkLi);
    $depositReceiptCollectionHolder.data('index', $depositReceiptCollectionHolder.find('.depositReceipt').length);
    $addDepositReceiptLink.children('a').on('click', function(e) {
        e.preventDefault();
        addDepositReceiptForm($depositReceiptCollectionHolder, $newDepositReceiptLinkLi);
    });

    function addDepositReceiptForm($depositReceiptCollectionHolder, $newLinkLi) {
        var prototypeCreate = $depositReceiptCollectionHolder.data('prototype-create');
        var index = $depositReceiptCollectionHolder.data('index');
        var newFormCreateRow = $('<tr class="depositReceipt"><td width="15%"><i class="fa fa-upload" aria-hidden="true"></i></td></tr>');
        var newFormCreate = newFormCreateRow.append($('<td width="70%" ></td>').append(prototypeCreate.replace(/__name__/g, index))).append($('<td width="15%" class="depositReceipt-delete"></td>'));
        $depositReceiptCollectionHolder.data('index', index + 1);
        $newLinkLi.before(newFormCreate);
        addDocumentFormDeleteLink(newFormCreate.find('.depositReceipt-delete'));
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