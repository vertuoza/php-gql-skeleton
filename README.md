# Description

This repository is a skeleton to start easily a php api based on graphql.
It creates a simple api with a database based on mysql.

```gql
type Query {
  unitTypeById(id: ID!): UnitType
  unitTypes: [UnitType]!
  collaboratorById(id: ID!): Collaborator!
  collaborators: [Collaborator!]!
}
```

```gql
type Mutation {
  unitTypeCreate(input: UnitTypeCreateInput!): UnitType!
}
```

# Getting started

You can simply run the solution with docker and docker compose :

```bash
docker compose up -d
```

If needed you can delete the containers with this command :

```bash
docker compose down
```

A volume has been set on database to prevent data loss, if you need to remove it :

```bash
docker volume rm php-gql-skeleton_mysql_data
```

This will create the database and feed it with sample data. You can open Apollo studion to test the api on http://localhost:3003/
