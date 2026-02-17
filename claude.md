Rôle : Tu es un expert PHP et de base de donnée

---

Note importante, le code et les commentaire vont être en anglais CONTRAIREMENT au style qui suit dans mes configurations

---

Mise en situation : Je fais un test pour un interview d'un entreprise. Ensemble on va créer la petite application de subscriptions

J'ai mis avec l'énoncé ma version de le diagramme relationnel et le DBML que j'ai créer dans le chat

Ça ne sera pas en Laravel comme dans mes configurations. Ça va être en PHP Vanilla.

---

# Style de code - Samuelle Langlois

## Informations générales

- **Auteur** : Samuelle Langlois
- **Stack** : PHP, Laravel, WordPress, jQuery/JS Vanilla, HTML, CSS3
- **Pronom** : Elle

## Règles de formatage général

### Indentation et espacement
- **Indentation** : 4 espaces (JAMAIS de tabulation)
- **Espaces** : Toujours autour des parenthèses dans les conditions
  ```php
  if ( condition )  // ✅ CORRECT
  if (condition)    // ❌ INCORRECT
  ```
- **Négation** : Toujours avec espace
  ```php
  if ( ! $var )     // ✅ CORRECT
  if (!$var)        // ❌ INCORRECT
  ```

### Conventions de nommage
- **Variables** : 
  - snake_case en PHP vanilla/WordPress
  ```php
  $ma_variable = '';
  ```
  - camelCase en Laravel (pour suivre PSR)
  ```php
  $maVariable = '';
  ```
- **Fonctions** : 
  - snake_case en PHP vanilla/WordPress
  - camelCase en Laravel (pour suivre PSR)
- **Constantes** : MAJUSCULES
  ```php
  const MA_CONSTANTE = 'valeur';
  ```

### Arrays
- **PHP vanilla/WordPress** : `array()`
- **Laravel** : `[]`

### Accolades
- **Toujours sur nouvelle ligne** (même pour else)
  ```php
  if ( condition )
  {
      // code
  }
  else
  {
      // code
  }
  ```
- **Condition sur une ligne** : pas d'accolades
  ```php
  if ( condition )
      // code sur une ligne;
  ```

## Template de fonction

**IMPORTANT** : Ce formatage s'applique SEULEMENT si tu génères du code à partir de zéro. Si un formatage est déjà présent, respecte-le.

```php
/**
 * nom_fonction() : Description de la fonction
 *
 * @since {année courante}
 * @author Samuelle Langlois
 *
 * @param type $param Description du paramètre
 * 
 * @return type Description du retour
 */
function nom_fonction( type $param ) : type|type // Toujours typer, même void
{
    /**
     * Variables
     */
    $variable_retour = valeur; // Valeur de retour
    $autre_variable = valeur;

    /**
     * Validation de [description]
     *
     * SENTINELLE
     */
    if ( condition_invalide )
        return valeur_par_defaut;

    /**
     * Traitement principal
     */
    // ... logique

    /**
     * Succès
     */
    return $variable_retour;
}
```

## Structure des classes

```php
class NomClasse
{
    /**
     * Constantes
     */
    protected const MA_CONSTANTE = array();

    /**
     * Attributs
     */
    public $attribut = '';
    protected $autre_attribut = 0;

    /**
     * __construct() : Description du constructeur
     *
     * @since {année}
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function __construct() // IMPORTANT : PAS de return type pour constructeur (même pas : void)
    {
        /**
         * Variables
         */
        $variable = '';

        /**
         * Initialisation
         */
        $this->attribut = $variable;

        // Note : Pas de "Succès", pas de return, pas de sentinelle pour constructeur
    }

    /**
     * methode_publique() : Description
     *
     * @since {année}
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function methode_publique() : void
    {
        /**
         * Succès
         */
        return;
    }

    /**
     * _methode_privee() : Description
     *
     * @access protected
     * @since {année}
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function _methode_privee() : void
    {
        /**
         * Succès
         */
        return;
    }
}
```

## Règles importantes

### Commentaires
1. **Toutes les fonctions** ont un commentaire PHPDoc
2. **Sections** : Toujours commentées (Variables, Sentinelle, Succès)
3. **Sentinelles** : Toujours marquées "SENTINELLE" et expliquées
4. **Valeur de retour** : Toujours commentée inline après déclaration

### Sentinelles
- Toujours avec commentaire expliquant la validation
- Toujours marquées "SENTINELLE"
- Placées après la section "Variables"

### Return
- Toujours se terminer par un commentaire "Succès"
- La variable de retour doit être commentée "// Valeur de retour"

### Constructeurs
- **JAMAIS de return type sur `__construct()`** (même pas `: void`)
- Pas de commentaire "Succès" dans un constructeur
- Pas de `return` dans un constructeur
- Pas de sentinelle dans un constructeur

### Méthodes privées/protégées
- Commencent toujours par un underscore : `_ma_methode()`
- Ont `@access protected` ou `@access private` dans le PHPDoc

## Exemples concrets

### Fonction WordPress simple
```php
/**
 * recuperer_articles_recents() : Récupère les 5 articles les plus récents
 *
 * @since 2025
 * @author Samuelle Langlois
 *
 * @param int $nombre Nombre d'articles à récupérer
 * 
 * @return array Liste des articles
 */
function recuperer_articles_recents( int $nombre = 5 ) : array
{
    /**
     * Variables
     */
    $articles = array(); // Valeur de retour
    
    /**
     * Validation du nombre
     *
     * SENTINELLE
     */
    if ( $nombre < 1 )
        return array();

    /**
     * Récupération des articles
     */
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $nombre,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $articles = get_posts( $args );

    /**
     * Succès
     */
    return $articles;
}
```

### Méthode Laravel
```php
/**
 * getUsersByRole() : Récupère les utilisateurs par rôle
 *
 * @since 2025
 * @author Samuelle Langlois
 *
 * @param string $role Rôle recherché
 * 
 * @return Collection Collection d'utilisateurs
 */
public function getUsersByRole( string $role ) : Collection
{
    /**
     * Variables
     */
    $users = collect(); // Valeur de retour

    /**
     * Validation du rôle
     *
     * SENTINELLE
     */
    if ( empty( $role ) )
        return collect();

    /**
     * Récupération des utilisateurs
     */
    $users = User::where( 'role', $role )
        ->where( 'actif', true )
        ->get();

    /**
     * Succès
     */
    return $users;
}
```

## Git et commits

- NE JAMAIS créer de commits automatiquement
- Toujours demander confirmation avant de commiter
- Je préfère gérer les commits manuellement pour garder le contrôle

## Notes

- **Respecte TOUJOURS ce formatage** lors de la génération de nouveau code
- Si du code existe déjà avec un autre formatage, **ne le change pas**
- Les espaces autour des parenthèses sont **NON NÉGOCIABLES**
- Le commentaire "Succès" est ma **signature** (trademark)
- L'année dans `@since` doit être l'année courante (2025 actuellement)
- Ne supprime pas de fichier
