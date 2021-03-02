<?php

$cmd_list[]="/usr/bin/apt-get update";
$cmd_list[]="/usr/bin/apt-get -y upgrade";
$cmd_list[]="/usr/bin/apt-get install -y wget";
$cmd_list[]="/usr/bin/apt-get install -y gnupg2";
$cmd_list[]="/usr/bin/apt-get install -y default-jdk > /dev/null 2>&1";
$cmd_list[]="/usr/bin/wget -qO /usr/src/elk_autoinstall/ELK-GPG-KEY https://artifacts.elastic.co/GPG-KEY-elasticsearch";
$cmd_list[]="/usr/bin/apt-key add /usr/src/elk_autoinstall/ELK-GPG-KEY > /dev/null 2>&1";
$cmd_list[]="/usr/bin/rm /usr/src/elk_autoinstall/ELK-GPG-KEY";
$cmd_list[]='if [ ! -f "/etc/apt/sources.list.d/elastic-7.x.list" ]; then /bin/echo "deb https://artifacts.elastic.co/packages/7.x/apt stable main" > /etc/apt/sources.list.d/elastic-7.x.list; fi';
$cmd_list[]="/usr/bin/apt-get update";
$cmd_list[]="/usr/bin/apt-get install -y elasticsearch";
$cmd_list[]="/bin/systemctl enable elasticsearch.service --now > /dev/null 2>&1";
$cmd_list[]="/usr/bin/apt-get install -y kibana";
$cmd_list[]="/bin/systemctl enable kibana.service --now > /dev/null 2>&1";
$cmd_list[]="/usr/bin/apt-get install -y logstash";
$cmd_list[]="/bin/systemctl enable logstash.service --now > /dev/null 2>&1";
$cmd_list[]="/usr/bin/apt-get install -y apache2";
$cmd_list[]="/usr/sbin/a2enmod proxy";
$cmd_list[]="/usr/sbin/a2enmod proxy_http";
$cmd_list[]="/usr/sbin/a2enmod headers";
$cmd_list[]="/usr/sbin/service apache2 restart";

$apache_str='<VirtualHost *:80>

# Proxying kibana listenning on the port 5601 
ProxyPreserveHost On
ProxyRequests On
ProxyPass / http://localhost:5601/
ProxyPassReverse / http://localhost:5601/

# Protecting with basic authentication
<Location />
        AuthType Basic
        AuthName "Restricted Content"
        AuthUserFile /etc/apache2/.htpasswd
        Require valid-user
   </Location>

</VirtualHost>';

$cmd_list[]="/bin/echo '" . $apache_str . "' > /etc/apache2/sites-available/000-default.conf";

$cmd_list[]="/usr/sbin/service apache2 reload";

foreach ($cmd_list as $cmd){

    $out=NULL;

    echo "$cmd: ";

        exec($cmd, $out, $ret);
    if ($ret == 0 ){

        echo "OK" . PHP_EOL;

    } else {

        echo "ERROR" . PHP_EOL;

        foreach($out as $line ){

            echo $line . PHP_EOL;

        }

        echo PHP_EOL;
        exit(1);
    }
}

echo PHP_EOL . "Para finalizar la autoinstalacion, cree el usuario admin para el servicio de Kibana con el comando: htpasswd -c /etc/apache2/.htpasswd admin" . PHP_EOL;