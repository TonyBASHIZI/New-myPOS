<?php

defined("ABSPATH") or die();

class Orders
{
    public function index()
    {
        $db = new Database();

        $query = "SELECT * FROM orders ORDER BY id DESC";
        $rows = $db->query($query);

        $data = [];

        if($rows)
            $data['rows'] = $rows;
        else
            $data['rows'] = [];

        load_view("orsers",$data);
    }
}