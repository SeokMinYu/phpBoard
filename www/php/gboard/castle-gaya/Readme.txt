
CASTLE - PHP 버전 
------------

1. CASTLE을 적용하려면 아래와 같이 하십시오.

   각 페이지 첫줄에 

<?php // CASTLE - KISA Web Attack Defense Tool
define("__CASTLE_PHP_VERSION_BASE_DIR__", "CASTLE 설치 경로");
include_once(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_referee.php");
?>

   위의 네줄을 추가하시면 됩니다.

   특히 주의해야할 것은 두번째 줄인 define() 부분으로 두번째 파라미터 부분에
   castle-php가 설치된 곳의 절대 또는 상대위치를 적어 주셔야 합니다.

   ex) 아파치의 DocumentRoot가 "/var/www/html"이고 
       /var/www/html/castle-php/ 에 CASTLE을 설치할 경우

<?php // CASTLE - KISA Web Attack Defense Tool
define("__CASTLE_PHP_VERSION_BASE_DIR__", "/var/www/html/castle-php");
include_once(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_referee.php");
?>

   또는 웹페이지가 존재하는 경로에서 상대 경로로 아래와 같이

<?php // CASTLE - KISA Web Attack Defense Tool
define("__CASTLE_PHP_VERSION_BASE_DIR__", "../castle-php");
include_once(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_referee.php");
?>

   위와 같이 추가하시면 됩니다.

   만일 제로보드 XE 버전과 같이 RAW_POST_DATA를 이용하시는 사이트에
   캐슬을 적용하고자 하신다면 (e.g., 제로보드 XE 버전과 같은 경우)
    
include_once(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_referee_raw.php");

   위와 같이 castle_referee.php 파일 대신에 castle_referee_raw.php 파일을
적용하시길 바랍니다.

   위에 대한 자세한 설명은 http://www.krcert.or.kr 홈페이지에서 확인하실 수 있습니다.
   
   감사합니다.

   - CASTLE 운영팀 - 

