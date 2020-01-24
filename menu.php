<nav id="main_menu">
    <fieldset>
        <legend>Navigation</legend>
        <fieldset>
            <legend>Vendeur</legend>
            <a href="../clients/index.php">Clients</a><a href="../commandes/index.php">Commandes</a>
        </fieldset>
        <?php if ($_SESSION['utilisateur']["utilisateur_type"] == "gestionnaire" || $_SESSION['utilisateur']["utilisateur_type"] == "administrateur") : ?>
            <fieldset>
                <legend>Gestionnaire</legend>
                <a href="../produits/index.php">Produits</a><a href="../categories/index.php">Catégories</a>
            </fieldset>
        <?php endif; ?>
        <?php if ($_SESSION['utilisateur']["utilisateur_type"] == "administrateur") : ?>
            <fieldset>
                <legend>Administrateur</legend>
                <a href="../utilisateurs/index.php">Utilisateurs</a>
            </fieldset>
        <?php endif; ?>
        <button><a href="../deconnexion.php">Déconnexion</a></button>
    </fieldset>
</nav>