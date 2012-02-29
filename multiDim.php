<?php
//two classes of objects which will be in multidimensional array
class Org {
    public $name;
    public function __construct($name) {
        $this->name = $name;
    }
}
class Org2 {
    public $name;
    public function __construct($name) {
        $this->name = $name;
    }
}

//main array
$arr = array();

//creating first subarray manually
//the subarray name must be like first class above
$arr['Org'] = array();

//creating second subarray for objects of selected class
//in another way
$x=2222;        //only for creating the sample object
$arr[get_class(new Org2("wtf".$x))] = array();

//pushing some new objects to (sub)arrays in (main)array
for ($i=0; $i<3; $i++) {
    $org1 = new Org("tst".$i);
    $org2 = new Org2("wtf".$i);
    array_push($arr[get_class($org1)], $org1);
    array_push($arr[get_class($org2)], $org2);
}

//printing everything
foreach ($arr as $key=>$value) {
    $counter = 0;
    echo "=====".$key." (key) | value: ".$value."<br/>";
    foreach ($value as $key2=>$value2) {
        if($value2 == null) {
            //echo "counter: ".$counter." | key: ".$key2;
            unset($arr[$key][$key2]);
        } else {
            print_r($value2);
            echo " [".$counter."]  [".$key2."]<br/>";
        }
        $counter++;
    }
}

//the additional, second part
echo "<br/>--------<br/><br/>";
//deleting one of the objects from selected (sub)array
unset($arr['Org'][1]);

//printing everything again (look at the counter values)
foreach ($arr as $key=>$value) {
    $counter = 0;
    echo "=====".$key." (key) | value: ".$value."<br/>";
    foreach ($value as $key2=>$value2) {
        if($value2 == null) {
            //echo "counter: ".$counter." | key: ".$key2;
            unset($arr[$key][$key2]);
        } else {
            print_r($value2);
            echo " [".$counter."]  [".$key2."]<br/>";
        }
        $counter++;
    }
}
?>