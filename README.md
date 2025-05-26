# Project name
    Simple Content Schedule System

# Project description
  We are building a platform that allows users to browse available services (like consultations, repairs, coaching sessions) and make online reservations easily

# Tool choices and design decisions
  - Laravel 12
  - MySQL
  - Docker
# Setup instructions
  - You will need to have docker installed on your machine as this project is dockerized
  - Clone project to your device
  - run inside the project
  `` docker compose up --build -d ``
  - run 
  `` docker exec -it content_scheduler_app bash ``
  - For simplicty, you just need to copy env.sample and name it .env
  - Then run
  `` composer install ``
  `` php artisan migrate ``
  `` php artisan db:seed ``
  - Open browser
  [http://localhost:8000]
  # Tasks list from business requirements
  Comming soon ...
  # Any known limitations
  Comming soon ...
  