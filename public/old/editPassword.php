<?php namespace TechWilk\Rota;

if (!empty($_GET['id'])) {
    header('Location: ../user/'.(int)$_GET['id'].'/password');
    exit;
}

header('Location: ../user/me');
exit;
