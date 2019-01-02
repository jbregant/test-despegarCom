#####requisitos:
#####	- apache 2.4 
#####	- php 7.2+

git clone https://github.com/jbregant/test-despegarCom.git

crear una db con nombre a eleccion
correr el script '/sql_scripts/test-despegarcom.sql' de inicializacion sobre la db creada
rellenar las variables $dbName, $dbHost, $dbUsername, $dbUserPassword en el archivo /src/db.php

diagrama de clases: /php-class-diagram-test-despegarcom.png
se utilizo un patron mvc, apuntando el esfuerzo hacia la fecha de expiracion de la reserva dejando de lado busquedas de vuelos -disponibles entre fechas, etc.

como validaciones podrian sumarse, por ejemplo en caso de que se amplie el espectro a busquedas de vuelos por rango de fechas, controlar la fecha de retorno teniendo en cuentas horarios de vuelos reales , tiempos de vuelos y vuelos disponibles de regreso, tambien podrian sumarse cantidad de pasajes y comprobar la capacidad del vuelo, entre otras.
