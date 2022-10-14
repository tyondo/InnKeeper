<div class="card-inner">
    <form action="{{$route}}" method="post">
        @csrf
        <div class="preview-block">
            <span class="preview-title-lg overline-title">@if(isset($itemDetails)) Update Tag @else Add Tag @endif</span>
            <div class="row gy-4">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="form-label" for="faq_question">Tag Name</label>
                        <div class="form-control-wrap">
                            <input type="text" name="name" value="{{isset($itemDetails)? $itemDetails->question : null}}" class="form-control" id="faq_question" placeholder="Project Title">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="form-label" for="parent_id">Tag Parent</label>
                        <div class="form-control-wrap">
                            <select name="parent_id" id="parent_id" class="form-select js-select2" data-search="on">
                                <option value="0">No Parent</option>
                                @foreach(getTagsList() as $item)
                                    <option value="{{$item->id}}">{{ucfirst(json_decode($item->name)->en)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="form-label" for="type">Tag Type</label>
                        <div class="form-control-wrap">
                            <select name="type" id="type" class="form-select js-select2" data-search="on">
                                <option value="0">No Type</option>
                                @foreach(getTagTypesList() as $item)
                                    <option value="{{$item->type}}">{{ucfirst($item->type)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                </div>
            </div>

        </div>
    </form>
</div>
