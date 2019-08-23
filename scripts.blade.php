<script type="text/javascript">
	//DataTables
	$(function () {
        $('#dtlistsms').DataTable({
            processing: true,
            serverSide: true,
            bFilter : false,
	    bPaginate : false,
            ajax: '{!! route('dtlistsms') !!}',
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'template', name: 'template'},
                {data: 'content', name: 'content'},
                {data: 'config', name: 'config'},
		        {
                    data: 'id_konfirmasi', name: 'id_konfirmasi', render: function (data, type, row) {
                        return '<button type="submit" style="margin-bottom: 5px" class="btn btn-xs btn-primary" onClick="popupedit('+data+',\''+row['content']+'\',\''+row['template']+'\',\''+row['config']+'\')">Edit</button>'
                        +'<button type="submit" class="btn btn-xs btn-warning" onClick="popupdelete('+data+')">Delete</button>'
                    }
                }
            ],
            ordering: false,
        });
    });

	//Popup edit: ngambil value dari datatables
    function popupedit(id,content,template,config){
	    if(template==='SMS'){
            w2popup.open({
                width: 400,
                height: 350,
                title: 'Edit Template',
                body: '<form name="addtemplate">' +
                      '<input type="hidden" id="idtemp" value="'+id+'"><label class="heading">Type of Template:</label><br/>' +
                      '<label><input name="typetemplate" type="radio" value="SMS" checked="checked">SMS</label><br/>'+
                      '<label><input name="typetemplate" type="radio" value="Email">Email</label><br/><br/>' +
                      '<label class="heading">Configuration:</label><br/>' +
                      '<select id="configtemplate" class="form-control input-sm" style="width:130px">' +
                      '<?php 
                        if (config == 'Confirmation') echo '<option value="Confirmation" selected>Confirmation</option><option value="SYS-AL1">SYS-AL1</option><option value="SYS-AL2">SYS-AL2</option>';
                        else if (config == 'SYS-AL1') echo '<option value="Confirmation">Confirmation</option><option value="SYS-AL1" selected>SYS-AL1</option><option value="SYS-AL2">SYS-AL2</option>';
                        else echo '<option value="Confirmation">Confirmation</option><option value="SYS-AL1">SYS-AL1</option><option value="SYS-AL2" selected>SYS-AL2</option>'; ?>' + 
                      '</select><br/>' +
                      '<label class="heading">Content:</label><br/>' +
                      '<textarea name="content" id="content" rows="4" cols="50">'+content+'</textarea>' +
                      '</form>',
                buttons: '<button class="w2ui-btn" onclick="edittemplate()">Update</button>' + '<button class="w2ui-btn" onclick="w2popup.close()">Cancel</button>',
                showMax: true
            });
        } if(template==='Email'){
            w2popup.open({
                width: 400,
                height: 300,
                title: 'Edit Template',          
                body: '<form name="addtemplate">' +
                      '<input type="hidden" id="idtemp" value="'+id+'"><label class="heading">Type of Template:</label><br/>' +
                      '<label><input name="typetemplate" type="radio" value="SMS">SMS</label><br/>' +
                      '<label><input name="typetemplate" type="radio" value="Email" checked="checked">Email</label><br/><br/>' +
                      '<label class="heading">Content:</label><br/>' +
                      '<textarea name="content" id="content" rows="4" cols="50">'+content+'</textarea>' +
                      '</form>',
                buttons: '<button class="w2ui-btn" onclick="edittemplate()">Update</button>' + '<button class="w2ui-btn" onclick="w2popup.close()">Cancel</button>',
                showMax: true
            });
        }
    }

    function redirectToGetMessages(){
        window.location = "{!! route('getmessages') !!}";
    }

    function edittemplate() {
        $('#loading').show();
        $('#loading').html('<img src="{{ asset('images/ajax-loader.gif') }}" style="display:block;margin:auto;"/>');
        var typetemplate2 = $("input[name='typetemplate']:checked").val();
        var configtemplate = $('#configtemplate :selected').val();
        var content = $("#content").val();
        var id = $("#idtemp").val();

        $.post('{!! route('edittemplate') !!}', {
            '_token': $('meta[name=csrd-token]').attr('content'),
            'template': typetemplate2,
            'config': configtemplate,
            'content': content,
            'id': id
        }).done(function (data) {
            w2popup.open({
                width: 400,
                height: 300,
                title: 'UPDATE TEMPLATE',
                body: '<div class="w2ui-centered">Template has been updated.</div>',
                buttons:
                    '<button class="w2ui-btn" onclick="redirectToGetMessages()">OK</button>',
                showMax: true
            });
            $('#loading').hide();
        }).fail(function (data) {
            console.log(data);
            $('#loading').hide();
        });
    }
</script>
