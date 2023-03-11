@extends('innkeeper::layouts.innkeeper')

@section('styles')
    <link rel="stylesheet" href="{{innkeeperAssets()}}assets/extensions/simple-datatables/style.css" />
    <link rel="stylesheet" href="{{innkeeperAssets()}}assets/css/pages/simple-datatables.css" />
@endsection
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Organization Management</h3>
                    <p class="text-subtitle text-muted">Current organizations onboarded</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav
                        aria-label="breadcrumb"
                        class="breadcrumb-header float-start float-lg-end"
                    >
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('innkeeper.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Organizations
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="organizationsList">
                        <thead>
                        <tr>
                            <th>Organization Name</th>
                            <th>Tenant Domain</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Date/Time Added</th>
                            {{--<th>User Roles</th>--}}
                            <th>Status</th>
                            <th>Operations</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($organizations))
                            @foreach ($organizations as $organization)
                                <tr>

                                    <td>{{ $organization->name }}</td>
                                    <td><a href="http://{{ $organization->domain . config('innkeeper.tenant_landing_page')}}" target="_blank">{{ $organization->domain }}</a></td>
                                    <td>{{ $organization->users[0]->first_name }}</td>
                                    <td>{{ $organization->users[0]->last_name }}</td>
                                    <td>{{ $organization->users[0]->email }}</td>
                                    <td>{{ $organization->users[0]->mobile_number }}</td>
                                    <td>{{ $organization->created_at->format('F d, Y h:ia') }}</td>
                                    <td>{{ $organization->users[0]->user_status }}
                                        {{--<span class="badge bg-success">Active</span>--}}
                                    </td>
                                    <td>
                                        <div class="btn-group mb-3" role="group">
                                            <button type="submit" class="btn btn-danger btn-sm todo-delete-btn" data-id="{{ $organization->id }}">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <!-- Need: Apexcharts -->

    <script>

        $('body').on('click', '.todo-delete-btn', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                'url': '/innkeeper/organizations/destroy/' + id,
                'type': 'post',
                data: {"id": id , _method: 'delete'},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(data) {
                console.log(data);

                window.location = window.location.href;
            }).fail(function() {
                alert('something went wrong!');
            });
        });
    </script>
@endsection
