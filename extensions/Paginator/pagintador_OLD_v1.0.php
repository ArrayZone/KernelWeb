<?php
/*
**
**    NOTA: Este script puede parecer algo COMPLICADO, pero es bastante simple y permite hacer varias paginaciones
**       Tambien puede parecer muy pesada la explicacion, pero es muy aconsejable leersela. (Alemnos hasta antes del script)
       Si alguien se le ocurren mejores explicaciones (mas abreviadas, mejor explicadas...), que no dude en avisarme y las reemplazare.
       
       
        Las palabras en los comentarios no tienen SIGNOS DE ACCENTUACION ni &ntilde; (aqui lo digo en html para que se entienda)
        por errores de incompatibilidad de texto
        Solo lo uso para Signos de exclamacion (no tienen tanta importancia)
**
 *--------------- Informacion del Script ---------------
**    Simple Paginador PHP
**    Divide en pginas resultados de consultas de MySQL
**
**    Version 1.0 - Revision 05.01.2012 */ $_ver_paginador="1.0.0"; /* Informacion para las etiquetas META
**
**    Comienzo de adaptacion del script 26.07.2011
**        El script havia sido previamente creado para paginas concretas, pero de forma muy incomoda
**        Ha sido adaptado para que cualquiera pueda usarlo facilmente
**
**
**
**    Autor:
**        Ruben Arroyo (RikuAnsem) de http://powerofdark.es
**        Una minima parte del projecto esta INSPIRADO en "Paginator" de "Jorge Pinedo Rosas (jpinedo)",
**        sobretodo el metodo de los comentarios :)
**
**    Descripcion:
**        Devuelve resultados de una consulta de MySQL por paginas, con un formato predeterminado el qual se puede modificar
**        El script esta disenado para que sea muy facil su uso y se puedan configurar varios sistemas de paginacion en la misma seccion
**        de una web. Cualquiera puede usar este script para lo que le venga en gana.
**
**    Licencia :
**        GNU/GPL con las siguientes extensiones:
**            *Si usa el Script, debe dar creditos en el sitio web con un enlace a http://powerofdark.es
**            *Uselo con el fin que quiera (personal o lucrativo).
**            *Si encuentra el codigo de utilidad y lo usa, mandeme un mail si lo desea o deje un comentario en la pagina
**             de documentación.
**            *Si mejora el codigo o encuentra errores, hagamelo saber al mail indicado o deje un comentario en la pagina
**             de documentación.
**
**
 *
 * Creacion de CSS
 *------------------------------------------------------------------------
 *

    El formato para el archivo .css se encuentra al final del archivo (<style>)
    para hacer toda la funcion en un solo archivo
    Puedes crear varios estilos y usar el que te apetezca cuando quieras.
    
    Tambien puedes escoger un Estilo DIFERENTE para la pagina que haya seleccionada




 *
 * Antes de comenzar a crear - A tener en cuenta
 *------------------------------------------------------------------------
 *
 
    Debemos incluir este archivo en la web:
    include_once('paginador.php'); //El once es para que solo lo ponga una vez por si volvemos a llamarlo
    
    
    El script utiliza 1 VARIABLE ARRAY OBLIGATORIA llamada "$_pag['']"; esta variable sera GLOBAL, es decir, agiliza mucho a la hora de usar las funciones.
    
    El array que MODIFICA el usuario se compone del siguiente esquema:
        $_pag[NOMBREPAGINADOR][SUBVARIABLE]=OPCION:
        
        NOMBREPAGINA: El "id" que tendra la variable por si usas varios paginadores diferentes en la misma pagina.
        SUBVARIABLE: La OPcion que se va a modificar
        
        EJEMPLO:
            $id1="pagina1"; //Es el ID que usaremos para la primera paginacion
            $_pag[$id1]['consulta']="SELECT * FROM ejemplo";




 
**            METODO DE EMPLEO
**            ----------------

**      1- Incluyendo el Script:
**        Lo podemos hacer con un require_once('paginador.php'); cuando queramos.
**            
**
**    2- Cración de las variables
**        -Puedes crear las variables de Informacion de paginación en este archivo, un archivo por Include o justo antes de ejecutar el script.
**        -La Unica variable obligatoria es la consulta (mas adelante se explica)
**            $_pag[$idpag]['consulta']="SELECT (CAMPOS) WHERE (campos)=(buscar)";
**                O
**            $_pag[$idpag]['consulta']="SELECT * FROM tabla WHERE MATCH (campox) AGAINST ('cualquiercosa');
**            ...
**
**
**        Lo importante de aqui, es que NO se use LIMIT  NI mysql_query, etc..., el metodo de consulta es opcional de cada uno
**
**    3- Ejecucion del codigo:
**        -Una vez preparado todo, debes ejecutar el script usando (sin comillas) "paginador($pagahora)".
        ¿Porque una funcion? Actualmente lo veo mas estable con una funcion.
        Si en el futuro lo veo conveniente, creare una clase.
        
        Hay que tener en mente que esto devolvera una consulta MYSQL_QUERY,
**        en la qual el usuario podra usar "mysql_fetch_array", "mysql_num_rows"...
**
**    4- Una vez cargado todo, hay que poner el siguiente codigo donde quieras que se VEAN las paginas:
**            echo $_pag[$idpaginador]['mostrarpaginas'];
**
**        ALTERNATIVAS - RESULTADOS (por si quieres personalizarlo):
            Usar echo para mostarrlo... echo
                $_pag[$idpaginador]['resul']['solopaginas']; //*anadIR RESUL COMO ARRAY*  Muestra el sistema de paginacion
                $_pag[$idpaginador]['resul']['solosaltar']; // *anadIR RESUL COMO ARRAY* Muestra la opcion "Saltar a pagina.."

                $_pag[$idpaginador]['pagina']; //Muestra la pagina actual abierta
                $_pag[$idpaginador]['paginastot']; //Muestra NUMERICAMENTE el TOTAL de PAGINAS

                $_pag[$idpaginador]['resul']['registros']; //Muestra la cantidad de registros de la CONSULTA actual
                $_pag[$idpaginador]['registrostot']; //Muestra NUMERICAMENTE el TOTAL de REGISTROS

    
**        El script guarda en una variable Informacion sobre cada consulta.
**


 *
 * Temas
 *------------------------------------------------------------------------
 *
    ¿Porque usar temas?
        Si quieres crear un estilo de paginas que vas a usar bastante, en lugar de tener que usar siempre copiar y pegar,
        creas un tema y lo cargas justo antes de hacer la paginacion. Asi te ahorras bastante lineas de codigo que pueden ser incomodos.
        
    ¿Como crear temas?
        Para crear un tema, ir a la funcion temas (function temas) y anadir un switch con el nombre del tema.
        
    ¿Como usar temas?
        Para usar un tema, solo tienes que ejecutar la siguiente funcion (recuerda que las variables que usa son globales
            lo que significa que no debes dar una variable de salida):
            
        temas ('idpaginador');
        
        => Ejemplo:
            temas ($id1);
            temas ('id1');

        --> Automaticamente se modificaran las variables <--
    
    ¿Puedo usar un tema pero modificar algunas opciones?
        Si, puedes, pero debes hacerlo justo DESPUES de cargar el tema.
    

 *
 * Valores por defecto
 *------------------------------------------------------------------------
 *
    Generalmente, las variables opcionales suelen tener valores por defecto.
    En caso de que no lo tengan o no te gusten, tienes 2 opciones:
        -Crear temas
        -Modificar los valores por defecto (justo al comienzo de la funcion de paginador)


 *
 * Valores personalizables
 *------------------------------------------------------------------------
 *
    Estos los los valores que puede modificar el Usuario, recuerda que debes usar el Array, y puedes usar una variable con el ID (ver el ejemplo para comprender mejor).
        ***************************************
            $_pag[$id]['SubVar']=VALOR;
            
            VALOR= El valor que quieres utilizar
            SubVar= La KEY del array que vamos a modificar
          ***************************************
        
        RECUERDA EL EJEMPLO: $_pag[$id1]['consulta']="SELECT * FROM ejemplo";
        
 * Lista de SubVariables:

           ***************************************
        Explicacion de Booleano (para los que no sepan):
            Es un "interruptor", es decir, esta ON o OFF.
            1=ON=TRUE=Encendido
            0=OFF=FALSE=Apagado
           ***************************************    
     *VARIABLE           TIPO         DEFINICION
    ==================================================
    
    OBLIGATORIO:
    ===========================
        *consulta        Cadena        La variable que contiene la consulta SQL Valida, SIN LIMIT
        
    OPCIONAL (si no quieres configurar estas opciones, se usara las propiedades por defecto):
    ===========================
                    =========== PARA MOD REWRITE O AJAX ===========
        *url1            Cadena        Si usas Ajax, Mod Rewrite, etc..., lo necesitarás!, justo después de esta URL,
                                    se empezará a generar la paginación
                                    SI lo usas, NO se generará una URL automática
                                    
        *url2            Cadena        Igual que url1, pero este se pone después de haber cargado la paginación
        

        *getseparador    Cadena        Lo que usara para enviar el GET en lugar de ? y &
                                    //Por defecto &
                                    
        *getvalor        Cadena        Lo que usara en lugar de = en el GET
                                    Si usas ModRewrite lo normal es poner /
                                    //Por defecto =
                    =========== /PARA MOD REWRITE O AJAX ===========
        
        *muestrasiempre   Boolenao    Muestra toda la Informacion de las paginas aun que solo haya 1
                                    Por defectoo TRUE EXCEPTO si limitmax ="0"
                                    
        *nombre            Cadena        Nombre que tendra el ? (
                                    Por defecto ?pag
                                
        *maxreg            Entero        Maximo de REGISTROS que se mostraran (por si quieres hacer un top 100, etc..)
                                    Por defecto TODOS
                                
        *limitmax        Entero        Maximo de RESULTADOS qe se mostraran por pagina
                                    Por defecto 20
                                    Si quieres mostrar todos los registros, usar "0" o Cualquier caracter no numerico
                                
        *maxpagmostrar        Entero    Total de paginas que se mostraran para ESCOGER (poner un valor PAR SIN DECIMALES)
                                    Por defecto 6 |
                                    Para mostrar todos los registros, usar "0"
                                    
        *mysqlerror        Booleano    Si se mostraran los errores al hacer consultas de SQL ¡USAR SOLO PARA DEBUG!
                                    Por defecto FALSE
                                
        *saltarpag        Booleano    Si se cargara (y posteriormente mostrara en el resultado completo) el "formulario" para saltar a X p&aacute;gina
                                    Por defecto FALSE
        
        *reutilizarget  Cadena        Por si quieres reutilizar solo cierta parte del GET
                                       Por defecto lo usa todo
                                    Si no quieres REUTILIZAR NADA, usa "null"
                                            Ej: $_pag[$id]['reutilizarget']="null"; //Esto no reutilizara ninguno
                                *************************************
                                EJEMPLO: (algo dificil de comprender)
                                    $_pag[$id]['reutilizarget']['id1']='op';
                                    $_pag[$id]['reutilizarget']['id2']='seccion';
                                    Los ID pueden ser cualquier texto/numero, se cargaran en la URL en el orden que los crees
                                *************************************
                                
        *style            Cadena     Nombre del estilo (CSS) que se va a usar
        *substyle        Cadena        Nombre del estilo (CSS) que se va a usar PARA la "p&aacute;gina actual" (si no se usa, se autoescoge el *style)
        
        *--------------------------------
        ** Textos ** | CADENAS | Se usan para elegir el texto que deseas cuando se muestran las p&aacute;ginas
        *--------------------------------
            *primera
            *anterior
            *siguiente
            *ultima
            
            *separadorlateral     Igual que los anteriores, pero este es para seprar entre "Siguiente" y los numeros (y final)
            
            
        *--------------------------------
        * Simbolos * | CADENAS | Se usan para escoger que signos hay entre los numeros (EJ: -1-  [2])
        *--------------------------------
            *simboloseld        Simbolo que se usara en la Derecha del Numero SELECCIONADO
            *simboloseli        Simbolo que se usara en la Izquierda del Numero SELECCIONADO
            *simbolonumd        Simbolo que se usara en la Derecha del Numero Linkeado
            *simbolonumi        Simbolo que se usara en la Izquierda del Numero Linkeado
        
            === Aclaracion ===
                sel = Seleccionado
                num = Numerico (los linkeados)
                i = izquierda
                d = derecha
            ===
            
            
 * NOTA IMPORTANTE
 *------------------------------------------------------------------------
 
 Usar el script que viene de ejemplo para probarlo y comprenderlo mejor


**    Si alguien tiene cualquier duda, encuentra errores, tiene sugerencias, mejoras del codigo, etc...
**    que no dude en ponerse en contacto conmigo por correo: powergm@hotmail.es
**
** ###################################################################
*/

//####################### SISTEMA DE PÁGINAS ##########################



//Preparamos una funcion para cargar el tema
function temas($_pag_ahora,$tema){
    global $_pag; //Globalizo la variable de pag
    
    switch ($tema){
        case 'blue':
            $_pag[$_pag_ahora]['style']="pag-blue-style";
            $_pag[$_pag_ahora]['simboloseli']="[";
            $_pag[$_pag_ahora]['simboloseld']="]";
            $_pag[$_pag_ahora]['simbolonumd']="-";
            $_pag[$_pag_ahora]['simbolonumi']="-";
            $_pag[$_pag_ahora]['primera']="[<<]";
            $_pag[$_pag_ahora]['anterior']="[<]";
            $_pag[$_pag_ahora]['siguiente']="[>]";
            $_pag[$_pag_ahora]['ultima']="[>>]";
            $_pag[$_pag_ahora]['separadorlateral']="|";
            break;
        case 'current':
            $_pag[$_pag_ahora]['style']="pag-style-current";
            $_pag[$_pag_ahora]['primera']="[<<]";
            $_pag[$_pag_ahora]['anterior']="[<]";
            $_pag[$_pag_ahora]['siguiente']="[>]";
            $_pag[$_pag_ahora]['ultima']="[>>]";
            break;
        case 'limpio':
            $_pag[$_pag_ahora]['style']="pag-style-limpio";
            $_pag[$_pag_ahora]['simboloseli']="[";
            $_pag[$_pag_ahora]['simboloseld']="]";
            $_pag[$_pag_ahora]['primera']="[<<]";
            $_pag[$_pag_ahora]['anterior']="[<]";
            $_pag[$_pag_ahora]['siguiente']="[>]";
            $_pag[$_pag_ahora]['ultima']="[>>]";
            $_pag[$_pag_ahora]['separadorlateral']="|";
            break;
    }
    //Para crear un tema, simplemente tienes que anadir un CASE al Switch y personalizar tu las opciones
}

//Preparo la funcion de paginador
function paginador($_pag_ahora){
    global $_pag; //Hago que la variable $_pag sea GLOBAL para manipular mejor la Informacion

/*
 * Valores por defecto
 *------------------------------------------------------------------------
 */

if (empty($_pag[$_pag_ahora]['consulta'])){
    //Si no se ha declarado ninguna consulta....
    //Se para la p&aacute;gina para evitar errores
    die ('Se necesita una consulta para hacer la paginaci&oacute;n');
}
if (empty($_pag[$_pag_ahora]['limitmax']) and $_pag[$_pag_ahora]['limitmax']!=0){
    //Si no se ha definido la cantidad de resultados que se mostraran
    //por pagina, se autoasignan 20
    $_pag[$_pag_ahora]['limitmax']="20";
    
}
if ($_pag[$_pag_ahora]['pagmostrar']!="0" and empty($_pag[$_pag_ahora]['pagmostrar'])){
    //La MITAD del maximo de paginas a mostrar si no se elige ninguna
    $_pag[$_pag_ahora]['pagmostrar']="3";
}
if (empty($_pag[$_pag_ahora]['getseparador'])){
    $_pag[$_pag_ahora]['getseparador']="&";
}
if (empty($_pag[$_pag_ahora]['getvalor'])){
    $_pag[$_pag_ahora]['getvalor']="=";
}
if (empty($_pag[$_pag_ahora]['nombre'])){
    $_pag[$_pag_ahora]['nombre']="pag";
}
if (empty($_pag[$_pag_ahora]['mysqlerror'])){
    $_pag[$_pag_ahora]['mysqlerror']="false";
}
if (empty($_pag[$_pag_ahora]['saltarpag'])){
    $_pag[$_pag_ahora]['saltarpag']="false";
}
if (empty($_pag[$_pag_ahora]['muestrasiempre']) and ($_pag[$_pag_ahora]['limitmax']!="" || $_pag[$_pag_ahora]['limitmax']!="0")){
    $_pag[$_pag_ahora]['muestrasiempre']="true";
}

if (empty($_pag[$_pag_ahora]['style'])){
    $_pag[$_pag_ahora]['style']="class=\"pag-style-current\"";
} else if (!empty($_pag[$_pag_ahora]['style'])){
    $_pag[$_pag_ahora]['style']="class=\"{$_pag[$_pag_ahora]['style']}\"";
}

if (empty($_pag[$_pag_ahora]['substyle'])){
    $_pag[$_pag_ahora]['substyle']=$_pag[$_pag_ahora]['style'];
} else {
    $_pag[$_pag_ahora]['substyle']="class=\"{$_pag[$_pag_ahora]['substyle']}\"";
}

// Nombre de las paaginaciones ->
    //& laquo; = « | & lt; = <
    //& raquo; = » | & gt; = >
    //(uso espacios despues del & para evitar errores)
    
      if (empty($_pag[$_pag_ahora]['primera'])){
        $_pag[$_pag_ahora]['primera']="[&lt;&lt;]";
    }
    if (empty($_pag[$_pag_ahora]['anterior'])){
        $_pag[$_pag_ahora]['anterior']="[&lt;]";
    }
    if (empty($_pag[$_pag_ahora]['siguiente'])){
        $_pag[$_pag_ahora]['siguiente']="[&gt;]";
    }
    if (empty($_pag[$_pag_ahora]['ultima'])){
        $_pag[$_pag_ahora]['ultima']="[&gt;&gt;]";
    }
    
// <- Nombre de las paaginaciones

//------------------------------------------------------------------------
 
 
 if ($_pag[$_pag_ahora]['mysqlerror']=="true"){
     //Si se van a mostrar los errores de MySQL
    $_pag[$_pag_ahora]['diemysql']=" or die (mysql_error)";
 } else {
     //Si se vuelve a consultar en la misma p&aacute;gina, que cambie la variable
     $_pag[$_pag_ahora]['diemysql']="";
 }
 
//Reseteamos las paginas que se mostraran
$_pag[$_pag_ahora]['resul']['completo']="<div>";


if ($_pag[$_pag_ahora]['maxreg']!=""){
    /* Si se ha establecido la cantidad de registros MAXIMA que el usuario puede consultar de la DB*/
    $_pag[$_pag_ahora]['consultaregistros']=$_pag[$_pag_ahora]['consulta'].' LIMIT 0,'.$_pag[$_pag_ahora]['maxreg'];
} else {
    /* Si no... se cargan */
    $_pag[$_pag_ahora]['consultaregistros']=$_pag[$_pag_ahora]['consulta'];
}

//Calculamos los registros totales
$_pag[$_pag_ahora]['registrostot']=ceil(mysql_num_rows(mysql_query($_pag[$_pag_ahora]['consultaregistros'].$_pag[$_pag_ahora]['diemysql'])));


if (!is_numeric($_pag[$_pag_ahora]['limitmax']) || $_pag[$_pag_ahora]['limitmax']=="0"){
    //Si se muestran todos los registros
    $_pag[$_pag_ahora]['paginastot']="1";
    $_pag[$_pag_ahora]['limitmax']=$_pag[$_pag_ahora]['registrostot'];
} else {
    //Calcula el total de paginas y redondea el resultado en "truncamiento" (elimina a partir de la ,)
    $_pag[$_pag_ahora]['paginastot']=ceil($_pag[$_pag_ahora]['registrostot']/$_pag[$_pag_ahora]['limitmax']);
}
    
    
    /*
 * Establecimiento de la p&aacute;gina actual.
 *------------------------------------------------------------------------
 */

    //Guardamos en una variable la p&aacute;gina actual
    $_pag[$_pag_ahora]['pagina']=$_GET[$_pag[$_pag_ahora]['nombre']];
    


if (!is_numeric($_pag[$_pag_ahora]['pagina']) and (($_pag[$_pag_ahora]['valorultima']!="" and $_pag[$_pag_ahora]['pagina']!=$_pag[$_pag_ahora]['valorultima']) ||  $_pag[$_pag_ahora]['pagina']=="")){
    /*Aqui un IF un poco dificil....
        Comprueba que SI NO es Numerico NI el texto es = 'valorultima' NI tiene texto...
        lo marque como la pagina 1 */
    $_pag[$_pag_ahora]['pagina']=1;

    // ** ALTERNATIVA **
    //Si el GET de la p&aacute;gina que se va a mostra NO es numerico NI coincide con el texto de la p&aacute;gina final...
    //bloqueamos la p&aacute;gina por posible intento de injeccion de datos
    //die ('<b>Error al paginar</b>: No intentes hacer una injección PHP');
} else {

    //Si el GET esta limpio
    if ($_pag[$_pag_ahora]['pagina']>$_pag[$_pag_ahora]['paginastot'] || $_pag[$_pag_ahora]['pagina']==$_pag[$_pag_ahora]['valorultima']){
        //Si la p&aacute;gina actual es MAJOR que las p&aacute;ginas que hay... Se preparara la ULTIMA p&aacute;gina
        $_pag[$_pag_ahora]['pagina']=$_pag[$_pag_ahora]['paginastot'];
        
    } elseif ($_pag[$_pag_ahora]['pagina']<1 || $_pag[$_pag_ahora]['pagina']==""){
        //Si por el contrario esta en una p&aacute;gina menor de 1 o no hay p&aacute;gina... se pondra por defecto la 1
        $_pag[$_pag_ahora]['pagina']=1;
    }
}
//------------------------------------------------------------------------


/*
 * Preparando el LIMIT de MySQL
 *------------------------------------------------------------------------
 */    
        //LIMITMIN, LIMIT | (0,10), es decir, empieza a contar desde 0 y cuenta 10 registros
        //NOTA: limitmax es el total de registros que contara, se prepara en las variables
    //Registro desde el que empezara a contar
    
    $_pag[$_pag_ahora]['limitmin']=$_pag[$_pag_ahora]['limitmax']*($_pag[$_pag_ahora]['pagina']-1);

    
    //Si queremos mostrar solo un Limite de registros (para el TOP, etc...), vamos a procurar
    //que no vayamos a mostrar mas registros de los que queremos mostrar
    if ($_pag[$_pag_ahora]['maxreg']!="" and ($_pag[$_pag_ahora]['limitmin'] + $_pag[$_pag_ahora]['limitmax'])>$_pag[$_pag_ahora]['maxreg']){
        //He hecho un while, si alguien conoce un metodo mejor que me lo pase ;)
        
        //Reseteamos el LimitMax
        $_pag[$_pag_ahora]['limitmax']=0;
        
        //Mientras LimitMax NO supere los registros Maximos...
        while (($_pag[$_pag_ahora]['limitmin'] + $_pag[$_pag_ahora]['limitmax'])<$_pag[$_pag_ahora]['maxreg']){
            //anadimos uno mas a mostrar
            $_pag[$_pag_ahora]['limitmax']++;    
        }
    }
    
    //Preparo el LIMIT para MySQL
    $_pag[$_pag_ahora]['limit']=" LIMIT {$_pag[$_pag_ahora]['limitmin']},{$_pag[$_pag_ahora]['limitmax']} ";
    
    //Meto la consulta con el Limit en la variable de la consulta
    $_pag[$_pag_ahora]['consulta']=mysql_query($_pag[$_pag_ahora]['consulta'].$_pag[$_pag_ahora]['limit']);

//------------------------------------------------------------------------



//Si hay m&aacute;s de una pagina y/o queremos mostrarlo de todas formas haremos todo el proceso
//En caso contrario, nos ahorraremos toda la carga del servidor>
if ($_pag[$_pag_ahora]['paginastot']>1 || $_pag[$_pag_ahora]['muestrasiempre']=="true"){

/*
 * Preparando la URL (se recicla)
 *------------------------------------------------------------------------
 */
 
 /*Esto es por si el muestra siempre es SI, pero NO va a saltar pagina
    estamos obligados a comprovar el paginastot, si no no tendr&aacute; suficiente Informacion
    y es provable de que no cargue el código
    Es algo lioso de compronder*/
if ($_pag[$_pag_ahora]['paginastot']>1 || $_pag[$_pag_ahora]['saltarpag']=="true") {

    
    if (!is_numeric($_pag[$_pag_ahora]['pagmostrar']) || $_pag[$_pag_ahora]['pagmostrar']=="0"){
        //Se mostraran todas las paginas si no se define un Numero o si se define 0
        $_pag[$_pag_ahora]['pagmostrar']=$_pag[$_pag_ahora]['paginastot'];
        
    } else {
        //Se calcula la cantidad de paginas a mostrar
        $_pag[$_pag_ahora]['pagmostrar']=ceil($_pag[$_pag_ahora]['pagmostrar']); //Calculo las p&aacute;ginas que se mostraran
    }
    
    //Comprovacion del GET
    
    if ($_pag[$_pag_ahora]['reutilizarget']!="null"){
        //Si NO es nulo (no podia ponerlo directamente en el IF siguiente...
        if(isset($_pag[$_pag_ahora]['reutilizarget']) and !is_array($_pag[$_pag_ahora]['reutilizarget'])){
            //Si se va a reutilizar el GET PERO...
            // $_pag[$idpaginador]['reutilizarget'] no es un array... grave error!
            die("<b>Error Paginador : </b>La variable \$_pag['idpaginador']['reutilizarget'] debe ser un array<br><br><b><u>Ejemplo</u></b>:<br>
                \$_pag['idpaginador']['reutilizarget']['id1']='op';<br>
                \$_pag['idpaginador']['reutilizarget']['id2']='seccion';<br><br>Los <b>ID</b> pueden ser cualquier <b>texto/numero</b>, se cargaran en la URL en el <b>orden que los crees</b>");        
        } else if (!isset($_pag[$_pag_ahora]['reutilizarget'])){
            //Si se va a reutilizar TODO el GET
            $_pag[$_pag_ahora]['reutilizarget']=array_keys($_GET);
        }
    }

    //Aqui comenzara a formarse la nueva URL   
    
    if ($_pag[$_pag_ahora]['url1']==""){
        //Si NO se ha establecido una URL "nueva"
       $_pag[$_pag_ahora]['url']=$_SERVER['PHP_SELF'];
            if ($_pag[$_pag_ahora]['getseparador']=="&"){
                //Si el separador del GET es el Normal...
                $_pag[$_pag_ahora]['url'].="?";
            } /*else {
                //Si el separador GET no es el normal (generalmente para modrewrite), sera el que usemos como primera opcion...
                // Desactivado por innecesario
                $_pag[$_pag_ahora]['url'].=$_pag[$_pag_ahora]['getseparador'];
            } */
    } else {
        //Si se va a usar una URL personalizada
        $_pag[$_pag_ahora]['url']=$_pag[$_pag_ahora]['url1'];
    }    

        //Añadimos la ' si es una funcion de javascript
        if ($_pag[$_pag_ahora]['js']=="true"){
            $_pag[$_pag_ahora]['url'].="'"; //Le añado al Final del comienzo de la URL
            $_pag[$_pag_ahora]['urlfin'].="'"; //Le añado al Principio del Fin de la URL
        }
        //Preparamos la variable del final
        $_pag[$_pag_ahora]['urlfin'].=$_pag[$_pag_ahora]['url2'];
        
    
     // Este foreach esta tomado de la Clase Paginado de webstudio (http://www.forosdelweb.com/showthread.php?t=65528) y modificado por mi
     /* NOTA: Para eliminar el GET actual, antes se hacia con un if y un unset,
                por errores con la MultiPaginacion (hacer varias paginaciones diferentes en la msma pagina), lo he tenido
                que comprovar ahora con un if e ignorarlo*/

    if ($_pag[$_pag_ahora]['reutilizarget']!="null"){
        //Si NO queremos mostrar ningun GET aparte del que vamos a cargar
     foreach($_pag[$_pag_ahora]['reutilizarget'] as $_temp[$_pag_ahora]['var']){
        if ($_pag[$_pag_ahora]['nombre']!=$_temp[$_pag_ahora]['var']){
            //Si el GET que estamos comprovando NO es el actual
            if(isset($GLOBALS[$_temp[$_pag_ahora]['var']])){
                // Si la variable es global al script
                 $_pag[$_pag_ahora]['url'].= $_temp[$_pag_ahora]['var'].$_pag[$_pag_ahora]['getvalor'].$GLOBALS[$_temp[$_pag_ahora]['var']].$_pag[$_pag_ahora]['getseparador'];
                
                //La preparamos para el "Saltar a..."
                $_nuevo_get_saltar=$GLOBALS[$_temp[$_pag_ahora]['var']];
                        
            }elseif(isset($_REQUEST[$_temp[$_pag_ahora]['var']])){
                // Si no es global (o register globals esta en OFF)
                $_pag[$_pag_ahora]['url'].= $_temp[$_pag_ahora]['var'].$_pag[$_pag_ahora]['getvalor'].$_REQUEST[$_temp[$_pag_ahora]['var']].$_pag[$_pag_ahora]['getseparador'];
                
                //La preparamos para el "Saltar a..."
                $_nuevo_get_saltar=$_REQUEST[$_temp[$_pag_ahora]['var']];
            }
            
            //Preparamos la variable para el "saltar a pagina"
            if (($_pag[$_pag_ahora]['saltarpag']=="true" and $_nuevo_get_saltar!="") or $_temp[$_pag_ahora]['js']=="true"){
                //Si se va a usar "saltar pag" y hay un nuevo GET para anadir...
                $_temp[$_pag_ahora]['saltar'].='<input type="hidden" name="'.$_temp[$_pag_ahora]['var'].'" value="'.$_nuevo_get_saltar.'">';
                // $_REQUEST[$_temp[$_pag_ahora]['var']]=""; //Reseteamos la variable... Creo que no sirve de nada... o causa problemas... asi que queda anulada (almenos por ahora)
            }
        }
     }
     }
 }
//------------------------------------------------------------------------


// ################ PROCESO DE PAGINACIÓN ################

    $_pag[$_pag_ahora]['resul']['completo'].= '<table border=0 width="97%"><tr><td>Pagina <b>'.$_pag[$_pag_ahora]['pagina'].'</b> de <b>'.$_pag[$_pag_ahora]['paginastot'].'</b> |---| (<b>'.$_pag[$_pag_ahora]['registrostot'].'</b> registro/s)</td></tr><tr><td align="right">
                Paginas: ';

    //Comenzamos a cargar la variable, no ponemos .= para que se resete la variable

    $_pag[$_pag_ahora]['resul']['solopaginas']= "<div id='paginador_basic' {$_pag[$_pag_ahora]['style']}>";
/*
 * Primera / Anterior
 *------------------------------------------------------------------------
 */        
        if ($_pag[$_pag_ahora]['pagina']>1){
            //Si NO esta en la p&aacute;gina 1 (vamos, que esta a parir de la 2)
            //muestra "Primera P&aacute;gina" y "P&aacute;gina Anterior"
            
            $_pag[$_pag_ahora]['resul']['solopaginas'].= '
            <a title="Primera P&aacute;gina (1)" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].'=1'.$_pag[$_pag_ahora]['urlfin'].'">'.$_pag[$_pag_ahora]['primera'].'</a>  
            <a title="P&aacute;gina Anterior ('.($_pag[$_pag_ahora]['pagina']-1).')" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].($_pag[$_pag_ahora]['pagina']-1).$_pag[$_pag_ahora]['urlfin'].'").>'.$_pag[$_pag_ahora]['anterior'].'</a> '.$_pag[$_pag_ahora]['separadorlateral'].' ';
        }

//------------------------------------------------------------------------

/*
 * Calcular cual sera la primera p&aacute;gina que empezara a mostrar
 *------------------------------------------------------------------------
 */
    //Restamos a la p&aacute;gina en la que estamos la cantidad de p&aacute;ginas que se van a mostrar
    //Para saber si empezamos a mostrar desde la p&aacute;gina 1, 2, 20...
    $_temp[$_pag_ahora]['empiezamostrar']=ceil($_pag[$_pag_ahora]['pagina']-$_pag[$_pag_ahora]['pagmostrar']);


    //Con el siguiente bucle, haremos que si esta en las Ultimas p&aacute;ginas,
        //muestre SI o SI (a no ser que no existan), las paginas que queremos que se muestren
    while (($_temp[$_pag_ahora]['empiezamostrar']+($_pag[$_pag_ahora]['pagmostrar']*2))>$_pag[$_pag_ahora]['paginastot']){
               $_temp[$_pag_ahora]['empiezamostrar']--;
    }
    
    //Si la p&aacute;gina por la que empieza a mostrar es menor de 1 (0, -1 ....), la pone en 1
    if ($_temp[$_pag_ahora]['empiezamostrar']<1){ $_temp[$_pag_ahora]['empiezamostrar']=1; }

//------------------------------------------------------------------------

        
/*
 * Mostrar las p&aacute;ginas que hay entre "Anterior" y "Siguiente"
 *------------------------------------------------------------------------
 */    
        //Pone el contador a 0 para el bucle
        $_temp[$_pag_ahora]['n']=0;
        
    while ($_temp[$_pag_ahora]['n']<1){
        
        if ($_temp[$_pag_ahora]['empiezamostrar']>$_pag[$_pag_ahora]['paginastot'] || $_temp[$_pag_ahora]['pagmostrada']>($_pag[$_pag_ahora]['pagmostrar']*2)){
            //Mira si se cumplen las condiciones de fin
            //Si ha mostrado todas o no hay mas p&aacute;ginas
            $_temp[$_pag_ahora]['n']++; //Finaliza el bucle
        }
        else {
            //Si aun hay p&aacute;ginas por mostrar
//            $_pag[$_pag_ahora]['resul']['completo'].= '<td>';
            
            if ($_pag[$_pag_ahora]['pagina']==$_temp[$_pag_ahora]['empiezamostrar']){
                //Si se va a mostrar el "boton" de la p&aacute;gina que estamos
                //la mostramos pero sin vinculo
                $_pag[$_pag_ahora]['resul']['solopaginas'].= '<span '.$_pag[$_pag_ahora]['substyle'].'>'.$_pag[$_pag_ahora]['simboloseli'].$_temp[$_pag_ahora]['empiezamostrar'].$_pag[$_pag_ahora]['simboloseld'].'</span>';
            } else {
                //Mostramos el "boton" de la p&aacute;gina
                $_pag[$_pag_ahora]['resul']['solopaginas'].= '<a href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].$_temp[$_pag_ahora]['empiezamostrar'].$_pag[$_pag_ahora]['urlfin'].'">'.$_pag[$_pag_ahora]['simbolonumi'].$_temp[$_pag_ahora]['empiezamostrar'].$_pag[$_pag_ahora]['simbolonumd'].'</a>';
            }
            
        //Sumamos 1 a la p&aacute;gina que empieza a mostrar, asi reciclamos la variable
        $_temp[$_pag_ahora]['empiezamostrar']++;
        
        //Con la siguiente variable llevaremos el total de p&aacute;ginas mostradas para el bucle
        $_temp[$_pag_ahora]['pagmostrada']++;
    
                if ($_temp[$_pag_ahora]['empiezamostrar']<$_pag[$_pag_ahora]['paginastot']){
                //Si aun quedan p&aacute;ginas por mostrar, pone un ESPACIO entre "boton" y "boton"
                $_pag[$_pag_ahora]['resul']['solopaginas'].= ' ';
            }
        }        
    }

//------------------------------------------------------------------------

/*
 * Siguiente / Ultima
 *------------------------------------------------------------------------
 */
    if ($_pag[$_pag_ahora]['pagina']<$_pag[$_pag_ahora]['paginastot']){
        //Si NO estamos en la Ultima p&aacute;gina,
        //mostrara los enlaces "Siguiente" y "Ultima"
            if ($_pag[$_pag_ahora]['valorultima']=="") {
                //Si NO hay texto en el "valor ultima", usara el numero de la Ultima p&aacute;gina
                $_pag[$_pag_ahora]['valorultima']=$_pag[$_pag_ahora]['paginastot'];
            }
        $_pag[$_pag_ahora]['resul']['solopaginas'].= ' '.$_pag[$_pag_ahora]['separadorlateral'].' <a title="P&aacute;gina Siguiente ('.($_pag[$_pag_ahora]['pagina']+1).')" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].($_pag[$_pag_ahora]['pagina']+1).$_pag[$_pag_ahora]['urlfin'].'">'.$_pag[$_pag_ahora]['siguiente'].'</a>
        
        <a title="Ultima P&aacute;gina ('.$_pag[$_pag_ahora]['paginastot'].')" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].$_pag[$_pag_ahora]['valorultima'].$_pag[$_pag_ahora]['urlfin'].'">'.$_pag[$_pag_ahora]['ultima'].'</a>
        ';
        
        //Antiguo ultima pagina
        //<td><a title="Ultima P&aacute;gina ('.$_pag[$_pag_ahora]['paginastot'].')" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].($_pag[$_pag_ahora]['paginastot']).'">[>>]</a>
        
    }
    //------------------------------------------------------------------------
    
    //CIERRO LAS 2 TABLAS (Normal y solo numeros)
    
    
    
    $_pag[$_pag_ahora]['resul']['solopaginas'].='</div>';
    $_pag[$_pag_ahora]['resul']['completo'].= $_pag[$_pag_ahora]['resul']['solopaginas'];
    
    /*
 * Formulario para saltar a X p&aacute;gina     
 *------------------------------------------------------------------------
 */    
 //Mostramos el "Formulario" para saltar a la p&aacute;gina que escriva el usuario
 //En el foreach hemos ido anadiendo a una variable controles HIDDEN para que el GET funcione Bien.
    if ($_pag[$_pag_ahora]['saltarpag']=="true"){
        if ($_pag[$_pag_ahora]['js']=="true"){
            //Si va a usar JavaScript, NO usamos form PERO si generamos un boton con la propiedad BUTTON, NO SUBMIT
            $_pag[$_pag_ahora]['temp']['formaction']=""; //Formateamos el form
            $_pag[$_pag_ahora]['temp']['button']="<input type='button' value='Ir' onclick={$_pag[$_pag_ahora]['url']}{$_pag[$_pag_ahora]['nombre']}='+document.getElementById('pagajax').value{$_pag[$_pag_ahora]['url2']} />";
        } else {
            //Si no usa el metodo con JavaScript, preparamos el form
            $_pag[$_pag_ahora]['temp']['formaction']=" method='get' name='form_ir_{$_pag[$_pag_ahora]['nombre']}' action='{$_pag[$_pag_ahora]['url']}'";
            //Boton con submit
            $_pag[$_pag_ahora]['temp']['button']='<input type="submit" value="Ir" />';
        }
        
        
        //Si se muestra el "Salta pagina"

        /*Aclaraciones:
            $pag_ahora --> Saltar = Las etiquetas hidden para el GET
            $pag_ahora --> button = Muestra el boton para el Form, si es con JavaScript, el Boton tendrá TODO lo importante del FORM
            $pag_ahora --> formaction = Lo que usara en el form SI NO usa JavaScript
        */
        $_pag[$_pag_ahora]['resul']['solosaltar']= '
            <div id="paginador_basic">
                
            <form '.$_pag[$_pag_ahora]['temp']['formaction'].'>'.
                $_temp[$_pag_ahora]['saltar'].
                
                '<input name="'.$_pag[$_pag_ahora]['nombre'].'" id="'.$_pag[$_pag_ahora]['nombre'].'"  type="text" size="4"/>'.
                $_pag[$_pag_ahora]['temp']['button'].'

            </form>
            </div>';
        
        $_pag[$_pag_ahora]['resul']['completo'].= '<tr><td align="right">Saltar a pagina '.$_pag[$_pag_ahora]['resul']['solosaltar'].'
            </td></tr>';
    }
    //------------------------------------------------------------------------

        $_pag[$_pag_ahora]['resul']['completo'].= '</table></div>';
    
} //Aquí cierro la comprovacion de que se muestra Si o Si o si hay mas de 1 pagina
$_pag[$_pag_ahora]['resul']['registros']=mysql_num_rows($_pag[$_pag_ahora]['consulta']);

/*
 *Limpieza de Variables
 *------------------------------------------------------------------------
*/
//Formateamos algunas variables para evitar futuros errores
//Las formateamos ahora porque ya hemos usado todos los recursos, y si se vuelve a cargar el script, pueden haber errores de variables que no se querian cargar...
        $_pag[$_pag_ahora]['url1']=="";
        $_pag[$_pag_ahora]['url2']=="";
        $_pag[$_pag_ahora]['getseparador']=="";
        $_pag[$_pag_ahora]['getvalor']=="";
        $_pag[$_pag_ahora]['urlfin']=="";

//------------------------------------------------------------------------

        
// ################ PROCESO DE PAGINACIÓN ################
//####################### SISTEMA DE PÁGINAS ##########################
} //Termina el paginador
?>
<style>
/*
 * DISENO CSS, PUEDES ANADIRLO EN EL ARCHIVO .CSS
 * URLs de paginación
 *------------------------------------------------------------------------
 */

    /* Cosas basicas para el paginador */
    #paginador_basic{
        border-right-width:2px;
        display:inline-table;
    }
    /* Cosas basicas para el paginador */
    
    
/*    Los SPAN son para la pagina en la que el usuario se encuentra,
    son como SubEstilos
    -Es decir, si aplicas un Style y no eliges SubStyle, por defecto intentara cargar
      el SubStyle del Style (hacer pruebas para comprenderlo bien)*/

    
    /* BlueStyle FOR Blue Theme */
    div.pag-blue-style a{     /*Url sin pasar el raton*/
        color:#0000FF;        /* Color del texto */
        background:#1E90FF;    /* Color del fondo */
        border-style:outset;/* Estilo del borde */
        
        text-decoration: none; /* no underline */
        margin: 2px;
    }
    div.pag-blue-style a:hover, div.pag-blue-style a:active{    /*Url al pasar el raton*/
        color:#0000FF;        /* Color del texto */
        background:#6699FF;    /* Color del fondo */
        border-style:inset;    /* Estilo del borde */
    }
    span.pag-blue-style {
        color:#000000;
        margin: 2px;
    }
    /* BlueStyle para Blue Theme */
    
    /* Estilo Limpio */
    div.pag-style-limpio a{
        font-size:17px;
        margin:2px;
    }
    span.pag-style-limpio{
        font-size:17px;
        margin:2px;
    }
    

    /* Estilo Limpio */
    
    /* Current (es el que usava antes AnimeID, creo que es el ejemplo de paginator) */
    div.pag-style-current a {
        padding: 1px 4px 1px 4px;
        margin: 2px;
        border: 1px solid #000000;
        
        text-decoration: none; /* no underline */
        color: #000000;
    }
    div.pag-style-current a:hover, div.pag-style-current a:active {
        border: 1px solid #000000;
        background-color:#000000;
        color: #fff;
    }
    span.pag-style-current {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #000000;
        
        font-weight: bold;
        background-color: #000000;
        color: #FFF;
    }
    /* Current (este no es mio, creo que es el ejemplo de paginator) */


    /* Style Disabled - Solo para pagina seleccionada */
    span.pag-style-disabled {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #EEE;
        
        color: #DDD;
    }
    /* Style Disabled - Solo para pagina seleccionada */
/*------------------------------------------------------------------------*/
</style>
<!-- A continuación, se generan unas etiquetas META para mejorar la busqueda en los motores de busqueda como google -->
<meta name="name" content="paginador-web-php-mysql" /> <!--Nombre del script-->
<meta name="autor" content="RikuAnsem" /> <!--Nombre del script-->
<meta name="script" content="paginador-web-php-mysql" /> <!--Nombre del script-->
<div style='visibility:hidden; display:none;'><a href="http://powerofdark.es/scripts">PowerOfDark.es - Scripts</a><br><a href="http://powerofdark.es/">PowerOfDark.es</a></div> <!--URL sitio oficial OCULTA | Manten el Script GRATIS-->
<meta name="version-paginador" content="<?=$_ver_paginador?>" /><!-- Version --><?php
/*
**
**    NOTA: Este script puede parecer algo COMPLICADO, pero es bastante simple y permite hacer varias paginaciones
**       Tambien puede parecer muy pesada la explicacion, pero es muy aconsejable leersela. (Alemnos hasta antes del script)
       Si alguien se le ocurren mejores explicaciones (mas abreviadas, mejor explicadas...), que no dude en avisarme y las reemplazare.
       
       
        Las palabras en los comentarios no tienen SIGNOS DE ACCENTUACION ni &ntilde; (aqui lo digo en html para que se entienda)
        por errores de incompatibilidad de texto
        Solo lo uso para Signos de exclamacion (no tienen tanta importancia)
**
 *--------------- Informacion del Script ---------------
**    Simple Paginador PHP
**    Divide en pginas resultados de consultas de MySQL
**
**    Version 1.0 - Revision 05.01.2012 */ $_ver_paginador="1.0.0"; /* Informacion para las etiquetas META
**
**    Comienzo de adaptacion del script 26.07.2011
**        El script havia sido previamente creado para paginas concretas, pero de forma muy incomoda
**        Ha sido adaptado para que cualquiera pueda usarlo facilmente
**
**
**
**    Autor:
**        Ruben Arroyo (RikuAnsem) de http://powerofdark.es
**        Una minima parte del projecto esta INSPIRADO en "Paginator" de "Jorge Pinedo Rosas (jpinedo)",
**        sobretodo el metodo de los comentarios :)
**
**    Descripcion:
**        Devuelve resultados de una consulta de MySQL por paginas, con un formato predeterminado el qual se puede modificar
**        El script esta disenado para que sea muy facil su uso y se puedan configurar varios sistemas de paginacion en la misma seccion
**        de una web. Cualquiera puede usar este script para lo que le venga en gana.
**
**    Licencia :
**        GNU/GPL con las siguientes extensiones:
**            *Si usa el Script, debe dar creditos en el sitio web con un enlace a http://powerofdark.es
**            *Uselo con el fin que quiera (personal o lucrativo).
**            *Si encuentra el codigo de utilidad y lo usa, mandeme un mail si lo desea o deje un comentario en la pagina
**             de documentación.
**            *Si mejora el codigo o encuentra errores, hagamelo saber al mail indicado o deje un comentario en la pagina
**             de documentación.
**
**
 *
 * Creacion de CSS
 *------------------------------------------------------------------------
 *

    El formato para el archivo .css se encuentra al final del archivo (<style>)
    para hacer toda la funcion en un solo archivo
    Puedes crear varios estilos y usar el que te apetezca cuando quieras.
    
    Tambien puedes escoger un Estilo DIFERENTE para la pagina que haya seleccionada




 *
 * Antes de comenzar a crear - A tener en cuenta
 *------------------------------------------------------------------------
 *
 
    Debemos incluir este archivo en la web:
    include_once('paginador.php'); //El once es para que solo lo ponga una vez por si volvemos a llamarlo
    
    
    El script utiliza 1 VARIABLE ARRAY OBLIGATORIA llamada "$_pag['']"; esta variable sera GLOBAL, es decir, agiliza mucho a la hora de usar las funciones.
    
    El array que MODIFICA el usuario se compone del siguiente esquema:
        $_pag[NOMBREPAGINADOR][SUBVARIABLE]=OPCION:
        
        NOMBREPAGINA: El "id" que tendra la variable por si usas varios paginadores diferentes en la misma pagina.
        SUBVARIABLE: La OPcion que se va a modificar
        
        EJEMPLO:
            $id1="pagina1"; //Es el ID que usaremos para la primera paginacion
            $_pag[$id1]['consulta']="SELECT * FROM ejemplo";




 
**            METODO DE EMPLEO
**            ----------------

**      1- Incluyendo el Script:
**        Lo podemos hacer con un require_once('paginador.php'); cuando queramos.
**            
**
**    2- Cración de las variables
**        -Puedes crear las variables de Informacion de paginación en este archivo, un archivo por Include o justo antes de ejecutar el script.
**        -La Unica variable obligatoria es la consulta (mas adelante se explica)
**            $_pag[$idpag]['consulta']="SELECT (CAMPOS) WHERE (campos)=(buscar)";
**                O
**            $_pag[$idpag]['consulta']="SELECT * FROM tabla WHERE MATCH (campox) AGAINST ('cualquiercosa');
**            ...
**
**
**        Lo importante de aqui, es que NO se use LIMIT  NI mysql_query, etc..., el metodo de consulta es opcional de cada uno
**
**    3- Ejecucion del codigo:
**        -Una vez preparado todo, debes ejecutar el script usando (sin comillas) "paginador($pagahora)".
        ¿Porque una funcion? Actualmente lo veo mas estable con una funcion.
        Si en el futuro lo veo conveniente, creare una clase.
        
        Hay que tener en mente que esto devolvera una consulta MYSQL_QUERY,
**        en la qual el usuario podra usar "mysql_fetch_array", "mysql_num_rows"...
**
**    4- Una vez cargado todo, hay que poner el siguiente codigo donde quieras que se VEAN las paginas:
**            echo $_pag[$idpaginador]['mostrarpaginas'];
**
**        ALTERNATIVAS - RESULTADOS (por si quieres personalizarlo):
            Usar echo para mostarrlo... echo
                $_pag[$idpaginador]['resul']['solopaginas']; //*anadIR RESUL COMO ARRAY*  Muestra el sistema de paginacion
                $_pag[$idpaginador]['resul']['solosaltar']; // *anadIR RESUL COMO ARRAY* Muestra la opcion "Saltar a pagina.."

                $_pag[$idpaginador]['pagina']; //Muestra la pagina actual abierta
                $_pag[$idpaginador]['paginastot']; //Muestra NUMERICAMENTE el TOTAL de PAGINAS

                $_pag[$idpaginador]['resul']['registros']; //Muestra la cantidad de registros de la CONSULTA actual
                $_pag[$idpaginador]['registrostot']; //Muestra NUMERICAMENTE el TOTAL de REGISTROS

    
**        El script guarda en una variable Informacion sobre cada consulta.
**


 *
 * Temas
 *------------------------------------------------------------------------
 *
    ¿Porque usar temas?
        Si quieres crear un estilo de paginas que vas a usar bastante, en lugar de tener que usar siempre copiar y pegar,
        creas un tema y lo cargas justo antes de hacer la paginacion. Asi te ahorras bastante lineas de codigo que pueden ser incomodos.
        
    ¿Como crear temas?
        Para crear un tema, ir a la funcion temas (function temas) y anadir un switch con el nombre del tema.
        
    ¿Como usar temas?
        Para usar un tema, solo tienes que ejecutar la siguiente funcion (recuerda que las variables que usa son globales
            lo que significa que no debes dar una variable de salida):
            
        temas ('idpaginador');
        
        => Ejemplo:
            temas ($id1);
            temas ('id1');

        --> Automaticamente se modificaran las variables <--
    
    ¿Puedo usar un tema pero modificar algunas opciones?
        Si, puedes, pero debes hacerlo justo DESPUES de cargar el tema.
    

 *
 * Valores por defecto
 *------------------------------------------------------------------------
 *
    Generalmente, las variables opcionales suelen tener valores por defecto.
    En caso de que no lo tengan o no te gusten, tienes 2 opciones:
        -Crear temas
        -Modificar los valores por defecto (justo al comienzo de la funcion de paginador)


 *
 * Valores personalizables
 *------------------------------------------------------------------------
 *
    Estos los los valores que puede modificar el Usuario, recuerda que debes usar el Array, y puedes usar una variable con el ID (ver el ejemplo para comprender mejor).
        ***************************************
            $_pag[$id]['SubVar']=VALOR;
            
            VALOR= El valor que quieres utilizar
            SubVar= La KEY del array que vamos a modificar
          ***************************************
        
        RECUERDA EL EJEMPLO: $_pag[$id1]['consulta']="SELECT * FROM ejemplo";
        
 * Lista de SubVariables:

           ***************************************
        Explicacion de Booleano (para los que no sepan):
            Es un "interruptor", es decir, esta ON o OFF.
            1=ON=TRUE=Encendido
            0=OFF=FALSE=Apagado
           ***************************************    
     *VARIABLE           TIPO         DEFINICION
    ==================================================
    
    OBLIGATORIO:
    ===========================
        *consulta        Cadena        La variable que contiene la consulta SQL Valida, SIN LIMIT
        
    OPCIONAL (si no quieres configurar estas opciones, se usara las propiedades por defecto):
    ===========================
                    =========== PARA MOD REWRITE O AJAX ===========
        *url1            Cadena        Si usas Ajax, Mod Rewrite, etc..., lo necesitarás!, justo después de esta URL,
                                    se empezará a generar la paginación
                                    SI lo usas, NO se generará una URL automática
                                    
        *url2            Cadena        Igual que url1, pero este se pone después de haber cargado la paginación
        

        *getseparador    Cadena        Lo que usara para enviar el GET en lugar de ? y &
                                    //Por defecto &
                                    
        *getvalor        Cadena        Lo que usara en lugar de = en el GET
                                    Si usas ModRewrite lo normal es poner /
                                    //Por defecto =
                    =========== /PARA MOD REWRITE O AJAX ===========
        
        *muestrasiempre   Boolenao    Muestra toda la Informacion de las paginas aun que solo haya 1
                                    Por defectoo TRUE EXCEPTO si limitmax ="0"
                                    
        *nombre            Cadena        Nombre que tendra el ? (
                                    Por defecto ?pag
                                
        *maxreg            Entero        Maximo de REGISTROS que se mostraran (por si quieres hacer un top 100, etc..)
                                    Por defecto TODOS
                                
        *limitmax        Entero        Maximo de RESULTADOS qe se mostraran por pagina
                                    Por defecto 20
                                    Si quieres mostrar todos los registros, usar "0" o Cualquier caracter no numerico
                                
        *maxpagmostrar        Entero    Total de paginas que se mostraran para ESCOGER (poner un valor PAR SIN DECIMALES)
                                    Por defecto 6 |
                                    Para mostrar todos los registros, usar "0"
                                    
        *mysqlerror        Booleano    Si se mostraran los errores al hacer consultas de SQL ¡USAR SOLO PARA DEBUG!
                                    Por defecto FALSE
                                
        *saltarpag        Booleano    Si se cargara (y posteriormente mostrara en el resultado completo) el "formulario" para saltar a X p&aacute;gina
                                    Por defecto FALSE
        
        *reutilizarget  Cadena        Por si quieres reutilizar solo cierta parte del GET
                                       Por defecto lo usa todo
                                    Si no quieres REUTILIZAR NADA, usa "null"
                                            Ej: $_pag[$id]['reutilizarget']="null"; //Esto no reutilizara ninguno
                                *************************************
                                EJEMPLO: (algo dificil de comprender)
                                    $_pag[$id]['reutilizarget']['id1']='op';
                                    $_pag[$id]['reutilizarget']['id2']='seccion';
                                    Los ID pueden ser cualquier texto/numero, se cargaran en la URL en el orden que los crees
                                *************************************
                                
        *style            Cadena     Nombre del estilo (CSS) que se va a usar
        *substyle        Cadena        Nombre del estilo (CSS) que se va a usar PARA la "p&aacute;gina actual" (si no se usa, se autoescoge el *style)
        
        *--------------------------------
        ** Textos ** | CADENAS | Se usan para elegir el texto que deseas cuando se muestran las p&aacute;ginas
        *--------------------------------
            *primera
            *anterior
            *siguiente
            *ultima
            
            *separadorlateral     Igual que los anteriores, pero este es para seprar entre "Siguiente" y los numeros (y final)
            
            
        *--------------------------------
        * Simbolos * | CADENAS | Se usan para escoger que signos hay entre los numeros (EJ: -1-  [2])
        *--------------------------------
            *simboloseld        Simbolo que se usara en la Derecha del Numero SELECCIONADO
            *simboloseli        Simbolo que se usara en la Izquierda del Numero SELECCIONADO
            *simbolonumd        Simbolo que se usara en la Derecha del Numero Linkeado
            *simbolonumi        Simbolo que se usara en la Izquierda del Numero Linkeado
        
            === Aclaracion ===
                sel = Seleccionado
                num = Numerico (los linkeados)
                i = izquierda
                d = derecha
            ===
            
            
 * NOTA IMPORTANTE
 *------------------------------------------------------------------------
 
 Usar el script que viene de ejemplo para probarlo y comprenderlo mejor


**    Si alguien tiene cualquier duda, encuentra errores, tiene sugerencias, mejoras del codigo, etc...
**    que no dude en ponerse en contacto conmigo por correo: powergm@hotmail.es
**
** ###################################################################
*/

//####################### SISTEMA DE PÁGINAS ##########################



//Preparamos una funcion para cargar el tema
function temas($_pag_ahora,$tema){
    global $_pag; //Globalizo la variable de pag
    
    switch ($tema){
        case 'blue':
            $_pag[$_pag_ahora]['style']="pag-blue-style";
            $_pag[$_pag_ahora]['simboloseli']="[";
            $_pag[$_pag_ahora]['simboloseld']="]";
            $_pag[$_pag_ahora]['simbolonumd']="-";
            $_pag[$_pag_ahora]['simbolonumi']="-";
            $_pag[$_pag_ahora]['primera']="[<<]";
            $_pag[$_pag_ahora]['anterior']="[<]";
            $_pag[$_pag_ahora]['siguiente']="[>]";
            $_pag[$_pag_ahora]['ultima']="[>>]";
            $_pag[$_pag_ahora]['separadorlateral']="|";
            break;
        case 'current':
            $_pag[$_pag_ahora]['style']="pag-style-current";
            $_pag[$_pag_ahora]['primera']="[<<]";
            $_pag[$_pag_ahora]['anterior']="[<]";
            $_pag[$_pag_ahora]['siguiente']="[>]";
            $_pag[$_pag_ahora]['ultima']="[>>]";
            break;
        case 'limpio':
            $_pag[$_pag_ahora]['style']="pag-style-limpio";
            $_pag[$_pag_ahora]['simboloseli']="[";
            $_pag[$_pag_ahora]['simboloseld']="]";
            $_pag[$_pag_ahora]['primera']="[<<]";
            $_pag[$_pag_ahora]['anterior']="[<]";
            $_pag[$_pag_ahora]['siguiente']="[>]";
            $_pag[$_pag_ahora]['ultima']="[>>]";
            $_pag[$_pag_ahora]['separadorlateral']="|";
            break;
    }
    //Para crear un tema, simplemente tienes que anadir un CASE al Switch y personalizar tu las opciones
}

//Preparo la funcion de paginador
function paginador($_pag_ahora){
    global $_pag; //Hago que la variable $_pag sea GLOBAL para manipular mejor la Informacion

/*
 * Valores por defecto
 *------------------------------------------------------------------------
 */

if (empty($_pag[$_pag_ahora]['consulta'])){
    //Si no se ha declarado ninguna consulta....
    //Se para la p&aacute;gina para evitar errores
    die ('Se necesita una consulta para hacer la paginaci&oacute;n');
}
if (empty($_pag[$_pag_ahora]['limitmax']) and $_pag[$_pag_ahora]['limitmax']!=0){
    //Si no se ha definido la cantidad de resultados que se mostraran
    //por pagina, se autoasignan 20
    $_pag[$_pag_ahora]['limitmax']="20";
    
}
if ($_pag[$_pag_ahora]['pagmostrar']!="0" and empty($_pag[$_pag_ahora]['pagmostrar'])){
    //La MITAD del maximo de paginas a mostrar si no se elige ninguna
    $_pag[$_pag_ahora]['pagmostrar']="3";
}
if (empty($_pag[$_pag_ahora]['getseparador'])){
    $_pag[$_pag_ahora]['getseparador']="&";
}
if (empty($_pag[$_pag_ahora]['getvalor'])){
    $_pag[$_pag_ahora]['getvalor']="=";
}
if (empty($_pag[$_pag_ahora]['nombre'])){
    $_pag[$_pag_ahora]['nombre']="pag";
}
if (empty($_pag[$_pag_ahora]['mysqlerror'])){
    $_pag[$_pag_ahora]['mysqlerror']="false";
}
if (empty($_pag[$_pag_ahora]['saltarpag'])){
    $_pag[$_pag_ahora]['saltarpag']="false";
}
if (empty($_pag[$_pag_ahora]['muestrasiempre']) and ($_pag[$_pag_ahora]['limitmax']!="" || $_pag[$_pag_ahora]['limitmax']!="0")){
    $_pag[$_pag_ahora]['muestrasiempre']="true";
}

if (empty($_pag[$_pag_ahora]['style'])){
    $_pag[$_pag_ahora]['style']="class=\"pag-style-current\"";
} else if (!empty($_pag[$_pag_ahora]['style'])){
    $_pag[$_pag_ahora]['style']="class=\"{$_pag[$_pag_ahora]['style']}\"";
}

if (empty($_pag[$_pag_ahora]['substyle'])){
    $_pag[$_pag_ahora]['substyle']=$_pag[$_pag_ahora]['style'];
} else {
    $_pag[$_pag_ahora]['substyle']="class=\"{$_pag[$_pag_ahora]['substyle']}\"";
}

// Nombre de las paaginaciones ->
    //& laquo; = « | & lt; = <
    //& raquo; = » | & gt; = >
    //(uso espacios despues del & para evitar errores)
    
      if (empty($_pag[$_pag_ahora]['primera'])){
        $_pag[$_pag_ahora]['primera']="[&lt;&lt;]";
    }
    if (empty($_pag[$_pag_ahora]['anterior'])){
        $_pag[$_pag_ahora]['anterior']="[&lt;]";
    }
    if (empty($_pag[$_pag_ahora]['siguiente'])){
        $_pag[$_pag_ahora]['siguiente']="[&gt;]";
    }
    if (empty($_pag[$_pag_ahora]['ultima'])){
        $_pag[$_pag_ahora]['ultima']="[&gt;&gt;]";
    }
    
// <- Nombre de las paaginaciones

//------------------------------------------------------------------------
 
 
 if ($_pag[$_pag_ahora]['mysqlerror']=="true"){
     //Si se van a mostrar los errores de MySQL
    $_pag[$_pag_ahora]['diemysql']=" or die (mysql_error)";
 } else {
     //Si se vuelve a consultar en la misma p&aacute;gina, que cambie la variable
     $_pag[$_pag_ahora]['diemysql']="";
 }
 
//Reseteamos las paginas que se mostraran
$_pag[$_pag_ahora]['resul']['completo']="<div>";


if ($_pag[$_pag_ahora]['maxreg']!=""){
    /* Si se ha establecido la cantidad de registros MAXIMA que el usuario puede consultar de la DB*/
    $_pag[$_pag_ahora]['consultaregistros']=$_pag[$_pag_ahora]['consulta'].' LIMIT 0,'.$_pag[$_pag_ahora]['maxreg'];
} else {
    /* Si no... se cargan */
    $_pag[$_pag_ahora]['consultaregistros']=$_pag[$_pag_ahora]['consulta'];
}

//Calculamos los registros totales
$_pag[$_pag_ahora]['registrostot']=ceil(mysql_num_rows(mysql_query($_pag[$_pag_ahora]['consultaregistros'].$_pag[$_pag_ahora]['diemysql'])));


if (!is_numeric($_pag[$_pag_ahora]['limitmax']) || $_pag[$_pag_ahora]['limitmax']=="0"){
    //Si se muestran todos los registros
    $_pag[$_pag_ahora]['paginastot']="1";
    $_pag[$_pag_ahora]['limitmax']=$_pag[$_pag_ahora]['registrostot'];
} else {
    //Calcula el total de paginas y redondea el resultado en "truncamiento" (elimina a partir de la ,)
    $_pag[$_pag_ahora]['paginastot']=ceil($_pag[$_pag_ahora]['registrostot']/$_pag[$_pag_ahora]['limitmax']);
}
    
    
    /*
 * Establecimiento de la p&aacute;gina actual.
 *------------------------------------------------------------------------
 */

    //Guardamos en una variable la p&aacute;gina actual
    $_pag[$_pag_ahora]['pagina']=$_GET[$_pag[$_pag_ahora]['nombre']];
    


if (!is_numeric($_pag[$_pag_ahora]['pagina']) and (($_pag[$_pag_ahora]['valorultima']!="" and $_pag[$_pag_ahora]['pagina']!=$_pag[$_pag_ahora]['valorultima']) ||  $_pag[$_pag_ahora]['pagina']=="")){
    /*Aqui un IF un poco dificil....
        Comprueba que SI NO es Numerico NI el texto es = 'valorultima' NI tiene texto...
        lo marque como la pagina 1 */
    $_pag[$_pag_ahora]['pagina']=1;

    // ** ALTERNATIVA **
    //Si el GET de la p&aacute;gina que se va a mostra NO es numerico NI coincide con el texto de la p&aacute;gina final...
    //bloqueamos la p&aacute;gina por posible intento de injeccion de datos
    //die ('<b>Error al paginar</b>: No intentes hacer una injección PHP');
} else {

    //Si el GET esta limpio
    if ($_pag[$_pag_ahora]['pagina']>$_pag[$_pag_ahora]['paginastot'] || $_pag[$_pag_ahora]['pagina']==$_pag[$_pag_ahora]['valorultima']){
        //Si la p&aacute;gina actual es MAJOR que las p&aacute;ginas que hay... Se preparara la ULTIMA p&aacute;gina
        $_pag[$_pag_ahora]['pagina']=$_pag[$_pag_ahora]['paginastot'];
        
    } elseif ($_pag[$_pag_ahora]['pagina']<1 || $_pag[$_pag_ahora]['pagina']==""){
        //Si por el contrario esta en una p&aacute;gina menor de 1 o no hay p&aacute;gina... se pondra por defecto la 1
        $_pag[$_pag_ahora]['pagina']=1;
    }
}
//------------------------------------------------------------------------


/*
 * Preparando el LIMIT de MySQL
 *------------------------------------------------------------------------
 */    
        //LIMITMIN, LIMIT | (0,10), es decir, empieza a contar desde 0 y cuenta 10 registros
        //NOTA: limitmax es el total de registros que contara, se prepara en las variables
    //Registro desde el que empezara a contar
    
    $_pag[$_pag_ahora]['limitmin']=$_pag[$_pag_ahora]['limitmax']*($_pag[$_pag_ahora]['pagina']-1);

    
    //Si queremos mostrar solo un Limite de registros (para el TOP, etc...), vamos a procurar
    //que no vayamos a mostrar mas registros de los que queremos mostrar
    if ($_pag[$_pag_ahora]['maxreg']!="" and ($_pag[$_pag_ahora]['limitmin'] + $_pag[$_pag_ahora]['limitmax'])>$_pag[$_pag_ahora]['maxreg']){
        //He hecho un while, si alguien conoce un metodo mejor que me lo pase ;)
        
        //Reseteamos el LimitMax
        $_pag[$_pag_ahora]['limitmax']=0;
        
        //Mientras LimitMax NO supere los registros Maximos...
        while (($_pag[$_pag_ahora]['limitmin'] + $_pag[$_pag_ahora]['limitmax'])<$_pag[$_pag_ahora]['maxreg']){
            //anadimos uno mas a mostrar
            $_pag[$_pag_ahora]['limitmax']++;    
        }
    }
    
    //Preparo el LIMIT para MySQL
    $_pag[$_pag_ahora]['limit']=" LIMIT {$_pag[$_pag_ahora]['limitmin']},{$_pag[$_pag_ahora]['limitmax']} ";
    
    //Meto la consulta con el Limit en la variable de la consulta
    $_pag[$_pag_ahora]['consulta']=mysql_query($_pag[$_pag_ahora]['consulta'].$_pag[$_pag_ahora]['limit']);

//------------------------------------------------------------------------



//Si hay m&aacute;s de una pagina y/o queremos mostrarlo de todas formas haremos todo el proceso
//En caso contrario, nos ahorraremos toda la carga del servidor>
if ($_pag[$_pag_ahora]['paginastot']>1 || $_pag[$_pag_ahora]['muestrasiempre']=="true"){

/*
 * Preparando la URL (se recicla)
 *------------------------------------------------------------------------
 */
 
 /*Esto es por si el muestra siempre es SI, pero NO va a saltar pagina
    estamos obligados a comprovar el paginastot, si no no tendr&aacute; suficiente Informacion
    y es provable de que no cargue el código
    Es algo lioso de compronder*/
if ($_pag[$_pag_ahora]['paginastot']>1 || $_pag[$_pag_ahora]['saltarpag']=="true") {

    
    if (!is_numeric($_pag[$_pag_ahora]['pagmostrar']) || $_pag[$_pag_ahora]['pagmostrar']=="0"){
        //Se mostraran todas las paginas si no se define un Numero o si se define 0
        $_pag[$_pag_ahora]['pagmostrar']=$_pag[$_pag_ahora]['paginastot'];
        
    } else {
        //Se calcula la cantidad de paginas a mostrar
        $_pag[$_pag_ahora]['pagmostrar']=ceil($_pag[$_pag_ahora]['pagmostrar']); //Calculo las p&aacute;ginas que se mostraran
    }
    
    //Comprovacion del GET
    
    if ($_pag[$_pag_ahora]['reutilizarget']!="null"){
        //Si NO es nulo (no podia ponerlo directamente en el IF siguiente...
        if(isset($_pag[$_pag_ahora]['reutilizarget']) and !is_array($_pag[$_pag_ahora]['reutilizarget'])){
            //Si se va a reutilizar el GET PERO...
            // $_pag[$idpaginador]['reutilizarget'] no es un array... grave error!
            die("<b>Error Paginador : </b>La variable \$_pag['idpaginador']['reutilizarget'] debe ser un array<br><br><b><u>Ejemplo</u></b>:<br>
                \$_pag['idpaginador']['reutilizarget']['id1']='op';<br>
                \$_pag['idpaginador']['reutilizarget']['id2']='seccion';<br><br>Los <b>ID</b> pueden ser cualquier <b>texto/numero</b>, se cargaran en la URL en el <b>orden que los crees</b>");        
        } else if (!isset($_pag[$_pag_ahora]['reutilizarget'])){
            //Si se va a reutilizar TODO el GET
            $_pag[$_pag_ahora]['reutilizarget']=array_keys($_GET);
        }
    }

    //Aqui comenzara a formarse la nueva URL   
    
    if ($_pag[$_pag_ahora]['url1']==""){
        //Si NO se ha establecido una URL "nueva"
       $_pag[$_pag_ahora]['url']=$_SERVER['PHP_SELF'];
            if ($_pag[$_pag_ahora]['getseparador']=="&"){
                //Si el separador del GET es el Normal...
                $_pag[$_pag_ahora]['url'].="?";
            } /*else {
                //Si el separador GET no es el normal (generalmente para modrewrite), sera el que usemos como primera opcion...
                // Desactivado por innecesario
                $_pag[$_pag_ahora]['url'].=$_pag[$_pag_ahora]['getseparador'];
            } */
    } else {
        //Si se va a usar una URL personalizada
        $_pag[$_pag_ahora]['url']=$_pag[$_pag_ahora]['url1'];
    }    

        //Añadimos la ' si es una funcion de javascript
        if ($_pag[$_pag_ahora]['js']=="true"){
            $_pag[$_pag_ahora]['url'].="'"; //Le añado al Final del comienzo de la URL
            $_pag[$_pag_ahora]['urlfin'].="'"; //Le añado al Principio del Fin de la URL
        }
        //Preparamos la variable del final
        $_pag[$_pag_ahora]['urlfin'].=$_pag[$_pag_ahora]['url2'];
        
    
     // Este foreach esta tomado de la Clase Paginado de webstudio (http://www.forosdelweb.com/showthread.php?t=65528) y modificado por mi
     /* NOTA: Para eliminar el GET actual, antes se hacia con un if y un unset,
                por errores con la MultiPaginacion (hacer varias paginaciones diferentes en la msma pagina), lo he tenido
                que comprovar ahora con un if e ignorarlo*/

    if ($_pag[$_pag_ahora]['reutilizarget']!="null"){
        //Si NO queremos mostrar ningun GET aparte del que vamos a cargar
     foreach($_pag[$_pag_ahora]['reutilizarget'] as $_temp[$_pag_ahora]['var']){
        if ($_pag[$_pag_ahora]['nombre']!=$_temp[$_pag_ahora]['var']){
            //Si el GET que estamos comprovando NO es el actual
            if(isset($GLOBALS[$_temp[$_pag_ahora]['var']])){
                // Si la variable es global al script
                 $_pag[$_pag_ahora]['url'].= $_temp[$_pag_ahora]['var'].$_pag[$_pag_ahora]['getvalor'].$GLOBALS[$_temp[$_pag_ahora]['var']].$_pag[$_pag_ahora]['getseparador'];
                
                //La preparamos para el "Saltar a..."
                $_nuevo_get_saltar=$GLOBALS[$_temp[$_pag_ahora]['var']];
                        
            }elseif(isset($_REQUEST[$_temp[$_pag_ahora]['var']])){
                // Si no es global (o register globals esta en OFF)
                $_pag[$_pag_ahora]['url'].= $_temp[$_pag_ahora]['var'].$_pag[$_pag_ahora]['getvalor'].$_REQUEST[$_temp[$_pag_ahora]['var']].$_pag[$_pag_ahora]['getseparador'];
                
                //La preparamos para el "Saltar a..."
                $_nuevo_get_saltar=$_REQUEST[$_temp[$_pag_ahora]['var']];
            }
            
            //Preparamos la variable para el "saltar a pagina"
            if (($_pag[$_pag_ahora]['saltarpag']=="true" and $_nuevo_get_saltar!="") or $_temp[$_pag_ahora]['js']=="true"){
                //Si se va a usar "saltar pag" y hay un nuevo GET para anadir...
                $_temp[$_pag_ahora]['saltar'].='<input type="hidden" name="'.$_temp[$_pag_ahora]['var'].'" value="'.$_nuevo_get_saltar.'">';
                // $_REQUEST[$_temp[$_pag_ahora]['var']]=""; //Reseteamos la variable... Creo que no sirve de nada... o causa problemas... asi que queda anulada (almenos por ahora)
            }
        }
     }
     }
 }
//------------------------------------------------------------------------


// ################ PROCESO DE PAGINACIÓN ################

    $_pag[$_pag_ahora]['resul']['completo'].= '<table border=0 width="97%"><tr><td>Pagina <b>'.$_pag[$_pag_ahora]['pagina'].'</b> de <b>'.$_pag[$_pag_ahora]['paginastot'].'</b> |---| (<b>'.$_pag[$_pag_ahora]['registrostot'].'</b> registro/s)</td></tr><tr><td align="right">
                Paginas: ';

    //Comenzamos a cargar la variable, no ponemos .= para que se resete la variable

    $_pag[$_pag_ahora]['resul']['solopaginas']= "<div id='paginador_basic' {$_pag[$_pag_ahora]['style']}>";
/*
 * Primera / Anterior
 *------------------------------------------------------------------------
 */        
        if ($_pag[$_pag_ahora]['pagina']>1){
            //Si NO esta en la p&aacute;gina 1 (vamos, que esta a parir de la 2)
            //muestra "Primera P&aacute;gina" y "P&aacute;gina Anterior"
            
            $_pag[$_pag_ahora]['resul']['solopaginas'].= '
            <a title="Primera P&aacute;gina (1)" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].'=1'.$_pag[$_pag_ahora]['urlfin'].'">'.$_pag[$_pag_ahora]['primera'].'</a>  
            <a title="P&aacute;gina Anterior ('.($_pag[$_pag_ahora]['pagina']-1).')" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].($_pag[$_pag_ahora]['pagina']-1).$_pag[$_pag_ahora]['urlfin'].'").>'.$_pag[$_pag_ahora]['anterior'].'</a> '.$_pag[$_pag_ahora]['separadorlateral'].' ';
        }

//------------------------------------------------------------------------

/*
 * Calcular cual sera la primera p&aacute;gina que empezara a mostrar
 *------------------------------------------------------------------------
 */
    //Restamos a la p&aacute;gina en la que estamos la cantidad de p&aacute;ginas que se van a mostrar
    //Para saber si empezamos a mostrar desde la p&aacute;gina 1, 2, 20...
    $_temp[$_pag_ahora]['empiezamostrar']=ceil($_pag[$_pag_ahora]['pagina']-$_pag[$_pag_ahora]['pagmostrar']);


    //Con el siguiente bucle, haremos que si esta en las Ultimas p&aacute;ginas,
        //muestre SI o SI (a no ser que no existan), las paginas que queremos que se muestren
    while (($_temp[$_pag_ahora]['empiezamostrar']+($_pag[$_pag_ahora]['pagmostrar']*2))>$_pag[$_pag_ahora]['paginastot']){
               $_temp[$_pag_ahora]['empiezamostrar']--;
    }
    
    //Si la p&aacute;gina por la que empieza a mostrar es menor de 1 (0, -1 ....), la pone en 1
    if ($_temp[$_pag_ahora]['empiezamostrar']<1){ $_temp[$_pag_ahora]['empiezamostrar']=1; }

//------------------------------------------------------------------------

        
/*
 * Mostrar las p&aacute;ginas que hay entre "Anterior" y "Siguiente"
 *------------------------------------------------------------------------
 */    
        //Pone el contador a 0 para el bucle
        $_temp[$_pag_ahora]['n']=0;
        
    while ($_temp[$_pag_ahora]['n']<1){
        
        if ($_temp[$_pag_ahora]['empiezamostrar']>$_pag[$_pag_ahora]['paginastot'] || $_temp[$_pag_ahora]['pagmostrada']>($_pag[$_pag_ahora]['pagmostrar']*2)){
            //Mira si se cumplen las condiciones de fin
            //Si ha mostrado todas o no hay mas p&aacute;ginas
            $_temp[$_pag_ahora]['n']++; //Finaliza el bucle
        }
        else {
            //Si aun hay p&aacute;ginas por mostrar
//            $_pag[$_pag_ahora]['resul']['completo'].= '<td>';
            
            if ($_pag[$_pag_ahora]['pagina']==$_temp[$_pag_ahora]['empiezamostrar']){
                //Si se va a mostrar el "boton" de la p&aacute;gina que estamos
                //la mostramos pero sin vinculo
                $_pag[$_pag_ahora]['resul']['solopaginas'].= '<span '.$_pag[$_pag_ahora]['substyle'].'>'.$_pag[$_pag_ahora]['simboloseli'].$_temp[$_pag_ahora]['empiezamostrar'].$_pag[$_pag_ahora]['simboloseld'].'</span>';
            } else {
                //Mostramos el "boton" de la p&aacute;gina
                $_pag[$_pag_ahora]['resul']['solopaginas'].= '<a href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].$_temp[$_pag_ahora]['empiezamostrar'].$_pag[$_pag_ahora]['urlfin'].'">'.$_pag[$_pag_ahora]['simbolonumi'].$_temp[$_pag_ahora]['empiezamostrar'].$_pag[$_pag_ahora]['simbolonumd'].'</a>';
            }
            
        //Sumamos 1 a la p&aacute;gina que empieza a mostrar, asi reciclamos la variable
        $_temp[$_pag_ahora]['empiezamostrar']++;
        
        //Con la siguiente variable llevaremos el total de p&aacute;ginas mostradas para el bucle
        $_temp[$_pag_ahora]['pagmostrada']++;
    
                if ($_temp[$_pag_ahora]['empiezamostrar']<$_pag[$_pag_ahora]['paginastot']){
                //Si aun quedan p&aacute;ginas por mostrar, pone un ESPACIO entre "boton" y "boton"
                $_pag[$_pag_ahora]['resul']['solopaginas'].= ' ';
            }
        }        
    }

//------------------------------------------------------------------------

/*
 * Siguiente / Ultima
 *------------------------------------------------------------------------
 */
    if ($_pag[$_pag_ahora]['pagina']<$_pag[$_pag_ahora]['paginastot']){
        //Si NO estamos en la Ultima p&aacute;gina,
        //mostrara los enlaces "Siguiente" y "Ultima"
            if ($_pag[$_pag_ahora]['valorultima']=="") {
                //Si NO hay texto en el "valor ultima", usara el numero de la Ultima p&aacute;gina
                $_pag[$_pag_ahora]['valorultima']=$_pag[$_pag_ahora]['paginastot'];
            }
        $_pag[$_pag_ahora]['resul']['solopaginas'].= ' '.$_pag[$_pag_ahora]['separadorlateral'].' <a title="P&aacute;gina Siguiente ('.($_pag[$_pag_ahora]['pagina']+1).')" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].($_pag[$_pag_ahora]['pagina']+1).$_pag[$_pag_ahora]['urlfin'].'">'.$_pag[$_pag_ahora]['siguiente'].'</a>
        
        <a title="Ultima P&aacute;gina ('.$_pag[$_pag_ahora]['paginastot'].')" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].$_pag[$_pag_ahora]['valorultima'].$_pag[$_pag_ahora]['urlfin'].'">'.$_pag[$_pag_ahora]['ultima'].'</a>
        ';
        
        //Antiguo ultima pagina
        //<td><a title="Ultima P&aacute;gina ('.$_pag[$_pag_ahora]['paginastot'].')" href="'.$_pag[$_pag_ahora]['url'].$_pag[$_pag_ahora]['nombre'].$_pag[$_pag_ahora]['getvalor'].($_pag[$_pag_ahora]['paginastot']).'">[>>]</a>
        
    }
    //------------------------------------------------------------------------
    
    //CIERRO LAS 2 TABLAS (Normal y solo numeros)
    
    
    
    $_pag[$_pag_ahora]['resul']['solopaginas'].='</div>';
    $_pag[$_pag_ahora]['resul']['completo'].= $_pag[$_pag_ahora]['resul']['solopaginas'];
    
    /*
 * Formulario para saltar a X p&aacute;gina     
 *------------------------------------------------------------------------
 */    
 //Mostramos el "Formulario" para saltar a la p&aacute;gina que escriva el usuario
 //En el foreach hemos ido anadiendo a una variable controles HIDDEN para que el GET funcione Bien.
    if ($_pag[$_pag_ahora]['saltarpag']=="true"){
        if ($_pag[$_pag_ahora]['js']=="true"){
            //Si va a usar JavaScript, NO usamos form PERO si generamos un boton con la propiedad BUTTON, NO SUBMIT
            $_pag[$_pag_ahora]['temp']['formaction']=""; //Formateamos el form
            $_pag[$_pag_ahora]['temp']['button']="<input type='button' value='Ir' onclick={$_pag[$_pag_ahora]['url']}{$_pag[$_pag_ahora]['nombre']}='+document.getElementById('pagajax').value{$_pag[$_pag_ahora]['url2']} />";
        } else {
            //Si no usa el metodo con JavaScript, preparamos el form
            $_pag[$_pag_ahora]['temp']['formaction']=" method='get' name='form_ir_{$_pag[$_pag_ahora]['nombre']}' action='{$_pag[$_pag_ahora]['url']}'";
            //Boton con submit
            $_pag[$_pag_ahora]['temp']['button']='<input type="submit" value="Ir" />';
        }
        
        
        //Si se muestra el "Salta pagina"

        /*Aclaraciones:
            $pag_ahora --> Saltar = Las etiquetas hidden para el GET
            $pag_ahora --> button = Muestra el boton para el Form, si es con JavaScript, el Boton tendrá TODO lo importante del FORM
            $pag_ahora --> formaction = Lo que usara en el form SI NO usa JavaScript
        */
        $_pag[$_pag_ahora]['resul']['solosaltar']= '
            <div id="paginador_basic">
                
            <form '.$_pag[$_pag_ahora]['temp']['formaction'].'>'.
                $_temp[$_pag_ahora]['saltar'].
                
                '<input name="'.$_pag[$_pag_ahora]['nombre'].'" id="'.$_pag[$_pag_ahora]['nombre'].'"  type="text" size="4"/>'.
                $_pag[$_pag_ahora]['temp']['button'].'

            </form>
            </div>';
        
        $_pag[$_pag_ahora]['resul']['completo'].= '<tr><td align="right">Saltar a pagina '.$_pag[$_pag_ahora]['resul']['solosaltar'].'
            </td></tr>';
    }
    //------------------------------------------------------------------------

        $_pag[$_pag_ahora]['resul']['completo'].= '</table></div>';
    
} //Aquí cierro la comprovacion de que se muestra Si o Si o si hay mas de 1 pagina
$_pag[$_pag_ahora]['resul']['registros']=mysql_num_rows($_pag[$_pag_ahora]['consulta']);

/*
 *Limpieza de Variables
 *------------------------------------------------------------------------
*/
//Formateamos algunas variables para evitar futuros errores
//Las formateamos ahora porque ya hemos usado todos los recursos, y si se vuelve a cargar el script, pueden haber errores de variables que no se querian cargar...
        $_pag[$_pag_ahora]['url1']=="";
        $_pag[$_pag_ahora]['url2']=="";
        $_pag[$_pag_ahora]['getseparador']=="";
        $_pag[$_pag_ahora]['getvalor']=="";
        $_pag[$_pag_ahora]['urlfin']=="";

//------------------------------------------------------------------------

        
// ################ PROCESO DE PAGINACIÓN ################
//####################### SISTEMA DE PÁGINAS ##########################
} //Termina el paginador
?>
<style>
/*
 * DISENO CSS, PUEDES ANADIRLO EN EL ARCHIVO .CSS
 * URLs de paginación
 *------------------------------------------------------------------------
 */

    /* Cosas basicas para el paginador */
    #paginador_basic{
        border-right-width:2px;
        display:inline-table;
    }
    /* Cosas basicas para el paginador */
    
    
/*    Los SPAN son para la pagina en la que el usuario se encuentra,
    son como SubEstilos
    -Es decir, si aplicas un Style y no eliges SubStyle, por defecto intentara cargar
      el SubStyle del Style (hacer pruebas para comprenderlo bien)*/

    
    /* BlueStyle FOR Blue Theme */
    div.pag-blue-style a{     /*Url sin pasar el raton*/
        color:#0000FF;        /* Color del texto */
        background:#1E90FF;    /* Color del fondo */
        border-style:outset;/* Estilo del borde */
        
        text-decoration: none; /* no underline */
        margin: 2px;
    }
    div.pag-blue-style a:hover, div.pag-blue-style a:active{    /*Url al pasar el raton*/
        color:#0000FF;        /* Color del texto */
        background:#6699FF;    /* Color del fondo */
        border-style:inset;    /* Estilo del borde */
    }
    span.pag-blue-style {
        color:#000000;
        margin: 2px;
    }
    /* BlueStyle para Blue Theme */
    
    /* Estilo Limpio */
    div.pag-style-limpio a{
        font-size:17px;
        margin:2px;
    }
    span.pag-style-limpio{
        font-size:17px;
        margin:2px;
    }
    

    /* Estilo Limpio */
    
    /* Current (es el que usava antes AnimeID, creo que es el ejemplo de paginator) */
    div.pag-style-current a {
        padding: 1px 4px 1px 4px;
        margin: 2px;
        border: 1px solid #000000;
        
        text-decoration: none; /* no underline */
        color: #000000;
    }
    div.pag-style-current a:hover, div.pag-style-current a:active {
        border: 1px solid #000000;
        background-color:#000000;
        color: #fff;
    }
    span.pag-style-current {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #000000;
        
        font-weight: bold;
        background-color: #000000;
        color: #FFF;
    }
    /* Current (este no es mio, creo que es el ejemplo de paginator) */


    /* Style Disabled - Solo para pagina seleccionada */
    span.pag-style-disabled {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #EEE;
        
        color: #DDD;
    }
    /* Style Disabled - Solo para pagina seleccionada */
/*------------------------------------------------------------------------*/
</style>
<!-- A continuación, se generan unas etiquetas META para mejorar la busqueda en los motores de busqueda como google -->
<meta name="name" content="paginador-web-php-mysql" /> <!--Nombre del script-->
<meta name="autor" content="RikuAnsem" /> <!--Nombre del script-->
<meta name="script" content="paginador-web-php-mysql" /> <!--Nombre del script-->
<div style='visibility:hidden; display:none;'><a href="http://powerofdark.es/scripts">PowerOfDark.es - Scripts</a><br><a href="http://powerofdark.es/">PowerOfDark.es</a></div> <!--URL sitio oficial OCULTA | Manten el Script GRATIS-->
<meta name="version-paginador" content="<?=$_ver_paginador?>" /><!-- Version -->