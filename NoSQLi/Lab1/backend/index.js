const express = require("express");
const bodyParser = require("body-parser");
const { MongoClient } = require("mongodb");

const app = express();
app.use(bodyParser.json());
app.use(express.static("public"));

const uri = "mongodb://mongo:27017";
let db;

MongoClient.connect(uri).then(async (client) => {
  db = client.db("lab");
  console.log("✅ Connected to MongoDB");

  // --- Tự động seed nếu user chưa tồn tại
  const users = db.collection("users");
  const existing = await users.findOne({ username: "admin" });
  if (!existing) {
    await users.insertOne({ username: "admin", password: "supersecret" });
    console.log("🌱 Seeded admin / supersecret");
  }

}).catch(err => {
  console.error("❌ MongoDB connection failed:", err);
});

app.post("/login", async (req, res) => {
  const { username, password } = req.body;
  const user = await db.collection("users").findOne({ username, password }); // ** nosqli ** db.users.findOne({ username: { "$ne": null }, password: { "$ne": null } })


  if (user) {
    res.send("✅ Welcome, " + user.username + "!");
  } else {
    res.status(401).send("❌ Invalid credentials");
  }
});

app.get("/", (req, res) => {
  res.redirect("/login.html");
});

app.listen(3000, () => {
  console.log("🚀 Server running on http://localhost:3000");
});
