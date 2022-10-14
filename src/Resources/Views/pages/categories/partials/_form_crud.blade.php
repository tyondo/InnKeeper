<div class="card-inner">
    <form action="{{$route}}" method="post">
        @csrf
        <div class="preview-block">
            <span class="preview-title-lg overline-title">@if(isset($itemDetails)) Update Category @else Add Category @endif</span>
            <div class="row gy-4">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="form-label" for="faq_question">Name</label>
                        <div class="form-control-wrap">
                            <input type="text" name="name" value="{{isset($itemDetails)? $itemDetails->question : null}}" class="form-control" id="faq_question" placeholder="Project Title">
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="form-label" for="parent_id">Category Parent</label>
                        <div class="form-control-wrap">
                            <select name="parent_id" id="parent_id" class="form-select js-select2" data-search="on">
                                <option value="0">No Parent</option>
                                @foreach(getCategoriesList() as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
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
