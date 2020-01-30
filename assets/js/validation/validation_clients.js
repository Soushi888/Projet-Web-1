window.addEventListener("load", function() {
  var envoi = document.getElementById("envoi");

  envoi.addEventListener("click", function(e) {
    erreurs = 0;

    var nom = document.getElementById("nom");
    var nomRegEx = /^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u;

    if (!nomRegEx.test(nom.value)) {
      document.getElementById("errNom").innerHTML =
        "Nom incorrect. Veuillez utiliser seulement des lettre et traits d'union.";
      erreurs++;
    }

    var prenom = document.getElementById("prenom");
    var prenomRegEx = /^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u;

    if (!prenomRegEx.test(prenom.value)) {
      document.getElementById("errPrenom").innerHTML =
        "Prénom incorrect. Veuillez utiliser seulement des lettre et traits d'union.";
      erreurs++;
    }

    var email = document.getElementById("email");
    var emailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;

    if (!emailRegEx.test(email.value)) {
      document.getElementById("errEmail").innerHTML =
        "Email incorrect. Veuillez entrer une adresse email valide.";
      erreurs++;
    }

    if (document.querySelector("input+p")) {
        document.querySelector("input+p").innerHTML = "";
    }

    var telephone = document.getElementById("telephone");
    var telephoneRegEx = /^(438|514)-[\d]{3}-[\d]{4}$/;

    if (!telephoneRegEx.test(telephone.value)) {
      document.getElementById("errTelephone").innerHTML =
        "Le numéro de téléphone doit commencer par 438 ou 514 et être au format 'xxx-xxx-xxxx'.";
      erreurs++;
    }

    var adresse = document.getElementById("adresse");
    var adresseRegEx = /^[\d]{1,5} [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+ [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð -]+$/;

    if (!adresseRegEx.test(adresse.value)) {
      document.getElementById("errAdresse").innerHTML =
        "L'adresse doit être au format '[numero civique] [rue/avenue/boulevard] [nom de rue/avenue/boulevard]'.";
      erreurs++;
    }

    var ville = document.getElementById("ville");
    var villeRegEx = /^[a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/;

    if (!villeRegEx.test(ville.value)) {
      document.getElementById("errVille").innerHTML =
        "La ville ne doit contenir que des lettres et des traits d'union.";
      erreurs++;
    }

    var cp = document.getElementById("cp");
    var cpRegEx = /^[a-zA-Z][\d][a-zA-z] [\d][a-zA-z][\d]$/;

    if (!cpRegEx.test(cp.value)) {
      document.getElementById("errCP").innerHTML =
        "Le code postal doit être au format 'X1Y 2Z3'.";
      erreurs++;
    }

    console.log(erreurs);

    if (erreurs > 0) {
      e.preventDefault();
    }
  });
});
