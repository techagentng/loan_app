<style>
    ul.image-list {
        list-style: none;
        columns: 2;
        -webkit-columns: 2;
        -moz-columns: 2;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title"><?php echo $this->lang->line("loans_attachments"); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">
    
    <?php if(count($attachments) > 0):  ?>
    <div id="required_fields_message">Please select the file</div>
    <ul class="image-list">
        <?php foreach ($attachments as $attachment): ?>
            <li>
                <label>
                    <input type="checkbox" 
                           class="select_file" 
                           value="<?= $attachment["id"]; ?>"
                           data-url="<?= getDomain(); ?>/uploads/loan-<?= $loan_info->loan_id; ?>/<?= $attachment["filename"]; ?>"
                           data-desc="<?= (trim($attachment["descriptions"]) !== "") ? $attachment["descriptions"] : $attachment["filename"]; ?>" /> &nbsp; 
                           <?= (trim($attachment["descriptions"]) !== "") ? $attachment["descriptions"] : $attachment["filename"]; ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <div class="alert alert-info">No attachment found.</div>
    <?php endif;?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close"><?= $this->lang->line("common_close"); ?></button>
    <?php if ($select_type === "proof"): ?>
        <button id="btn-select-proof" class="btn btn-primary" type="button"><?= $this->lang->line('common_select'); ?></button>
    <?php else: ?>
        <button id="btn-select-images" class="btn btn-primary" type="button"><?= $this->lang->line('common_select'); ?></button>
    <?php endif; ?>
</div>

<script type='text/javascript'>
    $(document).ready(function () {
        $(document).on("click", "#btn-select-proof", function () {
            var selected = '';
            $(".select_file").each(function () {
                if ($(this).is(":checked"))
                {
                    selected += '<li><input type="hidden" name="proofs[]" value="' + $(this).val() + '" /><a href=' + $(this).data("url") + ' target="_blank">' + $(this).data("desc") + '</a></li>';
                }
            });
            $(".sel-proof").html(selected);
            $('#attachment_modal').modal("hide");
        });
        
        $(document).on("click", "#btn-select-images", function () {
            var selected = '';
            $(".select_file").each(function () {
                if ($(this).is(":checked"))
                {
                    selected += '<li><input type="hidden" name="images[]" value="' + $(this).val() + '" /><a href=' + $(this).data("url") + ' target="_blank">' + $(this).data("desc") + '</a></li>';
                }
            });
            $(".sel-images").html(selected);
            $('#attachment_modal').modal("hide");
        });
    });
</script>