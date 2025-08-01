FROM maven:3.8.7-eclipse-temurin-17 AS build
WORKDIR /app
COPY . .
RUN mvn clean package -DskipTests

FROM openjdk:17-slim
WORKDIR /app

# Copy jar từ build stage
COPY --from=build /app/target/spel-lab-1.0.0.jar app.jar

# Copy flag.txt vào cùng thư mục
COPY flag.txt .

EXPOSE 8080
CMD ["java", "-jar", "app.jar"]
