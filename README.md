<p align="center"><img src="https://raw.githubusercontent.com/mms-gianni/klustair-frontend/master/docs/img/klustair.png" width="200"></p>

# KlustAIR Frontend
The Klustair scanner scanns your Kubernetes namespaces for the used images and scan them with trivy. This frontend displays the result of the scanned namespaces and images in a report. 

### Main Features: 
- The vulnerabilities of an images can be reviewed and whitelisted if they dont apply to any risk.
- Auditing the configuration of your kubernetes cluster 

### Related Klustair projects: 
- <a href="https://github.com/mms-gianni/klustair">Klustair runner</a> to scan all your used images with trivy
- <a href="https://github.com/mms-gianni/klustair-helm">Klustair Helm charts</a> to spin up Anchore and Klustair

### Related opensource projects
- <a href="https://github.com/aquasecurity/trivy">trivxy</a> A Simple and Comprehensive Vulnerability Scanner for Containers and other Artifacts
- <a href="https://github.com/Shopify/kubeaudit">kubeaudit</a> kubeaudit helps you audit your Kubernetes clusters against common security controls
- (DEPRECATED) <a href="https://github.com/anchore/anchore-engine">anchore-engine</a> A service that analyzes docker images and applies user-defined acceptance policies to allow automated container image validation and certification

## Pod Details
<img src="https://raw.githubusercontent.com/mms-gianni/klustair-frontend/master/docs/img/image_details.png" width="700" alt="Pod details">

## Docker

Docker images an tags

- <b>[VERSION]-apache</b><br>
  runs apache and PHP in a combined server. This container is based on Debian and is therefore bigger and has more vulnerabilities.

- <b>[VERSION]-nginx</b><br>
  Alpine baes Nginx server

- <b>[VERSION]-phpp-fpm</b><br>
  Alpine based php-fpm server

### Starting the Apache stack

    docker-compose up klustair-db klustair-apache

### Staring the Nginx/php-fpm stack

    docker-compose up klustair-db klustair-nginx klustair-php-fpm


