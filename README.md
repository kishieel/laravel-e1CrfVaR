# 🚀 Laravel e1CrfVaR
The Laravel e1CrfVaR app serves as a platform for managing tasks and facilitates the import of users from external providers.

### Prerequisites
Before diving into this application, ensure that you have the following prerequisites installed:

- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/)

The application and several third-party services are containerized using Docker, simplifying the setup process. Note that integration with https://dummyapi.io/ requires an app ID for specific functionalities.

### Setup Process
To start working on the project, follow these steps:

1. Clone the repository.
2. Obtain an app ID from dummyapi.io for integration.
3. Execute the following command:

   ```bash
   composer setup
   ```

  Follow the prompts for configuration.

### Available Commands
All commands are executed within containers, eliminating the need to install additional dependencies locally. Here are the available commands:

- `setup`: Initiates the setup process and prompts for necessary configuration.
- `start:dev`: Starts the development environment.
- `stop`: Stops the running containers.
- `prune`: Stops the running containers and removes volumens.
- `migrate`: Executes migrations.
- `migrate:fresh`: Resets and migrates the database.
- `seed`: Seeds the database with initial data.
- `lint`: Performs linting checks.
- `format`: Formats the codebase.
- `docs`: Generates documentation.
- `test`: Executes all tests.
- `test:feat`: Runs feature tests.
- `test:unit`: Runs unit tests.

### API Documentation

API documentation is available in interactive format on `http://localhost/docs` or in form of OpenAPI collection in `.docs/postman.json`.
### Usage
Once the setup process is complete, leverage the provided commands to effectively manage and interact with the application and its diverse functionalities.
