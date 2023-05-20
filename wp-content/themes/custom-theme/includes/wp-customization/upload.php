<?php

/** Remove accents from file name when uploading */
add_filter('sanitize_file_name', 'sanitize_file_name_remove_accents', 10);
function sanitize_file_name_remove_accents($filename) {
    return remove_accents($filename);
}