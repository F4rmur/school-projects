import sqlite3
import Playlist


def inserer(chanson):
    """
    Insère une nouvelle chanson dans la base de données.

    Args:
        chanson (tuple): Un tuple contenant les informations de la chanson à insérer.
                         Les informations attendues sont :
                         - ISRC (str) : L'identifiant unique de la chanson.
                         - titre (str) : Le titre de la chanson.
                         - artiste (str) : Le nom de l'artiste.
                         - duree (str) : La durée de la chanson.
                         - img (str) : L'image associée à la chanson.

    Returns:
        None
    """
    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            cursor.execute("INSERT INTO chansons(ISRC,titre, artiste, duree, img) VALUES (? ,?, ?, ?, ?)", (chanson))
            conn.commit()

    except sqlite3.Error as e:
        print(f"An error occurred: {e}")


'''def instancier():
    """
    Instancie les chansons présentes dans playlist dans la base de données

    Cette fonction récupère toutes les chansons de la base de données, puis les instancie dans la playlist en utilisant la méthode 'inserer' de la classe Playlist.

    Returns:
        None
    """
    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            cursor.execute('SELECT * FROM chansons')
            songs = cursor.fetchall()  # Récupère toutes les chansons

        # Parcours des chansons récupérées et instanciation dans Playlist
        for song in songs:
            Playlist.playlist.inserer(*song[1:])  # Déstructure les champs directement, en ignorant l'ISRC
    except sqlite3.Error as e:
        print(f"An error occurred: {e}")'''


def afficher():
    """
    Affiche toutes les chansons présentes dans la base de données.

    Returns:
        None mais print les chansons
    """
    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            cursor.execute('SELECT * FROM chansons')

        for song in cursor:
            print('{} | {:5} | {:5} | {:5}'.format(*song))
    except sqlite3.Error as e:
        print(f"An error occurred: {e}")


def supprimer(ISRC):
    """
    Supprime une chanson de la base de données en fonction de son ISRC.

    Args:
        ISRC (str): L'identifiant unique de la chanson à supprimer.

    Returns:
        None
    """
    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            cursor.execute("DELETE FROM chansons WHERE ISRC = ?", (ISRC,))
            conn.commit()
    except sqlite3.Error as e:
        print(f"An error occurred: {e}")


def modifier(atribue,valeur,iscr):
    """
    Modifie une chanson dans la base de données en fonction de son ISRC.

    Args:
        atribue (str): Le nom de l'attribut à modifier (titre, artiste, duree, img).
        valeur (str): La nouvelle valeur de l'attribut.
        iscr (str): L'identifiant unique de la chanson à modifier.

    Returns:
        None
    """

    try:
        with sqlite3.connect('chansons.db') as conn:
            cursor = conn.cursor()
            sql=f"UPDATE chansons  SET " + atribue+ " = ? WHERE ISRC = ?"
            cursor.execute (sql, (valeur,iscr))
            conn.commit()
    except sqlite3.Error as e:
        print(f"An error occurred: {e}")


#instancier()