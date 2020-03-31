<style>
    .DTE_Field_Type_upload { display: none !important; }
    .btn-choose-file { cursor: pointer; }
    .datepicker.datepicker-dropdown {
        z-index: 30000 !important;
    }
    
    .dataTables_scroll {
        width: 100% !important;
    }

    <?= $object->table_id ?> {
        border-bottom: 1px solid #ddd;
    }
    <?= $object->table_id ?> th:nth-child(1) {

        width: 40px !important;
        min-width: 40px !important;
    }

    <?= $object->table_id ?> td:nth-child(1) {
        text-align: center;
        white-space: nowrap;
    }
    
    .dataTables_scrollBody {
        max-height: fit-content !important;
        height: auto !important;
    }

    .dataTables_scroll {overflow:auto}
    
    table.dataTable.display tbody>tr:hover,
    table.dataTable.display tbody>tr:hover>.sorting_1,
    table.dataTable.display tbody>tr:hover>.sorting_2,
    table.dataTable.display tbody>tr:hover>.sorting_3,
    table.dataTable.display tbody>tr:hover>.sorting_4,
    table.dataTable.display tbody>tr.selected>.sorting_2, 
    table.dataTable.display tbody>tr.selected>.sorting_1, 
    table.dataTable.display tbody>tr.selected>.sorting_3, 
    table.dataTable.display tbody>tr.selected>.sorting_4, 
    table.dataTable.display tr.selected
    {
        background-color: #eff3fd !important;
    }
    
    div.dataTables_wrapper div.dataTables_processing {
        z-index: 99;
    }
    
    .dataTables_wrapper .dataTables_processing {
        background: none !important;
        background-color: transparent !important;
        border-color:transparent !important;
    }
    .dataTables_scrollHeadInner {
        width: auto !important;
        overflow: hidden;
    }
    
    .DTFC_LeftHeadWrapper table {
        margin-bottom: 0 !important;
    }

    .DTFC_LeftBodyWrapper {
        background: white;
    }

    .DTFC_LeftBodyLiner table {
        margin-top: -2px !important;
    }
    
/*    div.DTE_Footer button.btn, div.DTE_Footer div.DTE_Form_Buttons button {
        float:none !important;
    }*/
    
/*    div.DTE_Footer .DTE_Form_Buttons {
        float: right;
    }*/
    
/*    div.DTE_Footer .DTE_Form_Buttons button {
        display: inline !important;
    }*/
</style>

<script>
    $('#tab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {  
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
    $(document).ready(function () {

        //Data table Editor Fields
        <?php if ( isset( $object->editor_fields ) ): ?>
            <?php foreach($object->editor_fields as $field): ?>
                var obj = {};
                <?php foreach((array)$field->get_fields() as $key => $value): ?>
                    //Checking if there is customize date  
                    <?php if($key == 'data_type' && $value == 'date'): ?>
                        (function ($, DataTable) {

                        if ( ! DataTable.ext.editorFields ) {
                            DataTable.ext.editorFields = {};
                        }

                        var Editor = DataTable.Editor;

                        DataTable.ext.editorFields.<?=$field->get_fields()->type;?> = {
                            create: function ( conf ) {
                                var that = this;
                                conf._enabled = true;
                                conf._input = $(
                                    '<div class="input-group date">'+
                                        '<input type="text" id="'+Editor.safeId( conf.id )+'"  class="form-control" style="border-radius: 0px !important;" />'+
                                        '<span class="input-group-addon">'+
                                            '<span class="glyphicon glyphicon-calendar"></span>'+
                                        '</span>' +
                                    '</div>'
                                );
                                return conf._input;
                            },

                            get: function ( conf ) {
                                return $('#' + conf.id).val();
                            },

                            set: function ( conf, val ) {
                                $(conf._input[0]).find( "input[id='"+conf.id+"']" ).val( val )
                            }
                        };


                    })(jQuery, jQuery.fn.dataTable);
                        
                        
                    <?php endif; ?>
            
                    <?php if($key == 'data_type' && $value == 'group_select'): ?>
                        
                        
                        (function ($, DataTable) {

                        if ( ! DataTable.ext.editorFields ) {
                            DataTable.ext.editorFields = {};
                        }

                        var Editor = DataTable.Editor;

                        DataTable.ext.editorFields.<?=$field->get_fields()->type;?> = {
                            create: function ( conf ) {
                                var that = this;
                                conf._enabled = true;
                                
                                $select_html = '<select id="'+Editor.safeId( conf.id )+'" class="form-control">';
                                
                                <?php if(isset($field->get_fields()->default_text)): ?>
                                $select_html += '<option value="<?=$field->get_fields()->default_text;?>"><?=$field->get_fields()->default_text;?></option>';
                                <?php endif; ?>
                                <?php foreach($field->get_fields()->options as $option_key => $option_value): ?>
                                    
                                <?php $tmp_opt = explode(":", $option_key); ?>
                                <?php if ( isset( $tmp_opt[1] ) ): ?>                                    
                                    $select_html += '<option style="font-weight:bold; background: #efefef;" value="<?=$tmp_opt[0]?>"><?=$tmp_opt[1];?></option>';
                                <?php else: ?>
                                    $select_html += '<option style="font-weight:bold; background: #efefef;" disabled="disabled"><?=$option_key;?></option>';
                                <?php endif; ?>
                                
                                
                                    <?php $single_group = false; ?>
                                    <?php foreach($option_value as $category => $data_row): ?>                                        
                                    <?php if (!isset($data_row[0])) {$single_group = true; break;} ?>
                                        
                                        $select_html += '<option disabled="disabled" style="font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;<strong><?=$category;?></strong></option>';
                                        <?php foreach($data_row as $select_data): ?>
                                            $select_html += '<option value="<?=$select_data['value'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$select_data['label'];?></option>';                                    
                                        <?php endforeach;?>
                                    <?php endforeach;?>
                                        
                                    <?php if($single_group): ?>
                                        <?php foreach($option_value as $select_data): ?>
                                            $select_html += '<option value="<?=$select_data['value'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$select_data['label'];?></option>';                                    
                                        <?php endforeach;?>
                                    <?php endif; ?>
                                
                                
                                <?php endforeach; ?>
                                $select_html += '</select>';
                                
                                conf._input = $($select_html);
                                return conf._input;
                            },

                            get: function ( conf ) {
                                return $('#' + conf.id).val();
                            },

                            set: function ( conf, val ) {
                                
                                if ( $(conf._input[0]).find('option:contains('+val+')').length > 0)
                                {
                                    val = $(conf._input[0]).find('option:contains('+val+')').val();
                                    $(conf._input[0]).val(val);
                                }
                                else
                                {
                                    $(conf._input[0]).val(val)
                                }
                            }
                        };


                    })(jQuery, jQuery.fn.dataTable);
                        
                        
                    <?php endif; ?>
            
                    // File type
                    <?php if($key == 'type' && $value == 'file'): ?>
                        
                        
                        (function ($, DataTable) {

                        if ( ! DataTable.ext.editorFields ) {
                            DataTable.ext.editorFields = {};
                        }

                        var Editor = DataTable.Editor;

                        DataTable.ext.editorFields.<?=$field->get_fields()->type;?> = {
                            create: function ( conf ) {
                                var that = this;
                                conf._enabled = true;
                                
                                $html = '<div class="form-group input-group" style="margin:0">'
                                $html += '<input type="text" id="'+Editor.safeId( conf.id )+'" value="" placeholder="No file selected" class="required form-control" />'
                                $html += '<span class="input-group-addon btn-choose-file">'
                                $html += '<i class="fa fa-folder-open"></i>';
                                $html += '</span>';
                                <?php if( isset($field->get_fields()->show_select_asset) && $field->get_fields()->show_select_asset ): ?>
                                $html += '<span title="Select from Asset" class="input-group-addon <?=( isset($field->get_fields()->asset_class) ? $field->get_fields()->asset_class : 'asset' )?>" style="cursor: pointer; border-left: 1px solid #e6e6e6; background: #e6e6e6;" data-selected-field="' + Editor.safeId( conf.id ) + '">'
                                $html += '...';
                                $html += '</span>';
                                <?php endif; ?>
                                $html += '</div>';
                                
                                conf._input = $($html);
                                return conf._input;
                            },

                            get: function ( conf ) {
                                return $('#' + conf.id).val();
                            },

                            set: function ( conf, val ) {
                                $(conf._input[0]).find( "input[id='"+conf.id+"']" ).val( val )
                            }
                        };


                    })(jQuery, jQuery.fn.dataTable);
                    <?php endif; ?>
            
                    // Hidden type
                    <?php if($key == 'type' && $value == 'hidden'): ?>
                        
                        
                        (function ($, DataTable) {

                        if ( ! DataTable.ext.editorFields ) {
                            DataTable.ext.editorFields = {};
                        }

                        var Editor = DataTable.Editor;

                        DataTable.ext.editorFields.<?=$field->get_fields()->type;?> = {
                            create: function ( conf ) {
                                var that = this;
                                conf._enabled = true;
                                
                                $html = '<style> .DTE_Field.DTE_Field_Type_hidden{display:none;} </style>';
                                $html += '<input type="hidden" id="'+Editor.safeId( conf.id )+'" value="" placeholder="No file selected" class="required form-control" />'
                                
                                conf._input = $($html);
                                return conf._input;
                            },

                            get: function ( conf ) {
                                return $('#' + conf.id).val();
                            },

                            set: function ( conf, val ) {
                                $(conf._input[0]).find( "input[id='"+conf.id+"']" ).val( val )
                            }
                        };


                    })(jQuery, jQuery.fn.dataTable);
                    <?php endif; ?>
                    
                    // Select Icon Image
                    <?php if($key == 'type' && $value == 'select_icon_image'): ?>
                    
                        (function ($, DataTable) {

                            if ( ! DataTable.ext.editorFields ) {
                                DataTable.ext.editorFields = {};
                            }

                            var Editor = DataTable.Editor;

                            DataTable.ext.editorFields.select_icon_image = {
                                create: function ( conf ) {
                                    var that = this;
                                    conf._enabled = true;
                                    conf._input = $(
                                        '<div class="input-group" onClick="selectFile(\'DTE_Field_icon_image\')">'+
                                            '<input type="text" class="form-control" value="" id="'+Editor.safeId( conf.id )+'" />'+
                                            '<span class="input-group-addon btn-primary" id="basic-addon2">Select...</span>' +
                                        '</div>'
                                    );
                                    return conf._input;
                                },

                                get: function ( conf ) {
                                    return $('#' + conf.id).val();
                                },

                                set: function ( conf, val ) {
                                    $(conf._input[0]).find( "input[id='"+conf.id+"']" ).val( val )
                                }
                            };
                        })(jQuery, jQuery.fn.dataTable);
                    
                    <?php endif; ?>
                    // Select Icon Image
                    
                    <?php if($key == 'type' && $value == 'summernote'): ?>
                    
                        (function ($, DataTable) {

                            if ( ! DataTable.ext.editorFields ) {
                                DataTable.ext.editorFields = {};
                            }

                            var Editor = DataTable.Editor;

                            DataTable.ext.editorFields.summernote = {
                                create: function ( conf ) {
                                    var that = this;
                                    conf._enabled = true;
                                    conf._input = $('<textarea class="form-control" id="'+Editor.safeId( conf.id )+'"></textarea>');
                                    return conf._input;
                                },

                                get: function ( conf ) {
                                    return $('#' + conf.id).val();
                                },

                                set: function ( conf, val ) {}
                            };

                        })(jQuery, jQuery.fn.dataTable);
                    
                    <?php endif; ?>
                    // Select Icon Image
                    
                    // Span
                    <?php if($key == 'type' && $value == 'span'): ?>
                    
                        (function ($, DataTable) {

                            if ( ! DataTable.ext.editorFields ) {
                                DataTable.ext.editorFields = {};
                            }

                            var Editor = DataTable.Editor;

                            DataTable.ext.editorFields.span = {
                                create: function ( conf ) {
                                    var that = this;
                                    conf._enabled = true;
                                    conf._input = $('<span id="'+Editor.safeId( conf.id )+'"></span>');
                                    return conf._input;
                                },

                                get: function ( conf ) {},

                                set: function ( conf, val ) {
                                    $(conf._input[0]).html( val );
                                }
                            };

                        })(jQuery, jQuery.fn.dataTable);
                    
                    <?php endif; ?>
                    // Span
                    
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        
        
    });
</script>

<?php $has_edit = isset($object->has_edit_dblclick) ? $object->has_edit_dblclick : false; ?>
<script>
    var documentSectionTable;
    var dEditor;

(function ($) {

    //'use strict';
    var myEditor;
    
    $(document).ready(function () {
        $(document).on("click", ".btn-choose-file", function(event){
            event.stopImmediatePropagation();
            <?php if(isset($object->upload_trigger)): ?>
                <?=$object->upload_trigger;?>(this);
            <?php else: ?>
                
                <?php
                                
                foreach( $object->editor_fields as $dField )
                {
                    $dFields = (array)$dField->get_fields();
                    if ( in_array( "file", $dFields ) )
                    {
                        echo 'if ( $(this).parent().parent().parent().parent().hasClass("DTE_Field_Name_' . $dFields['data_column'] . '") ) {';                        
                        echo '$("input[name=\'upload_type\']").val( "' . $dFields['data_column'] . '" )';
                        echo '}';
                    }
                }

                ?>
                
                
            <?php endif; ?>
            $(".editor_upload").find("input").trigger("click");
        });
        
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
        
        var editor_server_params = {};
        <?php if( isset( $object->server_params["table_editor"] ) ): ?>
            <?php foreach($object->server_params["table_editor"] as $key => $param): ?>
                editor_server_params['<?=$key?>'] = '<?=$param; ?>';
            <?php endforeach; ?>
        <?php endif; ?>
        
        var data_fields = new Array();
        <?php if ( isset( $object->editor_fields ) ): ?>
            <?php foreach($object->editor_fields as $field): ?>
                var obj = {};
                <?php foreach((array)$field->get_fields() as $key => $value): ?>
                    <?php $key = ($key == 'data_column') ? 'name' : $key?>
                    <?php $key = ($key == 'default') ? 'def' : $key?>

                    <?php if($key == 'type' && $value == 'upload'): ?>
                        obj.display = function(filename) {
                            <?php if( isset($object->callbacks["uploadComplete"]) ): ?>
                                <?=$object->callbacks["uploadComplete"]; ?>(filename);
                            <?php else: ?>
                                
                                <?php
                                
                                foreach( $object->editor_fields as $dField )
                                {
                                    $dFields = (array)$dField->get_fields();
                                    if ( in_array( "file", $dFields ) )
                                    {
                                        echo 'if ( $("input[name=\'upload_type\']").val() == "'. $dFields['data_column'] .'" ) {';                                        
                                        echo '$("#DTE_Field_'.$dFields['data_column'].'").val(filename);';
                                        echo '}';
                                    }
                                }
                                
                                ?>
                                
                                
                            <?php endif; ?>
                        };
                        <?php if( isset($object->callbacks["preUpload"]) ): ?>
                            obj.display = <?=$object->callbacks["preUpload"]; ?>;
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($key == 'options' && ((!isset($field->get_fields()->data_type)) || (isset($field->get_fields()->data_type) && $field->get_fields()->data_type != 'group_select')) ): ?>
                        obj['<?=$key?>'] = new Array();
                        <?php foreach($value as $option): ?>
                            var obj_options = {};
                            obj_options['label'] = '<?=$option['label']?>';
                            obj_options['value'] = '<?=$option['value']?>';
                            
                            obj['<?=$key?>'].push( obj_options );
                            
                        <?php endforeach;?>
                    <?php else: ?>
                        <?php if (is_bool($value)): ?>
                            obj['<?=$key?>'] = <?=($value)?'true':'false'?>;
                        <?php else:?>
                            obj['<?=$key?>'] = <?= is_array($value) ? json_encode($value) : "'$value'"; ?>;
                        <?php endif;?>                    
                    <?php endif; ?>
                <?php endforeach;?>                

                data_fields.push(obj);

            <?php endforeach;?>
        <?php endif; ?>
        
        myEditor = new $.fn.dataTable.Editor({
            <?php if(isset($object->template)): ?>
                    template: '<?=$object->template?>',
            <?php endif;?>
                
            formOptions: {
                main: {
                    onEsc: false,
                    onBackground:  false,
                }
            },
                
            ajax: {
                url : '<?=$object->ajax_url;?>',
                type : 'POST',
                data : function( d ) {
                    $.each( editor_server_params, function( key, value ) {  
                        d[key] = value;
                    });
                    
                    $("#<?=isset($object->extra_params) ? $object->extra_params : 'dt-extra-params';?> input").each(function (key, value){
                        d[$(this).attr("name")] = $(this).val();
                    });

                    $("#<?=isset($object->extra_params) ? $object->extra_params : 'dt-extra-params';?> select").each(function (key, value){
                        d[$(this).attr("name")] = $(this).val();
                    });
                }
            },
            table: '<?=$object->table_id;?>',
            fields: data_fields,
            i18n: {
                create: {
                    title:  "<?=$object->create_title;?>"
                },
                edit: {
                    title:  "<?=$object->edit_title;?>"
                }
            }
        });
        
        <?php if( isset( $object->callbacks["displayOrder"] ) ): ?>            
            myEditor.on( 'open displayOrder', function ( e, mode, action) {
                <?=$object->callbacks["displayOrder"]?>(e, mode, action, this);
            });            
        <?php endif;?>
        
        <?php if( isset( $object->callbacks["postSubmit"] ) ): ?>
            myEditor.on( 'postSubmit', function ( e, json, data, action, xhr ) {
                return <?=$object->callbacks["postSubmit"]; ?>(e, json, data, action, xhr, this);
            });
        <?php endif; ?>
        
        <?php if( isset( $object->callbacks["preSubmit"] ) ): ?>
            myEditor.on( 'preSubmit', function ( e, o, action ) {
                return <?=$object->callbacks["preSubmit"]; ?>(e,o,action, this);
            });
        <?php endif; ?>

        <?php if( isset( $object->callbacks["preOpen"] ) ): ?>
        myEditor.on('preOpen', function(a, b, action) {
            return <?=$object->callbacks["preOpen"]; ?>(a,b,action, this);
        });
        <?php endif; ?>
        
        myEditor.on('postCreate postEdit close', function () {
            myEditor.off( 'preClose' );
        });

        myEditor.on('open', function(a, b, action) {
            <?php if( isset( $object->callbacks["open"] ) ): ?>
                <?=$object->callbacks["open"]; ?>(a,b,action, this);
                <?php if ( isset( $object->editor_fields ) ): ?>
                    <?php foreach($object->editor_fields as $field): ?>
                        var obj = {};
                        <?php foreach((array)$field->get_fields() as $key => $value): ?>

                            <?php if($key == 'data_type' && $value == 'date'): ?>

                            var $datepicker = $('#DTE_Field_<?php echo $field->get_fields()->data_column;?>');
                            $datepicker.datepicker({ format: 'dd/mm/yyyy', autoclose: true });
                            
                            if ( action == 'edit' && typeof a.target.s.editData !== 'undefined' )
                            {
                                var formatDate = a.target.s.editData.<?php echo $field->get_fields()->data_column;?>[Object.keys( a.target.s.editData.<?php echo $field->get_fields()->data_column;?> )[0]];
                                if(formatDate != "0000-00-00" && formatDate != '' ){
                                    $datepicker.datepicker('setDate', new Date(formatDate));
                                }
                            }
                                
                            <?php endif; ?>
                                
                                
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
            
            $("#closeEditor").remove();
            $(".DTE_Form_Buttons").prepend('<button id="closeEditor" class="btn" tabindex="1">Cancel</button>');
            $("#closeEditor").click(function(){myEditor.close()});

            $(".DTE_Form_Buttons").find(":contains('Delete')").removeClass("btn-primary");
            $(".DTE_Form_Buttons").find(":contains('Delete')").addClass("btn-danger");
        });
        
        myEditor.on( 'close', function () {
            <?= isset( $object->callbacks["close"] ) ? $object->callbacks["close"] : ''; ?>
        });

        
        <?php if( isset( $object->callbacks["close"] ) ): ?>
            myEditor.on('close', function(a, b, action) {
                <?=$object->callbacks["close"]; ?>(a,b,action,this);
            });
        <?php endif; ?>

        var buttons = new Array();        
        <?php if( isset( $object->table_buttons ) ): ?>
            <?php foreach($object->table_buttons as $dt_button): ?>
                var button_object = {};
                <?php $button = $dt_button->get_buttons(); ?>
                button_object.extend = '<?=$button->type?>';
                button_object.editor = myEditor;
                
                <?php if ( ($button->type == 'edit') ): ?>
                    <?php $has_edit = true; ?>
                <?php endif; ?>

                <?php if( isset($button->text) ): ?>
                    button_object.text = '<?=$button->text; ?>';
                <?php endif; ?>

                <?php if( isset($button->remove_message_callback) ): ?>
                    button_object.formMessage = <?=$button->remove_message_callback; ?>;
                <?php endif;?>

                <?php if( isset($button->action_callback) ): ?>
                    button_object.action = <?=$button->action_callback; ?>;
                <?php endif;?>


                buttons.push(button_object);
            <?php endforeach; ?>
        <?php endif; ?>
            
        var data_columns = new Array();
        <?php if( isset( $object->data_columns ) ): ?>
            <?php foreach($object->data_columns as $column): ?>
                <?php if(is_array($column)) : ?>
                    <?php  $column_str = ""; ?>
                    <?php foreach($column as $col_key => $col_val): ?>
                        <?php $column_str .= $col_key . ": '" . $col_val ."', " ; ?>
                    <?php endforeach; ?>
                    data_columns.push({<?=rtrim($column_str, ", "); ?>});
                <?php else : ?>
                    data_columns.push({data: '<?=$column;?>'});
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
            
        var server_params = {};
        <?php if ( isset( $object->server_params["table_list"] ) ): ?>
            <?php foreach($object->server_params["table_list"] as $key => $param): ?>
                server_params['<?=$key?>'] = '<?=$param; ?>';
            <?php endforeach; ?>
        <?php endif; ?>
        
        var column_defs = new Array();
        <?php if( isset( $object->table_definitions ) ): ?>
            <?php foreach($object->table_definitions as $definitions): ?>
                var obj = {};
                <?php foreach($definitions as $key => $value): ?>
                    <?php if(is_bool ($value)  === true ) { ?>
                        obj['<?=$key?>'] = <?=$value ? 'true' : 'false' ?>;
                    <?php } else if(is_numeric ($value)  === true)  {?>
                        obj['<?=$key?>'] = <?=$value?>;
                    <?php } else {?>
                        obj['<?=$key?>'] = <?= is_array($value) ? json_encode($value) : "'" . $value . "'"; ?>;
                    <?php } ?>
                <?php endforeach; ?>
                column_defs.push(obj);
            <?php endforeach; ?>
        <?php endif; ?>
            
        dEditor = myEditor;
            
        myTable = $('<?=$object->table_id; ?>').DataTable({
            autoWidth: false,
            processing : true,
            scrollX: <?= isset($object->scrollX) && $object->scrollX ? 'true'  : 'false' ?>,
            scrollCollapse: true,
            <?php if(isset($object->no_pagination) && $object->no_pagination): ?>
            paging:   false,
            <?php endif; ?>
            <?php if(isset($object->no_info) && $object->no_info): ?>
            info:   false,
            <?php endif; ?>
            oLanguage: {
                sSearch: "",
                sProcessing: "<img src='<?=base_url('images/ajax-loader-big.gif')?>'>",
                oPaginate: {
                    "sNext": '>',
                    "sLast": '>>',
                    "sFirst": '<<',
                    "sPrevious": '<'
                }
            },
            <?php if( isset( $object->fixedColumns ) && $object->fixedColumns ) : ?>
                fixedColumns:   {
                    leftColumns: <?= isset($object->leftColumns) ? $object->leftColumns : 1 ?>
                },
            <?php endif; ?>
            
            <?php if( !isset( $object->no_scroll ) ): ?>
            scrollY: "<?= isset($object->scrollY) ? $object->scrollY : '460px' ?>",
            <?php endif; ?>
            dom: 'Bfrtip',
            ajax: {
                url: '<?=$object->ajax_url;?>',
                type: 'POST',
                data: function( d ) {
                    $.each( server_params, function( key, value ) {                        
                        d[key] = value;
                    });
                    
                    $("#<?=isset($object->extra_params) ? $object->extra_params : 'dt-extra-params';?> input").each(function (key, value){
                        d[$(this).attr("name")] = $(this).val();
                    });

                    $("#<?=isset($object->extra_params) ? $object->extra_params : 'dt-extra-params';?> select").each(function (key, value){
                        d[$(this).attr("name")] = $(this).val();
                    });
                }
            },
            serverSide: <?=isset($object->server_side) && !$object->server_side ? 'false'  : 'true' ?>,
            searching: <?=(isset($object->allow_search) && $object->allow_search) ? 'true' : 'false'; ?>,
            iDisplayLength: <?=isset($object->display_length) ? $object->display_length : 50; ?>,
            initComplete: function(settings, json){                
            <?php if( isset( $object->callbacks["init_complete"] ) ): ?>
                    
                <?php if(isset($_GET['use_frame'])): ?>                    
                    $(".dataTables_scrollBody").css("max-height", ($(".md-div-container").height() - 143) + "px");
                    $(".dataTables_scrollBody").css("height", ($(".md-div-container").height() - 143) + "px");                    
                <?php else: ?>
                    $(".dataTables_scrollBody").css("max-height", ($(window).height() - 376) + "px");
                    $(".dataTables_scrollBody").css("height", ($(window).height() - 376) + "px");
                <?php endif; ?>
                    
                <?=$object->callbacks["init_complete"]; ?>(settings, json);
                
            <?php endif; ?>
            },
            drawCallback: function(settings) {                
                <?php if( isset( $object->callbacks["draw_callback"] ) ): ?>
                    <?=$object->callbacks["draw_callback"]; ?>(settings);
                <?php endif; ?>
            },
            columns: data_columns,
            <?php if( isset($object->order) ): ?>
            order: <?php echo json_encode($object->order);?>,
            <?php endif; ?>
            
            <?php if( isset($object->disable_select) && $object->disable_select ): ?>
                select : false,            
            <?php else: ?>            
                select: {
                    style: '<?php echo isset($object->select_style) ? $object->select_style : 'single' ;?>',
                },
            <?php endif; ?>
            
            <?php if( isset($object->row_group) ): ?>
                rowGroup: <?=$object->row_group?>,
            <?php endif; ?>
            
            lengthChange: false,
            buttons: buttons,
            <?php if(isset($object->callbacks["footerCallback"])): ?>
            footerCallback: function( row, data, start, end, display ){
                <?=$object->callbacks["footerCallback"];?>(row, data, start, end, display, this);
            },
            <?php endif; ?>
            columnDefs: column_defs
        });
        
        
            
        <?php if( isset( $object->callbacks["user_select"] ) ): ?>
            myTable.on( 'user-select', <?=$object->callbacks["user_select"]?>);
        <?php endif; ?>
        
        <?php if( $has_edit ): ?>
            <?php if( isset($object->edit_callback) ): ?>
                $('<?=$object->table_id;?> tbody').on( 'dblclick', 'tr', function () {
                    <?php echo $object->edit_callback; ?>
                });
            <?php else:?>
                $('<?=$object->table_id;?> tbody').on( 'dblclick', 'tr', function () {
                        myEditor.edit( this, '<?=$object->edit_title; ?>', 'Update');
                });
            <?php endif; ?>
        <?php endif; ?>

        <?php if( isset($object->has_delete_row) && $object->has_delete_row ): ?>            
            $('<?=$object->table_id;?> tbody ').on( 'click', '.dt_row_remove', function (e) {
                myEditor.remove( $(this).closest('tr'), 
                <?php if( isset($object->delete_row_callback_message) && $object->delete_row_callback_message ): ?>
                    <?=$object->delete_row_callback_message; ?>($(this).closest('tr')) 
                <?php else: ?>
                    {
                        title: 'Delete',
                        message: 'Are you sure you wish to remove this record?',
                        buttons: 'Delete'
                    }
                <?php endif; ?>
                );
            });
        <?php endif; ?>
    });    
}(jQuery));

$(function () {
    $('.dataTables_scrollBody').scroll(function(){
        $('.dataTables_scrollHeadInner').scrollLeft($(this).scrollLeft());
    });
});

function dtEditRow( element ) {
    $( element ).closest('tr').trigger('dblclick');
}
          
<?php if($object->has_btn_move_up_and_down) : ?>

    function dt_<?=$object->root_function_name?>_move_up ( e, dt, node, config ) {
        var trPosition = $("<?=$object->table_id?> tr").index($("<?=$object->table_id?> tr.selected"));
        if(trPosition != 1){
            move<?=$object->root_function_name?>RowUp(this);
            var selected = $("<?=$object->table_id?> tr.selected");
            $("<?=$object->table_id?> tr").removeClass("selected");
            selected.prev().addClass("selected");
        }
    }
    
    function dt_<?=$object->root_function_name?>_move_down ( e, dt, node, config ) {
        var trPosition = $("<?=$object->table_id?> tr").index($("<?=$object->table_id?> tr.selected"));
        var rowCount = $("<?=$object->table_id?> tr").length;
        if(trPosition < (rowCount - 1)){
           move<?=$object->root_function_name?>RowDown(this);
           var selected = $("<?=$object->table_id?> tr.selected");
            $("<?=$object->table_id?> tr").removeClass("selected");
            selected.next().addClass("selected");
        }
    }   
    
    function move<?=$object->root_function_name?>RowUp(table) {
        var tr = $("<?=$object->table_id?> tr.selected");
        move<?=$object->root_function_name?>Row(tr, 'up', table);
    }

    // Move the row down
    function move<?=$object->root_function_name?>RowDown(table) {
        var tr = $("<?=$object->table_id?> tr.selected");
        move<?=$object->root_function_name?>Row(tr, 'down', table);
    }

    // Move up or down (depending...)
    function move<?=$object->root_function_name?>Row(row, direction, table) {    
        var index = table.row(row).index();
        var order = - 1;

        if (direction === 'down') {
            order = 1;
        }

        var data1 = table.row(index).data();
        data1.order += order;
        var data2 = table.row(index + order).data();
        data2.order += - order;
        table.row(index).data(data2);
        table.row(index + order).data(data1);
        var move_ids = new Array();
        var offset = 0;
        $("<?=$object->table_id?> tr").each(function(){
            move_ids.push($(this).find('span').attr("row-id"));
            move_ids.push($(this).find('span').attr("row-id"));
            offset = $(this).find('span').attr("row-offset");
        });

        var params = {
            move_ids : move_ids,
            type        : "<?=$object->ajaxwork_move_function?>",
            offset      : offset
        };

        $.post(base_url + '<?=$object->ajax_url?>', params, function(data){
            table.row( index + order ).select();
        }, "json");
    }
    
    
<?php endif; ?>

<?php if($object->delete_row_callback_message == "dt_remove_row_callback") : ?>
    function dt_remove_row_callback(e){
        var name = $(e).find("td").eq(1).text();
        return {
            title: 'Delete',
            message: "Are you sure you wish to delete '" + name + "'?",
            buttons: 'Delete'
        };
    }
<?php endif; ?>

</script>