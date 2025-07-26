# Use an official Ruby runtime as a parent image
FROM ruby:3.1-slim

# Set the working directory in the container
WORKDIR /usr/src/app

# Install build tools needed for gems with native extensions
RUN apt-get update && apt-get install -y build-essential

# Copy Gemfile and Gemfile.lock
COPY Gemfile* ./

# Install dependencies into a local folder
RUN bundle config set --local path 'vendor/bundle'
RUN bundle install

# Copy the rest of the application code
COPY . .

# Make port 4567 available to the world outside this container
EXPOSE 4567

# Command to run the application using rackup, the standard way
CMD ["bundle", "exec", "rackup", "--host", "0.0.0.0", "-p", "4567"] 