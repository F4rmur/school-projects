from classes import *
import sqlite3



def instancie(depart, matrice):
    """
    renvoie la  playlist alimentée par toutes les chansons de la table chansons
    @param 
        depart: le titre de la chanson de départ
        matrice: la matrice de transition entre les chansons
    @return
        maPL: une instance de la classe Playlist contenant les chansons
    """
    maPL = Playlist()
    try:
        with sqlite3.connect('chansons.db') as db:
            chansons = []
            titres = []

            cursor = db.cursor()
            cursor.execute('SELECT * FROM chansons ORDER BY ISRC ASC')

            for song in cursor:
        
                new_song = CHANSON(song[0],song[1],song[2],song[3],song[4])
                titres.append(new_song.titre) #prépare la liste des sommets du graphe
                chansons.append(new_song) #prépare la liste des chansons de la playlist
        db.close()

        #crée l'instance de Graphe
        graphe = Graphe(titres, matrice)
        # return le parcours en profondeur du Graphe en format liste
        titres = Graphe.iterative_dfs(graphe,depart)

        #parcourt les titres et insère les chansons dans la playlist selon l'ordre du parcours
        for t in titres:
            for song in chansons:
                if song.titre == t:
                    maPL.insere(None, song)
        maPL.lit_tout()
        return maPL
    except:
        print('Une erreur est survenue lors de l\'instanciation')
