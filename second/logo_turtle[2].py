from turtle import *
'''change la couleur du fond
	@parom:rouge:int,vert:int,bleu:int'''
bgcolor(0,0,0)

a=Turtle() #rajoute clone
b=Turtle()
c=Turtle()
d=Turtle()
e=Turtle()
f=Turtle()

'''change la vitesse des tortues
	@parom:speed:str'''
a.speed("fastest")
b.speed("fastest")
c.speed("fastest")
d.speed("fastest")
e.speed("fastest")
f.speed("fastest")

'''deplace les tortues à l'endrois voulus et crée une liste
	@parom:x:int,y:int'''
list=[b,c,d] #liste les clones
b.goto(-150,30)
c.goto(-150,50)
d.goto(-150,70)
e.goto(-45,10)
f.goto(25,120)
f.left(90)


'''change la couleur des tortues
	@parom:couleur:str'''
a.color("white") #met la couleur
b.color("light grey")
c.color("dark grey")
d.color("grey")
e.color('white')
f.color('white')


a.ht() #curseur invisible
b.ht()
c.ht()
d.ht()
e.ht()
f.ht()
"""
listt=[a,b,c,d,e,f]
for i in listt:
    i.ht()
"""
'''trace courbes
	@parom:taille:int,left:int,rayon:int,longueur:int'''
for i in list: #forme
	i.width(10)
	i.left(25)
	i.circle(-400,50)

'''trace multiples triangles
	@parom:nombres:int'''
nombres = 3

for triangle in range(1, nombres + 1):
    # dessine un triangle
    e.forward(triangle * 10)
    for _ in range(2):
        e.left(120)
        e.forward(triangle * 20)
    e.left(120)
    e.forward(triangle * 10)

    e.right(90)
    e.penup()
    e.forward(7)
    e.pendown()
    e.left(90)

'''deplace la tortue et la fait ecrire
	@parom:x:int,y:int,width:int'''

def ende(): #definit la lettre e
    a.penup()
    a.width(10)
    a.goto(0,0)
    a.pendown()
    a.forward(60)
    a.left(90)
    a.circle(30,320)

def endn(): #definit la lettre N
    a.penup()
    a.goto(80,-25)
    a.width(10)
    a.pendown()
    a.left(90)
    a.forward(50)
    a.right(140)
    a.forward(65)
    a.left(140)
    a.forward(50)


def endd(): #definit la lettre D
    a.penup()
    a.width(10)
    a.goto(150,-25)
    a.pendown()
    a.left(90)
    a.forward(50)
    a.right(90)
    a.circle(-25,180)

ende() #écit le e
a.right(50) #remet la tortue vers la droite
endn() #écrit le N
a.right(90) #remet la tortue vers la droite
endd() #écrit D

'''definit la petale'''
def petale():
    f.circle(-40,50)
    f.goto(25,180)
    f.circle(40,50)


'''fait la fleur
    @parom:nb:int,x:int,y:int'''
f.forward(50)
nb=5
for i in range (nb):
     f.goto(25,180)
     petale()
     f.right(360/nb)

exitonclick() #attend que l'utilisateur click pour fermer la page
