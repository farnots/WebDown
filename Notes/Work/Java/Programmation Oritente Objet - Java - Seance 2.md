# Programmation Orienté Objet
> Dr. Jean-Rémy Falleri - [site web](http://www.labri.fr/perso/falleri/perso/ens/pg220/)

[TOC]

## Séance 2 

### Rappel

#### Définition

**Objet :** 

* Chaque objet a une nom
* Il dispose d'attribut (valeur)
* Il dispose de méthode (traitement que l'on peut leur demander)

On a une **encapsulation** des données, cad chaque object dispose sa structure interne et est indépendante du reste du projet.

**Class :**

* Permet de décrire les objets
* Permet de créer des objets (il s'agit d'un moule)

#### Création d'objet

Il faut faire appel à un **constructeur**, c'est à dire une fonction normal qui est le *nom de la class* et n'a pas de type de retour.
On utilise le mot clé `new` afin de créer un objet :

```java
Point p = new Point(5, 5);
```

#### Attribut et méthode static

Il s'agit d'élément qui sont fixe dans chaque *class* ce qui permet d'économiser de l'espace mémoire en ne créant pas d'*attribut* (copié à chaque initialisation d'un nouvel objet).

#### Référence de l'objet

On utilise le mot clé `this` pour changer les valeurs de la *class*.

#### Delegation

Un objet, dans ses *attributs*, peut avoir **d'autres objets**. 
Permet donc de faire appel à leurs traitement. 

**Note :** Les objet sont passé par référence.

### Héritage

> On nomme **getter** et **setter** les méthodes qui fixe ou qui récupère les variables de la class

Permet de faire de la ré-utilisation de code.

Ainsi les éléments qui sont hérité d'une **super class** (nommé **sous class**) disposent des *méthode* et des *attributs* issus de la **super class**.

**<u>Attention :</u>** Chaque **class** ne peut avoir que UNE seule **super class**

De plus chaque class créé dans java hérite de base de la class **object**

#### Création d'un héritage

Il suffit d'ajouter le mot clé `extends` :

```java
class ElementAvecCouleur{
	Couleur c;
	
	ElementAvecCouleur(Couleur c) {
		this.c = c;
	}
}
class Point extends ElementAvecCouleur {
	int x;
	int y;
	
	Point(int x, int y, Couleur c) {
		super(c);
		this.x = x;
		this.y = y;
	}
}
```

En utilisant le mot clé `super` on fait appel au super constructeur. Il est également possible d'utiliser : 

```java
super.attribut()
super.methods()
```

**Attention :** l'utilisation de super nécessite qu'il s'agisse de la **première** ligne de code.

#### Polymorphisme

**<u>Upcast</u> :**

On a un objet d'un type donnée et que l'on souhaite le voir de manière plus abstrait.

```java
ElementRepere e = new Point(0,0);
```

**<u>Downcast</u> :**

Attention ici, ce passage est dangereux et nécessite des précautions car on est pas sûr de la forme de notre élément.
Un **downcast** peut planter pendant l'execution. 

```java
ElementRepere e = new Point(0,0); // OK
Point p1 = (Point) e; // OK
Droit d1 = (Droite) e; // WRONG
```
Ainsi pour vérifier et tester le Downcast il faut faire *un test de sous-typage*

```java
Object o = new String("test");

System.out.println(o instanceof Object); // true
System.out.println(o instanceof String); // true
System.out.println(o instanceof Point); // false

if (o instanceof String) {
	String s = (String) o;
	System.out.println(s.toUpperCase()); // true
}
```

> Il faut éviter au plus possible de faire des `instanceof` ou des `Downcast` ! Ceci est vérifié à l'execution et il est préférable que ça soit vérifié à la compilation



