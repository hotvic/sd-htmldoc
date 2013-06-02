<?php foreach($tutos as $item): ?>
       <tr>
            <td><a href="<?php echo $item['url']; ?>"><?php echo $item['name']; ?></a></td>
            <td><a href="<?php echo $item['ufolder']; ?>"><?php echo $item['folder']; ?></a></td>
            <td><?php echo $item['date']; ?></td>
            <td><a href="<?php echo $url . '/page/author/' . $item['uid']; ?>"><?php echo $item['author']; ?></a></td>
        </tr>
<?php endforeach; ?>
