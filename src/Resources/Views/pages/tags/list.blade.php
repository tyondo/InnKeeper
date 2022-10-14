@extends('administration::layouts.admin')

@section('content')
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Tags Management</h3>
                <div class="nk-block-des text-soft">
                    <p>You have total @isset($items) {{count($items)}} @endisset tags.</p>
                </div>
            </div><!-- .nk-block-head-content -->
            <div class="nk-block-head-content">
                <div class="toggle-wrap nk-block-tools-toggle">
                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                    <div class="toggle-expand-content" data-content="pageMenu">
                        <ul class="nk-block-tools g-3">
                            {{--<li>
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-bs-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-filter-alt"></em><span>Filtered By</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><span>Open</span></a></li>
                                            <li><a href="#"><span>Closed</span></a></li>
                                            <li><a href="#"><span>Onhold</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>--}}
                            {{--<li class="nk-block-tools-opt d-none d-sm-block">
                                <a href="{{route('admin.projects.crud')}}" class="btn btn-primary"><em class="icon ni ni-plus"></em><span>Add Project</span></a>
                            </li>
                            <li class="nk-block-tools-opt d-block d-sm-none">
                                <a href="#" class="btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
                            </li>--}}
                        </ul>
                    </div>
                </div><!-- .toggle-wrap -->
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <div class="nk-block">
        <div class="row">
            <div class="col-lg-8 col-md-6 col-sm-12">
                <table class="datatable-init nowrap nk-tb-list is-separate" data-auto-responsive="false">
                    <thead>
                    <tr class="nk-tb-item nk-tb-head">
                        <th class="nk-tb-col nk-tb-col-check">
                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                <input type="checkbox" class="custom-control-input" id="puid">
                                <label class="custom-control-label" for="puid"></label>
                            </div>
                        </th>
                        <th class="nk-tb-col "><span>Tag Name</span></th>
                        <th class="nk-tb-col "><span>Type</span></th>
                        <th class="nk-tb-col tb-col-sm"><span>Parent</span></th>
                        <th class="nk-tb-col tb-col-md"><span>Added On</span></th>

                        <th class="nk-tb-col nk-tb-col-tools">
                            <ul class="nk-tb-actions gx-1 my-n1">
                                {{--<li class="me-n1">
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit Selected</span></a></li>
                                                <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Selected</span></a></li>
                                                <li><a href="#"><em class="icon ni ni-bar-c"></em><span>Update Stock</span></a></li>
                                                <li><a href="#"><em class="icon ni ni-invest"></em><span>Update Price</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>--}}
                            </ul>
                        </th>
                    </tr><!-- .nk-tb-item -->
                    </thead>
                    <tbody>
                    @if(isset($items))
                        @foreach($items as $item)
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col nk-tb-col-check">
                                    <div class="custom-control custom-control-sm custom-checkbox notext">
                                        <input type="checkbox" class="custom-control-input" id="puid1">
                                        <label class="custom-control-label" for="puid1"></label>
                                    </div>
                                </td>
                                <td class="nk-tb-col ">
                                    {{--<span class="tb-product">
                                        <img src="./images/product/a.png" alt="" class="thumb">
                                        <span class="title">Pink Fitness Tracker</span>
                                    </span>--}}
                                    <span class="title">{{json_decode($item->name)->en}}</span>
                                </td>
                                <td class="nk-tb-col ">
                                    <span class="tb-sub">{{$item->type}}</span>
                                </td>
                                <td class="nk-tb-col tb-col-sm">
                                    <span class="tb-lead">@if(!is_null($item->parent)) {{$item->parent->name}} @endif</span>
                                </td>
                                <td class="nk-tb-col tb-col-md">
                                    {{--<div class="asterisk tb-asterisk">
                                        <a href="#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                    </div>--}}
                                    <span class="tb-sub">{{$item->created_at}}</span>
                                </td>
                                <td class="nk-tb-col nk-tb-col-tools">
                                    <ul class="nk-tb-actions gx-1 my-n1">
                                        <li class="me-n1">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="{{route('enums.tags.details',$item->id)}}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                                        {{--<li><a href="#"><em class="icon ni ni-eye"></em><span>View Product</span></a></li>
                                                        <li><a href="#"><em class="icon ni ni-activity-round"></em><span>Product Orders</span></a></li>--}}
                                                        <li>
                                                            <a href="{{route('admin.services.remove',$item->id)}}" onclick="event.preventDefault(); document.getElementById('delete-form-{{$item->id}}').submit();">
                                                                <em class="icon ni ni-trash"></em><span>Remove</span>
                                                            </a>
                                                            <form id="delete-form-{{$item->id}}" action="{{ route('admin.services.remove',$item->id) }}" method="POST" style="display: none;">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                @csrf
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <!-- .nk-tb-item -->
                        @endforeach
                    @endif
                    </tbody>
                </table><!-- .nk-tb-list -->
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="nk-block nk-block-lg">
                    <div class="card card-bordered card-preview">
                        <div id="hot-swap-frequenty-asked">
                            @include('enum-manager::pages.tags.partials._form_crud')
                        </div>
                    </div><!-- .card-preview -->
                </div><!-- .nk-block -->
            </div>
        </div>

    </div><!-- .nk-block -->
@endsection
