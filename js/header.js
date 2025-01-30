$(document).ready(function() {
    const productInput = $('#product');
    const datalist = $('#products');

    // Gérer la datalist à chaque saisie
    productInput.on('input', function() {
        const query = productInput.val();

        if (query.length > 0) {
            $.ajax({
                url: 'produits_suggestions.php',
                type: 'GET',
                data: { search_term: query },
                cache: false,
                success: function(response) {
                    datalist.html(response);
                },
                error: function() {
                    console.error('Erreur de la requête.');
                    datalist.html('<option value="">Erreur lors de la récupération des suggestions.</option>');
                }
            });
        } else {
            datalist.html('');
        }
    });

    // Gérer la sélection du produit
    productInput.on('change', function() {
        const selectedOption = datalist.find('option').filter(function() {
            return $(this).val() === productInput.val();
        });

        if (selectedOption.length) {
            productInput.attr('data-product-id', selectedOption.attr('data-id'));
        } else {
            productInput.removeAttr('data-product-id');
        }
    });
});
