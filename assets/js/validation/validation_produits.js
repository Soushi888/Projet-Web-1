window.addEventListener("load", function() {
  var envoi = document.getElementById("envoi");

  envoi.addEventListener("click", function(e) {
    var erreurs = 0;

    var nom = document.getElementById("nom");
    var nomRegEx = /^[0-9a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u;

    if (!nomRegEx.test(nom.value)) {
      document.getElementById("errNom").innerHTML =
        "Nom incorrect. Veuillez utiliser seulement des lettre et traits d'union.";
      erreurs++;
    }

    var description = document.getElementById("description");

    if (description.value == "") {
      document.getElementById("errDescription").innerHTML =
        "Description obligatoire.";
      erreurs++;
    }

    var prix = document.getElementById("prix");
    var prixRegEx = /^[\d]*\.?[\d]{0,2}$/;

    if (!prixRegEx.test(prix.value)) {
      document.getElementById("errPrix").innerHTML =
        "Prix incorrect.";
      erreurs++;
    }

    var quantite = document.getElementById("quantite");

    if (quantite.value > 99999) {
      document.getElementById("errQuantite").innerHTML =
        "La quantité doit un être un nombre entre 0 et 99 999.";
      erreurs++;
    }

    console.log(erreurs);

    if (erreurs > 0) {
      e.preventDefault();
    }
  });
});
