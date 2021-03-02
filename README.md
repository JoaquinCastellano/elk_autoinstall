Script en php desarrollado con la intención de automatizar la instalación del stack de Elasticsearch.

Sistema operativo objetivo: Debian 10.8

Dependencias necesarias: php7.3-cli

El script configura apache2 para utilizar proxy reverso. Para la creacion del usuario es necesario ejecutar manualmente después de la instalacion el comando: ``` htpasswd -c /etc/apache2/.htpasswd admin  ```
