

class Chanson:
    # Variable qui permet de savoir combien il y a d'instance
    compteur_instances = 0

    def __init__(self, id, nom_de_la_chanson, auteur, temps, image, suivant = None):
      
        """Initialise une instance de la classe Chanson.
            IN : id : int , Identifiant unique de la chanson.
            nom_de_la_chanson : str , Titre de la chanson.
            auteur : str , Nom de l'artiste.
            temps : float , Durée de la chanson en minutes.
            image : str , Nom de fichier pour l'image associée.
            suivant : Chanson , Pointeur vers la chanson suivante. Par défaut à None.
            OUT : None
        """

        self.id_song = id
        self.titre = nom_de_la_chanson
        self.artiste = auteur
        self.duree = temps
        self.img = image
        self.next = suivant
        
        # Ajoute une instance au compteur
        Chanson.compteur_instances += 1
    
    def append(self, new_song):
        
        """ Ajoute une nouvelle chanson à la fin de la liste chaînée.
        IN :  new_song : Chanson , Instance de Chanson à ajouter après la dernière chanson.
        OUT :  None
        """
        
        if self.next is None:
            self.next = new_song
            return
        
        self.next.append(new_song)

    def numero(self, i):
       
        """  Renvoie la i-ème chanson dans la liste chaînée, ou None si l'indice dépasse la longueur.
        IN :  i : int , Position de la chanson à récupérer (0 pour la chanson actuelle).
        OUT : Chanson : La chanson trouvée à la position i, ou None si non trouvée.
        """
        
        if i <= 0:
            return self

        if self.next is None:
            return None

        return self.next.numero(i - 1)


    # Methode pour compter le nombre d'instances
    def nombre_instances():
        return Chanson.compteur_instances

class Playlist:
    def __init__(self):
        """ Initialise une instance de Playlist avec une liste vide.
            OUT : None """
        self.tete = None

    def append(self, song):

        """Ajoute une chanson à la fin de la playlist.
        IN :  song : Chanson , Instance de Chanson à ajouter à la playlist.
        OUT :  None """
        
        if self.tete is None:
            self.tete = song
        else:
            self.tete.append(song)


    
    def __getitem__(self, i):
       
        """prend la i-ème chanson de la playlist.
        IN :  i : int , Indice de la chanson à récupérer.
        OUT :   Chanson , Chanson trouvée à l'indice i, ou None si non trouvée."""
        
        return self.tete.numero(i)

    
    
    def inserer(self, previous_song, new_song):
       
        """ Insère une nouvelle chanson après une chanson spécifiée dans la playlist.
        IN : previous_song : Chanson , Instance de Chanson après laquelle insérer new_song  . new_song : Chanson , Instance de Chanson à insérer.
        OUT : None  """
       
        # Si la playlist est vide 
        if previous_song is None:
            new_song.next = self.tete
            self.tete = new_song
            return

        # Traverse la liste pour trouver previous_song
        chanson_courante = self.tete
        while chanson_courante is not None and chanson_courante != previous_song:
            chanson_courante = chanson_courante.next

        # Si previous_song est trouvé, insère new_song après lui
        if chanson_courante is not None:
            new_song.next = chanson_courante.next
            chanson_courante.next = new_song
        

playlist=Playlist()
