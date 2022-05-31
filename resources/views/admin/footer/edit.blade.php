@extends('layouts.admin-layout')

@section('title', 'Footer content')



@section('content')
  <!-- begin:: Content -->
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
              <div class="row">
                  
                <div class="col-md-12">
                    
                  <!--begin::Portlet-->
                  <div class="kt-portlet">
                    <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                          Edit Footer Content
                        </h3>
                      </div>
                      <div class="kt-portlet__head-tools">
                    
                     </div>
                    </div>

                    <!--begin::Form-->
                      <form class="kt-form kt-form--label-right" method="POST" action="{{route('footer.update',$footer->id)}}" enctype="multipart/form-data" files="true">
                        {{csrf_field()}}
                        
                        <input type="hidden" name="_method" value ="PUT"/>
                      <div class="kt-portlet__body"> 
                          <div class="form-group row {{ $errors->has('eng') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label">In English</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="eng" id="eng" value="{{$footer->eng}}" >
                                    @if ($errors->has('eng'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('eng') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('nl') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" >In Dutch</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="nl" id="nl" value="{{$footer->nl}}">
                                    @if ($errors->has('nl'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('nl') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('fr') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" >In French</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="fr" id="fr" value="{{$footer->fr}}">
                                    @if ($errors->has('fr'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('fr') }}</div>
                                  @endif
                              </div>
                          </div>
                        <div class="form-group row {{ $errors->has('link_nl') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" >link in NL</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="link_nl" id="link_nl" value="{{$footer->link_nl}}">
                                    @if ($errors->has('link_nl'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('link_nl') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('link_fr') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" >link in FR</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="link_fr" id="link_fr" value="{{$footer->link_fr}}">
                                    @if ($errors->has('link_fr'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('link_fr') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('link_status') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" >External Link</label>
                              <div class="col-8">
                                 @if ($footer->link_status == 0)
                                  <input type="checkbox" name="link_status" value="1">
                                 @else
                                  <input type="checkbox" name="link_status" value="1" checked>
                                 @endif
                                    @if ($errors->has('link_status'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('link_status') }}</div>
                                  @endif
                              </div>
                          </div>

                        <div class="clearfix"></div>
                        <div class="offset-5 kt-portlet__foot kt-portlet__foot--fit ">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success submit" rel="tooltip" title="Save footer contents">
                                    Save Changes
                                </button>
                                <input type="hidden" value="{{ csrf_token() }}" name="_token">
                            </div>
                        </div>
                    </form>
                  </div>

                  <!--end::Portlet-->
                </div>


              
              </div>
            </div>

            <!-- end:: Content -->

  @endsection
 

@push('scripts')
  <script type="text/javascript">
 
      var SummernoteDemo = function () {
        var demos = function () {
            $('#summernote').summernote({
                height: 150,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["view", ["fullscreen", "codeview", "help"]]
                ],
            });
            $('#summernote').on('summernote.keydown', function (we, e) {
                var code = $('#summernote').summernote('code');
                code = code.replace(/(<([^>]+)>)/ig, "");
                code = code.replace(/&nbsp;/g, '');
                var key = e.keyCode;
                var text_max = 255;
                var text_length = code.length;
                var text_remaining = text_max - text_length;
                $('#message_count').html(text_remaining + ' characters remaining');
                allowed_keys = [8, 37, 38, 39, 40, 46, 32, 13]
                if ($.inArray(key, allowed_keys) != -1)
                    return true
                else if (code.length >= 255) {
                    e.preventDefault();
                    e.stopPropagation()
                }
            });
        }
        return {
            init: function () {
                demos();
            }
        };
    }();
</script>
@endpush