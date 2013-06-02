<?php echo doctype('html4-trans'); ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <title><?php echo $this->lang->line('title'); ?></title>
    <style type="text/css">
        #result {
            color: #FF0000;
            font-weight: bold;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        fieldset {
            margin-left: auto;
            margin-right: auto;
            width: 60%;
        }
    </style>
</head>
<body>
    <?php
    echo form_open_multipart('send/send');
    echo form_fieldset($this->lang->line('legend'));
    ?>
    <table><tbody>
        <tr>
            <th>
                <span style="color: #FF0000;"> * </span>
                <label><?php echo $this->lang->line('label_tutorial'); ?></label>
            </th>
            <td><?php echo form_input('tuto'); ?></td>
            <td><?php echo form_error('tuto'); ?></td>
        </tr>
        <tr>
            <th>
                <span style="color: #FF0000;"> * </span>
                <label title="<?php echo $this->lang->line('tip_user'); ?>"><?php echo $this->lang->line('label_user'); ?></label>
            </th>
            <td><?php echo form_input('user', '', 'onchange="checkUser(this.value)"'); ?></td>
            <td><?php echo form_error('user'); ?></td>
            <td id="uhint"></td>
        </tr>
        <tr>
            <th>
                <span style="color: #FF0000;"> * </span>
                <label title="<?php echo $this->lang->line('tip_pwd'); ?>"><?php echo $this->lang->line('label_pwd'); ?></label>
            </th>
            <td><?php echo form_password('pwd'); ?></td>
            <td><?php echo form_error('pwd'); ?></td>
        </tr>
        <tr>
            <th>
                <span style="color: #FF0000;"> * </span>
                <label><?php echo $this->lang->line('label_file'); ?></label>
            </th>
            <td><?php echo form_upload('file'); ?></td>
            <td><?php echo form_error('file'); ?></td>
        </tr>
        <tr>
            <th>
                <label title="<?php echo $this->lang->line('tip_details'); ?>"><?php echo $this->lang->line('label_details'); ?></label>
            </th>
            <td><?php echo form_textarea(array('name' => 'details', 'cols' => '36', 'rows' => '5')); ?></td>
        </tr>
        <tr>
            <td></td>
            <td><?php echo form_submit('submit', $this->lang->line('label_submit')); ?></td>
        </tr>
    </tbody></table>
    <?php 
    echo form_fieldset_close();
    echo form_close();
    ?>
</body>
</html>
