<?php

return [

    /*
     * The maximum file size of an item in bytes.
     * Adding a larger file will result in an exception.
     *
     * Overridden from the package default of 10MB to 256MB so it doesn't
     * silently reject files that pass our own request validation rules
     * (e.g. the 100MB brochure/floorplan/payment_plan PDFs and the
     * 256MB project video upload).
     */
    'max_file_size' => env('MEDIA_MAX_FILE_SIZE', 1024 * 1024 * 256),

];
