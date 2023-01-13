<?php
$node1 = array(
                'id'=>1,
                'checkedFieldName'=>1,
                'flagUrl'=>1,
                'hasChildren'=>1,
                'population'=>1,
                'text'=>"Asia",
                'children' => array(
                                array(
                                    'id'=>1,
                                    'checkedFieldName'=>1,
                                    'flagUrl'=>1,
                                    'hasChildren'=>1,
                                    'population'=>1,
                                    'text'=>"Asia"
                                    ),
                                    array(
                                        'id'=>1,
                                        'checkedFieldName'=>1,
                                        'flagUrl'=>1,
                                        'hasChildren'=>1,
                                        'population'=>1,
                                        'text'=>"Asia"
                                    )
                                )
                );
$node2 = array(
    'id'=>1,
    'checkedFieldName'=>0,
    'flagUrl'=>1,
    'hasChildren'=>1,
    'population'=>1,
    'text'=>"USA",
    'children' => array(
        array(
            'id'=>1,
            'checkedFieldName'=>0,
            'flagUrl'=>1,
            'hasChildren'=>1,
            'population'=>1,
            'text'=>"Nevad"
        ),
        array(
            'id'=>1,
            'checkedFieldName'=>1,
            'flagUrl'=>1,
            'hasChildren'=>1,
            'population'=>1,
            'text'=>"Australia"
        )
    )
);
//var_dump(time()%2);
if ((time()%2)) {
    $treeA = array($node2);
} else {
    $treeA = array($node1);
}

echo json_encode($treeA);