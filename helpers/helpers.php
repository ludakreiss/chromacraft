<?php

function notfound() {
    http_response_code(404);
    include('../includes/notfound.html');
    die();
}