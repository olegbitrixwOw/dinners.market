<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
// http://www.jstree.com/
echo 'tree wip';

Yp::bx_head([
	'/vendor/vakata/jstree/dist/jstree.js',
	'/vendor/vakata/jstree/dist/themes/default/style.css',
]);


$a=1;
?>

<div id="jstree_div_demo">
    <ul>
        <li>Root node 1
            <ul>
                <li id="child_node_1">Child node 1</li>
                <li>Child node 2</li>
            </ul>
        </li>
        <li>Root node 2</li>
    </ul>
</div>

<?php


?>