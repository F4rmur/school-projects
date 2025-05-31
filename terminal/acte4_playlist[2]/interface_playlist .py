import classes
import recommandation
from tkinter import *
from PIL import ImageTk, Image
from leo import matrice_transition

def calcule_dimensions():
    """Fixe une taille standard pour la fenêtre"""
    largeur = 400
    hauteur = 350
    return f"{largeur}x{hauteur}"

class App:
    
    def __init__(self, uneplaylist):
        self.fenetre = Tk()
        self.fenetre.title('Ma playlist')
        self.fenetre.configure(bg='grey')
        self.fenetre.geometry(calcule_dimensions())

        # Création du cadre principal avec un canvas et une scrollbar
        self.canvas = Canvas(self.fenetre, bg='grey')
        self.scrollbar = Scrollbar(self.fenetre, orient=VERTICAL, command=self.canvas.yview)
        self.scrollable_frame = Frame(self.canvas, bg='grey')

        self.scrollable_frame.bind(
            "<Configure>",
            lambda e: self.canvas.configure(
                scrollregion=self.canvas.bbox("all")
            )
        )

        self.window_frame = self.canvas.create_window((0, 0), window=self.scrollable_frame, anchor="nw")

        self.canvas.configure(yscrollcommand=self.scrollbar.set)
        
        self.scrollbar.pack(side=RIGHT, fill=Y)
        self.canvas.pack(side=LEFT, fill=BOTH, expand=True)
        
        self.affiche(uneplaylist)
        self.fenetre.mainloop()
        
    def affiche(self, uneplaylist: classes.Playlist):
        """Affiche au maximum 3 chansons visibles avec une barre de défilement."""
        
        song = uneplaylist.tete
        k = 0
        while song is not None:
            frame = Frame(self.scrollable_frame, height=106, bg='grey')
            frame.grid(row=k * 2, column=0, pady=5, padx=5, sticky=W)

            my_image = ImageTk.PhotoImage(Image.open(song.img))
            photo = Label(frame, image=my_image)
            photo.image = my_image  # Pour éviter que l'image soit supprimée par le garbage collector
            photo.grid(row=0, column=0, rowspan=2, padx=3, pady=3, sticky=W)
            
            text_frame = Frame(frame, bg='grey')
            text_frame.grid(row=0, column=1, rowspan=2, sticky=W, padx=10)
            
            title = Label(text_frame, text=song.titre, fg="white", font="ubuntu", bg="grey", anchor=W, justify=LEFT)
            title.pack(anchor=W)
            
            singer = Label(text_frame, text=song.artiste, fg="white", bg="grey", anchor=W, justify=LEFT)
            singer.pack(anchor=W)
            
            # Ajouter une barre de séparation entre les chansons
            if song.next is not None:
                separator = Frame(self.scrollable_frame, height=2, width=380, bg='black')
                separator.grid(row=k * 2 + 1, column=0, pady=2)
            
            song = song.next
            k += 1
                
if __name__ == '__main__':
    maPL = recommandation.instancie("Ma soeur",matrice_transition)
    monApp = App(maPL)
