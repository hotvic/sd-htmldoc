<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <title><?php echo $title; ?></title>
    <style type="text/css">
        table {
            border-collapse: collapse;
        }
        th, td {
            padding-left: 7px;
            padding-right: 7px;
        }
        th {
            height: 30px;
            background-color: rgb(167, 201, 66);
            border: 0px;
        }
        td {
            background-color: #EAF2D3;
            text-align: center;
            padding-top: 3px;
            padding-bottom: 3px;
        }
        body {
            margin-bottom: 80px;
        }
        #footer {
            position: fixed;
            border-top: 1px;
            border-bottom: 0px;
            border-left: 0px;
            border-right: 0px;
            border-style: solid;
            bottom: 0px;
            width: 100%;
            height: 80px;
            background-color: white;
        }
    </style>
</head>
<body>
    <center><h1><?php echo $title; ?></h1></center>
    <hr>
    <center>
    <table border="1">
        <tr class="header">
            <th><b><a href="<?php echo $url; ?>/show/by/name"><?php echo $this->lang->line('tutorial'); ?></a></b></th>
            <th><b><?php echo $this->lang->line('sources'); ?></b></th>
            <th><b><a href="<?php echo $url; ?>"><?php echo $this->lang->line('date'); ?></a></b></th>
            <th><b><a href="<?php echo $url; ?>/show/by/author"><?php echo $this->lang->line('author'); ?></a></b></th>
        </tr>
