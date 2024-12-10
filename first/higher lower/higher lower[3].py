import csv
from random import *





def création_de_la_base_de_donnees(csv_source):
    """
    Crée la base de données à partir du fichier CSV spécifié.


    @param:
        csv_source (str): Le nom complet du fichier CSV source (la partie .csv comprise).

    pré-condition = le CSV source est dans le même dossier que ce fichier python et ce dossier est ouvert.

    Retourne:
        list: Une liste de dictionnaires représentant les données du fichier CSV.

    """
    with open(csv_source, newline='', encoding='UTF-8') as fichier_original:
        lecteur_csv = csv.DictReader(fichier_original)
        donnees = list(lecteur_csv)
        return donnees






def le_jeu(indice,devine):
    """
    Fonction principale du jeu.


    @param:
        indice (str): Le nom de la colonne contenant l'indice pour comparer les valeurs.
        devine (str): Le nom de la colonne contenant la valeur à comparer.
    
	pré-condition = l’indice doit être un descripteur correspondant à des objet qui aident à deviner , devine doit être un descripteur correspondant à une valeur
                    et les descripteurs doivent faire partie du csv choisis.

    post-condition = le programme pose une question “plus grand ou plus petit” , donne des points quand on donne la bonne reponse, s’arrêtent quand on se trompe et donne le score final.
    """
    # Choisis deux chansons aléatoires initiales
    celui_qui_est_choisis_en_1 = randint(1, len(donnee)-1)
    celui_qui_est_choisis_en_2 = randint(1, len(donnee)-1)
   
    # Initialise les points du joueur et le statut du jeu
    points = 0
    GAME = True
   
    # Boucle du jeu
    while GAME == True:
        # Si le joueur a au moins 1 point, le deuxième objet comparer devient le premier
        if points >= 1 :
             celui_qui_est_choisis_en_2 = celui_qui_est_choisis_en_1
           
        # Choisis un nouveau premier objet aléatoire
        celui_qui_est_choisis_en_1 = randint(1,len(donnee)-1)
       
        # Demande au joueur s'il pense que le deuxième objet a plus ou moins de streams que le premier
        choisis = str(input(donnee[celui_qui_est_choisis_en_2][indice] + ' "' + donnee[celui_qui_est_choisis_en_2][devine] + '" ' + "a-t-il plus ou moins de "+ devine +" que " + donnee[celui_qui_est_choisis_en_1][indice] + " ? "))
       
        # Vérifie la réponse du joueur et met à jour les points en conséquence (et si le joueur répond autre chose, la réponse est compté fausse)
        if donnee[celui_qui_est_choisis_en_2][devine] >= donnee[celui_qui_est_choisis_en_1][devine] and choisis == 'plus':
            points += 1
            print(points)
        elif donnee[celui_qui_est_choisis_en_2][devine] <= donnee[celui_qui_est_choisis_en_1][devine] and choisis == 'moins':
            points += 1
            print(points)
        else:
            # Si la réponse est incorrecte,affiche la valeur à deviner, affiche le score final et termine le jeu
            print('Terminé ! il avais '+donnee[celui_qui_est_choisis_en_1][devine]+" "+devine)
            print('points :')
            print(points)
            GAME = False




# Appel de la fonction principale pour démarrer le jeu
donnee = création_de_la_base_de_donnees(input(str("choisisez le csv source ")))
le_jeu(str(input("choisissez un type d'indice dans cette liste: " +str( donnee[1].keys()))),str(input("choisissez ce que vous voulez comparer dans cette liste: " + str( donnee[1].keys()))))
