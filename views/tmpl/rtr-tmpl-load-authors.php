<?php

$query = "SELECT * FROM " . rtr_wpl_tr_authors() . " ORDER BY id DESC";
$authors = $wpdb->get_results($query, ARRAY_A);

if (count($authors) > 0) {
    $i = 1;
    foreach ($authors as $inx => $stx) {
        $imgae = !empty($stx['profile_img']) ? esc_attr(trim($stx['profile_img'])) : RTR_WPL_COUNT_PLUGIN_URL . "assets/images/blank.jpg";
        ?>
        <tr class="data-row">
            <td><?php echo $i++; ?></td>
            <td class="data-name"><?php echo ucfirst($stx['name']); ?></td>
            <td class="data-email"><?php echo $stx['email']; ?></td>
            <td class="data-phone"><?php echo $stx['phone']; ?></td>
            <td class="data-image">

                <img src="<?php echo $imgae; ?>" style="height: 35px;"/>
            </td>
            <td class="data-post"><?php echo $stx['post']; ?></td>
            <td><?php echo $stx['create_at']; ?></td>
            <td>
                <div>
                    <a data-web="<?php echo $stx['website']; ?>" data-about="<?php echo $stx['about']; ?>" data-imgae="<?php echo $stx['profile_img']; ?>" data-fburl="<?php echo $stx['fb_url']; ?>" data-id="<?php echo $stx['id']; ?>" href="javascript:void(0)"  class="rtr-btn rtr-btn-primary btn-edit-author" title="Edit Author"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg" alt="Training course edit image">
</span></a>                                    
                    <a href="javascript:;" data-id="<?php echo $stx['id']; ?>" title="Delete Author" class=" rtr-btn rtr-btn-danger btn-del-author"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg" alt="Training course trash image"></span></a>                                    
                </div>
            </td>
        </tr>
        <?php
    }
}
?>