<?php header('Content-Type: application/json'); ?>

{
    "api": {
        "status": "Is under maintenance",
        "details": "{{ $exception->getMessage() }}"
    }
}