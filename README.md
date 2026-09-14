# Vertuoza - API GraphQL PHP

Ce dépôt contient l'implémentation d'une API GraphQL asynchrone en PHP basée sur ReactPHP, Overblog DataLoader et un Query Builder MySQL.

L'implémentation s'inscrit dans la continuité du squelette fourni, en respectant les conventions établies, la séparation des responsabilités en couches et l'isolation multi-tenant.

---

## 1. Démarrage rapide

### Prérequis
- Docker
- Docker Compose

### Lancement des conteneurs
```bash
docker compose up --build
```

Cette commande initialise :
- Le serveur MySQL avec les données de test initiales (`seed/init.sql`).
- Le serveur d'API GraphQL asynchrone (PHP / ReactPHP).

### Accès à l'interface de test (Apollo Sandbox)
Une interface Apollo Sandbox préconfigurée avec la session de test (`x-session`) est disponible à l'adresse suivante :
```
http://localhost:3003
```

---

## 2. Schéma GraphQL implémenté

Conformément aux spécifications, le schéma supporte les opérations suivantes :

### Types
```graphql
type Collaborator {
  id: ID!
  name: String!
  firstName: String!
}

type UnitType {
  id: ID!
  name: String!
  isSystem: Boolean!
}

input UnitTypeCreateInput {
  name: String!
}
```

### Opérations (Queries & Mutations)
```graphql
type Query {
  hello: String
  collaborators: [Collaborator!]!
  collaboratorById(id: ID!): Collaborator!
  unitTypes: [UnitType!]!
  unitTypeById(id: ID!): UnitType!
}

type Mutation {
  unitTypeCreate(input: UnitTypeCreateInput!): UnitType!
}
```

---

## 3. Organisation du code et principes appliqués

L'implémentation suit fidèlement l'architecture en couches du projet :

- **Couche GraphQL (`src/graphql/Resolvers/`)** : Déclaration des types (`ObjectType`, `InputObjectType`) et des points d'entrée (`Query`, `Mutation`). Les résolveurs ne contiennent aucune logique SQL et délèguent immédiatement aux Use Cases.
- **Couche Applicative (`src/usecases/`)** : Encapsulation de la logique métier (validation des entrées, vérification d'unicité, injection sécurisée du contexte utilisateur).
- **Couche Persistance (`src/repositories/`)** : Accès aux données avec mapping entre les modèles SQL bruts (`snake_case`) et les entités du domaine (`camelCase`).
- **Couche Domaine (`src/entities/`)** : Objets métier découplés de l'infrastructure de stockage.

### Points clés de la réalisation :
- **Étanchéité multi-tenant** : L'identifiant `tenantId` est extrait de la session authentifiée (`UserRequestContext`) et injecté dans chaque clause SQL sans jamais être exposé en paramètre GraphQL.
- **Gestion du N+1 (DataLoader)** : Utilisation d'`Overblog\DataLoader` pour regrouper (*batching*) et mettre en cache les requêtes par tick d'exécution, avec une isolation stricte des instances par tenant.
- **Validation et erreurs normalisées** : Utilisation des exceptions métier du projet (`BadUserInputException`, `NotFoundException`, `FieldError`) pour renvoyer des statuts d'erreur explicites (400, 404).
- **Génération d'identifiants** : Utilisation de la bibliothèque standard `ramsey/uuid` pour garantir des identifiants conformes RFC 4122 v4 avec entropie cryptographique.

---

## 4. Exemples de requêtes GraphQL

### Récupération de tous les collaborateurs
```graphql
query GetCollaborators {
  collaborators {
    id
    name
    firstName
  }
}
```

### Récupération d'un collaborateur par identifiant
```graphql
query GetCollaboratorById($id: ID!) {
  collaboratorById(id: $id) {
    id
    name
    firstName
  }
}
```
*Variables :*
```json
{
  "id": "4146e6ea-2534-4708-b33d-93af6cab43b6"
}
```

### Création d'une unité de mesure
```graphql
mutation CreateUnitType($input: UnitTypeCreateInput!) {
  unitTypeCreate(input: $input) {
    id
    name
    isSystem
  }
}
```
*Variables :*
```json
{
  "input": {
    "name": "Mètre carré"
  }
}
```
