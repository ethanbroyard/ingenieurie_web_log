<?php
function formatSlug($title) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
}
