from classes import *
import sqlite3

"""Ce fichier ne sert plus dans l'acte 4 du projet"""
    

    
def insertion_dans_base(song):
    try:
        with sqlite3.connect('chansons.db') as db:
    
            cursor = db.cursor()
    
            cursor.execute('INSERT INTO chansons VALUES (?,?,?,?,?)',(song.ISRC,song.titre,song.artiste,song.duree,song.img))
            db.commit()
        
        db.close()
    except:
        print('Une erreur est survenue lors de la l\'insertion dans la base')

def MAJ_chanson(attribut,valeur,ISRC):
    
    """MAJ la table chanson en changeant la valeur de l'attribut associée à l'ISRC"""
    sql = "UPDATE chansons SET " + str(attribut)+ "=? WHERE ISRC=?"
    try:
        with sqlite3.connect('chansons.db') as db:
    
            cursor = db.cursor()
    
            cursor.execute(sql,(valeur,ISRC,))
            db.commit()
        
        db.close()
    except:
        print('Une erreur est survenue lors de la mise à jour de la chanson d\'IRSC {ISRC}')
def supprimer_chanson(ISRC):
    
    try:
        with sqlite3.connect('chansons.db') as db:
    
            cursor = db.cursor()
    
            cursor.execute(f'DELETE FROM chansons where ISRC = ?',(ISRC,))
            db.commit()
        
        db.close()
    except:
        print('Une erreur est survenue lors de la suppression de la chanson d\'IRSC {ISRC}')
    
def lit_base():
    
    try:
        with sqlite3.connect('chansons.db') as db:
    
            cursor = db.cursor()
    
            cursor.execute('SELECT * FROM chansons ')
    
            for song in cursor:
       
                print('{} | {:25} | {:15} | {:5} | {}'.format(*song))
      
        
        db.close()
    except:
        print('Une erreur est survenue lors de la lecture dans la base')

def instancie():
    """renvoie la  playlist alimentée par toutes les chansons de la table chansons"""
    maPL = Playlist()
    try:
        with sqlite3.connect('chansons.db') as db:
    
            cursor = db.cursor()
    
            cursor.execute('SELECT * FROM chansons  ')
    
            for song in cursor:
       
                new_song = CHANSON(song[0],song[1],song[2],song[3],song[4])
                maPL.insere(None,new_song)
      
        
        db.close()
        return maPL
    except:
        print('Une erreur est survenue lors de l\'instanciation')
    
if __name__=='__main__':
    #insertion_dans_base(CHANSON("USUM71021486","Take the long way home","Supertramp","5:09","images/USUM71021486.gif"))
    #supprimer_chanson("GBCD87898765")
    #MAJ_chanson("titre","Castles made of sand","USQX90900760")
    
    #lit_base()
    maPL = instancie()
    maPL.lit_tout()

    
