<table>
    <?php

    foreach (new DirectoryIterator('./') as $file) {
        if ($file->getFilename() == 'ArrayAccess.php') {
            echo '<tr><td>getFilename()</td><td> ';
            var_dump($file->getFilename());
            echo '</td></tr>';
            echo '<tr><td>getBasename()</td><td> ';
            var_dump($file->getBasename());
            echo '</td></tr>';
            echo '<tr><td>isDot()</td><td> ';
            var_dump($file->isDot());
            echo '</td></tr>';
            echo '<tr><td>__toString()</td><td> ';
            var_dump($file->__toString());
            echo '</td></tr>';
            echo '<tr><td>getPath()</td><td> ';
            var_dump($file->getPath());
            echo '</td></tr>';
            echo '<tr><td>getPathname()</td><td> ';
            var_dump($file->getPathname());
            echo '</td></tr>';
            echo '<tr><td>getPerms()</td><td> ';
            var_dump($file->getPerms());
            echo '</td></tr>';
            echo '<tr><td>getInode()</td><td> ';
            var_dump($file->getInode());
            echo '</td></tr>';
            echo '<tr><td>getSize()</td><td> ';
            var_dump($file->getSize());
            echo '</td></tr>';
            echo '<tr><td>getOwner()</td><td> ';
            var_dump($file->getOwner());
            echo '</td></tr>';
            echo '<tr><td>$file->getGroup()</td><td> ';
            var_dump($file->getGroup());
            echo '</td></tr>';
            echo '<tr><td>getATime()</td><td> ';
            var_dump($file->getATime());
            echo '</td></tr>';
            echo '<tr><td>getMTime()</td><td> ';
            var_dump($file->getMTime());
            echo '</td></tr>';
            echo '<tr><td>getCTime()</td><td> ';
            var_dump($file->getCTime());
            echo '</td></tr>';
            echo '<tr><td>getType()</td><td> ';
            var_dump($file->getType());
            echo '</td></tr>';
            echo '<tr><td>isWritable()</td><td> ';
            var_dump($file->isWritable());
            echo '</td></tr>';
            echo '<tr><td>isReadable()</td><td> ';
            var_dump($file->isReadable());
            echo '</td></tr>';
            echo '<tr><td>isExecutable(</td><td> ';
            var_dump($file->isExecutable());
            echo '</td></tr>';
            echo '<tr><td>isFile()</td><td> ';
            var_dump($file->isFile());
            echo '</td></tr>';
            echo '<tr><td>isDir()</td><td> ';
            var_dump($file->isDir());
            echo '</td></tr>';
            echo '<tr><td>isLink()</td><td> ';
            var_dump($file->isLink());
            echo '</td></tr>';
            echo '<tr><td>getFileInfo()</td><td> ';
            var_dump($file->getFileInfo());
            echo '</td></tr>';
            echo '<tr><td>getPathInfo()</td><td> ';
            var_dump($file->getPathInfo());
            echo '</td></tr>';
            echo '<tr><td>openFile()</td><td> ';
            var_dump($file->openFile());
            echo '</td></tr>';
            echo '<tr><td>setFileClass()</td><td> ';
            var_dump($file->setFileClass());
            echo '</td></tr>';
            echo '<tr><td>setInfoClass()</td><td> ';
            var_dump($file->setInfoClass());
            echo '</td></tr>';
        }
    }

    $it = new DirectoryIterator('./');
    /*** loop directly over the object ***/
    while($it->valid())
    {
        /*** check if value is a directory ***/
//        if($it->isDir())
//        {
            /*** echo the key and current value ***/
            echo $it->key().' -- '.$it->current().'<br />';
       // }
        /*** move to the next iteration ***/
        $it->next();
    }

    ?>
</table>