<?php

$num1 = $_POST['num1'];
$num2 = $_POST['num2'];
$num3 = $_POST['num3'];
$age = $_POST['age'];
$cnt = $_POST['cnt'];
$age2 = $_POST['age2'];
$cnt2 = $_POST['cnt2'];
$price = 1000;
$price2 = 1000;
$sum = 0;
$sum2 = 0;
$total = 0;
$max = 0;

if($num1 > $num2 && $num1 > $num3)
{
	$max = $num1;
}
else if($num2 > $num3)
{
	$max = $num2;
}
else
{
	$max = $num3;
}
echo "if문을 이용한 최댓값 : $max";

$max2 = ($num1 > $num2) ? (($num1 > $num3) ? $num1 : $num3 ) : (($num2 > $num3) ? $num2 : $num3 );

echo "<br>조건연산자를 이용한 최댓값 : $max2";


if( $age > 0 && $age <= 3)
{
	$sum = $price*0.00*$cnt;
}
else if($age >= 4 && $age <= 13)
{
	$sum = $cnt*($price*0.50);
}
else if($age >= 14 && $age <= 19)
{
	$sum = $cnt*($price*0.75);
}
else
{
	$sum = $cnt*$price;
}

if( $age2 > 0 && $age2 <= 3)
{
	$sum2 = $price2*0.00*$cnt2;
}
else if($age2 >= 4 && $age2 <= 13)
{
	$sum2 = $cnt2*($price2*0.50);
}
else if($age2 >= 14 && $age2 <= 19)
{
	$sum2 = $cnt2*($price2*0.75);
}
else
{
	$sum2 = $cnt2*$price2;
}

$total = $sum + $sum2;
echo "<br><br> $age 세 $cnt 명 <br>";
echo "$age2 세 $cnt2 명 <br> 총 : $total 원";

?>