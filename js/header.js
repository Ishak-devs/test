// Écouteur d'événement qui s'exécute une fois que le DOM (structure HTML) est entièrement chargé
document.addEventListener('DOMContentLoaded', function() {
    //création fonction pour les produits recherché et la datalist
    const productInput = document.getElementById('product');
    const datalist = document.getElementById('products');

    //fonction pour gerer la datalist a chaque saisie
    productInput.addEventListener('input', function() {
        //récuperer la valeur du champ de recherche
        const query = productInput.value;

        //conditionner la recherche à au moins 1 caractère
        if (query.length > 0) {
            //création de variable afin de gérer les requêtes de maniere asynchrone
            const rechercheclient = new XMLHttpRequest();
            //récuperation du terme rechercher depuis le script et encodage de la recherche
            rechercheclient.open('GET', 'produits_suggestions.php?search_term=' + encodeURIComponent(query), true);
            //s'assurer que le navigateur ne garde pas en cache les résultats de la recherche
            rechercheclient.setRequestHeader('Cache-Control', 'no-cache');
            //meme chose pour les anciens navigateurs
            rechercheclient.setRequestHeader('Pragma', 'no-cache');

            //appel de rechercheclient a chaque fois que l'état de la requete change
            rechercheclient.onreadystatechange = function() {
                //condition de la requete, si requete ok et statut ok
                if (rechercheclient.readyState === XMLHttpRequest.DONE && rechercheclient.status === 200) {
                    //les résultats de la recherche vont dans la datalist
                    datalist.innerHTML = rechercheclient.responseText;
                }
            };

            //fonction pour gérer les erreurs
            rechercheclient.onerror = function() {
                console.error('Erreur de la requête.');
                datalist.innerHTML = '<option value="">Erreur lors de la récupération des suggestions.</option>';
            };

            //lancement de la requete
            rechercheclient.send();
        } else {
            //si la recherche est vide on vide la datalist
            datalist.innerHTML = '';
        }
    });

    //création de fonctioon pour gérer le changement de produit
    productInput.addEventListener('change', function() {
        //affichage de suggestion concernant le produit recherché
        const selectedOption = Array.from(datalist.options).find(option => option.value === productInput.value);
        //on accorde un id au produit trouvé
        if (selectedOption) {
            productInput.setAttribute('data-product-id', selectedOption.getAttribute('data-id'));
        } else {
            productInput.removeAttribute('data-product-id');
        }
    });
});

//fonction pour gérer la recherche d'un produit
function handleSubmit(event) {
    event.preventDefault();

    //stockage de l'id
    const productInput = document.getElementById('product');
    const selectedProductId = productInput.getAttribute('data-product-id');
//si aucun produit sélectionné
    if (!selectedProductId) {
        alert('Aucun produit sélectionné. Veuillez réessayer.');
        return;
    }

    //on re crée une fonction rechercheclient cette fois pour la recherche des détails du produit
    const rechercheclient = new XMLHttpRequest();
    rechercheclient.open('GET', 'produits_details.php?produit_id=' + encodeURIComponent(selectedProductId), true);

    //validation de la requête si communication http ok
    rechercheclient.onreadystatechange = function() {
        if (rechercheclient.readyState === XMLHttpRequest.DONE) {
            if (rechercheclient.status === 200) {
                window.location.href = 'produits_details.php?produit_id=' + selectedProductId;
            } else {
                alert('Erreur lors de la récupération des détails du produit. Code d\'état : ' + rechercheclient.status);
            }
        }
    };

    //gestion des erreurs
    rechercheclient.onerror = function() {
        alert('Erreur lors de la récupération des détails du produit. Veuillez réessayer.');
    };

    //execution de la requête
    rechercheclient.send();
}
