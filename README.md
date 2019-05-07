# plateforme-educative-api

## README FRONT POUR INSTALLATION BACKEND
- cloner le dépôt
- se placer dans le dépôt
- git checkout api-controllers (la branche la plus récente actuellement)
- composer install pour installer les dépendances
- dupliquer le .env et le renommer en .env.local qui ne sera pas commit contrairement au .env
- Dans le .env.local, editer la ligne suivante : 
`DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name` et remplacer par `DATABASE_URL=mysql://root:Ereul9Aeng@127.0.0.1:3306/Ochildren`
- Ensuite on crée la DB directement avec l’ORM de Symfony donc doctrine :
`php bin/console doctrine:database:create`
- Ensuite on fait les migrations que j’ai crée tout au long de mon dev :
`php bin/console doctrine:migration:migrate`
⇒ si aucune migration trouvé c’est que vous êtes sur la branche master qui n’en contient pas donc il faut aller sur la dernère branche, exemple api-controllers et refaire la manip
- Si tout se passe bien on peut charger nos fixtures:
`php bin/console doctrine:fixtures:load`
## Configurer jwtToken (sinon il ne pourra jamais générer de token)
- A la racine du back : 
```
mkdir -p config/jwt # For Symfony3+, no need of the -p option
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
- ensuite noter dans le .env.local à la suite
```
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=[VOTRE PASSPHRASE COISI JUSTE AU DESSUS A METTRE ICI SANS CROCHET]
###< lexik/jwt-authentication-bundle ###
```
- Maintenant jwt peux générer des tokens donc vous devez demander un token sur l’url /api/login_check et le mettre dans vos requêtes. (Il expire au bout d’une heure donc il faudra recheck quand il est expiré mais je sais comment font les autres react)

