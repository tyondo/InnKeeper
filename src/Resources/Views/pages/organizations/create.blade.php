@extends('innkeeper::layouts.innkeeper')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Organization Management</h3>
                    <p class="text-subtitle text-muted">
                        Easily configure new and existing organizations
                    </p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb"
                        class="breadcrumb-header float-start float-lg-end" >
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('innkeeper.organizations.list')}}">Organizations</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                New
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Onboard New Organization</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="contact-tab" data-bs-toggle="tab" href="#contact"
                                role="tab" aria-controls="contact" aria-selected="true" >Contact</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="organization-tab" data-bs-toggle="tab" href="#organization" role="tab" aria-controls="organization"
                                aria-selected="false" >Organization</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="connection-tab" data-bs-toggle="tab"
                                href="#connection" role="tab" aria-controls="connection" aria-selected="false" >Connection</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="organizationForm">
                            <div class="tab-pane fade show active"
                                id="contact" role="tabpanel" aria-labelledby="contact-tab" >
                                <div class="card mt-2">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <h6 class="card-title">Administrator Information (the first user in the system)</h6>
                                                <div class="form form-vertical form-body">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="form-group has-icon-left">
                                                                <label for="first_name">First Name</label>
                                                                <div class="position-relative">
                                                                    <input type="text" name="first_name" class="form-control" placeholder="first name" id="first_name" />
                                                                    <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="form-group has-icon-left">
                                                                <label for="last_name">Last Name</label>
                                                                <div class="position-relative">
                                                                    <input type="text" name="last_name" class="form-control" placeholder="last name" id="last_name" />
                                                                    <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="form-group has-icon-left">
                                                                <label for="email">Email</label>
                                                                <div class="position-relative">
                                                                    <input type="email" name="email" class="form-control" placeholder="Email" id="email" />
                                                                    <div class="form-control-icon"> <i class="bi bi-envelope"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="form-group has-icon-left">
                                                                <label for="mobile_number">Mobile</label>
                                                                <div class="position-relative">
                                                                    <input type="number" name="mobile_number" class="form-control" placeholder="Mobile" id="mobile_number" />
                                                                    <div class="form-control-icon">
                                                                        <i class="bi bi-phone"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{--<div class="col-12">
                                                            <div class="form-group has-icon-left">
                                                                <label for="password-id-icon">Password</label>
                                                                <div class="position-relative">
                                                                    <input
                                                                        type="password"
                                                                        class="form-control"
                                                                        placeholder="Password"
                                                                        id="password-id-icon"
                                                                    />
                                                                    <div class="form-control-icon">
                                                                        <i class="bi bi-lock"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>--}}
                                                        <div class="col-12">
                                                            <div class="form-check">
                                                                <div class="checkbox mt-2">
                                                                    <input type="checkbox" name="email_tenant" id="remember-me-v" class="form-check-input" checked />
                                                                    <label for="remember-me-v">Email Access Details</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="organization" role="tabpanel" aria-labelledby="organization-tab" >
                                <div class="card mt-2">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <h6 class="card-title">Branding & Routing (the domain will be used to validate existence of the tenant)</h6>
                                            <div class="form form-vertical form-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group has-icon-left">
                                                            <label for="organization_name">Organization Name</label>
                                                            <div class="position-relative">
                                                                <input type="text" name="organization_name" class="form-control" placeholder="organization name" id="organization_name" />
                                                                <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group has-icon-left">
                                                            <label for="organization_domain">Tenant Domain</label>
                                                            <div class="position-relative">
                                                                <input type="text" name="organization_domain" class="form-control" placeholder="domain name" id="organization_domain" />
                                                                <div class="form-control-icon"><i class="bi bi-person"></i> </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="organization_status">Tenant Status</label>
                                                            <select name="organization_status" id="organization_status" class="choices form-select">
                                                                <option value="active">Active</option>
                                                                <option value="deactivated">Deactivated</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="management_status">Management Status</label>
                                                            <select name="management_status" id="management_status" class="choices form-select">
                                                                <option value="new_organization">New Organization</option>
                                                                <option value="manage_organization">Manage Organization</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="connection" role="tabpanel" aria-labelledby="connection-tab" >
                                <div class="card mt-2">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <h6 class="card-title">Database Connection (the database name will be auto-generated)</h6>
                                            <div class="form form-vertical form-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group has-icon-left">
                                                            <label for="db_username">DB Username</label>
                                                            <div class="position-relative">
                                                                <input type="text" name="db_username" class="form-control" placeholder="name" id="db_username" />
                                                                <div class="form-control-icon">
                                                                    <i class="bi bi-person"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group has-icon-left">
                                                            <label for="db_password">DB Password</label>
                                                            <div class="position-relative">
                                                                <input type="password" name="db_password" class="form-control" placeholder="password" id="db_password" />
                                                                <div class="form-control-icon">
                                                                    <i class="bi bi-lock-fill"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group has-icon-left">
                                                            <label for="db_host">DB Host</label>
                                                            <div class="position-relative">
                                                                <input type="text" name="db_host" class="form-control" placeholder="name" id="db_host" />
                                                                <div class="form-control-icon">
                                                                    <i class="bi bi-house"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group has-icon-left">
                                                            <label for="db_port">DB Port</label>
                                                            <div class="position-relative">
                                                                <input type="text" name="db_port" class="form-control" placeholder="port" id="db_port" />
                                                                <div class="form-control-icon">
                                                                    <i class="bi bi-pip-fill"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-check">
                                                            <div class="checkbox mt-2">
                                                                <input type="checkbox" name="use_default_connection" id="use_default_connection" class="form-check-input" checked />
                                                                <label for="use_default_connection">Use Innkeeper Details</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary me-1 mb-1" id="saveOrganizationBtn"> Submit </button>
                                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1" >
                                                            Reset
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
<script>
    $("#saveOrganizationBtn").click(function() {
        var e=this;
        var formElements = $("#organizationForm").find("select, textarea, input").map(function(index, elem){
            var type = $(this).prop("type");
            if (type !== "button" && type !== "submit") {
                if (elem.name !== ""){
                    return {name: elem.name, value: elem.value};
                }
            }
        });
        console.log(formElements);
        let url = "{{route('innkeeper.organizations.store')}}";

        $.post(url,formElements,function(data){
            $(e).find("[type='submit']").html("Creating");
            console.log(data);
            if(data.success){
                window.location=data.location_url;
            }
        }).fail(function(response) {
            $(e).find("[type='submit']").html("Login");
            $(".alert").remove();
            var erroJson = JSON.parse(response.responseText);

            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong! Check your inputs",
            });

            for (var err in erroJson) {
                for (var errstr of erroJson[err])
                    $("[name='" + err + "']").after("<div class='alert alert-danger'>" + errstr + "</div>");
            }
        });


    });

</script>
@endsection
