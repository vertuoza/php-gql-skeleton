# Description

This repository is a skeleton to start easily a php api based on graphql.
It creates a simple api with a database based on mysql.

```gql
type Query {
  hello: String
  unitTypeById(id: String!): UnitType
  unitTypes: [UnitType]!
  collaborators: [Collaborator!]!
  collaboratorById(id: ID!): Collaborator!
}

type Mutation {
  unitTypeCreate(input: UnitTypeCreateInput!): UnitType!
}

type Collaborator {
  id: ID!
  name: String!
  firstName: String!
}

input UnitTypeCreateInput {
  name: String!
}
```

## Errors

Business errors are returned in the standard GraphQL `errors` array with a stable `extensions.code`:

| code             | when                                                                                 |
| ---------------- | ------------------------------------------------------------------------------------ |
| `NOT_FOUND`      | `collaboratorById` with an id that does not exist for the tenant of the current user |
| `BAD_USER_INPUT` | `unitTypeCreate` with an empty/too long/already used name (details in `extensions.fields`) |

Unexpected errors are logged server side and returned as a generic "Internal server error".

# Getting started

you can simply run the solution with docker and docker-compose.

```bash
docker-compose up
```

This will create the database and feed it with sample data. You can open Apollo studion to test the api on http://localhost:3003/
