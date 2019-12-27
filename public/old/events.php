<?php

if (isset($_GET['view']) && $_GET['view'] !== 'all') {
    header('Location: ../user/me');
    exit;
}

if (isset($_GET['filter'])) {
    header('Location: ../events/type/'.(int) $_GET['filter']);
    exit;
}

header('Location: ../events');
