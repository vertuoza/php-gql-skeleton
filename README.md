# Description

This repository is a skeleton to start easily a php api based on graphql.
It creates a simple api with a database based on mysql.

```gql
type Query {
  hello: String
  unitTypeById(id: String!): UnitType
  unitTypes: [UnitType]!
  collaborators; [Collaborator!]!
  collaboratorById(id: ID!): Collaborator!
}
```

# Getting started

you can simply run the solution with docker and docker-compose.

```bash
docker-compose up
```

This will create the database and feed it with sample data. You can open Apollo studion to test the api on http://localhost:3003/
