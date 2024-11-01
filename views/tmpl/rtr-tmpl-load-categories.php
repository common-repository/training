<?php
$query = "SELECT * from " . rtr_wpl_tr_categories() . " ORDER BY id DESC";
$categories = $wpdb->get_results($query, ARRAY_A);

/*global $wpdb;
$categories = $wpdb->get_results( "SELECT * from " . rtr_wpl_tr_categories() . " ORDER BY id DESC", ARRAY_A );
*/

if (count($categories) > 0) {
    $i = 1;
    foreach ($categories as $inx => $stx) {
        ?>
        <tr class="data-row">
            <td><?php echo $i++; ?></td>
            <td><span class="category-txt" data-id="<?php echo $stx['id']; ?>"><?php echo ucfirst($stx['name']); ?></span><span class="edit-category-info" title="Edit"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg" alt="Training course edit image"></span></td>
            <td><?php
                $dataLabels = (array) json_decode($stx['subcategories']);
                foreach ($dataLabels as $label) {
                    ?>
                    <span class="label label-primary taghover" title="Remove <?php echo $label; ?>" data-id="<?php echo $stx['id']; ?>" data-val="<?php echo $label; ?>"><?php echo $label; ?> &times;</span>
                    <?php
                }
                ?> <span class="edit-subcategory-info" data-id="<?php echo $stx['id']; ?>" title="Edit subcategories"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg" alt="Training course edit image"></span></td>
            <td><?php echo $stx['created_dt']; ?></td>
            <td>
                <div>
                    <a href="javascript:;" data-id="<?php echo $stx['id']; ?>" title="Delete Category" class=" rtr-btn rtr-btn-danger btn-del-category"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg" alt="Training course trash image"></span></a>                                    
                </div>
            </td>
        </tr>
        <?php
    }
}
?>