<?php
require '../connect.php';
$ik=$_GET['ik'];	//kategori tempat ibadah sekitar
$fas=$_GET['fas']; //fasilitas
$destinasi=$_GET['destinasi'];			//destinasi hotel

$querysearch	="SELECT DISTINCT hotel.id as id, hotel.name as name, st_x(st_centroid(hotel.geom)) as lon, st_y(st_centroid(hotel.geom)) as lat";

if ($ik!="") {
	$querysearch	.=", small_industry.id as id2, small_industry.name as name2, st_x(st_centroid(small_industry.geom)) as lon2, st_y(st_centroid(small_industry.geom)) as lat2";
}

$querysearch	.=" from hotel left join detail_hotel on detail_hotel.id_hotel=hotel.id left join angkot on detail_hotel.id_angkot=angkot.id left join detail_facility_hotel on detail_facility_hotel.id_hotel = hotel.id left join facility_hotel on detail_facility_hotel.id_facility = facility_hotel.id, small_industry left join detail_product_small_industry on small_industry.id=detail_product_small_industry.id_small_industry left join product_small_industry on detail_product_small_industry.id_product=product_small_industry.id where ";
if ($ik!="") {
	$querysearch	.="product_small_industry.id = $ik and st_distancesphere(hotel.geom, small_industry.geom) <= 300 ";
}
if($ik!=""&&$fas!=""){
	$querysearch	.="and ";
}
if($fas!=""){
	$querysearch	.="facility_hotel.id in ($fas) ";
}
if ($ik!=""&&$destinasi!="") {
	$querysearch	.="and ";
}else if($fas!=""&&$destinasi!=""){
	$querysearch	.="and ";
}
if ($destinasi!="") {
	$querysearch    .="angkot.id = '$destinasi'";  
}
$hasil=pg_query($querysearch);
while($baris = pg_fetch_array($hasil))
	{
		  $id=$baris['id'];
		  $name=$baris['name'];
		  $id2=$baris['id2'];
		  $name2=$baris['name2'];
		  $lat=$baris['lat'];
		  $lng=$baris['lon'];
		  $lat2=$baris['lat2'];
		  $lng2=$baris['lon2'];
		  $dataarray[]=array('id'=>$id,'name'=>$name, 'id2'=>$id2,'name2'=>$name2,'lng'=>$lng, 'lat'=>$lat, 'lng2'=>$lng2, 'lat2'=>$lat2);
	}
echo json_encode ($dataarray);
// echo $querysearch;
?>