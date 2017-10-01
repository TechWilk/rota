<?php

if (!empty($_GET['id'])) {
    header('Location: ../event/'.(int)$_GET['id']);
}
