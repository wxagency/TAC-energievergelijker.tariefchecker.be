@extends('layouts.admin-layout')

@section('title', 'Request page data')



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
                          Edit language of each content
                        </h3>
                      </div>
                      <div class="kt-portlet__head-tools">
                    
                     </div>
                    </div>

                    <!--begin::Form-->
                  
                      <form class="kt-form kt-form--label-right" method="POST" action="{{route('language.update',$language->id)}}" enctype="multipart/form-data" files="true">
                        {{csrf_field()}}
                        
                        <input type="hidden" name="_method" value ="PUT"/>
                      <div class="kt-portlet__body"> 
                          
                          <div class="form-group row {{ $errors->has('content_eng') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Change Content in English">Content in English</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="content_eng" id="content_eng" value="{{$language->eng}}" required="required">
                                    @if ($errors->has('content_eng'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('content_eng') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('content_nl') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Change Content in Dutch">Content in NL</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="content_nl" id="content_nl" value="{{$language->nl}}" required="required">
                                    @if ($errors->has('content_nl'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('content_nl') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('content_fr') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Change Content in French">Content in FR</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="content_fr" id="content_fr" value="{{$language->fr}}" required="required">
                                    @if ($errors->has('content_fr'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('content_fr') }}</div>
                                  @endif
                              </div>
                          </div>
                      </div>

                        <div class="clearfix"></div>
                        <div class="offset-5 kt-portlet__foot kt-portlet__foot--fit ">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success submit" rel="tooltip" title="Save Updates in different Language">
                                    Save Changes
                                </button>
<!--                                <a href="{{ route('language.index') }}" class="btn btn-secondary">
                                Back
                                </a>-->
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
