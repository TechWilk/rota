<?php

namespace TechWilk\Rota;

if (!empty($_GET['action']) && $_GET['action'] == 'edit') {
    header('Location: ../user/me');
    exit;
}

header('Location: ../user/new');
exit;
