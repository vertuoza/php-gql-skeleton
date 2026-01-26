# Test Vertuoza

## Description

This repository is a skeleton to start easily a php api based on graphql.
It creates a simple api with a database based on mysql.

```gql
type Query {
  hello: String
  unitTypeById(id: String!): UnitType
  unitTypes: [UnitType]!
}
```

## Getting started

you can simply run the solution with docker and docker-compose.

```bash
docker-compose up
```

This will create the database and feed it with sample data. You can open Apollo studion to test the api on http://localhost:3003/

---
---
---


## Fin du test Merlin

## Requêtes GraphQL

URL : http://localhost:3003/graphql

### Afficher toutes les unités

Type : Read

```graphql
query {
  unitTypes {
    id
    isSystem
    name
  }
}
```

#### Afficher les unités par ID

Type : Read

Paramètre : UnitType ID

```graphql
query UnitType($id: ID!) {
  unitType(id: $id) {
    id
    name
    isSystem
  }
}


# ex : variable : { "id": "4c2241c9-841f-44c9-9a51-18cfd8f37890"}
```

#### Ajouter une unité en spécifiant un nom

Type : Create

```graphql
mutation Mutation($name: String!) {
  createUnitType(name: $name) {
    id
    isSystem
    name
  }
}

# ex : variable : { "name": "Litres" }
```

### Collaborateurs

#### Afficher toutes les collaborateurs

Type : Read

```graphql
query Query {
  CollaboratorType {
    id
    first_name
    name
  }
}
```

#### Afficher les collaborateurs par ID

Type : Read

Paramètre : Collaborateur ID

```graphql
query Query($id: String!) {
  CollaboratorId(id: $id) {
    first_name
    id
    name
  }
}

# ex : variables : { "id": "4146e6ea-2534-4708-b33d-93af6cab43b6"}
```

## Commandes utilisées

* git clone https://github.com/vertuoza/php-gql-skeleton
* docker compose up -d --build
* git checkout -b m-vandenhulle
* composer install
* PR

## Commandes utiles

### bBckup Base de données MySql

* docker exec php-gql-skeleton-mysql_gql-1 mysqldump -u guest -pguest php-gql >

* docker exec -it php-gql-skeleton-mysql_gql-1 mysql -u root -p
* USE php-gql;
* TRUNCATE TABLE unit_type;