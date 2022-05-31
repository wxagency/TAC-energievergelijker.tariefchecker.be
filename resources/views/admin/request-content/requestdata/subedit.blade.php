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
                          Edit Subtitle and Content
                        </h3>
                      </div>
                      <div class="kt-portlet__head-tools">
                    
                     </div>
                    </div>

                    <!--begin::Form-->
                  
                      <form class="kt-form kt-form--label-right" method="POST" action="{{route('request-content.update',$subtitle->id)}}" enctype="multipart/form-data" files="true">
                        {{csrf_field()}}
                        
                        <input type="hidden" name="_method" value ="PUT"/>
                      <div class="kt-portlet__body"> 
                          <div class="form-group row {{ $errors->has('NL_subtitle') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Change subtitle in Dutch">NL Subtitle</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="NL_subtitle" id="NL_subtitle" value="{{$subtitle->NL_subtitle}}">
                                    @if ($errors->has('NL_subtitle'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('NL_subtitle') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('FR_subtitle') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Change subtitle in french">FR Subtitle</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="FR_subtitle" id="FR_subtitle" value="{{$subtitle->FR_subtitle}}">
                                    @if ($errors->has('FR_subtitle'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('FR_subtitle') }}</div>
                                  @endif
                              </div>
                          </div>
                          
                          <div class="form-group row {{ $errors->has('NL_description') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label"  rel="tooltip" title="Content in Dutch">NL Description</label>
                              <div class="col-8">
                                <textarea class="form-control m-input m-input--solid summernote"  id="summernotea" aria-describedby="Description" placeholder="Content" name="NL_description" >{{$subtitle->NL_content}}</textarea>
                                @if ($errors->has('NL_description'))

                                <span class="text-danger"> *{{ $errors->first('NL_description') }}</span>
                                @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('FR_description') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Content in French">FR Description</label>
                              <div class="col-8">
                                <textarea class="form-control m-input m-input--solid summernote"  id="summernotea" aria-describedby="Description" placeholder="Content" name="FR_description" >{{$subtitle->FR_content}}</textarea>
                                @if ($errors->has('FR_description'))

                                <span class="text-danger"> *{{ $errors->first('FR_description') }}</span>
                                @endif
                              </div>
                          </div>
                        
                      </div>

                        <div class="clearfix"></div>
                        <div class="offset-5 kt-portlet__foot kt-portlet__foot--fit ">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success submit" rel="tooltip" title="Save the updates">
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
  
  $(document).ready(function() {
  $('.summernote').summernote();
});
 
   
</script>

@endpush
