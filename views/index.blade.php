<h1>{{ __('LDAP Information') }}</h1>

@component('modal-component',[
    "id" => "LdapAttrChooseModal",
    "title" => "Choosing LDAP Attributes",
    "footer" => [
        "text" => "List",
        "class" => "btn-success",
        "onclick" => "chooseAttributeOnModal()"
    ]
])
@include('inputs', [
    "inputs" => [
        "Write the attribute name(s) that you would like to list" => "attrName:text:name,samaccountname"
    ]
])
@endcomponent

@component('modal-component',[
    "id" => "EditNameModal",
    "title" => "Edit Name",
    "footer" => [
        "text" => "Edit",
        "class" => "btn-success",
        "onclick" => "editUser()"
    ]
])
@include('inputs', [
    "inputs" => [
        "Write New User Name" => "usrName:text"
    ]
])
@endcomponent

@component('modal-component',[
    "id" => "AddUserNameModal",
    "title" => "Add User Name",
    "footer" => [
        "text" => "Add",
        "class" => "btn-success",
        "onclick" => "addUser()"
    ]
])
@include('inputs', [
    "inputs" => [
        "User Name" => "usrName:text"
    ]
])
@endcomponent

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
        <div id="chooseAttrButton" class="tab-pane">
            <button class="btn btn-success mb-2" id="ldapUsrAddButton" onclick="showLdapUsrAddModal()" type="button">Add User</button>
        </div>
        <div class="table-responsive ldapTable" id="ldapUserTable"></div> 
    </div>

    <div id="tab2" class="tab-pane">
        <div class="table-responsive ldapTable" id="ldapPCTable"></div> 
    </div>

    <div id="tab3" class="tab-pane">
        <div id="chooseAttrButton" class="tab-pane">
            <button class="btn btn-success mb-2" id="ldapAttrButton" onclick="showLdapAttrChooseModal()" type="button">Choose Attributes</button>
        </div>
        <div class="table-responsive ldapTable" id="ldapAdminAttrTable"></div> 
    </div>
</div>

<script>

    var globeUserName;
    
    function ListUsersTab() {
        var form = new FormData();
        request(API('listUsers'), form, function(response) {
            $('#ldapUserTable').html(response).find('table').DataTable({
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

    // ***Right Click Function of Users Table***
    // --DELETE USER--

    function deleteUser(line) {
        showSwal('{{__("Yükleniyor...")}}','info',2000);
        var form = new FormData();
        let name = line.querySelector("#name").innerHTML;
        form.append("userNameToBeDeleted", name);

        request(API('deleteUser'), form, function(response) {
            message = JSON.parse(response)["message"];
            showSwal(message, 'success', 3000);
            ListUsersTab();
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message, 'error', 3000);
        });
        
    }

    // --ADD USER--

    function showLdapUsrAddModal() {
        $('#AddUserNameModal').modal("show");
    }

    function addUser() {
        $('#AddUserNameModal').modal("hide");
        showSwal('{{__("Yükleniyor...")}}','info',2000);
        var form = new FormData();
        let userName = $('#AddUserNameModal').find('input[name=usrName]').val();
        form.append("userName", userName);

        request(API('addUser'), form, function(response) {
            message = JSON.parse(response)["message"];
            showSwal(message, 'success', 3000);
            ListUsersTab();
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message, 'error', 3000);
        });
    }

    // --EDIT USER--

    function showEditNameModal(line) {
        $('#EditNameModal').modal("show");
        globeUserName = line.querySelector("#name").innerHTML;
        
    }

    function editUser(line) {
        $('#EditNameModal').modal("hide");
        showSwal('{{__("Yükleniyor...")}}','info',2000);
        var form = new FormData();
        
        let newUsrName = $('#EditNameModal').find('input[name=usrName]').val();
        form.append("userName", globeUserName);
        form.append("newUsrName", newUsrName);
        
        request(API('editUser'), form, function(response) {
            message = JSON.parse(response)["message"];
            showSwal(message, 'success', 3000);
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message, 'error', 3000);
        });
        
    }

    function ListPCsTab() {
        var form = new FormData();
        request(API('listComputers'), form, function(response) {
            $('#ldapPCTable').html(response).find('table').DataTable({
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
        request(API('listAdminAttributes'), form, function(response) {
            $('#ldapAdminAttrTable').html(response).find('table').DataTable({
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

    function showLdapAttrChooseModal() {
        $('#LdapAttrChooseModal').modal("show");
    }

    function chooseAttributeOnModal() {
        showSwal('{{__("Yükleniyor...")}}','info',2000);
        var form = new FormData();
        let attributes = $('#LdapAttrChooseModal').find('input[name=attrName]').val();
        form.append("attributes", attributes);
        request(API('listAdminAttributes'), form, function(response) {
            $('#ldapAdminAttrTable').html(response).find('table').DataTable({
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
    
</script>