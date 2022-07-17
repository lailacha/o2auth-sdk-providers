uniformiser les data de sorties
utiliser des factorys



1/ On doit afficher un lien pour se connecter au provider 
    créer une fonction loginUrl pour générer l'url en utilisant les apramètre de la classe
        - l'url d'authorization (là où on va faire la requête)
        - l'url de redirection
        - le scope

2/ On doit créer la fonction qui s'execute à la redirection
    
  
3/ Créer un user qui aura les champs suivant
    provider-id
    provider-name
    firstname
    lastname
    mail



une seule callback pour tous les providers

Faire un factory qui créé via un tableau de config notre provider
il prend en argument les applications et leurs secret etc