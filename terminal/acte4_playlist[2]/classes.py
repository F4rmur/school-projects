from collections import deque

class CHANSON:
    def __init__(self,isrc,titel,artist,time,im,suivant=None):
        self.ISRC = isrc
        self.titre = titel
        self.artiste = artist
        self.duree = time
        self.img = im
        self.next = suivant
    
    
class Playlist():
    def __init__(self):
        self.tete = None


    def est_vide(self)-> bool:
        return self.tete is None
  
    
    def insere(self,previous_song,new_song)-> None:
        """insère une nouvelle chanson juste après previous_song"""
        #cas où previous_song est en tête
        
        if self.tete is None:
            
            tmp = new_song
            tmp.next = self.tete
            self.tete = tmp
        #cas général  
        else:
            chansoncourante = self.tete
            while chansoncourante.next is not None and chansoncourante.next is not previous_song:
                chansoncourante = chansoncourante.next
            tmp = new_song
            tmp.next = chansoncourante.next
            chansoncourante.next = tmp
       
    

    def taille(self)-> int:
        
        if self.tete == None:
            return 0
        else:
            t = 1
            chansoncourante = self.tete
            while chansoncourante.next is not None:
                t = t + 1
                chansoncourante = chansoncourante.next
            return t

    def plus_long_texte(self):
        chansoncourante = self.tete
        plt=max(len(chansoncourante.titre),len(chansoncourante.artiste))
       
        while chansoncourante is not None:
                        
            tmp = max(len(chansoncourante.titre),len(chansoncourante.artiste)) 
            if tmp > plt:
                plt = tmp
            chansoncourante = chansoncourante.next
        
        return plt

        
    # fonction de test     
    def lit_tout(self):
        chansoncourante = self.tete
        while chansoncourante is not None:
            print(chansoncourante.titre)
            chansoncourante= chansoncourante.next


    ####################################""

class pile:
        def __init__(self):
            self.contenu = []

        def est_vide(self):
            return len(self.contenu)==0

        def empiler(self,v):
            self.contenu.append(v)
        
        def depiler(self):
            """retourne l'élément en haut de pile si celle-ci n'est pas vide"""
            if not self.est_vide():
                return self.contenu.pop() 
            
            else:
                raise Exception("saisie incorrecte") 

    ###################################


if __name__=="__main__":

        song1=CHANSON("FRZ019102280","Volutes","Alain Bashung","3:25","images/FRZ019102280.gif")
        song2=CHANSON("GBCJN7800001","Miss You","Rolling Stones","4:49","images/GBCJN7800001.gif")
        #song3=CHANSON("USQX90900760","Castles Made of Sand","Jimi Hendrix","2:47","images/USQX90900760.gif")
        #song4=CHANSON("USUM71021486","Take The Long Way Home","Supertramp","5:19","images/USUM71021486.gif")

        

        ########## instanciation ############

        maPL=Playlist()
        maPL.insere(None,song1)
        maPL.insere(None,song2)
        #maPL.insere(None,song4)
        #maPL.insere(song4,song3)

        maPL.lit_tout()
        
def trier_par_poids_non_nul(liste):
    # filtrer les éléments où le poids est non nul
    liste_filtree = [couple for couple in liste if couple[1] != 0]

    # trie la liste filtrée par le poids (index 1 du tuple)
    liste_triee = sorted(liste_filtree, key=lambda x: x[1], reverse=True)

    # récupère les rangs triés
    rangs_tries = [couple[0] for couple in liste_triee]
    return rangs_tries

class Noeud:
    """Classe représentant un noeud dans le graphe"""
    def init(self, song):
        self.song = song
        self.visite = False



class Graphe:
    def __init__(self, chansons, matrice):
        """Initialise le graphe avec une liste de chansons et une matrice de transition."""
        self.chansons = chansons
        self.matrice = matrice
        self.nb_sommets = len(chansons)


    def voisins(self, sommet):
        """Retourne la liste des voisins accessibles depuis un sommet donné avec leurs poids."""
        index = self.chansons.index(sommet) # Trouve l'index du sommet dans la liste des chansons
        voisins = []
        for i, poids in enumerate(self.matrice[index]): # self.matrice[index] est la matrice du sommet  
            voisins.append((self.chansons[i],poids)) #fait une liste de tuples (voisin, poids)
        return voisins


    def iterative_dfs(graph, node):
        """
        Effectue une recherche en profondeur itérative sur un graphe.

        Args:
            graph (Graphe): Le graphe sur lequel effectuer la recherche. 
            node (str): Le sommet de départ pour la recherche.

        Returns:
            list: Une liste des sommets visités dans l'ordre de leur visite.
        """

        visited = []
        stack = deque() #deque = pile
        stack.append(node)

        while stack:
            node = stack.pop()
            if node not in visited:
                visited.append(node)
                #liste les voisins non visités triée par poids
                unvisited = [n for n in trier_par_poids_non_nul(graph.voisins(node)) if n not in visited]
                stack.extend(unvisited) #ajoute les voisins non visités à la pile

        return visited