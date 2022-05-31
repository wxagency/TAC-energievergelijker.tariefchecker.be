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
                          Edit Video Content
                        </h3>
                      </div>
                      <div class="kt-portlet__head-tools">
                    
                     </div>
                    </div>

                    <!--begin::Form-->
                  
                      <form class="kt-form kt-form--label-right" method="POST" action="{{route('request-content.store')}}" enctype="multipart/form-data" files="true">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$videocontent->id}}" name="id">
                        <div class="kt-portlet__body"> 
                           <div class="form-group row {{ $errors->has('NL_title') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-2 col-form-label" rel="tooltip" title="Edit the title in dutch">NL Title</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="NL_title" id="NL_title" value="{{$videocontent->NL_title}}">
                                    @if ($errors->has('NL_title'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('NL_title') }}</div>
                                  @endif
                              </div>
                          </div> 
                            <div class="form-group row {{ $errors->has('FR_title') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-2 col-form-label" rel="tooltip" title="Edit the title in french">FR Title</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="FR_title" id="FR_title" value="{{$videocontent->FR_title}}">
                                  @if ($errors->has('FR_title'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('FR_title') }}</div>
                                  @endif
                              </div>
                          </div>
                            
                          <div class="form-group row {{ $errors->has('videolink') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-2 col-form-label" rel="tooltip" title="enter the video link here">Youtube Video link.</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="videolink" id="videolink" placeholder="enter the link of video here" value="{{$videocontent->video}}">
                                    @if ($errors->has('videolink'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('videolink') }}</div>
                                  @endif
                              </div>
                              <div class="col-5 offset-2">
                                  <img src="{{url('images/youtubelink.png')}}" alt="youtube link format" height="40px" width="100%">
                              </div> 
                          </div>
                          
                          <div class="form-group row {{ $errors->has('NL_description') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-2 col-form-label" rel="tooltip" title="Description in dutch">NL Description</label>
                              <div class="col-8">
                                <textarea class="form-control m-input m-input--solid summernote"  id="summernotes" aria-describedby="Description" placeholder="Content" name="NL_description">{{$videocontent->NL_content}}</textarea>
                                @if ($errors->has('NL_description'))

                                <span class="text-danger"> *{{ $errors->first('NL_description') }}</span>
                                @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('FR_description') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-2 col-form-label" rel="tooltip" title="Description in french">FR Description</label>
                              <div class="col-8">
                                <textarea class="form-control m-input m-input--solid summernote"  id="summernotes" aria-describedby="Description" placeholder="Content" name="FR_description">{{$videocontent->FR_content}}</textarea>
                                @if ($errors->has('FR_description'))

                                <span class="text-danger"> *{{ $errors->first('FR_description') }}</span>
                                @endif
                              </div>
                          </div>  
                       
                      </div>

                        <div class="clearfix"></div>
                        <div class="offset-5 kt-portlet__foot kt-portlet__foot--fit ">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success submit">
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



