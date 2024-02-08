<?
$data = array(
    "id" => "tester",
    "name" => "홍길동",
    "email" => "test@naver.com",
    "cell" => "010-1234-1234",
);
$json = json_encode($data);

$str="TGQzbGx0VGlNUXV0NnYwSGJWZGhKaGJESEE4U25odzZZYmgxMEdGQ3hYYz0%3D";
echo hash('sha256', $json);
?>