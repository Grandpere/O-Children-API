<!-- PROJECT SHIELDS -->
![GitHub contributors](https://img.shields.io/github/contributors/Grandpere/O-Children-API?color=brightgreen)
![GitHub forks](https://img.shields.io/github/forks/Grandpere/O-Children-API)
![GitHub stars](https://img.shields.io/github/stars/Grandpere/O-Children-API)
![GitHub issues](https://img.shields.io/github/issues-raw/Grandpere/O-Children-API)
![GitHub](https://img.shields.io/github/license/Grandpere/O-Children-API)
![GitHub last commit](https://img.shields.io/github/last-commit/Grandpere/O-Children-API?color=informational)

<!-- PROJECT LOGO -->
<!--
<p align="center">
  <a href="https://github.com/github_username/repo">
    <img src="images/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">YOUR_TITLE</h3>

  <p align="center">
    YOUR_SHORT_DESCRIPTION
    <br />
    <a href="https://github.com/github_username/repo"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/github_username/repo">View Demo</a>
    ·
    <a href="https://github.com/github_username/repo/issues">Report Bug</a>
    ·
    <a href="https://github.com/github_username/repo/issues">Request Feature</a>
  </p>
</p>
-->


# O'Children (Backend & API)

> An educational site for children.


<!-- TABLE OF CONTENTS -->
## Table of Contents

* [About the Project](#about-the-project)
  * [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Usage](#usage)
* [Roadmap](#roadmap)
* [Contributing](#contributing)
* [License](#license)
* [Contact](#contact)
* [Acknowledgements](#acknowledgements)


<!-- ABOUT THE PROJECT -->
## About The Project

[![O'Children backend ScreenShot]](./docs/images/ochildren.gif)

The objective of this project was to develop an educational site.

- **Context** : end of training project. 
- **Duration** : 4 weeks
- **Team** : 1 backend developer and 3 frontend developers 
- **Tasks** : 
  1. API
  2. Admin interface


### Built With

* [Symfony 4](https://symfony.com/)
* [Bootstrap](https://getbootstrap.com/)
* [Doctrine](https://www.doctrine-project.org/)
* [Twig](https://twig.symfony.com/)
* [JWT](https://github.com/lexik/LexikJWTAuthenticationBundle)
* [NelmioApiDoc](https://github.com/nelmio/NelmioApiDocBundle)



<!-- GETTING STARTED -->
## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites

* [composer](https://getcomposer.org/download/)

### Installation
 
1. Clone this repo to your local machine using
    ```sh
    git clone https://github.com/Grandpere/O-Children-API.git
    ```
2. Install composer dependencies
    ```sh
    composer install
    ```
3. Copy .env to .env.local or create one
    ```sh
   cp .env .env.local
    ```
4. Update DATABASE_URL in this file with your credentials informations
    ```.dotenv
    DATABASE_URL=mysql://YOUR_USER:YOUR_PASSWORD@127.0.0.1:3306/YOUR_DBNAME`
    ```
5. Create database with Doctrine
    ```sh
   php bin/console doctrine:database:create
    ```
6. Make migrations
    ```sh
   php bin/console doctrine:migration:migrate
    ```
7. Loading fixtures
    ```sh
   php bin/console doctrine:fixtures:load
    ```
8. Generate keys for JWT
    ```sh
   mkdir -p config/jwt # For Symfony3+, no need of the -p option
   openssl genrsa -out config/jwt/private.pem -aes256 4096
   openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
    ```
   [More information](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation)
9. Configure JWT 

    :warning: put your pass_phrase (previous step) in .env.local to be able generate token
    
    [More information](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#configuration)
   
   
<!-- USAGE EXAMPLES -->
<!--
## Usage

Use this space to show useful examples of how a project can be used. Additional screenshots, code examples and demos work well in this space. You may also link to more resources.

_For more examples, please refer to the [Documentation](https://example.com)_
-->


<!-- ROADMAP -->
## Roadmap

See the [open issues](https://github.com/Grandpere/O-Children-API/issues) for a list of proposed features (and known issues).

:construction_worker: Add docker for easier installation and prevent missing dependencies \
:construction_worker: More improvements...

<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request



<!-- LICENSE -->
## License

Distributed under the LGPL-3.0 License. See `LICENSE` for more information.



<!-- CONTACT -->
## Contact

[@LMarozzo](https://twitter.com/LMarozzo)

[https://github.com/Grandpere/O-Children-API](https://github.com/Grandpere/O-Children-API)



<!-- ACKNOWLEDGEMENTS -->
## Acknowledgements

* [O'Clock](https://oclock.io/)
* [Mathieu (project's frontend team)](https://github.com/MathieuOP)

<!-- OTHERS -->
## Frontend repository

[Frontend repository](https://github.com/Grandpere/O-Children)
