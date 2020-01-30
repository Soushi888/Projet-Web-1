window.addEventListener("load", function() {
  var envoi = document.getElementById("envoi");

  envoi.addEventListener("click", function(e) {
    var erreurs = 0;

    var categorie = document.getElementById("categorie");
    var categorieRegEx = /^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/;

    if (!categorieRegEx.test(categorie.value)) {
      document.getElementById("errCategorie").innerHTML =
        "Catégorie incorrect. Veuillez utiliser seulement des lettre et traits d'union.";
      erreurs++;
    }

    console.log(erreurs);

    if (erreurs > 0) {
      e.preventDefault();
    }
  });

  if (document.getElementById("envoiMod") !== null) {
    var envoiMod = document.getElementById("envoiMod");

    envoiMod.addEventListener("click", function(e) {

      erreurs = 0;

      var nouveau_nom = document.getElementById("nouveau_nom");
      var nouveau_nomRegEx = /^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/;

      if (!nouveau_nomRegEx.test(nouveau_nom.value)) {
        document.getElementById("errNouveau_nom").innerHTML =
          "Catégorie incorrect. Veuillez utiliser seulement des lettre et traits d'union.";
        erreurs++;
      }

      console.log(erreurs);

      if (erreurs > 0) {
        e.preventDefault();
      }
    });
  }
});
