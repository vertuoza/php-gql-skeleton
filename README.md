# Description

This repository creates a GraphQL API on PHP on the top of a relational database (MySQL); here an example of a query:

```gql
type Query {
  hello: String
  unitTypeById(id: String!): UnitType
  unitTypes: [UnitType]!
}
```

# Getting started

Deploy the solution as follows:

```bash
docker compose up
```

This creates the database and feed it with sample data. You can open Apollo studio to test the API on http://localhost:3003/
