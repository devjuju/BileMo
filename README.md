# 📱 BileMo API

BileMo est une API REST permettant d’exposer un catalogue de téléphones mobiles haut de gamme en B2B (Business to Business).

Les clients de l’API peuvent consulter les produits et gérer leurs utilisateurs via une authentification sécurisée.

---

## 🧩 Contexte

BileMo est une entreprise spécialisée dans la vente de téléphones mobiles haut de gamme.

Le business model ne repose pas sur la vente directe aux particuliers, mais sur la mise à disposition du catalogue via une **API sécurisée**, destinée à des partenaires professionnels.

Chaque client accède à l’API pour :

- consulter les produits
- gérer ses utilisateurs finaux
- intégrer le catalogue dans son propre système

---

## 🎯 Objectifs du projet

- Exposer une API REST sécurisée
- Permettre l’accès au catalogue produit
- Gérer les utilisateurs liés aux clients
- Respecter le modèle de maturité de Richardson (niveau 1, 2 et 3)
- Implémenter une architecture propre et découplée (CQRS)
- Sécuriser l’API avec JWT
- Documenter l’API avec Nelmio

---

## 🏗️ Architecture

Le projet repose sur une architecture **CQRS (Command Query Responsibility Segregation)** :
