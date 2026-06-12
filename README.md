# 📱 BileMo API (Symfony + Docker)

API REST B2B permettant aux plateformes partenaires d’accéder au catalogue de téléphones mobiles BileMo.

## 📌 Contexte

BileMo est une entreprise spécialisée dans les téléphones mobiles haut de gamme.

Le projet consiste à développer une API REST sécurisée permettant aux clients partenaires :

- de consulter le catalogue produits
- de gérer leurs utilisateurs
- d’accéder aux données via JWT Authentication
- de consommer une API respectant les niveaux 1, 2 et 3 du modèle de Richardson

Projet réalisé dans le cadre de la formation OpenClassrooms.

---

# 🧩 Fonctionnalités principales

## 📱 Produits

- 📋 Liste paginée des produits
- 🔍 Détail d’un produit
- 🧪 Filtres dynamiques (marque, prix)

## 👤 Utilisateurs

- 📋 Liste des utilisateurs liés au client authentifié
- 🔍 Détail d’un utilisateur
- ➕ Création utilisateur
- ❌ Suppression utilisateur

## 🔐 Sécurité

- Authentification JWT
- Isolation des données multi-clients
- Validation des données API
- Gestion centralisée des erreurs

## ⚙️ Architecture

- Architecture CQRS
- DTO
- Specification Pattern
- HATEOAS
- Cache HTTP
- Documentation Swagger / Nelmio

---

# 🚀 Stack technique

| Technologie        | Description                |
| ------------------ | -------------------------- |
| Symfony            | Framework PHP              |
| Docker             | Environnement conteneurisé |
| Apache             | Serveur web                |
| MySQL              | Base de données            |
| Doctrine ORM       | Accès aux données          |
| JWT                | Authentification API       |
| NelmioApiDocBundle | Documentation Swagger      |
| PHPStan / Codacy   | Qualité du code            |

---

# 🐳 Démarrage rapide (TL;DR)

```bash
git clone https://github.com/devjuju/BileMo.git

cd bilemo-api

docker-compose up -d --build

docker-compose exec php composer install

docker-compose exec php php bin/console doctrine:database:create

docker-compose exec php php bin/console doctrine:migrations:migrate

docker-compose exec php php bin/console doctrine:fixtures:load
```

👉 API disponible sur :

```txt
http://localhost:8080
```

👉 Documentation Swagger :

```txt
http://localhost:8080/api/doc
```

---

# ⚙️ Installation détaillée

<details>
<summary>📦 Voir les étapes complètes</summary>

## 1. Cloner le projet

```bash
git clone https://github.com/USERNAME/bilemo-api.git
```

## 2. Lancer Docker

```bash
docker-compose up -d --build
```

## 3. Installer les dépendances Symfony

```bash
docker-compose exec php composer install
```

## 4. Configuration environnement

Créer un fichier `.env.local` :

```env
DATABASE_URL="mysql://bilemo_user:bilemo_pass@database:3306/bilemo_db"
```

## 5. Générer les clés JWT

```bash
docker-compose exec php php bin/console lexik:jwt:generate-keypair
```

## 6. Base de données

```bash
docker-compose exec php php bin/console doctrine:database:create

docker-compose exec php php bin/console doctrine:migrations:migrate

docker-compose exec php php bin/console doctrine:fixtures:load
```

</details>

---

# 🌐 Accès aux services

| Service    | URL                           |
| ---------- | ----------------------------- |
| API        | http://localhost:8496         |
| Swagger    | http://localhost:8496/api/doc |
| phpMyAdmin | http://localhost:8495         |

---

# 🔐 Authentification JWT

## Endpoint de connexion

```http
POST /api/login_check
```

## Exemple de requête

```json
{
  "username": "client@bilemo.com",
  "password": "password"
}
```

## Réponse

```json
{
  "token": "JWT_TOKEN"
}
```

## Utilisation du token

```http
Authorization: Bearer JWT_TOKEN
```

---

# 📚 Documentation API

La documentation Swagger est disponible via :

```txt
/api/doc
```

Elle permet de :

- tester les endpoints
- visualiser les schémas JSON
- consulter les réponses HTTP
- utiliser directement le JWT

---

# 🏗️ Architecture du projet

```text
.
├── apache/
├── docker/
├── php/
├── src/
│   ├── Api/
│   ├── Application/
│   ├── Domain/
│   ├── Infrastructure/
│   └── Entity/
├── uml/
├── docker-compose.yml
```

---

# 🧠 Architecture utilisée

Le projet repose sur une architecture moderne inspirée du Clean Architecture.

## CQRS

Séparation :

- des opérations de lecture (Query)
- des opérations d’écriture (Command)

## DTO

Les DTO permettent :

- de contrôler les données exposées
- d’éviter d’exposer les entités Doctrine

## Specification Pattern

Utilisé pour :

- les filtres dynamiques
- les recherches Doctrine complexes

## HATEOAS

Les réponses API contiennent :

- liens de navigation
- endpoints associés
- documentation API

---

# 📊 Diagrammes UML

Les diagrammes UML sont disponibles dans le dossier :

```txt
/uml
```

Contenu :

- diagrammes de classes
- diagrammes de séquence
- diagrammes CQRS
- diagrammes DTO
- diagrammes HATEOAS
- diagrammes du modèle de données

Formats disponibles :

- `.jpg` (version graphique)

---

# 🧪 Commandes utiles

## Accéder au conteneur PHP

```bash
docker-compose exec php bash
```

## Voir les logs

```bash
docker-compose logs -f
```

## Stopper Docker

```bash
docker-compose down
```

## Reset complet

```bash
docker-compose down -v
```

---

# 🔒 Sécurité

Le projet implémente :

- JWT Authentication
- isolation multi-clients
- validation Symfony
- contrôle des accès
- gestion centralisée des exceptions

---

# ⚡ Optimisations

## Cache HTTP

Mise en cache des réponses API :

- ETag
- Cache-Control
- optimisation performances

## Pagination

Toutes les collections sont paginées afin de :

- limiter les charges SQL
- améliorer les performances API

---

# 📈 Qualité du code

Le projet respecte :

- les standards Symfony
- les principes SOLID
- PSR-12
- architecture maintenable

Outils utilisés :

- PHPStan
- Codacy
- Symfony Validator

---

# 🚀 Améliorations possibles

- Tests fonctionnels API
- Versioning API
- Rate limiting
- OAuth2
- Docker production
- CI/CD GitHub Actions

---

# 👨‍💻 Auteur

Projet réalisé dans le cadre de la formation OpenClassrooms.
