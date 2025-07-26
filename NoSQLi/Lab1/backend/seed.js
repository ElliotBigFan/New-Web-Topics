const { MongoClient } = require("mongodb");

(async () => {
  const uri = "mongodb://mongo:27017";
  const client = new MongoClient(uri);
  await client.connect();
  const db = client.db("lab");
  const users = db.collection("users");

  await users.deleteMany({});
  await users.insertOne({
    username: "admin",
    password: "supersecret"
  });

  console.log("✔️ Seeded user: admin / supersecret");
  process.exit(0);
})();
