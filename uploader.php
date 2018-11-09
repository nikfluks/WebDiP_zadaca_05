<!DOCTYPE html>
<html>
    <head>
        <title>PHP - Primjer file upload</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
        // $userfile is where file went on webserver
        $userfile = $_FILES['userfile']['tmp_name'];

        // $userfile_name is original file name
        $userfile_name = $_FILES['userfile']['name'];

        // $userfile_size is size in bytes
        $userfile_size = $_FILES['userfile']['size'];

        // $userfile_type is mime type e.g. image/gif
        $userfile_type = $_FILES['userfile']['type'];

        // $userfile_error is any error encountered
        $userfile_error = $_FILES['userfile']['error'];
// userfile_error was introduced at PHP 4.2.0
// use this code with newer versions
        if ($userfile_error > 0) {
            echo 'Problem: ';
            switch ($userfile_error) {
                case 1: echo 'File exceeded upload_max_filesize';
                    break;
                case 2: echo 'File exceeded max_file_size';
                    break;
                case 3: echo 'File only partially uploaded';
                    break;
                case 4: echo 'No file uploaded';
                    break;
            }
            exit;
        }

// one more check: does the file have the right MIME type?
        
        if ($userfile_type != 'image/png' && $userfile_type != 'image/jpeg') {
            
            var_dump($userfile_type);
            echo 'PROBLEM: datoteka nije slika!' . $userfile;
            exit;
        }

// put the file where we'd like it
        $upfile = 'slike/' . $userfile_name;

// is_uploaded_file and move_uploaded_file added at version 4.0.3
        if (is_uploaded_file($userfile)) {
            if (!move_uploaded_file($userfile, $upfile)) {
                echo 'Problem: Could not move file to destination directory';
                exit;
            }
        } else {
            echo 'Problem: Possible file upload attack. Filename: ' . $userfile_name;
            exit;
        }

        echo 'File uploaded successfully<br /><br />';
        ?>
    </body>
</html>
