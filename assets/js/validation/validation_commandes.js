window.addEventListener("load", function() {
  var envoi = document.getElementById("envoi");

  envoi.addEventListener("click", function(e) {
    erreurs = 0;

    var Id = document.getElementById("client_id");

    if (isNaN(Id.value)) {
      document.getElementById("errID").innerHTML =
        "Le ID du client doit être un numéro.";
      erreurs++;
    } else {
      document.getElementById("errID").innerHTML = "";
    }

    var adresse = document.getElementById("adresse_livraison");
    var adresseRegEx = /^[\d]{1,5} [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+ [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð -]+$/;

    if (!adresseRegEx.test(adresse.value)) {
      document.getElementById("errAdresse").innerHTML =
        "L'adresse doit être au format '[numero civique] [rue/avenue/boulevard] [nom de rue/avenue/boulevard]'.";
      erreurs++;
    } else {
      document.getElementById("errAdresse").innerHTML = "";
    }

    var ville = document.getElementById("ville_livraison");
    var villeRegEx = /^[a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/;

    if (!villeRegEx.test(ville.value)) {
      document.getElementById("errVille").innerHTML =
        "La ville ne doit contenir que des lettres et des traits d'union.";
      erreurs++;
    } else {
      document.getElementById("errVille").innerHTML = "";
    }

    var cp = document.getElementById("cp_livraison");
    var cpRegEx = /^[a-zA-Z][\d][a-zA-z] [\d][a-zA-z][\d]$/;

    if (!cpRegEx.test(cp.value)) {
      document.getElementById("errCP").innerHTML =
        "Le code postal doit être au format 'X1Y 2Z3'.";
      erreurs++;
    } else {
      document.getElementById("errCP").innerHTML = "";
    }

    if (document.getElementById("commande_date") !== null) {
      var date = document.getElementById("commande_date");
      var dateRegEx = /^(20[\d]{2})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]) ([01][\d]|2[1-3]):([0-6]\d):([0-6]\d)$/;

      if (!dateRegEx.test(date.value)) {
        document.getElementById("errDate").innerHTML =
          "La date doit être valide.";
        erreurs++;
      } else {
        document.getElementById("errDate").innerHTML = "";
      }
    }

    console.log(erreurs);

    if (erreurs > 0) {
      e.preventDefault();
    }
  });
});
