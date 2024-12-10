import tkinter
from PIL import Image, ImageTk
import sqlite3

def main_fenetre():
    
    """Initialise et configure la fenêtre principale de l'application.
    OUT : None"""

    fenetre = tkinter.Tk()
    fenetre.title("Spotify")
    fenetre.geometry("400x400")
    fenetre.minsize(200, 100)
    fenetre.config(bg="gray")
    affiche(fenetre)
    fenetre.mainloop()


def renvoie_image():
    """
    Récupère les chemins d'accès aux images des albums de musique stockées dans la base de données.
    
    OUT :
        image (list) : Liste de tuples contenant les chemins d'accès aux images des albums.
    """
    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            cursor.execute('SELECT img FROM chansons')
            image = cursor.fetchall()
            cursor.close()
            return image

    except sqlite3.OperationalError as e:
        print(f'une erreur {e} est survenue au niveau de l image')

def renvoie_ISRC():
    """
    Récupère les codes ISRC des chansons de musique stockées dans la base de données.
    
    OUT :
        ISRC (list) : Liste de tuples contenant les codes ISRC des chansons.
    """
    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            cursor.execute('SELECT ISRC FROM chansons')
            ISRC = cursor.fetchall()
            cursor.close()
            return ISRC

    except sqlite3.OperationalError as e:
        print(f'une erreur {e} est survenue au niveau de l ISRC')

def renvoie_titre():
    """
    Récupère les titres des chansons de musique stockées dans la base de données.
    
    OUT :
        titre (list) : Liste de tuples contenant les titres des chansons.
    """

    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            cursor.execute('SELECT titre FROM chansons')
            titre = cursor.fetchall()
            cursor.close()
            return titre

    except sqlite3.OperationalError as e:
        print(f'une erreur {e} est survenue au niveau du titre')

def renvoie_artist():
    """
    Récupère les noms des artistes des chansons de musique stockées dans la base de données.
    
    OUT :
        artiste (list) : Liste de tuples contenant les noms des artistes des chansons.
    """
    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            cursor.execute('SELECT artiste FROM chansons')
            titre = cursor.fetchall()
            cursor.close()
            return titre

    except sqlite3.OperationalError as e:
        print(f'une erreur {e} est survenue au niveau de l artiste')


def affiche(fenetre):
    
    """Affiche les chansons de la playlist dans la fenêtre spécifiée.
    IN :fenetre (Tk) : Fenêtre Tkinter dans laquelle les chansons sont afficher.
    OUT :None
    """
    
    for i in range(0,len(renvoie_ISRC())):
        try:
            # Affichage des images d'albums 
            img = Image.open(*renvoie_image()[i]).resize((50, 50))
            photo = ImageTk.PhotoImage(img)
            img_label = tkinter.Label(fenetre, image=photo)
            img_label.image = photo
            img_label.grid(row=i, column=0, padx=10, pady=10, sticky="w")
        except:
            print(f"une erreur est survenue au niveau de l'image de {renvoie_ISRC()[i][0]}")
        
        try:
            # Affichage des titres de musiques
            titre_label = tkinter.Label(fenetre, text=renvoie_titre()[i][0], font=("Arial",12 , "bold"), bg="gray", fg="white")
            titre_label.grid(row=i, column=1, padx=10, pady=10, sticky="w")
        except:
            print(f"une erreur est survenue au niveau du titre de {renvoie_ISRC()[i][0]}")

        try:
            # Affichage de l'artiste
            artiste_label = tkinter.Label(fenetre, text=renvoie_artist()[i][0], font=("Arial", 10), bg="gray", fg="lightgray")
            artiste_label.grid(row=i, column=2, padx=10, pady=10, sticky="w")
        except:
            print(f"une erreur est survenue au niveau de l'artiste de {renvoie_ISRC()[i][0]}")


main_fenetre()
