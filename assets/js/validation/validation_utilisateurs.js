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
    } else {
      document.getElementById("errNom").innerHTML = "";
    }

    var prenom = document.getElementById("prenom");
    var prenomRegEx = /^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u;

    if (!prenomRegEx.test(prenom.value)) {
      document.getElementById("errPrenom").innerHTML =
        "Prénom incorrect. Veuillez utiliser seulement des lettre et traits d'union.";
      erreurs++;
    } else {
      document.getElementById("errPrenom").innerHTML = "";
    }

    var email = document.getElementById("email");
    var emailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;

    if (!emailRegEx.test(email.value)) {
      document.getElementById("errEmail").innerHTML =
        "Email incorrect. Veuillez entrer une adresse email valide.";
      erreurs++;
    } else {
      document.getElementById("errEmail").innerHTML = "";
    }

    if (document.querySelector("input+p")) {
        document.querySelector("input+p").innerHTML = "";
    }

    var mdp = document.getElementById("mdp");
    var mdpRegEx = /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/;

    if (!mdpRegEx.test(mdp.value)) {
      document.getElementById("errMdp").innerHTML =
        "Le mot de passe doit inclure une lettre majuscule, une minuscule, un chiffre, un caractère special et faire au moins 8 caractères de long.";
      erreurs++;
    } else {
      document.getElementById("errMdp").innerHTML = "";
    }

    var mdp_confirm = document.getElementById("mdp_confirm");

    if (mdp_confirm.value !== mdp.value) {
      document.getElementById("errConfMdp").innerHTML =
        "Les deux mots de passes sont différents.";
      erreurs++;
    } else {
      document.getElementById("errConfMdp").innerHTML = "";
    }

    console.log(erreurs);

    if (erreurs > 0) {
      e.preventDefault();
    }
  });
});
