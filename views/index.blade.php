<h1>{{ __('LDAP Information') }}</h1>

<ul class="nav nav-tabs" role="tablist" style="margin-bottom: 15px;">
    <li class="nav-item">
        <a class="nav-link active"  onclick="ListUsersTab()" href="#tab1" data-toggle="tab">List Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link "  onclick="ListPCsTab()" href="#tab2" data-toggle="tab">List PCs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link "  onclick="AdminAttrTab()" href="#tab3" data-toggle="tab">Admin attributes</a>
    </li>
</ul>

<div class="tab-content">
    <div id="tab1" class="tab-pane active">
        <div id="ldapPrintArea">
        <div class="table-responsive ldapTable" id="ldapUserTable"></div> 
    </div>

    <div id="tab2" class="tab-pane">
        <div id="ldapPrintArea">
        <div class="table-responsive ldapTable" id="ldapPCTable"></div> 
    </div>

    <div id="tab3" class="tab-pane">
        <div id="ldapPrintArea">
        <div class="table-responsive ldapTable" id="ldapAdminAttrTable"></div> 
    </div>
</div>

<script>
    
    function ListUsersTab() {
        var form = new FormData();
        request(API('showListedUsers'), form, function(response) {
            $('.ldapUserTable').html(response).find('table').DataTable({
            bFilter: true,
            "language" : {
                url : "/turkce.json"
            }
            });;
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message, 'error', 3000);
        });
    }

    function ListPCsTab() {
        var form = new FormData();
        request(API('showListedComputers'), form, function(response) {
            $('.ldapPCTable').html(response).find('table').DataTable({
            bFilter: true,
            "language" : {
                url : "/turkce.json"
            }
            });;
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message, 'error', 3000);
        });
    }

    function AdminAttrTab() {
        var form = new FormData();
        request(API('showListedAdminAttributes'), form, function(response) {
            $('.ldapAdminAttrTable').html(response).find('table').DataTable({
            bFilter: true,
            "language" : {
                url : "/turkce.json"
            }
            });;
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message, 'error', 3000);
        });
    }
    
    getHostname();
    function getHostname() {
        showSwal('{{__("Yükleniyor...")}}', 'info');
        let data = new FormData();
        request(API('tab1'), data, function(response){
            response = JSON.parse(response);
            $('#hostname').text(response.message);
            Swal.close();
            $('#setHostnameModal').modal('hide')
        }, function(response){
            response = JSON.parse(response);
            showSwal(response.message, 'error');
        });
    }
</script>