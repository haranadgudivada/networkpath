<?php
/**
* @author     Ignatius Teo
* @datetime   04 July 2013
* @purpose    Standard commenting way
*/
class Dijkstra
{
  protected $graph;

  public function __construct($graph) {
    $this->graph = $graph;
  }

  public function shortestPath($source, $target, $maxLatency) {
    // array of best estimates of shortest path to each
    // vertex
    $d = array();
    // array of predecessors for each vertex
    $pi = array();
    // queue of all unoptimized vertices
    $Q = new SplPriorityQueue();

    foreach ($this->graph as $v => $adj) {
      $d[$v] = INF; // set initial distance to "infinity"
      $pi[$v] = null; // no known predecessors yet
      foreach ($adj as $w => $cost) {
        // use the edge cost as the priority
        $Q->insert($w, $cost);
      }
    }
    // initial distance at source is 0
    $d[$source] = 0;

    while (!$Q->isEmpty()) {
      // extract min cost
      $u = $Q->extract();
      if (!empty($this->graph[$u])) {		  
        // "relax" each adjacent vertex
        foreach ($this->graph[$u] as $v => $cost) {
          // alternate route length to adjacent neighbor
          $alt = $d[$u] + $cost;
          // if alternate route is shorter
          if ($alt < $d[$v]) {
            $d[$v] = $alt; // update minimum length to vertex
            $pi[$v] = $u;  // add neighbor to predecessors
                           //  for vertex
          }
        }
      }
    }

    // we can now find the shortest path using reverse
    // iteration
    $S = new SplStack(); // shortest path with a stack
    $u = $target;
    $dist = 0;
    // traverse from target to source
    while (isset($pi[$u]) && $pi[$u]) {
      $S->push($u);
      $dist += $this->graph[$u][$pi[$u]]; // add distance to predecessor
      $u = $pi[$u];
    }

    // stack will be empty if there is no route back
    if ($S->isEmpty()) {
      echo "Path not found from ".@$source." to ".@$target."\n";
    }
    else {
      // add the source node and print the path in reverse
      // (LIFO) order
      $S->push($source);
      $ouput="$dist:";
      $sep = '';
      foreach ($S as $v) {
        $ouput .= $v.'->';
      }
	  return $ouput;
    }
  }
}
/**
* @author     Haranad Gudivada
* @datetime   12 Jan 2021
* @purpose    Standard commenting way
*/
if (isset($argc))
{
	$devicesArray=array();
	$devicesConnects=array();
	$row = 1;
	if (($handle = fopen($argv[1], "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			$row++;
			$deviceId1Var=preg_replace('/[^A-Za-z0-9\-]/', '', $data[0]);
			$deviceId2Var=preg_replace('/[^A-Za-z0-9\-]/', '', $data[1]);
			$deviceId1 = array_search($data[0],$devicesArray);
			$deviceId2 = array_search($data[1], $devicesArray);
			if($deviceId1){
				
			} else {
				$devicesArray[]=$data[0]; 
			}
			${$deviceId1Var}[$data[1]] = $data[2];
			
			if($deviceId2)
			{
				
			} else {
				$devicesArray[]=$data[1];
			}
			${$deviceId2Var}[$data[0]]=$data[2];
		}
		fclose($handle);
	} else {
		echo "Please enter valid CSV file path";
	}
	$graph=array();
	foreach($devicesArray as $device)
	{
		$deviceVar=preg_replace('/[^A-Za-z0-9\-]/', '', $device);
		$graph[$device]=${$deviceVar};
	}

	$g = new Dijkstra($graph);
	
	keepAsking:
	echo "Please enter the input(Eg: Device1 Device2 Latency): ";
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	if(strtolower(trim($line)) == 'quit'){
		exit;
	}
	$enteredInput=explode(" ", trim($line));
	if(count($enteredInput) == 3)
	{
		$shortestPath=$g->shortestPath($enteredInput[0], $enteredInput[1], $enteredInput[2]);	
		$shortestPathArray=explode(":", $shortestPath);
		if(count($shortestPathArray) > 0 && $shortestPathArray[0] > 0)
		{
			$latencyOuput=$shortestPathArray[0];$shortestPathOutput=$shortestPathArray[1];
			fclose($handle);
			if($enteredInput[2] >= $latencyOuput) {
			echo "Shortest path is ".$shortestPathOutput.$latencyOuput." \n";
			} else {
				 echo "Path not found from ".@$enteredInput[0]." to ".@$enteredInput[1]."\n";
			}
		} else {
			echo $shortestPath;
		}
		
	}
	else
	{
			echo "Please enter valid input! \n";
	}
	goto keepAsking;
}
?>
