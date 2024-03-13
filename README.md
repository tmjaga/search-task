# Simple Demo Search Task

Simple search over the database, depending on various criteria

## Requirements
- Docker
>If you are using Windows, please make sure to install Docker Desktop.
Next, you should ensure that Windows Subsystem for Linux 2 (WSL2) is installed and enabled.

## Installation and run

#### 1. Clone the project
```bash
git clone https://github.com/tmjaga/search-task.git
```
#### 2. Navigate into the project folder using terminal
```bash
cd search-task
```
### 3. Start search-task docker container
In the project folder run:
```bash
docker compose up -d
```
### 4. Import DB structure and data in to the DB
With phpMyAdmin (which included in to container), avaliable at http://localhost:8080

Username: root

Password: root

Import the following files in to the ucattu-db database:
- /database/ucattu_db_structure.sql 
- /database/ucattu_db_data.sql

## Usage
Open the application in browser at: http://localhost:8000
