<?php
session_start();
include('db.php');
require_once('header.php');

if (isset($_SESSION['erreur_panier'])): ?>
    <p class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['erreur_panier']);
                                    unset($_SESSION['erreur_panier']); ?></p>
<?php elseif (isset($_SESSION['message'])): ?>
    <p class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']);
                                    unset($_SESSION['message']); ?></p>
<?php endif; ?>

<head>
    <link rel="stylesheet" href="css/panier.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.update-quantity-form').on('submit', function(event) {
                event.preventDefault();

                var $form = $(this);
                var $row = $form.closest('tr');
                var $sousTotalCell = $row.find('.sous-total');
                var $totalPanierCell = $('#total-panier');
                var quantite = $form.find('input[name="quantite"]').val();

                if (quantite == 0) {
                    $row.remove();
                    $.ajax({
                        url: 'update_quantity.php',
                        type: 'POST',
                        data: $form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                if (response.totalPanier == 0) {
                                    $('#panier-vide').show();
                                    $('#panier').hide();
                                } else {
                                    $('#panier-vide').hide();
                                    $('#panier').show();
                                    $totalPanierCell.text(response.totalPanier + ' €');
                                }
                            } else {
                                alert('Erreur lors de la mise à jour de la quantité.');
                            }
                        },
                        error: function() {
                            alert('Erreur lors de la mise à jour de la quantité.');
                        }
                    });
                } else {
                    $.ajax({
                        url: 'update_quantity.php',
                        type: 'POST',
                        data: $form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                if (response.totalPanier == 0) {
                                    $('#panier-vide').show();
                                    $('#panier').hide();
                                } else {
                                    $('#panier-vide').hide();
                                    $('#panier').show();
                                    $sousTotalCell.text(response.sousTotal + ' €');
                                    $totalPanierCell.text(response.totalPanier + ' €');
                                }
                            } else {
                                alert('Erreur lors de la mise à jour de la quantité.');
                            }
                        },
                        error: function() {
                            alert('Erreur lors de la mise à jour de la quantité.');
                        }
                    });
                }
            });
        });
    </script>
</head>

<div class="container">
    <div class="content">
        <?php
        if (isset($_SESSION['utilisateur_id'])) {
            $utilisateur_id = $_SESSION['utilisateur_id'];

            try {
                $sql = "
                    SELECT dp.produit_id, p.nom_produit, dp.quantite, dp.prix 
                    FROM details_panier dp
                    JOIN produits p ON dp.produit_id = p.produit_id
                    WHERE dp.utilisateur_id = :utilisateur_id";

                $stmt = $pdo->prepare($sql);
                $stmt->execute(['utilisateur_id' => $utilisateur_id]);
                $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo '<p>Erreur : ' . $e->getMessage() . '</p>';
                $details = [];
            }
        } else {
            $details = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
        }

        if ($details) {
            $totalPanier = 0;

            echo '<h2>Votre panier</h2>';
            echo '<div id="panier">';
            echo '<table border="1">';
            echo '<tr><th>Produit</th><th>Quantité</th><th>Sous-total</th><th>Actions</th></tr>';

            foreach ($details as $item) {
                $quantite = intval($item['quantite']);
                $prix = floatval($item['prix']);
                $produit_id = $item['produit_id'];

                echo '<tr data-price="' . $prix . '">';
                echo '<td>' . htmlspecialchars($item['nom_produit']) . '</td>';
                echo '<td>';
                echo '<form class="update-quantity-form">';
                echo '<input type="number" name="quantite" value="' . $quantite . '" min="0">';
                echo '<input type="hidden" name="produit_id" value="' . $produit_id . '">';
                echo '<button type="submit">Mettre à jour</button>';
                echo '</form>';
                echo '</td>';
                echo '<td class="sous-total">' . number_format($quantite * $prix, 2) . ' €</td>';
                echo '<td><a href="supprimer_panier.php?produit_id=' . $produit_id . '">Supprimer</a></td>';
                echo '</tr>';

                $totalPanier += $quantite * $prix;
            }

            echo '<tr><td colspan="3">Total</td><td id="total-panier">' . number_format($totalPanier, 2) . ' €</td></tr>';
            echo '</table>';
            echo '</div>';

            if ($totalPanier == 0) {
                echo '<div id="panier-vide" style="display: block;">Votre panier est vide.</div>';
                echo '<style>#panier { display: none; }</style>';
            } else {
                echo '<form action="passercommande.php" method="post">';
                echo '<button type="submit">Passer la commande</button>';
                echo '</form>';
            }
        } else {
            echo '<p>Votre panier est vide.</p>';
        }
        ?>
    </div>

    <?php require_once('footer.html'); ?>
</div>