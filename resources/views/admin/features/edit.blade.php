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
                          Edit Contract details
                        </h3>
                      </div>
                      <div class="kt-portlet__head-tools">
                    
                     </div>
                    </div>

                    <!--begin::Form-->
                  
                      <form class="kt-form kt-form--label-right" method="POST" action="{{route('feature.update',$feature->id)}}" enctype="multipart/form-data" files="true">
                        {{csrf_field()}}
                        
                        <input type="hidden" name="_method" value ="PUT"/>
                      <div class="kt-portlet__body"> 
                          
                          <div class="form-group row {{ $errors->has('part') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Section of Contract">Contract</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="part" id="part" value="{{$feature->part}}" disabled="disabled">
                                    @if ($errors->has('part'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('part') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('field') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Type of Contract">Contract type</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="field" id="field" value="{{$feature->field}}" disabled="disabled">
                                    @if ($errors->has('field'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('field') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('condition') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Feature of the contract">Condition</label>
                              <div class="col-8">
                                  <input class="form-control" type="text" name="condition" id="condition" value="{{$feature->condition}}" disabled="disabled">
                                    @if ($errors->has('condition'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('condition') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('NL_description') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Description of Contract details in Dutch">NL Description</label>
                              <div class="col-8">
                                <textarea class="form-control m-input m-input--solid summernote" required="required"  id="summernotea" aria-describedby="Description" placeholder="Content" name="NL_description" >{{$feature->NL_description}}</textarea>
                                @if ($errors->has('NL_description'))

                                <span class="text-danger"> *{{ $errors->first('NL_description') }}</span>
                                @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('FR_description') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Description of Contract details in French">FR Description</label>
                              <div class="col-8">
                                  <textarea class="form-control m-input m-input--solid summernote" required="required"  id="summernotea" aria-describedby="Description" placeholder="Content" name="FR_description" rows="6">{{$feature->FR_description}}</textarea>
                                @if ($errors->has('FR_description'))

                                <span class="text-danger"> *{{ $errors->first('FR_description') }}</span>
                                @endif
                              </div>
                          </div>
                      </div>

                        <div class="clearfix"></div>
                        <div class="offset-5 kt-portlet__foot kt-portlet__foot--fit ">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success submit" rel="tooltip" title="Save Contract details">
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
  
  $(document).ready(function() {
  $('.summernote').summernote();
});
 
   
</script>
@endpush

