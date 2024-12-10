function calcul(){ //calcul l'imc en fonction de la taille (en cm) est du poids (en kg)
    let retour = document.getElementById("retour");
    let taille = parseInt(document.getElementById("nb1").value);
    let poid = parseInt(document.getElementById("nb2").value);

    taille= taille/100 //met la taille en metres
    let imc = (poid/(taille*taille)).toFixed(2); //tofixed sert à limité le nombre de chiffres après la virgule
    retour.textContent = imc;
}




function afficherResultat() { //est declenché en appuillant sur le bouton et sert à executé tout le processus d'affichage
    let nb1 = document.getElementById("nb1").value; //taille
    let nb2 = document.getElementById("nb2").value; //poid

    if (nb1 == "" || nb2 == "") { //verifie si une des cases est vide
        document.getElementById("message").textContent = "veuillez remplir les deux cases";
        return; //arrete la fonction si une case est vide
    }else{
        document.getElementById("message").textContent = ""; //retire le message précédent si les cases sont remplies
    }

    let carte = document.querySelector(".card"); //.card représente le contenant de la partie calcul et réponse
    carte.style.width = "600px"; //change la taille de card
    calcul();

    let details = document.querySelector(".details");
    details.style.display = "block"; //fait apparaitre la partie reponse


    let fleche = document.getElementById("fleche");
    if (retour.textContent < 18.5) { //les 34 prochaines lignes serves à placer la fleche et afficher la bonne réponse dans la bonne couleur en fonction de l'imc 
    rotationAngle = 15; //definit la rotation de la fleche à faire
    document.getElementById("conseil").textContent = "Vous êtes trop maigre, il faut aller voir un medecin.";
    conseil.style.color = '#aa0000';
    }else{
        if (retour.textContent < 25) {
        rotationAngle = 45;
        document.getElementById("conseil").textContent = "Vous avez une corpulence normale.";
        conseil.style.color = '#24c72c';
        }else{
            if (retour.textContent < 30) {
            rotationAngle = 75;
            document.getElementById("conseil").textContent = "Vous êtes en surpoids, faite attention à votre alimentation.";
            conseil.style.color = '#d8db04';
            }else{
                if (retour.textContent < 35) {
                rotationAngle = 105;
                document.getElementById("conseil").textContent = "Vous êtes en obésité modérée, mangé mieux et faite du sport.";
                conseil.style.color = '#ffbb00';
                }else{
                    if (retour.textContent < 40) {
                    rotationAngle = 135;
                    document.getElementById("conseil").textContent = "Vous êtes en obésité sévére, allez voir un medecin.";
                    conseil.style.color = '#ad6122';
                    }else{
                        if (retour.textContent > 40) {
                        rotationAngle = 165;
                        document.getElementById("conseil").textContent = "Vous êtes en obésité massive, allez voir un medecin !";
                        conseil.style.color = '#aa0000';
                        }
                    }
                }
            }
        }
    }

    fleche.style.transform = `rotate(${rotationAngle}deg)`; //fait tourner la fleche
    
}



function resetTailleCard() {
    var card = document.querySelector(".card");
    card.style.width = "200px"; //change la taille de card
    let details = document.querySelector(".details");
    details.style.display = "none"; //fait disparaitre la partie reponse
}