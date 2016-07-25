# Introduction

Dans un premier lieu, créer un nouveau projet symfony. Plusieurs manières possibles : 

* En utilisant l'installeur Symfony
* Si vous avez le plugin PHPStorm Symfony, vous pouvez créer directement un projet en passant par ce dernier

### En passant par l'installateur Symfony

```
php symfony new <app_name>
```

![](http://puu.sh/oBuYY/653592521f.png)

# Préparation de la base de données

Je vous conseille de créer d'abord la base de données, une fois pour toute. Je vais ici utiliser **Uwamp** et **PHPMyAdmin** afin de le faire rapidement.
Utilisez ce que vous désirez afin de créer votre base de données.

Une fois la base de données créée, je vous recommande d'aller directement dans le fichier [parameters.yml](app/config/parameters.yml) et de modifier les informations relatives à votre base de données

#### /!\ ATTENTION /!\
Pensez bien, lorsque vous désirerez mettre en production votre application, ou encore la mettre en ligne sur un dépôt Github, de bien inclure dans votre fichier [.gitignore](.gitignore) ce fichier [parameters.yml](app/config/parameters.yml), 
car il contient les informations de votre base de données, ils ne donc doivent pas être accessible au grand public. De plus, vous avez sûrement remarqué le fichier [parameters.yml.dist](app/config/parameters.yml.dist), il s'agit d'un
fichier équivalent au fichier [parameters.yml](app/config/parameters.yml), cependant, il peut être utilisé au moment du déploiement. En effet, lorsque vous indiquez à Symfony que votre environnement est désormais en **production**
(en définissant la variable système **SYMFONY_ENV** = **prod** par exemple.

Une fois tout cela configuré, passons tout d'abord à la configuration de l'entité correspond à un projet dans un Portfolio.

# Création d'une entité sur Doctrine
Par défaut, Symfony utilise l'ORM Doctrine. Il est donc déjà prêt à l'emploi après que vous ayez utilisé la commande **php symfony new \<app_name\>**.

Une fois de plus, vous disposez de plusieurs manières pour créer cette dites entité. Je vous conseille, lorsque vous commencez à développer sous Symfony, de les créer manuellement vous même, en créant un fichier dans le répértoire
[src/AppBundle/Entity](src/AppBundle/Entity). La convention veut que, généralement, le nom de fichier d'une entité est au singulier. Donc pour un projet dans un portfolio, le fichier s'appellera **Project.php** et se situera donc dans
[src/AppBundle/Entity/Project.php](src/AppBundle/Entity/Project.php).

Une dernière chose avant de vouloir créer l'entité. Il faut tout d'abord définir quelles informations voulez vous qu'un projet contienne. Par exemple :

* Un titre
* Une description
* Une image à la une
* Un auteur
* Les plateformes sur lesquelles est disponible le projet
* La/les catégories

En plus de ces **attibuts**, on y ajoutera systématiquement (ou presque), des champs **created_at** et **edited_at**. Une fois de plus, il ne s'agit pas d'une règle, mais de bonnes pratiques à respecter. De plus, avoir en base
la date de création d'un projet est très utile, notamment pour trier par ordre du plus récent au plus ancien par exemple.

#### Créer l'entité sans le CLI

Commencez tout d'abord par créer un fichier dans le dossier [src/AppBundle/Entity](src/AppBundle/Entity). Renommez le en **Project.php**

```php
use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    ...
    
```

Commencez donc par importer l'espace de nom via le `use Doctrine\ORM\Mapping as ORM;`.

Ensuite, au dessus du nom de votre classe, doivent être présents le `@ORM\Table(name="project")` et le `@ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")`
Le **@ORM\Table** permet d'indiquer à Doctrine le nom de la table associé à cette entité. On utilise donc la propriété **name="project"**.

Quant au **@ORM\Entity**, il permet d'indiquer diverses propriétés relatives à cette entité. Ici, il définit quel est le **Repository** associé à cette classe (je n'aborderai pas ici la notion de Repository, n'hésitez pas à lire [la documentation](http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes) si cela vous intéresse !)

Ensuite, vous devez, pour chaque champs que vous voulez ajouter à votre entité, définir un attribut en php, puis au dessus l'annoter.
**@ORM\Column** va permettre d'indiquer plusieurs paramètres pour la colonne (champs) que vous êtes entrain d'ajouter, tel que son type, son nom, sa taille, si elle est nulle etc...

**@ORM\Id** est utilisé pour les champs qui doivent être reconnus en tant qu'identifiant

**@ORM\GeneratedValue(strategy="AUTO"** correspond à l'**AUTO_INCREMENT** en SQL. C'est à dire que ce champs va incrémenter de 1 à chaque nouvel ajout en base.

#### Générer l'entité via le CLI (Commande Line Interface)

```
php bin/console doctrine:generate:entity
```

Tout d'abord, à quoi correspond le **bin/console** ? Il s'agit enfait d'un fichier, intépretable par PHP (c'est donc ce pourquoi on utilise le **php** avant la commande), qui est crée par symfony et qui nous permet simplement
d'éxécuter diverses commandes, toutes très utiles. N'hésitez pas à jeter un coup d'oeil à ce fichier, il s'agit d'un fichier php.


Pour en revenir à la création d'une entité, après avoir exécuté la commande, vous vous retrouvez via un assistant de création assez clair : 
![](http://puu.sh/oBwKQ/0a02625036.png)

Vous vous demandez sûrement ce à quoi correspond un **Bundle** ? Il s'agit d'une notion assez importante de Symfony. En effet, ce dernier repose sur l'utilisation de Bundles. Pour faire simple, un Bundle est un ensemble
de fichiers (PHP, Javascript, CSS, images...). On pourrait les comparer à des plugins, car un Bundle contient généralement un module pour une application. Dans le cas de notre application, un seule bundle suffira, mais dans le cas
d'applications très complexes, nous pourrions avoir le besoin de plusieurs bundles : un pour le blog, un pour l'adminisatration... La [documentation de Symfony](http://symfony.com/doc/current/book/bundles.html#page-creation-bundles) est très claire sur le sujet.

Lorsque l'assistant vous demande donc la notation raccourcie de votre entité, sous la forme **\<NomDuBundle>:\<NomDeLentite>**, vous pouvez taper **AppBundle:Project** (par défaut, un Bundle nommé **App** est déjà crée)
**Si vous avez à ce moment là, une erreur de type PDOException, c'est que vous n'avez pas configuré votre base de données dans le fichier [parameters.yml](app/config/parameters.yml), car afin de créer l'entité, Doctrine a besoin de se
connecter à la base de données**

On vous demande ensuite le type de format que vous voulez utilisez (par défaut, ce qui est entre crochet est déjà sélectionné, donc si vous êtes d'accord avec le choix par défaut, tapez juste sur Entrer). Nous utiliserons le format
par défaut, qui sont les annotations (c'est à dire que nos informations à propos des attributs Doctrine seront directement stockés dans le fichier PHP correspondant à l'entité). Par défaut, l'assistant créer automatiquement un champs
**id**, vous n'avez donc pas à vous en préoccuper

Laissez vous donc guider par l'assistant, il est très intuitif.

*A savoir : Si vous (et ce que vous devez essayer de faire un maximum) respectez les conventions de nommages, en terminant un nom d'attribut par la valeur '\_at', l'assistant va automatiquement reconnaître qu'il s'agit d'un
type Datetime, pareil si vous commencez votre nom par 'is\_', il le mettra en booléen*

Voici mon fichier [Project.php](src/AppBundle/Entity/Project.php)

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="text", nullable=true)
     */
    private $imageUrl;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return Project
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }
}

```

Comme vous pouvez le voir, l'assistant s'est occupé de créer directement les attributs d'instance, mais aussi les getters et les setters. Vous pouvez rajouter ce que vous désirez dans ce fichier ne vous en faîtes pas,
il s'agit d'une simple classe PHP, afin de manipuler une entité en base. Vous n'avez pas à vous pré occuper de la conversion en classe PHP -> Base de données, c'est l'ORM qui s'occupe de tout cela.

Si vous êtes attentifs, vous avez peut être remarqué que je n'ai pas d'attributs correspondant à mon auteur, ni à mes catégories. Pourquoi ? Car il aurait d'abord fallu penser à créer en premier toutes les entités dont
nous aurions eu besoin. Bon dans le cadre de cette application, cela reste très basique, il nous faut un auteur, et une catégorie. A vous de définir ce que vous voulez dans chacune de ces entités.

#### /!\ ATTENTION /!\
Si vous voulez réellement faire un système de portfolio, où par exemple un utilisateur pourrait disposer de son propre portfolio, il faudrait d'abord que vous ayez un système d'inscription/connexion d'utilisateurs en marche.
Ce tutorial ne s'attardera donc pas sur cette partie, et je suppose donc que vous en avez un de fonctionnel. Sinon, vous pouvez simplement stocker l'auteur comme chaîne de caractère, afin d'afficher au moins quelque chose.


# Les relations entre les entités sur Doctrine
Avant de continuer, il vous faudra peut-être quelques notions de SGBDR, mais je vais essayer de le simplifier au maximum.

On veut que, pour chaque projet, un seul auteur ait créée ce projet. Cependant, un même auteur peut avoir créé plusieurs projet. Donc de Project -> User (mon entité s'appelle User, mais un auteur est un utilisateur), il y a
une relation de n1 (Au maximum **N** projets peuvent appartenir à **UN** seul et même auteur, car un auteur rappelez vous, peut être à l'origine de plusieurs projets). 
Sur Doctrine, cette relation s'appelle **OneToMany** ou **ManyToOne** (l'ordre est très important, nous y reviendrons)

Dans une relation, il y a toujours une entité dites **Propriétaire** et une relation dites **inverse**. La proriétaire est généralement celle, dans une relation ManyToOne ou OneToMany, le côté Many.
Si vous avez quelques notions de SGBDR, cela va vous paraître plus simple : La table propriétaire est celle qui contient la référence à la clé étrangère de l'autre entité. Donc dans la table **Project**, nous
aurions un attribut **user_id** qui ferait référence à notre auteur, dans la table **User**.

Dans votre entité [src/AppBundle/Entity/Project.php](src/AppBundle/Entity/Project.php), rajoutez un attribut **$author**, et ces annotations correspondantes :

```php
    use Doctrine\ORM\Mapping as ORM;
    ...
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="projects")
     */
     
    private $author;
    
```

Le `@var User` est totalement facultatif. Il permet d'indiquer à votre IDE le type de retour de cet attribut. 

Le `@ORM\ManyToOne` est enfait une classe, que vous devez au préalablement importer, si votre IDE ne l'a pas fait automatiquement.

`targetEntity` permet d'indiquer l'extrémité de la relation, c'est à dire avec quelle entité nous créons la relation, et le `inversedBy`, qui est toujours dans l'entité propriétaire, permet d'indiquer la relation inverse. 

Il faut donc aussi créer cette relation **projects**, qui sera contenu
dans [src/AppBundle/Entity/User.php](src/AppBundle/Entity/User.php).


```php
    use Doctrine\ORM\Mapping as ORM;
    ...
    
    /**
     * @var Project[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Project", mappedBy="author")
     */
     
    private $projects;
```

Notez bien la différence ici : on utilise une relation **OneToMany**, car nous somme dans l'entité inverse. **UN** auteur peut avoir fait **PLUSIEURS** projets.
Le `mappedBy` est la même chose qu'**inversedBy**, mais dans l'entité inverse.

Pour faire plus simple, en général, le inversedBy fait référence à l'attribut dans la targetEntity (donc User) qui a la relation **OneToMany**, et le mappedBy fait référence à l'attribut dans la targetEntity (donc Project) qui a la relation **ManyToOne**.

Ces notions de propriétaire et d'inverse peuvent être parfois difficile à appréhender, mais plus vous ferez de simples exemples, plus vous comprendrez ces notions.

Une fois ces attributs rajoutés, pensez aussi à rajouter les getter et les setters. Une fois de plus, le CLI de Symfony nous mâche le travail, exécutez simplement la commande :

`php bin/console doctrine:generate:entities <BundleName>`

#### L'entité Category

Pour nos catégories, nous voulons le comportement suivant : Un projet peut appartenir à plusieurs catégories. Cela signifie que plusieurs catégories pourront appartenir à plusieurs projets.

Il s'agit donc d'une relation **n à n**, ou **ManyToMany** sur Doctrine. Vous devriez donc rajouter un nouvel attribut dans [src/AppBundle/Entity/Category.php](src/AppBundle/Entity/Category.php) et dans [src/AppBundle/Entity/Project.php](src/AppBundle/Entity/Project.php)

**Category.php**

```php
    /**
     * @var Project[]
     * 
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Project", mappedBy="categories")
     */
     
    private $projects;

```

**Project.php**

```php
    /**
     * @var Category[]
     * 
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category", inversedBy="projects")
     */
     
    private $categories;

```

N'oubliez pas de réexécuter la commande `php bin/console doctrine:generate:entities <BundleName>` afin de générer les getters et les setters des attributs que vous venez de rajouter.

Une fois ces modifications ajoutées, il est temps de réellement appliquer ces changements en base. Une fois de plus, le CLI de Symfony nous fournit une commande toute prête :

`php bin/console doctrine:schema:update [-f | --dump-sql]`

Afin que les reqûetes SQL soient réellement effectués, vous devez exécuter la commande avec le paramètre -f (**force**). Si vous voulez aussi voir ce que Doctrine réalise comme requêtes, ajoutez le paramètre --dump-sql.

![](http://puu.sh/oBBwl/28408fea10.png)

Une fois tout cela fait, votre système est normalement prêt, vous n'aurez plus qu'à mettre en forme tout cela, faire un simple CRUD afin de gérer les projets etc...

Voici un petit exemple sur comment vous devriez ajouter un nouveau projet par exemple : 

![](http://puu.sh/oBCIp/d2a21f735e.png)

Grâce à Doctrine, ajouter un projet est fait d'une manière totalement orientée objet ! 
Ensuite, un petit aperçu sur comment récupérer ce projet sur Twig :

![](http://puu.sh/oBCYK/a35ece3ca6.png)

J'espère que le tutoriel est assez compéhensif. N'hésitez pas à clone le projet, le fork etc...





 


