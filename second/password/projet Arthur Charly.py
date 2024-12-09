#partie de Arthur
def dictionnaire(source):
    '''
    un dictionnaire formé des lettres qui peuvent ce suivre est crée à base du fichier mis en parametre de la fonction
    @param: source:txt, fichier:"str", dico:dictionnaire{key:str[liste:str]}

    pré-condition: le nom de fichier indiquer pour source est correct et source est un fichier texte qui contient des lettres de l'alphabet latin sans accent et en miniscule
                    le fichier source doit être dans le même dossier que le code
    
    post-condition: renvoie un dictionnaire avec toutes les lettres de l'alphabet latin qui peuvent se lires à la suite
    '''
    dico = {}   #définit dico en tant qu'un dictionnaire vide
    autorisé=[chr(97+n) for n in range (26)]    #définit les caractéres que l'on veut garder (les lettres sans accent en miniscule)
    with open(source, "r", encoding="ISO-8859-1") as file:  #ouvre le fichier choisis pour pouvoir l'utiliser ensuite
        fichier = file.read()   #définit fichier comme la suite de caractére contenus dans le fichier ouvert

        for i in range(len(fichier)):   #fait répéter le code ci-dessous pour chaque caractére de fichier
            if fichier[i] in autorisé  and fichier[i+1] in autorisé:    #vérifie si la future clé de dico et son item sont autorisé et saute au caractéres suivant si ce n'est pas le cas
                if fichier[i + 1] not in dico.get(fichier[i], []) :    #vérifie si la clé (caractére) et son item (caractére d'aprés) sont deja associer et saute au caractére d'aprés si c'est le cas
                    if fichier[i] not in dico:  #si le caractére n'est pas déja existant en clé de dico alors 
                        dico[fichier[i]] = []   #une, liste vide est crée pour cette nouvelle clé

                    dico[fichier[i]].append(fichier[i + 1]) #si le caractére est déja existant en clé de dico alors le caractére d'aprés est ajouté à la liste associé au caractére dans le dico
    
    return(dico)


#partie de charly

def modpass(dict,n):

    '''Génère un mot de passe composée de plusieurs mots séparés par des tirets.
    à partir d'un dictionnaire

    @Param :
    - dico (dict): Un dictionnaire représentant les associations de lettres pour générer les mots.
    - n (int): Le nombre de mots à générer.

    précondition = le dictionnaire ne doit pas être vide et doit comporter seulement des lettre en minuscule,le nombre de mot ne doit pas être nul et doit être positif.
    
    post-condition = le mot de passe doit être lisible.'''

    #listes de consonnes et de voyelles

    consonnes = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z']
    voyelles = ['a', 'e', 'i', 'o', 'u']

    lettres = chr(97 + randint(0,26))#Choisi une lettre minuscule au hasard dans le tableau Ascii 
    lettre = lettres[len(lettres)-1]#récupère la dernière lettres choisi
    mot = lettre    #stock la valeur lettre dans mot en tant que valeur tampon

    for k in range(n): #nombre de mots désiré
        for i in range(7):  # génère les  7 autre lettres 
            if lettre in consonnes:
                # choisi une voyelle pour la suivante si la lettre précédente est une consonne 
                lettre_ap = random.choice([v for v in voyelles if v != lettre])

            else:
                # choisi une consonne pour la suivante si la lettre précédente est une voyelle 
                lettre_ap = random.choice([c for c in consonnes if c != lettre])

            # Ajoute la lettre suivante au mot et met à jour la lettre précédente
            mot += lettre_ap
            lettre = lettre_ap

        # Ajoute un tiret entre les mots, sauf pour le dernier mot
        if k<n-1:  

            mot += "-"

    return mot


from random import randint
import random
print(modpass(dictionnaire("liste_francais.txt"),5))