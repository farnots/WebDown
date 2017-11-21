#  Compression Jpeg

> [site web des sources](http://donias.vvv.enseirb-matmeca.fr)
>
> marc.donias@enseirb-matmeca.fr

[TOC]

## Principe

> * Codage du contenu fréquentiel "prerceptible" 
>
>
> * 1981 : date du **jpeg**
> * Codage avec perte, permet de 10 à 15% de tailles jpeg inférieur.

### Compression

Le but est d'atténuer les hautes fréquences pour avoir moins d'information à coder.

* **Découpage des blocs** en $8*8$
* On les **exprimes en fréquentiel** (*Transformé en cos discret*)
  * Ici cadre mono-canal (mais marche en *rgb* en utilisant $U$ et $V$)
* On **quantifie l'information** en limitant le nombre d'entier possible (avec le maximum de $0$)
* **Codage entropique** sans perte (type *Huffman*)
* On dispose d'un **flux minaire**

C'est la phase de **quantification** qui altère la qualité de l'image en changeant l'image. Le principe n'est donc pas bijectif

### Décompression

Pour re-former l'image, on refait le cheminement inverse pour obtenir une image compressé *décompressé*. 

On dispose alors une erreur en faisant la différence des deux images permettant de générer une image représenant les pertes de compression.

### Cheminement 

Le premier éléments en fréquence situé en $1*1$ représente le continu.

Les coefficiants obtenus après la *transformé de Fourrier* serait complexe. En utilisant la *transformé en cosinus* alors on a que des valeurs réels. Et donc deux fois moins d'information à coder.

Pour quantifier la matrice fréquentiel on utilise cette fonction :
$$
F_Q = E [\frac{F}{\alpha * Q}]
$$
Avec

-  $\alpha$ un paramètre non linéraire du facteur de qualité ($1$ à $99$)
-  $Q​$ matrice atténuant une gamme désirée de fréquences (valeur plus importante pour les hautres fréquences afin de les diminuer)
-  $E$ représente **la partie entière**

## Transformée (discrète) en cosinus (*DCT*)

<u>Formule en une dimension :</u>
$$
F(k) = W(k)\sum_{n=0}^{N-1}f(n)cos(\pi k \frac{2n+1}{2N})
$$
La transformé inverse est donc :
$$
f(n) = \sum_{n=0}^{N-1}W(k)F(k)cos(\pi k \frac{2n+1}{2N})
$$
<u>Formule pour le jpeg</u>


$$
F(u,v) = \sum_{m=0}^{7}\sum_{n=0}^{7} f(m,n)cos(\pi u \frac{2m+1}{16})cos(\pi u \frac{2n+1}{16})
$$

## Quantification

$\alpha$ déprend du paramètre $q$ car il suit les équations suivantes : 
$$
\alpha = \frac{50}{q}  \text{ si } 1 \leq q \leq 50\\
\alpha = \frac{100-q}{50}  \text{ si } 50 \leq q \leq 99
$$
Ce coefficiant $q=1$ permet donc de diviser jusqu'à $50$ (*tous les coefficiants sauf le continue seront egaux à 0*). A l'inverse le $q=50$ alors on obtiens toute l'image… mais sans poids diminué.

## Performances

- Le facteur des qualitées : $q$
- Le facteur de compression : $\frac{T_{\text{initiale}}}{T_{\text{finale}}}$ en taille d'octet entre les images de *intiale* et *finale*.

## Simulation Jpeg

### Simulation 1 bloc

On visualise l'image et réaliser une simulation sur un bloc.

```matlab
imread('cameraman.tif');
```

On recentre les données en faisant un $-128$ qui va permettre de diminuer le premier coefficient lors de la *DCT* : le **DC** (celui qui représente la composant continue).

**<u>Attention :</u>** Penser à recentrer l'image lors de la reconstruction (cad $+128$).

### Simulation image

Utiliser la fonction `blkproc` pour traiter tous les blocs de l'image sans aucune boucle `for`.

### Analyse des performances

Calculer le rapport signal sur bruit (*SNR*)
$$
PSNR = 20 log_{10}\frac{255}{MSE}\\
\text{avec  } {MSE}^2 = \bar{ {I-I_c}^2 }
$$
Pour recreer les images *jpeg*, on peut comparer nos résultats avec la fonction de *MATLAB* en utilisant `imwrite` à partir de l'**image d'origine**.

De plus `imfinfo` on peut obtenir la taille de l'image passé en paramètre.



## Difficultées souvent rencontrée 

- Affichage de l'image modifié 
- Confusion $x / y \neq i/j$
  - $i/j$ représente la matrice et $i$ est la première dimension
  - $x/y$ représente le géométrique et $x$ est la première dimension
- **Ne pas** faire de calculs avec des entiers (sinon $10-30=-20$ sera faux)