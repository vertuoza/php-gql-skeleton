# Description

This repository is a skeleton to start easily a php api based on graphql.
It creates a simple api with a database based on mysql.

```gql
type Query {
  hello: String
  collaborators: [Collaborator!]!
  collaboratorById(id: ID!): Collaborator!
  unitTypeById(id: String!): UnitType
  unitTypes: [UnitType]!
}
```

```gql
type Collaborator {
  id: ID!
  name: String!
  firstName: String!
}
```

```gql
type UnitType {
  id: ID
  name: String
  isSystem: Boolean!
}
```

```gql
type Mutation {
  unitTypeCreate(input: UnitTypeCreateInput!): UnitType!
}
```

```gql
input UnitTypeCreateInput {
  name: String!
}
```

# Getting started

you can simply run the solution with docker and docker-compose.

```bash
docker-compose up
```

This will create the database and feed it with sample data. You can open Apollo Studio to test the api on http://localhost:3003/
