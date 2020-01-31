<header class="titre">
    <img src="../assets/img/logo.png" alt="Logo MusicStore" class="logo">
    <h1>MusicStore&trade;</h1>
    <h2><?= $titre ?></h2>
    <h3 >
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h3>
</header>