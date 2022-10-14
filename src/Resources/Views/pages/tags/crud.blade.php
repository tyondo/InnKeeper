@extends('administration::layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <div class="nk-block-head-sub"><a class="back-to" href="{{route('projects.list')}}"><em class="icon ni ni-arrow-left"></em><span>Project List</span></a></div>
                            <h2 class="nk-block-title fw-normal">Manage Project</h2>
                        </div>
                    </div><!-- .nk-block-head -->
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <form action="{{route('projects.store')}}" method="post">
                                    @csrf
                                <div class="preview-block">
                                    <span class="preview-title-lg overline-title">Basic Information</span>
                                    <div class="row gy-4">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label class="form-label" for="project_name">Project Title</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" name="name" class="form-control" id="project_name" placeholder="Project Title">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label" for="project_cost">Cost</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-text-hint">
                                                        <span class="overline-title">Usd</span>
                                                    </div>
                                                    <input type="number" name="cost" class="form-control" id="project_cost" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="project_category_id">Category</label>
                                                <div class="form-control-wrap">
                                                    <select name="project_category_id" id="project_category_id" class="form-select js-select2" data-search="on">
                                                        @foreach(projectCategories() as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="project_type">Type</label>
                                                <div class="form-control-wrap">
                                                    <select name="type" id="project_type" class="form-select js-select2" data-search="on">
                                                        @foreach(projectType() as $item)
                                                        <option value="{{$item}}">{{ucfirst($item)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="project_client">Client Name</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-left">
                                                        <em class="icon ni ni-user"></em>
                                                    </div>
                                                    <input type="text" name="client" class="form-control" id="project_client" placeholder="client">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="project_location">Location</label>
                                                <div class="form-control-wrap">
                                                    <select name="location" id="project_location" class="form-select js-select2" data-search="on">
                                                        @foreach(getCountries() as $item)
                                                            <option value="{{$item['name']}}">{{$item['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="project_start_id">Started Date</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-right">
                                                        <em class="icon ni ni-calendar-alt"></em>
                                                    </div>
                                                    <input type="text" name="start_date" class="form-control date-picker" id="project_start_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="project_completion_date">Completed Date</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-right">
                                                        <em class="icon ni ni-calendar-alt"></em>
                                                    </div>
                                                    <input type="text" name="completion_date" class="form-control date-picker" id="project_completion_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="nk-block nk-block-lg">
                                                <div class="nk-block-head">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="title nk-block-title">Project Description</h4>
                                                        <div class="nk-block-des">
                                                            <p>A detailed explanation of what the project is about.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <textarea class="form-control summernote-basic" name="description" id="project_description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="project_cost">Project Thumbnail</label>
                                                        <div class="form-control-wrap input-upload-wrapper">
                                                            <div class="form-text-hint bg-gray-300">
                                                                <span class="overline-title">Upload File</span>
                                                            </div>
                                                            <input type="number" name="cost" class="form-control" onclick="fileSelectionModal(this);" id="project_cost" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="nk-upload-item">
                                                        <div class="nk-upload-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72">
                                                                <g>
                                                                    <rect x="16" y="14" width="40" height="44" rx="6" ry="6" style="fill:#7e95c4" />
                                                                    <rect x="32" y="17" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <rect x="32" y="22" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <rect x="32" y="27" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <rect x="32" y="32" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <rect x="32" y="37" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <path d="M35,14h2a0,0,0,0,1,0,0V43a1,1,0,0,1-1,1h0a1,1,0,0,1-1-1V14A0,0,0,0,1,35,14Z" style="fill:#fff" />
                                                                    <path d="M38.0024,42H33.9976A1.9976,1.9976,0,0,0,32,43.9976v2.0047A1.9976,1.9976,0,0,0,33.9976,48h4.0047A1.9976,1.9976,0,0,0,40,46.0024V43.9976A1.9976,1.9976,0,0,0,38.0024,42Zm-.0053,4H34V44h4Z" style="fill:#fff" />
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        <div class="nk-upload-info">
                                                            <div class="nk-upload-title"><span class="title">dashlite-latest-version.zip</span></div>
                                                            <div class="nk-upload-size">25.49 MB</div>
                                                        </div>
                                                        <div class="nk-upload-action">
                                                            <a href="#" class="btn btn-icon btn-trigger" data-dismiss="modal"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="project_cost">Project Gallery</label>
                                                        <div class="form-control-wrap input-upload-wrapper">
                                                            <div class="form-text-hint bg-gray-300">
                                                                <span class="overline-title">Upload File</span>
                                                            </div>
                                                            <input type="number" name="cost" class="form-control" id="project_cost" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="nk-upload-item">
                                                        <div class="nk-upload-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72">
                                                                <g>
                                                                    <rect x="16" y="14" width="40" height="44" rx="6" ry="6" style="fill:#7e95c4" />
                                                                    <rect x="32" y="17" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <rect x="32" y="22" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <rect x="32" y="27" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <rect x="32" y="32" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <rect x="32" y="37" width="8" height="2" rx="1" ry="1" style="fill:#fff" />
                                                                    <path d="M35,14h2a0,0,0,0,1,0,0V43a1,1,0,0,1-1,1h0a1,1,0,0,1-1-1V14A0,0,0,0,1,35,14Z" style="fill:#fff" />
                                                                    <path d="M38.0024,42H33.9976A1.9976,1.9976,0,0,0,32,43.9976v2.0047A1.9976,1.9976,0,0,0,33.9976,48h4.0047A1.9976,1.9976,0,0,0,40,46.0024V43.9976A1.9976,1.9976,0,0,0,38.0024,42Zm-.0053,4H34V44h4Z" style="fill:#fff" />
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        <div class="nk-upload-info">
                                                            <div class="nk-upload-title"><span class="title">dashlite-latest-version.zip</span></div>
                                                            <div class="nk-upload-size">25.49 MB</div>
                                                        </div>
                                                        <div class="nk-upload-action">
                                                            <a href="#" class="btn btn-icon btn-trigger" data-dismiss="modal"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary btn-block">Save Project</button>
                                        </div>
                                    </div>

                                </div>
                                </form>
                            </div>
                        </div><!-- .card-preview -->
                    </div><!-- .nk-block -->

                </div><!-- .components-preview -->
            </div>
        </div>
    </div>


    <div class="modal fade zoom" id="filePickerUploadModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Folder</h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body modal-body-lg">
                    @include('media-manager::partials._browse_list')
                </div>
                <div class="modal-footer bg-light">
                    <span class="sub-text">Media Management</span>
                </div>
            </div>
        </div>
    </div>


@endsection
