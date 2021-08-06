@extends('adminlte::page')

@section('title', 'Tree View')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
  
@stop

@section('content')

    <div class="tab-content p-1">
        <div class="loading loadr d-none">Loading&#8230;</div>
        <div class="font-weight-bold m-2 font-italic text-primary"><h4 class="right"><i class="fas fa-sitemap"></i> Organizational Structure </h4></div>
<style>
    /*Now the CSS*/

.tree ul {
	padding-top: 20px; position: relative;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

.tree li {
	float: left; text-align: center;
	list-style-type: none;
	position: relative;
	padding: 20px 5px 0 5px;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
	content: '';
	position: absolute; top: 0; right: 50%;
	border-top: 1px solid #ccc;
	width: 50%; height: 20px;
}
.tree li::after{
	right: auto; left: 50%;
	border-left: 1px solid #ccc;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
	display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
	border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
	border-right: 1px solid #ccc;
	border-radius: 0 5px 0 0;
	-webkit-border-radius: 0 5px 0 0;
	-moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
	border-radius: 5px 0 0 0;
	-webkit-border-radius: 5px 0 0 0;
	-moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
	content: '';
	position: absolute; top: 0; left: 50%;
	border-left: 1px solid #ccc;
	width: 0; height: 20px;
}

.tree li a{
	border: 1px solid #ccc;
	padding: 5px 10px;
	text-decoration: none;
	color: #666;
	font-family: arial, verdana, tahoma;
	font-size: 11px;
	display: inline-block;
	
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
	background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
	border-color:  #94a0b4;
}

</style>
@php($html = [])
<?php  ?>
@foreach($reportings as $reporty)
	<?php 
	$reporty = (Object) $reporty;
	$tos = $reporty->report_to;
	$id = $reporty->employee_id;
	$emp_name = $reporty->emp_name;
	$reporting_emp_name = $reporty->reporting_emp_name;

	//echo '- <br> h'.$reporting_emp_name.' h<br> -';

	$flg= 0;
	if(isset($html[$reporting_emp_name])) {
		
		// now prakssh is set
		// loop into to check if prak
		foreach($html[$reporting_emp_name] as $key => $value) {
			$i = 0;
			$flg1 = 0;
			//var_dump($value).PHP_EOL;
			//exit;
			foreach($value as $k => $v) {
				$flg1 = 0;
				
					if(isset($html[$key][$k])){
					if($html[$key][$k] == $reporting_emp_name) {
						//echo '<br>'.$k.'<br>';
						//exit;
						$html[$key][$k]['children'] = array_merge($html[$key][$k]['children'], ['name'=> $emp_name, 'children' => []]);
						$flg1 = 1;
					} 
				}
				
				

			}
			
		}

			if($flg1==0) {
				$html[$reporting_emp_name] = array_merge($html[$reporting_emp_name], [['name' => $emp_name, 'children' => []]]);	
			}
			
			
			
	} else {

		foreach($html as $key => $value) {
			$i = 0;
			
			foreach($value as $k => $v) {
				$flg = 0;
				foreach($v as $vv) {
					
					if($vv == $reporting_emp_name) {

						//echo '<br>'.$html[$key][$k]['children'][$k].'<br>';
						//exit;
						$html[$key][$k]['children'] = array_merge($html[$key][$k]['children'], ['name'=> $emp_name, 'children' => []]);
						$flg = 1;
					} 
				}
				

			}
			
		}

		if($flg==0) {
			//echo 'run<br>';
			$html[$reporting_emp_name] = [['name' => $emp_name, 'children' => []]];
			// prakash -> [['name' => 'Sabui', children => []]]
		}
		
	}
	?>

@endforeach
<?php  //print_r($html); exit;  ?>

<div class="tree">
	@php($tmp_parent='')
	@foreach($html as $key => $value)
	
		<ul>
			<li>
				<a href="#">{{ $key }}</a>
				<ul>
				@foreach($value as $k => $value)
				
					<li><a href="#">{{ $value['name'] }}</a></li>
				
				@endforeach
			</ul>
			</li>
		</ul>


	@php($temp_parent = $key)
	@endforeach

<?php

function toUL(array $array)
{
    $html = '<ul>' . PHP_EOL;

    foreach ($array as $value)
    {
        $html .= '<li><a href="#">' . $value['name'];
        if (!empty($value['children']))
        {
            $html .= toUL($value['children']);
        }
        $html .= '</a></li>' . PHP_EOL;
    }

    $html .= '</ul>' . PHP_EOL;

    return $html;
}


?>

	{{-- <ul>
		<li>
			<a href="#">Parent</a>
			<ul>
				<li>
					<a href="#">Child</a>
					<ul>
						<li>
							<a href="#">Grand Child</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#">Child</a>
					<ul>
						<li><a href="#">Grand Child</a></li>
						<li>
							<a href="#">Grand Child</a>
							<ul>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
							</ul>
						</li>
						<li><a href="#">Grand Child</a></li>
					</ul>
				</li>
			</ul>
		</li>
	</ul> --}}
</div>
    
</div>
    
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/main.css') }}">
@stop

@section('js')

    <script>
    
      
    
    </script>
@stop
