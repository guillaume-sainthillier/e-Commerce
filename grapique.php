<?php

include_once( 'lib/ofc/open-flash-chart.php' );

// generate some random data
srand((double)microtime()*1000000);

$bar = new bar_outline( 50, '#9933CC', '#8010A0' );
$bar->key( 'Page views', 10 );

$data = array();
for( $i=0; $i<9; $i++ )
{
  $bar->data[] = rand(5,9);
}


$g = new graph();
$g->title( 'Bar Chart', '{font-size: 20px;}' );

//
// BAR CHART:
//
//$g->set_data( $data );
//$g->bar_filled( 50, '#9933CC', '#8010A0', 'Page views', 10 );
//
// ------------------------
//
$g->data_sets[] = $bar;
//
// X axis tweeks:
//
$g->set_x_labels( array( 'January','February','March','April','May','June','July','August','September' ) );
//
// set the X axis to show every 2nd label:
//
$g->set_x_label_style( 10, '#9933CC', 0, 2 );
//
// and tick every second value:
//
$g->set_x_axis_steps( 2 );
//


$g->set_y_max( 10 );
$g->y_label_steps( 4 );
$g->set_y_legend( 'Open Flash Chart', 12, '#736AFF' );
echo $g->render();
?>